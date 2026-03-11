<div class="mx-auto max-w-7xl p-6 lg:p-8">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <flux:heading size="xl">Novosti</flux:heading>
            <flux:text class="mt-1">Upravljajte objavama na stranici.</flux:text>
        </div>
        <flux:button href="{{ route('admin.posts.create') }}" variant="primary" icon="plus" wire:navigate>
            Nova objava
        </flux:button>
    </div>

    {{-- Posts list --}}
    <div class="space-y-3">
        @forelse($posts as $post)
            <div wire:key="post-{{ $post->id }}" class="flex items-center gap-4 p-4 bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700">
                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <flux:heading size="sm" class="truncate">{{ $post->title }}</flux:heading>
                    </div>
                    <div class="flex items-center gap-3 mt-1">
                        @if($post->published_at)
                            <flux:text size="sm">{{ $post->published_at->format('d.m.Y.') }}</flux:text>
                        @endif
                        @if($post->is_published)
                            <flux:badge size="sm" color="green">Objavljeno</flux:badge>
                        @else
                            <flux:badge size="sm" color="zinc">Skica</flux:badge>
                        @endif
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-1 shrink-0">
                    <flux:button
                        wire:click="togglePublish({{ $post->id }})"
                        variant="ghost"
                        size="sm"
                        :icon="$post->is_published ? 'eye-slash' : 'eye'"
                        :tooltip="$post->is_published ? 'Sakrij' : 'Objavi'"
                    />
                    <flux:button
                        href="{{ route('admin.posts.edit', $post) }}"
                        variant="ghost"
                        size="sm"
                        icon="pencil-square"
                        tooltip="Uredi"
                        wire:navigate
                    />
                    <flux:button
                        wire:click="deletePost({{ $post->id }})"
                        wire:confirm="Jeste li sigurni da želite obrisati ovu objavu?"
                        variant="ghost"
                        size="sm"
                        icon="trash"
                        tooltip="Obriši"
                        class="text-red-500 hover:text-red-700"
                    />
                </div>
            </div>
        @empty
            <div class="text-center py-16">
                <flux:icon name="document-text" class="size-12 mx-auto text-zinc-300 dark:text-zinc-600 mb-4" />
                <flux:heading size="sm">Nema objava</flux:heading>
                <flux:text class="mt-1">Započnite dodavanjem prve objave.</flux:text>
                <div class="mt-4">
                    <flux:button href="{{ route('admin.posts.create') }}" variant="primary" icon="plus" wire:navigate>
                        Nova objava
                    </flux:button>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($posts->hasPages())
        <div class="mt-6">
            {{ $posts->links() }}
        </div>
    @endif
</div>
