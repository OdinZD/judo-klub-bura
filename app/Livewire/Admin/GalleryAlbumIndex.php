<?php

namespace App\Livewire\Admin;

use App\Models\GalleryAlbum;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class GalleryAlbumIndex extends Component
{
    use WithPagination;

    public function togglePublish(int $albumId): void
    {
        $album = GalleryAlbum::findOrFail($albumId);
        $album->update(['is_published' => ! $album->is_published]);
    }

    public function deleteAlbum(int $albumId): void
    {
        $album = GalleryAlbum::findOrFail($albumId);

        // Delete all images (triggers model events to remove files)
        $album->images->each->delete();
        $album->delete();
    }

    public function render(): View
    {
        return view('livewire.admin.gallery-album-index', [
            'albums' => GalleryAlbum::withCount('images')
                ->orderByDesc('created_at')
                ->paginate(15),
        ])->layout('layouts.app.sidebar');
    }
}
