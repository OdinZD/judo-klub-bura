@php
    use App\Models\Post;
    use App\Models\Competition;
    use App\Models\GalleryAlbum;
    use App\Models\ContactMessage;
    use App\Models\Coach;
    use App\Models\TrainingGroup;

    $publishedPosts = Post::where('is_published', true)->count();
    $competitions = Competition::count();
    $albums = GalleryAlbum::count();
    $unreadMessages = ContactMessage::where('is_read', false)->count();
    $activeCoaches = Coach::where('is_active', true)->count();
    $activeGroups = TrainingGroup::where('is_active', true)->count();

    $recentMessages = ContactMessage::latest()->take(5)->get();
    $recentPosts = Post::latest()->take(5)->get();
@endphp

<x-layouts::app :title="__('Dashboard')">
    <div class="flex flex-col gap-6">

        {{-- Welcome Banner --}}
        <div class="bg-bura-gradient rounded-2xl px-6 py-8 text-white shadow-lg sm:px-8">
            <h1 class="text-2xl font-bold">Dobrodošli, {{ auth()->user()->name }}!</h1>
            <p class="mt-1 text-white/80">Upravljajte sadržajem kluba iz jednog mjesta.</p>
        </div>

        {{-- Stat Cards --}}
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            {{-- Objavljene novosti --}}
            <div class="flex items-center gap-4 rounded-2xl border border-bura-100 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-800">
                <div class="flex size-12 shrink-0 items-center justify-center rounded-xl bg-bura-500/10">
                    <flux:icon name="newspaper" class="size-6 text-bura-500" />
                </div>
                <div>
                    <p class="text-2xl font-bold text-bura-500">{{ $publishedPosts }}</p>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Objavljene novosti</p>
                </div>
            </div>

            {{-- Natjecanja --}}
            <div class="flex items-center gap-4 rounded-2xl border border-bura-100 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-800">
                <div class="flex size-12 shrink-0 items-center justify-center rounded-xl bg-adriatic-500/10">
                    <flux:icon name="trophy" class="size-6 text-adriatic-500" />
                </div>
                <div>
                    <p class="text-2xl font-bold text-adriatic-500">{{ $competitions }}</p>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Natjecanja</p>
                </div>
            </div>

            {{-- Albumi galerije --}}
            <div class="flex items-center gap-4 rounded-2xl border border-bura-100 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-800">
                <div class="flex size-12 shrink-0 items-center justify-center rounded-xl bg-bura-600/10">
                    <flux:icon name="photo" class="size-6 text-bura-600" />
                </div>
                <div>
                    <p class="text-2xl font-bold text-bura-600">{{ $albums }}</p>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Albumi galerije</p>
                </div>
            </div>

            {{-- Nepročitane poruke --}}
            <div class="flex items-center gap-4 rounded-2xl border border-bura-100 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-800">
                <div class="flex size-12 shrink-0 items-center justify-center rounded-xl bg-red-500/10">
                    <flux:icon name="envelope" class="size-6 text-red-500" />
                </div>
                <div>
                    <p class="text-2xl font-bold text-red-500">{{ $unreadMessages }}</p>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Nepročitane poruke</p>
                </div>
            </div>

            {{-- Aktivni treneri --}}
            <div class="flex items-center gap-4 rounded-2xl border border-bura-100 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-800">
                <div class="flex size-12 shrink-0 items-center justify-center rounded-xl bg-bura-700/10">
                    <flux:icon name="users" class="size-6 text-bura-700" />
                </div>
                <div>
                    <p class="text-2xl font-bold text-bura-700">{{ $activeCoaches }}</p>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Aktivni treneri</p>
                </div>
            </div>

            {{-- Grupe treninga --}}
            <div class="flex items-center gap-4 rounded-2xl border border-bura-100 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-800">
                <div class="flex size-12 shrink-0 items-center justify-center rounded-xl bg-adriatic-600/10">
                    <flux:icon name="clock" class="size-6 text-adriatic-600" />
                </div>
                <div>
                    <p class="text-2xl font-bold text-adriatic-600">{{ $activeGroups }}</p>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Grupe treninga</p>
                </div>
            </div>
        </div>

        {{-- Recent Items --}}
        <div class="grid gap-4 lg:grid-cols-2">

            {{-- Recent Messages --}}
            <div class="rounded-2xl border border-bura-100 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-800">
                <h2 class="mb-4 text-lg font-semibold text-zinc-900 dark:text-zinc-100">Zadnje poruke</h2>

                @if($recentMessages->isEmpty())
                    <p class="text-sm text-zinc-400">Nema poruka.</p>
                @else
                    <div class="divide-y divide-zinc-100 dark:divide-zinc-700">
                        @foreach($recentMessages as $message)
                            <div class="flex items-start gap-3 py-3 first:pt-0 last:pb-0">
                                @unless($message->is_read)
                                    <span class="mt-1.5 size-2 shrink-0 rounded-full bg-bura-500"></span>
                                @else
                                    <span class="mt-1.5 size-2 shrink-0"></span>
                                @endunless
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $message->name }}</p>
                                    <p class="truncate text-sm text-zinc-500 dark:text-zinc-400">{{ $message->subject }}</p>
                                </div>
                                <time class="shrink-0 text-xs text-zinc-400">{{ $message->created_at->format('d.m.Y') }}</time>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Recent Posts --}}
            <div class="rounded-2xl border border-bura-100 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-800">
                <h2 class="mb-4 text-lg font-semibold text-zinc-900 dark:text-zinc-100">Zadnje novosti</h2>

                @if($recentPosts->isEmpty())
                    <p class="text-sm text-zinc-400">Nema novosti.</p>
                @else
                    <div class="divide-y divide-zinc-100 dark:divide-zinc-700">
                        @foreach($recentPosts as $post)
                            <div class="flex items-center gap-3 py-3 first:pt-0 last:pb-0">
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $post->title }}</p>
                                </div>
                                @if($post->is_published)
                                    <span class="shrink-0 rounded-full bg-adriatic-500/10 px-2.5 py-0.5 text-xs font-medium text-adriatic-600 dark:text-adriatic-400">Objavljeno</span>
                                @else
                                    <span class="shrink-0 rounded-full bg-zinc-100 px-2.5 py-0.5 text-xs font-medium text-zinc-500 dark:bg-zinc-700 dark:text-zinc-400">Skica</span>
                                @endif
                                <time class="shrink-0 text-xs text-zinc-400">{{ $post->created_at->format('d.m.Y') }}</time>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.posts.create') }}" wire:navigate
               class="inline-flex items-center gap-2 rounded-xl bg-bura-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-bura-600">
                <flux:icon name="plus" class="size-4" />
                Nova novost
            </a>
            <a href="{{ route('admin.competitions.create') }}" wire:navigate
               class="inline-flex items-center gap-2 rounded-xl bg-adriatic-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-adriatic-600">
                <flux:icon name="plus" class="size-4" />
                Novo natjecanje
            </a>
            <a href="{{ route('admin.gallery.index') }}" wire:navigate
               class="inline-flex items-center gap-2 rounded-xl border border-bura-200 bg-white px-4 py-2.5 text-sm font-medium text-bura-600 transition hover:bg-bura-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-bura-400 dark:hover:bg-zinc-700">
                <flux:icon name="photo" class="size-4" />
                Upravljaj galerijom
            </a>
        </div>

    </div>
</x-layouts::app>
