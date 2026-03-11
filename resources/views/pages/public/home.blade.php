<x-layouts.public>
    {{-- ============================================= --}}
    {{-- HERO SECTION --}}
    {{-- ============================================= --}}
    <section class="relative py-20 lg:py-32 bg-bura-gradient-soft overflow-hidden">
        {{-- Grain overlay --}}
        <div class="absolute inset-0 bg-grain opacity-50 pointer-events-none"></div>

        {{-- Animated wind streak --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute inset-y-0 w-[200%] animate-wind opacity-30">
                <div class="h-full w-full" style="background: linear-gradient(135deg, transparent 40%, rgb(14 165 233 / 0.08) 45%, rgb(14 165 233 / 0.12) 50%, rgb(14 165 233 / 0.08) 55%, transparent 60%)"></div>
            </div>
        </div>

        {{-- Blurred accent orbs --}}
        <div class="absolute top-10 right-1/4 w-96 h-96 bg-bura-500/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 left-1/6 w-80 h-80 bg-adriatic-500/5 rounded-full blur-3xl"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                {{-- Left column --}}
                <div>
                    <div class="animate-fade-up">
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-bura-100 text-bura-600 text-sm font-medium">
                            <span class="size-1.5 rounded-full bg-bura-500"></span>
                            Od 2005. godine
                        </span>
                    </div>

                    <h1 class="mt-6 font-display font-bold text-4xl sm:text-5xl lg:text-6xl tracking-tight text-slate-text leading-tight animate-fade-up animate-stagger-1" x-data="windLetters">
                        <span data-wind-letter class="inline-block">S</span><span data-wind-letter class="inline-block">n</span><span data-wind-letter class="inline-block">a</span><span data-wind-letter class="inline-block">g</span><span data-wind-letter class="inline-block">a</span><br><span class="text-bura-gradient"><span data-wind-letter class="inline-block">j</span><span data-wind-letter class="inline-block">a</span><span data-wind-letter class="inline-block">d</span><span data-wind-letter class="inline-block">r</span><span data-wind-letter class="inline-block">a</span><span data-wind-letter class="inline-block">n</span><span data-wind-letter class="inline-block">s</span><span data-wind-letter class="inline-block">k</span><span data-wind-letter class="inline-block">e</span></span> <span data-wind-letter class="inline-block">b</span><span data-wind-letter class="inline-block">u</span><span data-wind-letter class="inline-block">r</span><span data-wind-letter class="inline-block">e</span>
                    </h1>

                    <p class="mt-6 text-lg text-slate-muted max-w-lg leading-relaxed animate-fade-up animate-stagger-2">
                        Judo klub koji spaja tradiciju borilačkih vještina s duhom Jadrana. Treniramo snagu tijela i uma za sve uzraste.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-4 animate-fade-up animate-stagger-3">
                        <flux:button variant="primary" href="{{ route('contact') }}" wire:navigate class="hover:scale-[1.02] transition-transform">
                            Započni trenirati
                        </flux:button>
                        <flux:button variant="ghost" href="{{ route('about') }}" wire:navigate>
                            Saznaj više
                        </flux:button>
                    </div>

                    <div class="mt-10 flex gap-8 animate-fade-up animate-stagger-4">
                        <x-stat-counter value="15+" label="Godina iskustva" />
                        <x-stat-counter value="200+" label="Aktivnih članova" />
                        <x-stat-counter value="50+" label="Medalja" />
                    </div>
                </div>

                {{-- Right column --}}
                <div class="relative animate-fade-up animate-stagger-2">
                    <div class="aspect-[4/3] rounded-2xl bg-bura-100 shadow-2xl shadow-bura-500/10 overflow-hidden">
                        <div class="w-full h-full bg-gradient-to-br from-bura-200 to-bura-100 flex items-center justify-center">
                            <svg class="size-20 text-bura-300" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" />
                            </svg>
                        </div>
                    </div>

                    {{-- Floating badge --}}
                    <div class="absolute -bottom-4 -left-4 bg-white rounded-xl shadow-lg shadow-bura-500/10 p-4 border border-bura-100/50">
                        <div class="flex items-center gap-3">
                            <div class="size-10 rounded-full bg-bura-gradient flex items-center justify-center text-white">
                                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                                </svg>
                            </div>
                            <div>
                                <span class="font-display font-bold text-sm text-slate-text">Natjecanja</span>
                                <span class="block text-xs text-slate-muted">Državna & međunarodna</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================= --}}
    {{-- FEATURES - "Zašto Bura?" --}}
    {{-- ============================================= --}}
    <section class="py-16 sm:py-20 lg:py-28">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-section-heading
                title="Zašto Bura?"
                subtitle="Više od judo kluba - zajednica koja gradi snažne, disciplinirane i samouvjerene ljude."
                tag="PREDNOSTI"
            />

            <div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <x-feature-card
                    icon="bolt"
                    title="Snaga"
                    description="Razvijamo fizičku snagu i izdržljivost kroz strukturirane treninge prilagođene svim razinama."
                />
                <x-feature-card
                    icon="academic-cap"
                    title="Disciplina"
                    description="Judo uči poštovanje, samodisciplinu i upornost - vrijednosti koje oblikuju karakter."
                />
                <x-feature-card
                    icon="users"
                    title="Zajednica"
                    description="Postani dio obitelji. Naši članovi su jedni drugima podrška na tatamiju i izvan njega."
                />
                <x-feature-card
                    icon="trophy"
                    title="Natjecanja"
                    description="Redovito sudjelujemo na državnim i međunarodnim natjecanjima s izvrsnim rezultatima."
                />
                <x-feature-card
                    icon="shield-check"
                    title="Sigurnost"
                    description="Certificirani treneri i suvremena oprema osiguravaju sigurno okruženje za treniranje."
                />
                <x-feature-card
                    icon="sparkles"
                    title="Svi uzrasti"
                    description="Programi za djecu od 5 godina, mlade, odrasle i rekreativce. Svatko je dobrodošao."
                />
            </div>
        </div>
    </section>

    {{-- ============================================= --}}
    {{-- ABOUT PREVIEW --}}
    {{-- ============================================= --}}
    <section class="py-16 sm:py-20 lg:py-28 bg-bura-50/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-5 gap-8 lg:gap-16 items-center">
                {{-- Image (3 cols) --}}
                <div class="lg:col-span-3 relative lg:-mr-8">
                    <div class="aspect-[4/3] rounded-2xl bg-bura-100 overflow-hidden">
                        <div class="w-full h-full bg-gradient-to-br from-bura-200 to-adriatic-100 flex items-center justify-center">
                            <svg class="size-16 text-bura-300" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Text (2 cols) --}}
                <div class="lg:col-span-2">
                    <x-section-heading
                        title="Naša priča"
                        subtitle="Od male grupe entuzijasta do jednog od najaktivnijih judo klubova na Jadranu."
                        :centered="false"
                        tag="O NAMA"
                    />

                    <p class="mt-6 text-slate-muted leading-relaxed">
                        Judo Klub Bura osnovan je s vizijom da stvori prostor gdje se snaga tijela i uma razvijaju zajedno, nošeni duhom jadranske bure - vjetra koji čisti, osvježava i pokreće.
                    </p>

                    <div class="mt-8">
                        <flux:button variant="primary" href="{{ route('about') }}" wire:navigate class="hover:scale-[1.02] transition-transform">
                            Saznaj više o nama
                        </flux:button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================= --}}
    {{-- TRAINING SCHEDULE --}}
    {{-- ============================================= --}}
    <section class="py-16 sm:py-20 lg:py-28">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-section-heading
                title="Raspored treninga"
                subtitle="Odaberi grupu koja ti odgovara i započni svoju judo avanturu."
                tag="TRENINZI"
            />

            @php $trainingGroups = \App\Models\TrainingGroup::where('is_active', true)->orderBy('sort_order')->with('sessions')->get(); @endphp

            <div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($trainingGroups as $group)
                    <x-schedule-card
                        :title="$group->name"
                        :ageRange="$group->age_range"
                        :icon="$group->icon"
                        :iconColor="$group->icon_color"
                        :times="$group->sessions->map(fn ($s) => ['day' => $s->day_of_week->label(), 'time' => $s->start_time.' - '.$s->end_time])->toArray()"
                    />
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================================= --}}
    {{-- LATEST NEWS --}}
    {{-- ============================================= --}}
    @php $latestPosts = \App\Models\Post::published()->orderByDesc('published_at')->take(3)->get(); @endphp
    @if($latestPosts->count() > 0)
        <section class="py-16 sm:py-20 lg:py-28 bg-bura-50/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <x-section-heading
                    title="Najnovije"
                    subtitle="Pratite što se događa u našem klubu."
                    tag="NOVOSTI"
                />

                <div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($latestPosts as $post)
                        <a href="{{ route('news.show', $post) }}" wire:navigate class="group bg-white rounded-2xl p-6 border border-bura-100 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-bura-500/10">
                            <flux:text size="sm" class="text-bura-500 font-medium">{{ $post->published_at->format('d.m.Y.') }}</flux:text>
                            <h3 class="mt-2 font-display font-semibold text-lg text-slate-text group-hover:text-bura-600 transition-colors">{{ $post->title }}</h3>
                            <p class="mt-2 text-slate-muted text-sm leading-relaxed line-clamp-3">{{ $post->excerpt ?: Str::limit($post->content, 120) }}</p>
                            <span class="mt-4 inline-flex items-center text-sm font-medium text-bura-500">
                                Pročitaj više
                                <svg class="ml-1 size-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                            </span>
                        </a>
                    @endforeach
                </div>

                <div class="mt-8 text-center">
                    <flux:button variant="ghost" href="{{ route('news') }}" wire:navigate>
                        Sve novosti
                    </flux:button>
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================= --}}
    {{-- CTA SECTION --}}
    {{-- ============================================= --}}
    <section class="relative py-16 sm:py-20 lg:py-28 bg-bura-gradient overflow-hidden">
        {{-- Wind line overlay --}}
        <div class="absolute inset-0 bg-wind-lines opacity-20" style="background-image: repeating-linear-gradient(-45deg, transparent, transparent 10px, rgb(255 255 255 / 0.06) 10px, rgb(255 255 255 / 0.06) 12px)"></div>

        {{-- Grain overlay --}}
        <div class="absolute inset-0 bg-grain opacity-30 pointer-events-none"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:pl-12">
                <x-section-heading
                    title="Spreman za izazov?"
                    subtitle="Pridruži se Buri i otkrij snagu u sebi. Prvi trening je besplatan."
                    :centered="false"
                    :light="true"
                />

                <div class="mt-8 flex flex-wrap gap-4">
                    <flux:button href="{{ route('contact') }}" wire:navigate class="bg-white text-bura-600 hover:bg-white/90 hover:scale-[1.02] transition-all">
                        Kontaktiraj nas
                    </flux:button>
                    <flux:button href="{{ route('about') }}" wire:navigate class="border border-white/30 text-white hover:bg-white/10 transition-all">
                        Više o klubu
                    </flux:button>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>
