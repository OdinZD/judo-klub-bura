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
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" wire:loading.class="opacity-50" wire:target="setCategory">
        @forelse($albums as $album)
            <a
                href="{{ route('gallery.album', $album->slug) }}"
                wire:navigate
                wire:key="album-{{ $album->id }}"
                class="group bg-white rounded-2xl border border-bura-100 overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-bura-500/10"
            >
                {{-- Cover image --}}
                <div class="relative aspect-[4/3] overflow-hidden bg-gradient-to-br from-bura-100 to-adriatic-50">
                    @if($album->cover_url)
                        <img
                            src="{{ $album->cover_url }}"
                            alt="{{ $album->title }}"
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                            loading="lazy"
                        >
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="size-12 text-bura-300" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" />
                            </svg>
                        </div>
                    @endif

                    {{-- Category badge --}}
                    <div class="absolute top-3 right-3">
                        <flux:badge size="sm" :color="$album->category->color()">
                            {{ $album->category->label() }}
                        </flux:badge>
                    </div>

                    {{-- Image count badge --}}
                    <div class="absolute bottom-3 left-3">
                        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-white bg-black/50 backdrop-blur-sm rounded-full">
                            <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" />
                            </svg>
                            {{ $album->images_count }}
                        </span>
                    </div>
                </div>

                {{-- Album info --}}
                <div class="p-5">
                    <h3 class="font-display font-semibold text-lg text-slate-text group-hover:text-bura-600 transition-colors">
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
                <svg class="size-16 mx-auto text-bura-200 mb-4" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" />
                </svg>
                <p class="text-slate-muted text-lg">Nema albuma u ovoj kategoriji.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($albums->hasPages())
        <div class="mt-10">
            {{ $albums->links() }}
        </div>
    @endif
</div>
