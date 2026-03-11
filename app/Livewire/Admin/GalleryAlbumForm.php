<?php

namespace App\Livewire\Admin;

use App\Enums\GalleryCategory;
use App\Models\GalleryAlbum;
use App\Models\GalleryImage;
use App\Services\ImageService;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

class GalleryAlbumForm extends Component
{
    use WithFileUploads;

    public ?GalleryAlbum $album = null;

    public string $title = '';

    public string $description = '';

    public string $event_date = '';

    public string $category = '';

    public bool $is_published = false;

    public array $newImages = [];

    public function mount(?GalleryAlbum $album = null): void
    {
        if ($album?->exists) {
            $this->album = $album;
            $this->title = $album->title;
            $this->description = $album->description ?? '';
            $this->event_date = $album->event_date->format('Y-m-d');
            $this->category = $album->category->value;
            $this->is_published = $album->is_published;
        }
    }

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'event_date' => ['required', 'date'],
            'category' => ['required', 'string', 'in:'.implode(',', array_column(GalleryCategory::cases(), 'value'))],
            'is_published' => ['boolean'],
            'newImages.*' => ['image', 'max:10240'],
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

        if ($this->album?->exists) {
            $this->album->update($albumData);
            $album = $this->album;
        } else {
            $album = GalleryAlbum::create($albumData);
        }

        // Process new image uploads
        if (! empty($this->newImages)) {
            $maxSort = $album->images()->max('sort_order') ?? -1;

            foreach ($this->newImages as $file) {
                $paths = $imageService->store($file, $album->id);

                $album->images()->create([
                    'image_path' => $paths['image_path'],
                    'thumbnail_path' => $paths['thumbnail_path'],
                    'sort_order' => ++$maxSort,
                ]);
            }

            $this->newImages = [];
        }

        session()->flash('message', $this->album?->exists ? 'Album je ažuriran.' : 'Album je kreiran.');

        $this->redirectRoute('admin.gallery.edit', ['album' => $album], navigate: true);
    }

    public function removeImage(int $imageId): void
    {
        $image = GalleryImage::findOrFail($imageId);

        if ($this->album && $image->gallery_album_id === $this->album->id) {
            // If this image was the cover, clear it
            if ($this->album->cover_image_path === $image->thumbnail_path) {
                $this->album->update(['cover_image_path' => null]);
            }

            $image->delete();
        }
    }

    public function setCover(int $imageId): void
    {
        $image = GalleryImage::findOrFail($imageId);

        if ($this->album && $image->gallery_album_id === $this->album->id) {
            $this->album->update(['cover_image_path' => $image->thumbnail_path]);
        }
    }

    public function render(): View
    {
        $existingImages = $this->album?->exists
            ? $this->album->images()->orderBy('sort_order')->get()
            : collect();

        return view('livewire.admin.gallery-album-form', [
            'existingImages' => $existingImages,
            'categories' => GalleryCategory::cases(),
            'isEditing' => $this->album?->exists ?? false,
        ])->layout('layouts.app.sidebar');
    }
}
