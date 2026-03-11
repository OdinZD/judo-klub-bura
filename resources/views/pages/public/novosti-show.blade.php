<x-layouts.public>
    <x-page-hero :title="$post->title" />

    <section class="py-16 sm:py-20 lg:py-28">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Breadcrumb --}}
            <nav class="mb-8 flex items-center gap-2 text-sm text-slate-muted">
                <a href="{{ route('news') }}" wire:navigate class="hover:text-bura-500 transition-colors">Novosti</a>
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                <span class="truncate">{{ $post->title }}</span>
            </nav>

            {{-- Date --}}
            <div class="mb-6">
                <flux:badge size="sm" color="sky">{{ $post->published_at->format('d.m.Y.') }}</flux:badge>
            </div>

            {{-- Content --}}
            <article class="prose prose-lg max-w-none text-slate-text prose-headings:font-display">
                {!! nl2br(e($post->content)) !!}
            </article>

            {{-- Back link --}}
            <div class="mt-12 pt-8 border-t border-bura-100">
                <a href="{{ route('news') }}" wire:navigate class="inline-flex items-center text-sm font-medium text-bura-500 hover:text-bura-600 transition-colors">
                    <svg class="mr-1 size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
                    Sve novosti
                </a>
            </div>
        </div>
    </section>
</x-layouts.public>
