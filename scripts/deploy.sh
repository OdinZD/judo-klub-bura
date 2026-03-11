#!/bin/bash
# ============================================================
# Judo Klub Bura — Deploy to Hetzner Server
# ============================================================
# Run this from your local machine (Git Bash on Windows) to
# deploy the website to your server.
#
# Usage:
#   bash scripts/deploy.sh YOUR_SERVER_IP
#
# Example:
#   bash scripts/deploy.sh 65.21.123.45
#
# What it does:
#   1. Installs PHP dependencies (without dev packages)
#   2. Builds CSS/JS assets
#   3. Copies everything to the server via rsync
#   4. Runs database migrations on the server
#   5. Clears caches so the server picks up changes
# ============================================================

set -euo pipefail

SERVER_IP="${1:-}"

if [ -z "$SERVER_IP" ]; then
    echo "Usage: bash scripts/deploy.sh YOUR_SERVER_IP"
    echo "Example: bash scripts/deploy.sh 65.21.123.45"
    exit 1
fi

echo ""
echo "=== Deploying Judo Klub Bura ==="
echo "Server: $SERVER_IP"
echo ""

# ----------------------------------------------------------
# 1. Build locally
# ----------------------------------------------------------
echo "[1/4] Installing dependencies and building assets..."
composer install --no-dev --no-interaction --optimize-autoloader
npm ci
npm run build
echo "  Build complete"

# ----------------------------------------------------------
# 2. Copy files to server
# ----------------------------------------------------------
echo "[2/4] Uploading files to server..."
rsync -azh --delete \
    --exclude='.git' \
    --exclude='.github' \
    --exclude='.claude' \
    --exclude='node_modules' \
    --exclude='tests' \
    --exclude='.env' \
    --exclude='storage/app' \
    --exclude='storage/logs' \
    --exclude='storage/framework/sessions' \
    --exclude='storage/framework/cache/data' \
    --exclude='database/database.sqlite' \
    --exclude='_plan_raw.md' \
    ./ root@"$SERVER_IP":/var/www/judo-klub-bura/
echo "  Files uploaded"

# ----------------------------------------------------------
# 3. Run server-side commands
# ----------------------------------------------------------
echo "[3/4] Running server commands..."
ssh root@"$SERVER_IP" << 'EOF'
    cd /var/www/judo-klub-bura

    # Fix ownership so Nginx/PHP can read/write
    chown -R www-data:www-data .
    chmod -R 775 storage bootstrap/cache

    # Database & caches
    php artisan migrate --force
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan event:cache
    php artisan storage:link 2>/dev/null || true

    # Restart PHP so it picks up changes
    systemctl reload php8.4-fpm
EOF
echo "  Server updated"

# ----------------------------------------------------------
# 4. Reinstall dev dependencies locally
# ----------------------------------------------------------
echo "[4/4] Restoring local dev dependencies..."
composer install --no-interaction
echo ""
echo "=== Deploy complete! ==="
echo "Visit: http://$SERVER_IP"
echo ""
