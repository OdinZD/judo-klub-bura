#!/bin/bash
# ============================================================
# Judo Klub Bura — Ubuntu Server Setup
# ============================================================
# This installs everything your server needs to host the website.
# Run this ONCE as root on your Hetzner server.
#
# Usage:
#   ssh root@YOUR_SERVER_IP
#   bash /var/www/judo-klub-bura/scripts/server-setup.sh
# ============================================================

set -euo pipefail

APP_DIR="/var/www/judo-klub-bura"

if [ "$(id -u)" -ne 0 ]; then
    echo "ERROR: Run this script as root (use: sudo bash $0)"
    exit 1
fi

echo ""
echo "=== Judo Klub Bura — Server Setup ==="
echo ""

# ----------------------------------------------------------
# 1. Update system
# ----------------------------------------------------------
echo "[1/7] Updating system..."
apt-get update -qq
apt-get upgrade -y -qq
echo "  Done"

# ----------------------------------------------------------
# 2. Install PHP 8.4
# ----------------------------------------------------------
echo "[2/7] Installing PHP 8.4..."
apt-get install -y -qq software-properties-common
add-apt-repository -y ppa:ondrej/php
apt-get update -qq
apt-get install -y -qq \
    php8.4-fpm \
    php8.4-cli \
    php8.4-mbstring \
    php8.4-xml \
    php8.4-curl \
    php8.4-gd \
    php8.4-sqlite3 \
    php8.4-zip \
    php8.4-bcmath \
    php8.4-intl

# Production PHP settings
PHP_INI="/etc/php/8.4/fpm/php.ini"
sed -i 's/upload_max_filesize = .*/upload_max_filesize = 20M/' "$PHP_INI"
sed -i 's/post_max_size = .*/post_max_size = 25M/' "$PHP_INI"
sed -i 's/expose_php = .*/expose_php = Off/' "$PHP_INI"

systemctl restart php8.4-fpm
echo "  PHP $(php -r 'echo PHP_VERSION;') installed"

# ----------------------------------------------------------
# 3. Install Nginx
# ----------------------------------------------------------
echo "[3/7] Installing Nginx..."
apt-get install -y -qq nginx
rm -f /etc/nginx/sites-enabled/default
echo "  Nginx installed"

# ----------------------------------------------------------
# 4. Configure Nginx for the website
# ----------------------------------------------------------
echo "[4/7] Configuring Nginx..."
cat > /etc/nginx/sites-available/judo-klub-bura << 'NGINX'
server {
    listen 80;
    listen [::]:80;
    server_name _;
    root /var/www/judo-klub-bura/public;

    add_header X-Content-Type-Options "nosniff" always;
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;

    index index.php;
    charset utf-8;

    gzip on;
    gzip_types text/plain text/css application/json application/javascript text/xml image/svg+xml;

    location ~* \.(css|js|png|jpg|jpeg|gif|ico|svg|webp|woff|woff2)$ {
        expires 30d;
        access_log off;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    client_max_body_size 25M;
}
NGINX

ln -sf /etc/nginx/sites-available/judo-klub-bura /etc/nginx/sites-enabled/
nginx -t
systemctl reload nginx
echo "  Nginx configured"

# ----------------------------------------------------------
# 5. Set up the application
# ----------------------------------------------------------
echo "[5/7] Setting up the application..."

# Set correct ownership
chown -R www-data:www-data "$APP_DIR"

# Make storage and cache writable
chmod -R 775 "$APP_DIR/storage"
chmod -R 775 "$APP_DIR/bootstrap/cache"

# Create storage directories if missing
mkdir -p "$APP_DIR/storage/app/public/gallery/originals"
mkdir -p "$APP_DIR/storage/app/public/gallery/thumbnails"
mkdir -p "$APP_DIR/storage/framework/cache/data"
mkdir -p "$APP_DIR/storage/framework/sessions"
mkdir -p "$APP_DIR/storage/framework/views"
mkdir -p "$APP_DIR/storage/logs"

# Create SQLite database if missing
if [ ! -f "$APP_DIR/database/database.sqlite" ]; then
    touch "$APP_DIR/database/database.sqlite"
fi
chown www-data:www-data "$APP_DIR/database/database.sqlite"
chmod 664 "$APP_DIR/database/database.sqlite"

# Create .env from template if not exists
if [ ! -f "$APP_DIR/.env" ]; then
    cp "$APP_DIR/.env.production.example" "$APP_DIR/.env"
    echo "  Created .env from template"
fi

# Generate application key if empty
cd "$APP_DIR"
if grep -q "^APP_KEY=$" .env; then
    php artisan key:generate --force
    echo "  Generated APP_KEY"
fi

# Run migrations
php artisan migrate --force
echo "  Migrations complete"

# Create storage symlink (makes uploaded images accessible via URL)
php artisan storage:link 2>/dev/null || true

# Cache everything for production speed
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

echo "  Application ready"

# ----------------------------------------------------------
# 6. Firewall
# ----------------------------------------------------------
echo "[6/7] Configuring firewall..."
apt-get install -y -qq ufw
ufw --force reset >/dev/null 2>&1
ufw default deny incoming >/dev/null 2>&1
ufw default allow outgoing >/dev/null 2>&1
ufw allow 22/tcp >/dev/null 2>&1
ufw allow 80/tcp >/dev/null 2>&1
ufw allow 443/tcp >/dev/null 2>&1
ufw --force enable >/dev/null 2>&1
echo "  Firewall active (SSH + HTTP + HTTPS only)"

# ----------------------------------------------------------
# 7. Security extras
# ----------------------------------------------------------
echo "[7/7] Installing security tools..."
apt-get install -y -qq fail2ban unattended-upgrades
dpkg-reconfigure -f noninteractive unattended-upgrades
systemctl enable fail2ban
systemctl restart fail2ban
echo "  fail2ban + auto-updates active"

# ----------------------------------------------------------
# Done
# ----------------------------------------------------------
SERVER_IP=$(curl -s ifconfig.me 2>/dev/null || hostname -I | awk '{print $1}')

echo ""
echo "============================================="
echo "  Setup complete!"
echo "============================================="
echo ""
echo "Your website is now live at:"
echo "  http://$SERVER_IP"
echo ""
echo "To add HTTPS after connecting a domain, run:"
echo "  apt install certbot python3-certbot-nginx"
echo "  certbot --nginx -d YOUR-DOMAIN.hr -d www.YOUR-DOMAIN.hr"
echo ""
