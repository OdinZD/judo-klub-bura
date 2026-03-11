<?php

namespace App\Livewire;

use App\Models\GalleryAlbum;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class AlbumImageGrid extends Component
{
    use WithPagination;

    public GalleryAlbum $album;

    public function render(): View
    {
        $images = $this->album->images()->paginate(40);

        return view('livewire.album-image-grid', [
            'images' => $images,
        ]);
    }
}
