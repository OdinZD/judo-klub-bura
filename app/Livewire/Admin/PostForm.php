<?php

namespace App\Livewire\Admin;

use App\Models\Post;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class PostForm extends Component
{
    public ?Post $post = null;

    public string $title = '';

    public string $content = '';

    public string $excerpt = '';

    public string $published_at = '';

    public bool $is_published = false;

    public function mount(?Post $post = null): void
    {
        if ($post?->exists) {
            $this->post = $post;
            $this->title = $post->title;
            $this->content = $post->content;
            $this->excerpt = $post->excerpt ?? '';
            $this->published_at = $post->published_at?->format('Y-m-d') ?? '';
            $this->is_published = $post->is_published;
        }
    }

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'published_at' => ['nullable', 'date'],
            'is_published' => ['boolean'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'content' => $this->content,
            'excerpt' => $this->excerpt ?: null,
            'published_at' => $this->published_at ?: null,
            'is_published' => $this->is_published,
        ];

        if ($this->is_published && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        if ($this->post?->exists) {
            $this->post->update($data);
            $post = $this->post;
        } else {
            $post = Post::create($data);
        }

        session()->flash('message', $this->post?->exists ? 'Objava je ažurirana.' : 'Objava je kreirana.');

        $this->redirectRoute('admin.posts.edit', ['post' => $post], navigate: true);
    }

    public function render(): View
    {
        return view('livewire.admin.post-form', [
            'isEditing' => $this->post?->exists ?? false,
        ])->layout('layouts.app.sidebar');
    }
}
