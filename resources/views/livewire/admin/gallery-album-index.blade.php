<div class="mx-auto max-w-7xl p-6 lg:p-8">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <flux:heading size="xl">Galerija</flux:heading>
            <flux:text class="mt-1">Upravljajte albumima i fotografijama.</flux:text>
        </div>
        <flux:button href="{{ route('admin.gallery.create') }}" variant="primary" icon="plus" wire:navigate>
            Novi album
        </flux:button>
    </div>

    {{-- Albums list --}}
    <div class="space-y-3">
        @forelse($albums as $album)
            <div wire:key="album-{{ $album->id }}" class="flex items-center gap-4 p-4 bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700">
                {{-- Thumbnail --}}
                <div class="shrink-0 size-16 rounded-lg overflow-hidden bg-zinc-100 dark:bg-zinc-700">
                    @if($album->cover_url)
                        <img src="{{ $album->cover_url }}" alt="" class="size-full object-cover">
                    @else
                        <div class="size-full flex items-center justify-center">
                            <flux:icon name="photo" class="size-6 text-zinc-400" />
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <flux:heading size="sm" class="truncate">{{ $album->title }}</flux:heading>
                        <flux:badge size="sm" :color="$album->category->color()">
                            {{ $album->category->label() }}
                        </flux:badge>
                    </div>
                    <div class="flex items-center gap-3 mt-1">
                        <flux:text size="sm">{{ $album->event_date->format('d.m.Y.') }}</flux:text>
                        <flux:text size="sm">{{ $album->images_count }} fotografija</flux:text>
                        @if($album->is_published)
                            <flux:badge size="sm" color="green">Objavljeno</flux:badge>
                        @else
                            <flux:badge size="sm" color="zinc">Skica</flux:badge>
                        @endif
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-1 shrink-0">
                    <flux:button
                        wire:click="togglePublish({{ $album->id }})"
                        variant="ghost"
                        size="sm"
                        :icon="$album->is_published ? 'eye-slash' : 'eye'"
                        :tooltip="$album->is_published ? 'Sakrij' : 'Objavi'"
                    />
                    <flux:button
                        href="{{ route('admin.gallery.edit', $album) }}"
                        variant="ghost"
                        size="sm"
                        icon="pencil-square"
                        tooltip="Uredi"
                        wire:navigate
                    />
                    <flux:button
                        wire:click="deleteAlbum({{ $album->id }})"
                        wire:confirm="Jeste li sigurni da želite obrisati ovaj album? Sve fotografije će biti trajno izbrisane."
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
                <flux:icon name="photo" class="size-12 mx-auto text-zinc-300 dark:text-zinc-600 mb-4" />
                <flux:heading size="sm">Nema albuma</flux:heading>
                <flux:text class="mt-1">Započnite dodavanjem prvog albuma.</flux:text>
                <div class="mt-4">
                    <flux:button href="{{ route('admin.gallery.create') }}" variant="primary" icon="plus" wire:navigate>
                        Novi album
                    </flux:button>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($albums->hasPages())
        <div class="mt-6">
            {{ $albums->links() }}
        </div>
    @endif
</div>
