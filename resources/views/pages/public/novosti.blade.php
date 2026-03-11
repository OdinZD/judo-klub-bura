<x-layouts.public>
    <x-page-hero title="Novosti" subtitle="Najnovije vijesti i događanja iz Judo Kluba Bura." />

    <section class="py-16 sm:py-20 lg:py-28">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @php $posts = \App\Models\Post::published()->orderByDesc('published_at')->paginate(10); @endphp

            <div class="space-y-8">
                @forelse($posts as $post)
                    <article class="group">
                        <a href="{{ route('news.show', $post) }}" wire:navigate class="block bg-white rounded-2xl p-6 sm:p-8 border border-bura-100 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-bura-500/10">
                            <div class="flex items-center gap-3 mb-3">
                                <flux:badge size="sm" color="sky">{{ $post->published_at->format('d.m.Y.') }}</flux:badge>
                            </div>
                            <h2 class="font-display font-semibold text-xl text-slate-text group-hover:text-bura-600 transition-colors">
                                {{ $post->title }}
                            </h2>
                            <p class="mt-3 text-slate-muted leading-relaxed line-clamp-3">
                                {{ $post->excerpt ?: Str::limit($post->content, 200) }}
                            </p>
                            <span class="mt-4 inline-flex items-center text-sm font-medium text-bura-500">
                                Pročitaj više
                                <svg class="ml-1 size-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                            </span>
                        </a>
                    </article>
                @empty
                    <div class="text-center py-16">
                        <svg class="size-12 mx-auto text-bura-200 mb-4" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6V7.5Z" />
                        </svg>
                        <h3 class="font-display font-semibold text-lg text-slate-text">Nema novosti</h3>
                        <p class="mt-1 text-slate-muted">Uskoro ćemo objaviti nove vijesti.</p>
                    </div>
                @endforelse
            </div>

            @if($posts->hasPages())
                <div class="mt-10">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </section>
</x-layouts.public>
