<?php

namespace App\Livewire;

use App\Enums\GalleryCategory;
use App\Models\GalleryAlbum;
use Illuminate\Contracts\View\View;
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

    public function render(): View
    {
        $query = GalleryAlbum::published()
            ->withCount('images')
            ->orderByDesc('event_date');

        if ($this->category !== 'sve') {
            $categoryEnum = GalleryCategory::tryFrom($this->category);
            if ($categoryEnum) {
                $query->byCategory($categoryEnum);
            }
        }

        return view('livewire.gallery-albums', [
            'albums' => $query->paginate(12),
            'categories' => GalleryCategory::cases(),
        ]);
    }
}
