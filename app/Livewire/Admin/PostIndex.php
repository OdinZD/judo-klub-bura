<?php

namespace App\Livewire\Admin;

use App\Models\Post;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class PostIndex extends Component
{
    use WithPagination;

    public function togglePublish(int $postId): void
    {
        $post = Post::findOrFail($postId);
        $post->update([
            'is_published' => ! $post->is_published,
            'published_at' => ! $post->is_published ? ($post->published_at ?? now()) : $post->published_at,
        ]);
    }

    public function deletePost(int $postId): void
    {
        Post::findOrFail($postId)->delete();
    }

    public function render(): View
    {
        return view('livewire.admin.post-index', [
            'posts' => Post::orderByDesc('created_at')->paginate(15),
        ])->layout('layouts.app.sidebar');
    }
}
