I now have a thorough understanding of the entire codebase. Let me compile the comprehensive implementation plan.

---

# Gallery System Implementation Plan for Judo Klub Bura

## Codebase Context Summary

After thorough exploration, here is what the existing project provides:

- **Stack**: Laravel 12, Livewire 4, Flux UI (v2.9), Tailwind CSS v4 (via `@tailwindcss/vite`)
- **Design system**: Custom colors (`bura-*`, `adriatic-*`), semantic colors (`snow`, `ice`, `slate-text`, `slate-muted`), utility classes (`bg-grain`, `bg-wind-lines`, `bg-bura-gradient`, `bg-bura-gradient-soft`), animations (`animate-fade-up`, `animate-wind`, stagger delays)
- **Fonts**: Sora (display/headings), Plus Jakarta Sans (body)
- **Public layout**: `<x-layouts.public>` wraps `<x-public-navigation />`, `<main>{{ $slot }}</main>`, `<x-public-footer />`
- **Admin layout**: `<x-layouts::app>` which resolves to `<x-layouts::app.sidebar>` with Flux sidebar, nav groups, and `<flux:main>` content area
- **Components**: `<x-page-hero>`, `<x-section-heading>`, `<x-feature-card>`, `<x-stat-counter>`, `<x-schedule-card>`
- **Existing gallery**: Placeholder-only `GalleryGrid` Livewire component with CSS masonry columns, category filter buttons, basic Alpine.js lightbox (no navigation, no real images)
- **Routing patterns**: Public pages use `Route::view()`, settings use `Route::livewire()`, auth middleware applied via groups
- **Storage**: Standard `public` disk configured at `storage/app/public` with `/storage` URL prefix. Symbolic link configured.
- **No Enums directory exists** -- will create `app/Enums/`
- **No existing packages** for images -- clean slate
- **Testing**: PHPUnit with SQLite in-memory, `RefreshDatabase` trait, standard Laravel test patterns

---

## Phase 1: Data Architecture

### 1A. Create GalleryCategory Enum

**File**: `app/Enums/GalleryCategory.php`

```php
<?php

namespace App\Enums;

enum GalleryCategory: string
{
    case Treninzi = 'treninzi';
    case Natjecanja = 'natjecanja';
    case Dogadaji = 'dogadaji';

    public function label(): string
    {
        return match ($this) {
            self::Treninzi => 'Treninzi',
            self::Natjecanja => 'Natjecanja',
            self::Dogadaji => 'Događaji',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Treninzi => 'sky',
            self::Natjecanja => 'amber',
            self::Dogadaji => 'teal',
        };
    }
}
```

Rationale: A backed enum gives type safety, DB storage as a string column, and a single source of truth for labels and badge colors. The existing gallery uses these exact three categories.

### 1B. Create Migration for gallery_albums table

**File**: `database/migrations/YYYY_MM_DD_HHMMSS_create_gallery_albums_table.php`

```php
Schema::create('gallery_albums', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->date('event_date');
    $table->string('category'); // stored as enum string value
    $table->string('cover_image_path')->nullable();
    $table->boolean('is_published')->default(false);
    $table->timestamps();

    $table->index(['is_published', 'event_date']);
    $table->index('category');
});
```

Key decisions:
- `event_date` as a `date` column (not `datetime`) because competition dates are day-granularity
- `cover_image_path` nullable because it can be auto-set from the first image or manually chosen
- Composite index on `is_published` + `event_date` for the common query pattern (published albums, sorted by date)
- `slug` unique index for URL resolution

### 1C. Create Migration for gallery_images table

**File**: `database/migrations/YYYY_MM_DD_HHMMSS_create_gallery_images_table.php`

```php
Schema::create('gallery_images', function (Blueprint $table) {
    $table->id();
    $table->foreignId('gallery_album_id')->constrained()->cascadeOnDelete();
    $table->string('image_path');
    $table->string('thumbnail_path');
    $table->string('caption')->nullable();
    $table->unsignedInteger('sort_order')->default(0);
    $table->timestamps();

    $table->index(['gallery_album_id', 'sort_order']);
});
```

Key decisions:
- `cascadeOnDelete` -- deleting an album removes all images (files cleaned up via model events)
- Both `image_path` and `thumbnail_path` stored -- originals for lightbox, thumbnails for grid
- `sort_order` for manual ordering within an album
- No separate `width`/`height` columns -- unnecessary complexity since we will render with `object-cover`

### 1D. GalleryAlbum Model

**File**: `app/Models/GalleryAlbum.php`

```php
<?php

namespace App\Models;

use App\Enums\GalleryCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class GalleryAlbum extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'event_date',
        'category',
        'cover_image_path',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'category' => GalleryCategory::class,
            'is_published' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (GalleryAlbum $album) {
            if (empty($album->slug)) {
                $album->slug = Str::slug($album->title);
            }
        });
    }

    // --- Relationships ---

    public function images(): HasMany
    {
        return $this->hasMany(GalleryImage::class)->orderBy('sort_order');
    }

    // --- Scopes ---

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeByCategory($query, GalleryCategory $category)
    {
        return $query->where('category', $category);
    }

    // --- Accessors ---

    public function getCoverUrlAttribute(): ?string
    {
        if ($this->cover_image_path) {
            return asset('storage/' . $this->cover_image_path);
        }

        // Fall back to first image thumbnail
        $firstImage = $this->images()->first();
        return $firstImage ? asset('storage/' . $firstImage->thumbnail_path) : null;
    }
}
```

### 1E. GalleryImage Model

**File**: `app/Models/GalleryImage.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class GalleryImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'gallery_album_id',
        'image_path',
        'thumbnail_path',
        'caption',
        'sort_order',
    ];

    protected static function booted(): void
    {
        static::deleting(function (GalleryImage $image) {
            Storage::disk('public')->delete($image->image_path);
            Storage::disk('public')->delete($image->thumbnail_path);
        });
    }

    public function album(): BelongsTo
    {
        return $this->belongsTo(GalleryAlbum::class, 'gallery_album_id');
    }

    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->image_path);
    }

    public function getThumbnailUrlAttribute(): string
    {
        return asset('storage/' . $this->thumbnail_path);
    }
}
```

### 1F. Model Factories (for testing and seeding)

**File**: `database/factories/GalleryAlbumFactory.php`
**File**: `database/factories/GalleryImageFactory.php`

Standard Laravel factories with faker-generated data. The album factory generates title, slug, description, event_date, category (random from enum), published state. The image factory associates to an album and generates placeholder paths.

---

## Phase 2: Image Storage Strategy

### Decision: Use Intervention Image v3 for Thumbnails

**Yes, use Intervention Image.** Rationale:
- With hundreds/thousands of images, serving original 3-5MB DSLR photos in a grid would be devastating for page load times
- A 400px-wide thumbnail at ~30-60KB vs a 4000px original at 3-5MB is a 50-100x bandwidth difference
- CSS `object-fit` only controls rendering -- the browser still downloads the full file
- Intervention Image v3 is a single `composer require intervention/image` with zero additional config needed for the GD driver (which PHP ships with)
- The thumbnail generation happens once at upload time, not on every request

**Install**: `composer require intervention/image`

### Storage Directory Structure

```
storage/app/public/gallery/
    originals/          # Full-size images served in lightbox
        {album_id}/     # Grouped by album for organization
            {uuid}.jpg
    thumbnails/         # Resized for grid display
        {album_id}/
            {uuid}.jpg
```

### Thumbnail Service

**File**: `app/Services/ImageService.php`

```php
<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class ImageService
{
    public function store(UploadedFile $file, int $albumId): array
    {
        $filename = Str::uuid() . '.jpg';

        $originalPath = "gallery/originals/{$albumId}/{$filename}";
        $thumbnailPath = "gallery/thumbnails/{$albumId}/{$filename}";

        // Store original (re-encode to strip EXIF, normalize orientation)
        $image = Image::read($file);
        $image->orient(); // auto-rotate based on EXIF
        Storage::disk('public')->put(
            $originalPath,
            $image->encodeByExtension('jpg', quality: 85)
        );

        // Generate thumbnail (400px wide, maintain aspect ratio)
        $image->scaleDown(width: 400);
        Storage::disk('public')->put(
            $thumbnailPath,
            $image->encodeByExtension('jpg', quality: 80)
        );

        return [
            'image_path' => $originalPath,
            'thumbnail_path' => $thumbnailPath,
        ];
    }

    public function delete(string $imagePath, string $thumbnailPath): void
    {
        Storage::disk('public')->delete([$imagePath, $thumbnailPath]);
    }
}
```

Key details:
- `orient()` fixes mobile photo rotation issues (very common with phone photos)
- UUID filenames prevent collisions and path traversal
- Quality 85 for originals (visually lossless), 80 for thumbnails (good enough at small size)
- `scaleDown` only shrinks -- if an image is already smaller than 400px, it stays as-is
- All conversion to JPG normalizes formats (PNG, HEIC, WebP inputs all become JPG output)

### Setup requirement
`php artisan storage:link` must be run once during deployment (already configured in `config/filesystems.php` lines 76-78).

---

## Phase 3: Public Gallery UX (Main Focus)

### 3A. Albums Listing Page (`/galerija`)

**Modify**: `resources/views/pages/public/gallery.blade.php`

Replace the current `<livewire:gallery-grid />` with `<livewire:gallery-albums />`. Keep the `<x-page-hero>` as-is.

```blade
<x-layouts.public>
    <x-page-hero title="Galerija" subtitle="Trenutci s treninga, natjecanja i klupskih događanja." />

    <section class="py-16 sm:py-20 lg:py-28">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <livewire:gallery-albums />
        </div>
    </section>
</x-layouts.public>
```

**New File**: `app/Livewire/GalleryAlbums.php`

This replaces the old `GalleryGrid.php`. The component:
- Has a `$category` public property (string, default `'sve'`)
- Queries `GalleryAlbum::published()` with optional category filter
- Uses `withCount('images')` for image count badges
- Paginates with 12 albums per page (cursor pagination for performance)
- Eager-loads cover images

```php
<?php

namespace App\Livewire;

use App\Enums\GalleryCategory;
use App\Models\GalleryAlbum;
use Livewire\Component;
use Livewire\WithPagination;

class GalleryAlbums extends Component
{
    use WithPagination;

    public string $category = 'sve';

    public function setCategory(string $category): void
    {
        $this->category = $category;
        $this->resetPage();
    }

    public function render()
    {
        $query = GalleryAlbum::published()
            ->withCount('images')
            ->orderByDesc('event_date');

        if ($this->category !== 'sve') {
            $query->where('category', $this->category);
        }

        return view('livewire.gallery-albums', [
            'albums' => $query->paginate(12),
            'categories' => GalleryCategory::cases(),
        ]);
    }
}
```

**New File**: `resources/views/livewire/gallery-albums.blade.php`

Design approach:
- Category filter tabs at top (same pattern as existing `gallery-grid.blade.php` but using enum data)
- Responsive grid: `grid-cols-1 sm:grid-cols-2 lg:grid-cols-3` of album cards
- Each album card: cover image with `object-cover`, title, date, image count badge, category badge
- Cards link to album detail page with `wire:navigate`
- Loading states with `wire:loading`
- Pagination at bottom using Flux/Livewire pagination

```blade
<div>
    {{-- Category filter tabs --}}
    <div class="flex flex-wrap justify-center gap-2 mb-10">
        <flux:button
            wire:click="setCategory('sve')"
            :variant="$category === 'sve' ? 'primary' : 'ghost'"
            size="sm"
        >
            Sve
        </flux:button>
        @foreach($categories as $cat)
            <flux:button
                wire:click="setCategory('{{ $cat->value }}')"
                :variant="$category === $cat->value ? 'primary' : 'ghost'"
                size="sm"
            >
                {{ $cat->label() }}
            </flux:button>
        @endforeach
    </div>

    {{-- Album grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"
         wire:loading.class="opacity-50" wire:target="setCategory">
        @forelse($albums as $album)
            <a href="{{ route('gallery.album', $album->slug) }}" wire:navigate
               wire:key="album-{{ $album->id }}"
               class="group bg-white border border-bura-100 rounded-2xl overflow-hidden
                      transition-all duration-300 hover:-translate-y-1 hover:shadow-lg
                      hover:shadow-bura-500/10">
                {{-- Cover image --}}
                <div class="aspect-[4/3] relative overflow-hidden bg-bura-50">
                    @if($album->cover_url)
                        <img src="{{ $album->cover_url }}"
                             alt="{{ $album->title }}"
                             class="w-full h-full object-cover transition-transform duration-500
                                    group-hover:scale-105"
                             loading="lazy" />
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-bura-100 to-adriatic-50
                                    flex items-center justify-center">
                            <flux:icon name="photo" class="size-12 text-bura-300" />
                        </div>
                    @endif

                    {{-- Category badge (top-right) --}}
                    <div class="absolute top-3 right-3">
                        <flux:badge size="sm" :color="$album->category->color()">
                            {{ $album->category->label() }}
                        </flux:badge>
                    </div>

                    {{-- Image count badge (bottom-left) --}}
                    <div class="absolute bottom-3 left-3">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full
                                     bg-slate-text/60 backdrop-blur-sm text-white text-xs font-medium">
                            <flux:icon name="photo" class="size-3.5" />
                            {{ $album->images_count }}
                        </span>
                    </div>
                </div>

                {{-- Card body --}}
                <div class="p-5">
                    <h3 class="font-display font-semibold text-lg text-slate-text
                               group-hover:text-bura-600 transition-colors line-clamp-1">
                        {{ $album->title }}
                    </h3>
                    <p class="mt-1 text-sm text-slate-muted">
                        {{ $album->event_date->translatedFormat('d. F Y.') }}
                    </p>
                    @if($album->description)
                        <p class="mt-2 text-sm text-slate-muted line-clamp-2">
                            {{ $album->description }}
                        </p>
                    @endif
                </div>
            </a>
        @empty
            <div class="col-span-full text-center py-16">
                <flux:icon name="photo" class="size-16 text-bura-200 mx-auto mb-4" />
                <p class="text-slate-muted">Nema albuma u ovoj kategoriji.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($albums->hasPages())
        <div class="mt-12 flex justify-center">
            {{ $albums->links() }}
        </div>
    @endif
</div>
```

Design notes:
- Album cards follow the exact same pattern as `<x-feature-card>`: white background, `border-bura-100`, `rounded-2xl`, `hover:-translate-y-1`, `hover:shadow-lg hover:shadow-bura-500/10`
- Cover image uses `group-hover:scale-105` for a subtle zoom effect on hover
- `loading="lazy"` for images below the fold
- `line-clamp-1` and `line-clamp-2` for text truncation
- `translatedFormat` uses Carbon's locale-aware formatting (Croatian day/month names)
- Empty state with icon follows existing design patterns

### 3B. Album Detail Page (`/galerija/{slug}`)

**New File**: `resources/views/pages/public/gallery-album.blade.php`

This is a full page view (not a Livewire full-page component) that embeds a Livewire component for the image grid.

```blade
<x-layouts.public>
    {{-- Breadcrumb --}}
    <div class="bg-bura-gradient-soft border-b border-bura-100/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex items-center gap-2 text-sm">
                <a href="{{ route('gallery') }}" wire:navigate
                   class="text-slate-muted hover:text-bura-500 transition-colors">
                    Galerija
                </a>
                <flux:icon name="chevron-right" class="size-4 text-slate-muted/50" />
                <span class="text-slate-text font-medium">{{ $album->title }}</span>
            </nav>
        </div>
    </div>

    {{-- Album header --}}
    <section class="py-10 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <div class="flex items-center gap-3 mb-4">
                    <flux:badge :color="$album->category->color()">
                        {{ $album->category->label() }}
                    </flux:badge>
                    <span class="text-sm text-slate-muted">
                        {{ $album->event_date->translatedFormat('d. F Y.') }}
                    </span>
                    <span class="text-sm text-slate-muted">
                        &middot; {{ $album->images_count }} fotografija
                    </span>
                </div>
                <h1 class="font-display font-bold text-3xl lg:text-4xl text-slate-text">
                    {{ $album->title }}
                </h1>
                @if($album->description)
                    <p class="mt-4 text-lg text-slate-muted leading-relaxed">
                        {{ $album->description }}
                    </p>
                @endif
            </div>
        </div>
    </section>

    {{-- Image grid with lightbox --}}
    <section class="pb-16 sm:pb-20 lg:pb-28">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <livewire:album-image-grid :album="$album" />
        </div>
    </section>
</x-layouts.public>
```

**New File**: `app/Livewire/AlbumImageGrid.php`

```php
<?php

namespace App\Livewire;

use App\Models\GalleryAlbum;
use App\Models\GalleryImage;
use Livewire\Component;
use Livewire\WithPagination;

class AlbumImageGrid extends Component
{
    use WithPagination;

    public GalleryAlbum $album;

    public function render()
    {
        return view('livewire.album-image-grid', [
            'images' => $this->album->images()->paginate(40),
        ]);
    }
}
```

Rationale for 40 images per page: balances between "enough to scroll through" and "not loading 200 thumbnails at once." At ~40KB per thumbnail, 40 images = ~1.6MB which is acceptable. Infinite scroll could be added later but standard pagination is simpler and more predictable.

**New File**: `resources/views/livewire/album-image-grid.blade.php`

This is the core visual component. It contains: (a) the image grid, and (b) the full-featured lightbox, all managed by a single Alpine.js `x-data` scope.

```blade
<div x-data="albumLightbox(@js($images->items()))" x-cloak>
    {{-- Image grid --}}
    <div class="columns-2 sm:columns-3 lg:columns-4 gap-3 sm:gap-4">
        @foreach($images as $index => $image)
            <div wire:key="img-{{ $image->id }}"
                 class="break-inside-avoid mb-3 sm:mb-4 group relative rounded-xl overflow-hidden cursor-pointer"
                 x-on:click="open({{ $index }})">
                <img src="{{ $image->thumbnail_url }}"
                     alt="{{ $image->caption ?? '' }}"
                     class="w-full h-auto rounded-xl"
                     loading="lazy" />

                {{-- Hover overlay --}}
                <div class="absolute inset-0 bg-slate-text/0 group-hover:bg-slate-text/40
                            transition-all duration-300 flex items-end rounded-xl">
                    @if($image->caption)
                        <div class="w-full p-3 translate-y-full group-hover:translate-y-0
                                    transition-transform duration-300">
                            <span class="text-white text-sm font-medium">
                                {{ $image->caption }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($images->hasPages())
        <div class="mt-12 flex justify-center">
            {{ $images->links() }}
        </div>
    @endif

    {{-- Lightbox --}}
    <template x-teleport="body">
        <div x-show="isOpen" x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[60] flex items-center justify-center"
             x-on:keydown.escape.window="close()"
             x-on:keydown.left.window="prev()"
             x-on:keydown.right.window="next()"
             style="display: none;">

            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-slate-text/90 backdrop-blur-sm"
                 x-on:click="close()"></div>

            {{-- Close button --}}
            <button x-on:click="close()"
                    class="absolute top-4 right-4 z-10 p-2 rounded-full bg-white/10
                           hover:bg-white/20 text-white transition-colors">
                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>

            {{-- Counter --}}
            <div class="absolute top-4 left-4 z-10 px-3 py-1.5 rounded-full bg-white/10
                        backdrop-blur-sm text-white text-sm font-medium">
                <span x-text="currentIndex + 1"></span> / <span x-text="total"></span>
            </div>

            {{-- Previous button --}}
            <button x-on:click.stop="prev()" x-show="currentIndex > 0"
                    class="absolute left-4 z-10 p-3 rounded-full bg-white/10
                           hover:bg-white/20 text-white transition-colors">
                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="2"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
            </button>

            {{-- Next button --}}
            <button x-on:click.stop="next()" x-show="currentIndex < total - 1"
                    class="absolute right-4 z-10 p-3 rounded-full bg-white/10
                           hover:bg-white/20 text-white transition-colors">
                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="2"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
            </button>

            {{-- Image container --}}
            <div class="relative max-w-5xl w-full mx-4 sm:mx-8">
                <img :src="currentImage?.image_url"
                     :alt="currentImage?.caption || ''"
                     class="max-h-[85vh] w-auto mx-auto rounded-lg object-contain"
                     x-on:load="preloadAdjacent()" />

                {{-- Caption --}}
                <p x-show="currentImage?.caption" x-text="currentImage?.caption"
                   class="mt-3 text-white/80 text-center text-sm"></p>
            </div>
        </div>
    </template>
</div>
```

### 3C. Lightbox Alpine.js Component

**New file (inline or extracted)**: The `albumLightbox` Alpine component. I recommend defining it inline within the blade file using `x-data` for simplicity, but the actual logic should be extracted to a JS module for maintainability.

**Add to**: `resources/js/app.js` (or a separate `resources/js/album-lightbox.js` imported from app.js)

```js
import Alpine from 'alpinejs';

// Register the lightbox component globally
document.addEventListener('alpine:init', () => {
    Alpine.data('albumLightbox', (initialImages) => ({
        isOpen: false,
        currentIndex: 0,
        images: initialImages.map(img => ({
            id: img.id,
            image_url: img.image_url,
            thumbnail_url: img.thumbnail_url,
            caption: img.caption,
        })),

        get total() {
            return this.images.length;
        },

        get currentImage() {
            return this.images[this.currentIndex] || null;
        },

        open(index) {
            this.currentIndex = index;
            this.isOpen = true;
            document.body.style.overflow = 'hidden';
        },

        close() {
            this.isOpen = false;
            document.body.style.overflow = '';
        },

        next() {
            if (this.currentIndex < this.total - 1) {
                this.currentIndex++;
            }
        },

        prev() {
            if (this.currentIndex > 0) {
                this.currentIndex--;
            }
        },

        preloadAdjacent() {
            // Preload next image
            if (this.currentIndex < this.total - 1) {
                const next = new Image();
                next.src = this.images[this.currentIndex + 1].image_url;
            }
            // Preload previous image
            if (this.currentIndex > 0) {
                const prev = new Image();
                prev.src = this.images[this.currentIndex - 1].image_url;
            }
        },
    }));
});
```

Lightbox design decisions:
- **Teleported to `<body>`** via `x-teleport` to avoid z-index stacking context issues (the public nav is `z-50`, lightbox is `z-[60]`)
- **Keyboard navigation**: Left/Right arrows to navigate, Escape to close
- **Image counter**: "3 / 24" display in top-left
- **Preloading**: Adjacent images preloaded after current image loads, so navigation feels instant
- **Scroll lock**: `document.body.style.overflow = 'hidden'` prevents background scroll while lightbox is open
- **No swipe gestures in v1**: Touch/swipe can be added later with a few lines of pointer event handling. For v1, the prev/next buttons work on mobile. This avoids scope creep.
- **`object-contain`** on lightbox image: shows the full image without cropping, constrained to `max-h-[85vh]`
- **Transition animations**: Fade in/out on the backdrop

### 3D. Image Data Passing to Alpine

There is a subtlety: the lightbox needs both thumbnail URLs (for the grid) and original image URLs (for the lightbox). The grid renders thumbnails from Blade, but the lightbox needs the original URLs passed to Alpine.

The `@js($images->items())` in the `x-data` attribute serializes the paginated image collection. Each `GalleryImage` model should have `image_url` and `thumbnail_url` as appended attributes (via `$appends` on the model) OR the Livewire component should map the data explicitly.

Better approach: Add `$appends` to `GalleryImage`:

```php
protected $appends = ['image_url', 'thumbnail_url'];
```

This way, when serialized to JSON for Alpine, each image object includes the computed URLs.

### 3E. Responsive Behavior

- **Albums grid**: 1 column on mobile, 2 on `sm:`, 3 on `lg:`
- **Image grid (masonry)**: 2 columns on mobile, 3 on `sm:`, 4 on `lg:` (same as current placeholder)
- **Lightbox**: Full viewport overlay, image constrained to `max-h-[85vh]`, prev/next buttons at left/right edges
- **Lightbox on mobile**: Buttons are large enough for touch (48px targets via `p-3` + icon), positioned at screen edges
- **Album card cover images**: `aspect-[4/3]` with `object-cover` ensures consistent card heights regardless of photo aspect ratio

---

## Phase 4: Admin Upload System

### 4A. Admin Routes

**Modify**: `routes/web.php`

Add a new route group for gallery admin:

```php
Route::middleware(['auth', 'verified'])->prefix('dashboard')->group(function () {
    Route::livewire('galerija', 'admin.gallery-album-index')->name('admin.gallery.index');
    Route::livewire('galerija/create', 'admin.gallery-album-form')->name('admin.gallery.create');
    Route::livewire('galerija/{album}/edit', 'admin.gallery-album-form')->name('admin.gallery.edit');
});
```

This follows the same pattern as `routes/settings.php` which uses `Route::livewire()`.

### 4B. Add Gallery to Admin Sidebar

**Modify**: `resources/views/layouts/app/sidebar.blade.php`

Add a "Galerija" group to the sidebar nav, after the existing Platform group:

```blade
<flux:sidebar.group :heading="__('Sadržaj')" class="grid">
    <flux:sidebar.item icon="photo" :href="route('admin.gallery.index')"
                       :current="request()->routeIs('admin.gallery.*')" wire:navigate>
        Galerija
    </flux:sidebar.item>
</flux:sidebar.group>
```

### 4C. Album Index (Admin)

**New File**: `app/Livewire/Admin/GalleryAlbumIndex.php`

Lists all albums (published and unpublished) with actions to create, edit, publish/unpublish, and delete.

```php
<?php

namespace App\Livewire\Admin;

use App\Models\GalleryAlbum;
use Livewire\Component;
use Livewire\WithPagination;

class GalleryAlbumIndex extends Component
{
    use WithPagination;

    public function togglePublish(GalleryAlbum $album): void
    {
        $album->update(['is_published' => ! $album->is_published]);
    }

    public function deleteAlbum(GalleryAlbum $album): void
    {
        // Delete all image files first
        foreach ($album->images as $image) {
            $image->delete(); // triggers model event to delete files
        }
        $album->delete();
    }

    public function render()
    {
        return view('livewire.admin.gallery-album-index', [
            'albums' => GalleryAlbum::withCount('images')
                ->orderByDesc('event_date')
                ->paginate(15),
        ])->layout('layouts.app', ['title' => 'Galerija']);
    }
}
```

**New File**: `resources/views/livewire/admin/gallery-album-index.blade.php`

Uses the admin app layout (dark sidebar). Table-like layout with:
- Create button at top
- Table/list of albums: title, date, category badge, image count, published status, edit/delete actions
- Uses Flux components: `flux:button`, `flux:badge`, `flux:modal` (for delete confirmation), `flux:separator`

```blade
<div>
    <div class="flex items-center justify-between mb-8">
        <div>
            <flux:heading size="xl">Galerija</flux:heading>
            <flux:text class="mt-1">Upravljanje foto albumima.</flux:text>
        </div>
        <flux:button variant="primary" :href="route('admin.gallery.create')" wire:navigate
                     icon="plus">
            Novi album
        </flux:button>
    </div>

    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700
                overflow-hidden">
        {{-- Album list --}}
        @forelse($albums as $album)
            <div class="flex items-center gap-4 p-4 border-b border-zinc-100 dark:border-zinc-800
                        last:border-b-0"
                 wire:key="admin-album-{{ $album->id }}">
                {{-- Thumbnail --}}
                <div class="size-16 rounded-lg overflow-hidden bg-zinc-100 dark:bg-zinc-800 shrink-0">
                    @if($album->cover_url)
                        <img src="{{ $album->cover_url }}" class="size-full object-cover" />
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <span class="font-semibold text-sm truncate">{{ $album->title }}</span>
                        <flux:badge size="sm" :color="$album->category->color()">
                            {{ $album->category->label() }}
                        </flux:badge>
                        @unless($album->is_published)
                            <flux:badge size="sm" color="zinc">Skriveno</flux:badge>
                        @endunless
                    </div>
                    <flux:text size="sm" class="mt-0.5">
                        {{ $album->event_date->translatedFormat('d. F Y.') }}
                        &middot; {{ $album->images_count }} fotografija
                    </flux:text>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-2">
                    <flux:button size="sm" variant="ghost"
                                 wire:click="togglePublish({{ $album->id }})">
                        {{ $album->is_published ? 'Sakrij' : 'Objavi' }}
                    </flux:button>
                    <flux:button size="sm" variant="ghost" icon="pencil"
                                 :href="route('admin.gallery.edit', $album)" wire:navigate />
                    <flux:button size="sm" variant="ghost" icon="trash"
                                 wire:click="deleteAlbum({{ $album->id }})"
                                 wire:confirm="Jeste li sigurni da želite obrisati ovaj album?" />
                </div>
            </div>
        @empty
            <div class="p-12 text-center">
                <flux:text>Nema albuma. Kreirajte prvi album.</flux:text>
            </div>
        @endforelse
    </div>

    @if($albums->hasPages())
        <div class="mt-6">{{ $albums->links() }}</div>
    @endif
</div>
```

### 4D. Album Create/Edit Form

**New File**: `app/Livewire/Admin/GalleryAlbumForm.php`

A single Livewire component handling both create and edit modes.

```php
<?php

namespace App\Livewire\Admin;

use App\Enums\GalleryCategory;
use App\Models\GalleryAlbum;
use App\Models\GalleryImage;
use App\Services\ImageService;
use Livewire\Component;
use Livewire\WithFileUploads;

class GalleryAlbumForm extends Component
{
    use WithFileUploads;

    public ?GalleryAlbum $album = null;

    // Album form fields
    public string $title = '';
    public string $description = '';
    public string $event_date = '';
    public string $category = 'treninzi';
    public bool $is_published = false;

    // File uploads
    public array $newImages = [];

    // Existing images (for edit mode)
    public array $existingImages = [];
    public ?int $coverId = null;

    public function mount(?GalleryAlbum $album = null): void
    {
        if ($album?->exists) {
            $this->album = $album;
            $this->title = $album->title;
            $this->description = $album->description ?? '';
            $this->event_date = $album->event_date->format('Y-m-d');
            $this->category = $album->category->value;
            $this->is_published = $album->is_published;
            $this->existingImages = $album->images->toArray();
        }
    }

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'event_date' => ['required', 'date'],
            'category' => ['required', 'string', \Illuminate\Validation\Rule::enum(GalleryCategory::class)],
            'newImages.*' => ['image', 'max:10240'], // max 10MB per image
        ];
    }

    public function save(ImageService $imageService): void
    {
        $this->validate();

        $albumData = [
            'title' => $this->title,
            'description' => $this->description ?: null,
            'event_date' => $this->event_date,
            'category' => $this->category,
            'is_published' => $this->is_published,
        ];

        if ($this->album) {
            $this->album->update($albumData);
            $album = $this->album;
        } else {
            $album = GalleryAlbum::create($albumData);
        }

        // Process new image uploads
        $maxSort = $album->images()->max('sort_order') ?? 0;

        foreach ($this->newImages as $index => $file) {
            $paths = $imageService->store($file, $album->id);

            $album->images()->create([
                'image_path' => $paths['image_path'],
                'thumbnail_path' => $paths['thumbnail_path'],
                'sort_order' => $maxSort + $index + 1,
            ]);
        }

        $this->newImages = [];

        $this->redirect(route('admin.gallery.edit', $album), navigate: true);
    }

    public function removeImage(int $imageId, ImageService $imageService): void
    {
        $image = GalleryImage::findOrFail($imageId);
        $image->delete(); // Model event cleans up files
        $this->existingImages = $this->album->fresh()->images->toArray();
    }

    public function setCover(int $imageId): void
    {
        $image = GalleryImage::findOrFail($imageId);
        $this->album->update(['cover_image_path' => $image->thumbnail_path]);
    }

    public function updateOrder(array $orderedIds): void
    {
        foreach ($orderedIds as $index => $id) {
            GalleryImage::where('id', $id)->update(['sort_order' => $index]);
        }
        $this->existingImages = $this->album->fresh()->images->toArray();
    }

    public function render()
    {
        return view('livewire.admin.gallery-album-form', [
            'categories' => GalleryCategory::cases(),
            'isEditing' => $this->album?->exists ?? false,
        ])->layout('layouts.app', [
            'title' => $this->album?->exists ? 'Uredi album' : 'Novi album',
        ]);
    }
}
```

**New File**: `resources/views/livewire/admin/gallery-album-form.blade.php`

Two sections: (1) album metadata form, (2) image management (only visible in edit mode).

```blade
<div class="max-w-4xl">
    {{-- Back link --}}
    <div class="mb-6">
        <flux:button variant="ghost" icon="arrow-left"
                     :href="route('admin.gallery.index')" wire:navigate>
            Natrag
        </flux:button>
    </div>

    <flux:heading size="xl" class="mb-8">
        {{ $isEditing ? 'Uredi album' : 'Novi album' }}
    </flux:heading>

    {{-- Album metadata --}}
    <form wire:submit="save" class="space-y-6">
        <flux:field>
            <flux:label>Naziv albuma</flux:label>
            <flux:input wire:model="title" placeholder="npr. Državno prvenstvo 2026" />
            <flux:error name="title" />
        </flux:field>

        <flux:field>
            <flux:label>Opis</flux:label>
            <flux:textarea wire:model="description" placeholder="Kratak opis albuma..." rows="3" />
            <flux:error name="description" />
        </flux:field>

        <div class="grid sm:grid-cols-2 gap-6">
            <flux:field>
                <flux:label>Datum događaja</flux:label>
                <flux:input wire:model="event_date" type="date" />
                <flux:error name="event_date" />
            </flux:field>

            <flux:field>
                <flux:label>Kategorija</flux:label>
                <flux:select wire:model="category">
                    @foreach($categories as $cat)
                        <flux:select.option value="{{ $cat->value }}">
                            {{ $cat->label() }}
                        </flux:select.option>
                    @endforeach
                </flux:select>
                <flux:error name="category" />
            </flux:field>
        </div>

        <flux:field>
            <flux:label>
                <flux:checkbox wire:model="is_published" />
                Objavljeno (vidljivo na stranici)
            </flux:label>
        </flux:field>

        {{-- Image upload section --}}
        <flux:separator />

        <div>
            <flux:heading size="lg" class="mb-4">Fotografije</flux:heading>

            {{-- Upload area --}}
            <div class="border-2 border-dashed border-zinc-300 dark:border-zinc-600 rounded-xl p-8
                        text-center"
                 x-data x-on:dragover.prevent x-on:drop.prevent="
                    $refs.fileInput.files = $event.dataTransfer.files;
                    $refs.fileInput.dispatchEvent(new Event('change'))
                 ">
                <flux:icon name="cloud-arrow-up" class="size-10 text-zinc-400 mx-auto mb-3" />
                <p class="text-sm text-zinc-500 mb-3">
                    Povucite fotografije ovdje ili
                </p>
                <flux:button size="sm" x-on:click="$refs.fileInput.click()">
                    Odaberite datoteke
                </flux:button>
                <input type="file" x-ref="fileInput" wire:model="newImages" multiple
                       accept="image/*" class="hidden" />
            </div>

            {{-- Upload progress --}}
            <div wire:loading wire:target="newImages" class="mt-4">
                <div class="flex items-center gap-3 text-sm text-zinc-500">
                    <flux:icon name="arrow-path" class="size-4 animate-spin" />
                    Učitavanje fotografija...
                </div>
            </div>

            {{-- New images preview --}}
            @if(count($newImages))
                <div class="mt-4 grid grid-cols-4 sm:grid-cols-6 gap-2">
                    @foreach($newImages as $img)
                        <div class="aspect-square rounded-lg overflow-hidden bg-zinc-100">
                            <img src="{{ $img->temporaryUrl() }}"
                                 class="size-full object-cover" />
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <flux:button type="submit" variant="primary">
            {{ $isEditing ? 'Spremi promjene' : 'Kreiraj album' }}
        </flux:button>
    </form>

    {{-- Existing images (edit mode only) --}}
    @if($isEditing && count($existingImages))
        <flux:separator class="my-8" />

        <flux:heading size="lg" class="mb-4">
            Postojeće fotografije ({{ count($existingImages) }})
        </flux:heading>

        <div class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-6 gap-3">
            @foreach($existingImages as $image)
                <div wire:key="existing-{{ $image['id'] }}"
                     class="group relative aspect-square rounded-lg overflow-hidden bg-zinc-100">
                    <img src="{{ asset('storage/' . $image['thumbnail_path']) }}"
                         class="size-full object-cover" />

                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/50
                                transition-colors flex items-center justify-center
                                opacity-0 group-hover:opacity-100">
                        <div class="flex gap-1">
                            <button wire:click="setCover({{ $image['id'] }})"
                                    class="p-1.5 rounded bg-white/20 hover:bg-white/40
                                           text-white text-xs" title="Naslovnica">
                                <flux:icon name="star" class="size-4" />
                            </button>
                            <button wire:click="removeImage({{ $image['id'] }})"
                                    wire:confirm="Obrisati ovu fotografiju?"
                                    class="p-1.5 rounded bg-red-500/80 hover:bg-red-500
                                           text-white text-xs" title="Obriši">
                                <flux:icon name="trash" class="size-4" />
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
```

### 4E. Image Reordering

For v1, image reordering can be handled with a simple "sort_order" approach. The `updateOrder` method on the Livewire component accepts an array of IDs in the desired order and updates `sort_order` accordingly.

For the UI, a simple drag-and-drop could be implemented with `SortableJS` (a tiny dependency), but for v1 the images are simply displayed in upload order. Drag reordering can be added as a follow-up enhancement. The data model supports it already via `sort_order`.

---

## Phase 5: Routes (Complete)

**Modify**: `routes/web.php`

Final state:

```php
<?php

use Illuminate\Support\Facades\Route;

// Public routes
Route::view('/', 'pages.public.home')->name('home');
Route::view('/o-nama', 'pages.public.about')->name('about');
Route::view('/galerija', 'pages.public.gallery')->name('gallery');
Route::get('/galerija/{album:slug}', function (\App\Models\GalleryAlbum $album) {
    abort_unless($album->is_published, 404);
    $album->loadCount('images');
    return view('pages.public.gallery-album', compact('album'));
})->name('gallery.album');
Route::view('/kontakt', 'pages.public.kontakt')->name('contact');

// Admin routes
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->prefix('dashboard')->group(function () {
    Route::livewire('galerija', 'admin.gallery-album-index')->name('admin.gallery.index');
    Route::livewire('galerija/create', 'admin.gallery-album-form')->name('admin.gallery.create');
    Route::livewire('galerija/{album}/edit', 'admin.gallery-album-form')->name('admin.gallery.edit');
});

require __DIR__.'/settings.php';
```

The album detail route uses route-model binding with `{album:slug}` and an inline closure that checks `is_published`. This keeps unpublished albums hidden from the public while admin can access them via the dashboard.

---

## Phase 6: Transition Strategy

The old `GalleryGrid` component and its blade view should be replaced, not preserved alongside the new system. Here is the clean transition:

1. **Delete**: `app/Livewire/GalleryGrid.php`
2. **Delete**: `resources/views/livewire/gallery-grid.blade.php`
3. **Modify**: `resources/views/pages/public/gallery.blade.php` -- change `<livewire:gallery-grid />` to `<livewire:gallery-albums />`
4. **Create**: All new files listed below

There is no data to migrate -- the old component had only hardcoded placeholder data.

---

## Complete File List

### Files to CREATE (17 files):

| # | File | Purpose |
|---|------|---------|
| 1 | `app/Enums/GalleryCategory.php` | Category enum with labels and colors |
| 2 | `database/migrations/XXXX_create_gallery_albums_table.php` | Albums migration |
| 3 | `database/migrations/XXXX_create_gallery_images_table.php` | Images migration |
| 4 | `app/Models/GalleryAlbum.php` | Album model with scopes, relationships |
| 5 | `app/Models/GalleryImage.php` | Image model with file deletion on delete |
| 6 | `app/Services/ImageService.php` | Thumbnail generation via Intervention Image |
| 7 | `app/Livewire/GalleryAlbums.php` | Public album listing component |
| 8 | `resources/views/livewire/gallery-albums.blade.php` | Album cards grid view |
| 9 | `app/Livewire/AlbumImageGrid.php` | Public album detail image grid component |
| 10 | `resources/views/livewire/album-image-grid.blade.php` | Image grid + lightbox view |
| 11 | `resources/views/pages/public/gallery-album.blade.php` | Album detail page |
| 12 | `app/Livewire/Admin/GalleryAlbumIndex.php` | Admin album list component |
| 13 | `resources/views/livewire/admin/gallery-album-index.blade.php` | Admin album list view |
| 14 | `app/Livewire/Admin/GalleryAlbumForm.php` | Admin album create/edit component |
| 15 | `resources/views/livewire/admin/gallery-album-form.blade.php` | Admin album form view |
| 16 | `database/factories/GalleryAlbumFactory.php` | Album factory for testing |
| 17 | `database/factories/GalleryImageFactory.php` | Image factory for testing |

### Files to MODIFY (4 files):

| # | File | Change |
|---|------|--------|
| 1 | `routes/web.php` | Add album detail route + admin gallery routes |
| 2 | `resources/views/pages/public/gallery.blade.php` | Replace `<livewire:gallery-grid />` with `<livewire:gallery-albums />` |
| 3 | `resources/views/layouts/app/sidebar.blade.php` | Add Galerija link to admin sidebar |
| 4 | `resources/js/app.js` | Add `albumLightbox` Alpine component registration |

### Files to DELETE (2 files):

| # | File | Reason |
|---|------|--------|
| 1 | `app/Livewire/GalleryGrid.php` | Replaced by `GalleryAlbums` |
| 2 | `resources/views/livewire/gallery-grid.blade.php` | Replaced by `gallery-albums.blade.php` |

### Package to INSTALL:

- `composer require intervention/image` (Intervention Image v3)

### Artisan commands:

- `php artisan storage:link` (if not already run)
- `php artisan migrate`

---

## Implementation Order

The implementation must follow dependency order:

**Step 1: Foundation** (no dependencies)
1. Install Intervention Image: `composer require intervention/image`
2. Create `app/Enums/GalleryCategory.php`
3. Create both migrations
4. Create both models (`GalleryAlbum`, `GalleryImage`)
5. Create `app/Services/ImageService.php`
6. Create model factories

**Step 2: Public Gallery** (depends on Step 1)
7. Create `app/Livewire/GalleryAlbums.php` + blade view
8. Create `app/Livewire/AlbumImageGrid.php` + blade view
9. Create `resources/views/pages/public/gallery-album.blade.php`
10. Add Alpine lightbox component to `resources/js/app.js`
11. Add album detail route to `routes/web.php`
12. Modify `gallery.blade.php` to use new component

**Step 3: Admin System** (depends on Step 1)
13. Create `app/Livewire/Admin/GalleryAlbumIndex.php` + blade view
14. Create `app/Livewire/Admin/GalleryAlbumForm.php` + blade view
15. Add admin routes to `routes/web.php`
16. Add Galerija link to admin sidebar

**Step 4: Cleanup** (depends on Steps 2-3)
17. Delete `app/Livewire/GalleryGrid.php`
18. Delete `resources/views/livewire/gallery-grid.blade.php`
19. Run `php artisan migrate`
20. Run `php artisan storage:link` (if needed)

**Step 5: Testing** (depends on Step 4)
21. Write feature tests for public gallery routes
22. Write feature tests for admin CRUD operations
23. Write unit tests for ImageService

---

## Key Architectural Decisions and Tradeoffs

### Intervention Image: YES

As stated earlier, the bandwidth difference between serving 4000px originals in a grid vs. 400px thumbnails is roughly 50-100x per image. For a page showing 40 images, this is the difference between ~4MB and ~200MB page weight. Intervention Image is a single, well-maintained dependency that solves a real problem. The alternative (CSS-only `object-fit`) only affects rendering -- the browser still downloads the full file.

### Pagination vs. Infinite Scroll

Standard pagination is chosen over infinite scroll for several reasons:
- Simpler implementation (Livewire has pagination built in)
- Predictable behavior -- users can bookmark a specific page
- The footer is reachable (infinite scroll often makes footers unreachable)
- SEO-friendly (each page has a distinct URL)
- Infinite scroll can be added later if desired by switching `paginate()` to cursor pagination and using Livewire's `loadMore` pattern

### Album-Based vs. Flat Image Gallery

Album-based is the right choice because:
- Hundreds/thousands of images need organizational structure
- Competition photos naturally group by event
- Album covers provide visual hierarchy on the listing page
- Better scaling -- loading 12 album cards vs. 1000 image thumbnails on the main gallery page

### Alpine.js Lightbox vs. Separate Package

A custom Alpine.js lightbox is preferred over a package like GLightbox or Fancybox because:
- It integrates natively with the existing Alpine/Livewire stack
- It keeps the design system consistent (uses the project's colors, typography, animations)
- It is only ~50 lines of JS -- not worth a dependency
- It gives full control over behavior (preloading, keyboard nav, scroll lock)

### Route-Model Binding for Album Detail

Using `{album:slug}` route-model binding with an inline closure (rather than a controller) is consistent with the existing `Route::view()` pattern used by other public pages. The closure is minimal -- just a published check and eager-loading.

### Admin Using Existing App Layout

The admin interface uses `<x-layouts::app>` (sidebar layout) which is already configured. No new admin layout is needed. This keeps admin styling consistent with the existing settings pages.

### File Organization

Livewire admin components go in `app/Livewire/Admin/` namespace and views in `resources/views/livewire/admin/` directory. This follows a clean separation between public and admin concerns.

---

### Critical Files for Implementation

- `C:\Users\odin.perica\PhpstormProjects\judo-klub-bura\app\Livewire\GalleryGrid.php` - Current placeholder component to replace; study its category filtering pattern
- `C:\Users\odin.perica\PhpstormProjects\judo-klub-bura\resources\views\livewire\gallery-grid.blade.php` - Current masonry grid and lightbox implementation to replace; informs the new design
- `C:\Users\odin.perica\PhpstormProjects\judo-klub-bura\routes\web.php` - Must add album detail route and admin route group
- `C:\Users\odin.perica\PhpstormProjects\judo-klub-bura\resources\views\layouts\app\sidebar.blade.php` - Admin sidebar to extend with gallery link; pattern reference for sidebar items
- `C:\Users\odin.perica\PhpstormProjects\judo-klub-bura\resources\css\app.css` - Design system reference (colors, animations, utilities) that all new views must follow