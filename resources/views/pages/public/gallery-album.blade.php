<x-layouts.public>
    {{-- Breadcrumb hero --}}
    <section class="relative py-12 lg:py-16 bg-bura-gradient-soft overflow-hidden">
        <div class="absolute inset-0 bg-wind-lines"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-sm text-slate-muted mb-6">
                <a href="{{ route('gallery') }}" wire:navigate class="hover:text-bura-600 transition-colors">Galerija</a>
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
                <span class="text-slate-text font-medium">{{ $album->title }}</span>
            </nav>

            {{-- Album header --}}
            <div class="flex flex-wrap items-center gap-3 mb-3">
                <flux:badge size="sm" :color="$album->category->color()">
                    {{ $album->category->label() }}
                </flux:badge>
                <span class="text-sm text-slate-muted">{{ $album->event_date->translatedFormat('d. F Y.') }}</span>
                <span class="text-sm text-slate-muted">&middot;</span>
                <span class="text-sm text-slate-muted">{{ $album->images_count }} {{ trans_choice('fotografija|fotografije|fotografija', $album->images_count) }}</span>
            </div>

            <h1 class="font-display font-bold text-3xl lg:text-4xl text-slate-text">{{ $album->title }}</h1>

            @if($album->description)
                <p class="mt-3 text-slate-muted max-w-2xl leading-relaxed">{{ $album->description }}</p>
            @endif
        </div>
    </section>

    {{-- Image grid --}}
    <section class="py-10 sm:py-14 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <livewire:album-image-grid :album="$album" />
        </div>
    </section>
</x-layouts.public>
