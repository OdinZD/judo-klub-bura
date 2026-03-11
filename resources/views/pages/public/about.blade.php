<x-layouts.public>
    {{-- ============================================= --}}
    {{-- PAGE HERO --}}
    {{-- ============================================= --}}
    <x-page-hero title="O nama" subtitle="Upoznajte klub koji živi duhom Jadrana i snagom juda." />

    {{-- ============================================= --}}
    {{-- CLUB STORY --}}
    {{-- ============================================= --}}
    <section class="py-16 sm:py-20 lg:py-28">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                {{-- Text --}}
                <div class="relative">
                    {{-- Decorative quote mark --}}
                    <span class="absolute -top-8 -left-4 text-bura-100 text-9xl font-display leading-none pointer-events-none select-none">&ldquo;</span>

                    <div class="relative">
                        <x-section-heading
                            title="Naša priča"
                            subtitle="Kako je jadranski vjetar postao naša inspiracija."
                            :centered="false"
                            tag="POVIJEST"
                        />

                        <div class="mt-6 space-y-4 text-slate-muted leading-relaxed">
                            <p>
                                Judo Klub Bura osnovan je 2005. godine u Splitu, gradu gdje se snaga mora susreće sa snagom čovjeka. Ime smo dobili po buri - moćnom jadranskom vjetru koji čisti, osvježava i pokreće sve pred sobom.
                            </p>
                            <p>
                                Naši osnivači, skupina entuzijasta s crnim pojasevima i velikom vizijom, željeli su stvoriti prostor gdje judo nije samo sport, već način života. Prostor gdje se uči poštovanje, razvija disciplina i grade prijateljstva koja traju cijeli život.
                            </p>
                            <p>
                                Danas smo ponosni na više od 200 aktivnih članova svih uzrasta, od malih judasa koji tek uče padove do iskusnih seniora koji se natječu na međunarodnoj razini.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Image --}}
                <div>
                    <div class="aspect-[4/3] rounded-2xl bg-bura-100 overflow-hidden">
                        <div class="w-full h-full bg-gradient-to-br from-bura-200 to-adriatic-100 flex items-center justify-center">
                            <svg class="size-16 text-bura-300" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================= --}}
    {{-- MISSION & VALUES --}}
    {{-- ============================================= --}}
    <section class="py-16 sm:py-20 lg:py-28 bg-bura-50 relative overflow-hidden">
        <div class="absolute inset-0 bg-wind-lines"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-section-heading
                title="Naše vrijednosti"
                subtitle="Principi koji nas vode svaki dan na tatamiju i izvan njega."
                tag="MISIJA"
            />

            <div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $values = [
                        ['icon' => 'bolt', 'color' => 'bura', 'title' => 'Snaga', 'desc' => 'Razvijamo fizičku i mentalnu snagu. Judo nas uči da je prava snaga u kontroli, ne u sili.'],
                        ['icon' => 'academic-cap', 'color' => 'amber', 'title' => 'Disciplina', 'desc' => 'Kroz redovite treninge i poštovanje pravila gradimo samodisciplinu koja se prenosi u svakodnevni život.'],
                        ['icon' => 'heart', 'color' => 'rose', 'title' => 'Poštovanje', 'desc' => 'Judo počinje i završava poklonom. Poštovanje prema protivniku, treneru i sebi je temelj svega.'],
                        ['icon' => 'users', 'color' => 'adriatic', 'title' => 'Zajedništvo', 'desc' => 'Bura nije samo klub - to je obitelj. Zajedno slavimo pobjede i učimo iz poraza.'],
                    ];
                @endphp

                @foreach($values as $value)
                    <div class="bg-white rounded-2xl p-6 border border-bura-100 text-center">
                        <div class="size-14 mx-auto flex items-center justify-center rounded-full bg-gradient-to-br from-{{ $value['color'] }}-100 to-{{ $value['color'] }}-50 text-{{ $value['color'] }}-500 mb-4">
                            <flux:icon :name="$value['icon']" class="size-7" />
                        </div>
                        <h3 class="font-display font-semibold text-lg text-slate-text mb-2">{{ $value['title'] }}</h3>
                        <p class="text-slate-muted text-sm leading-relaxed">{{ $value['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================================= --}}
    {{-- COACHES / TEAM --}}
    {{-- ============================================= --}}
    <section class="py-16 sm:py-20 lg:py-28">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-section-heading
                title="Naš tim"
                subtitle="Iskusni treneri posvećeni razvoju svakog člana."
                tag="TRENERI"
            />

            @php $coaches = \App\Models\Coach::where('is_active', true)->orderBy('sort_order')->get(); @endphp

            <div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($coaches as $coach)
                    <div class="group bg-white rounded-2xl p-6 border border-bura-100 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-bura-500/10">
                        <div class="size-20 mx-auto rounded-full bg-gradient-to-br from-bura-100 to-adriatic-100 flex items-center justify-center mb-4 overflow-hidden">
                            @if($coach->photo_url)
                                <img src="{{ $coach->photo_url }}" alt="{{ $coach->name }}" class="size-full object-cover">
                            @else
                                <svg class="size-8 text-bura-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                            @endif
                        </div>
                        <div class="text-center">
                            <h3 class="font-display font-semibold text-lg text-slate-text">{{ $coach->name }}</h3>
                            <p class="text-bura-500 text-sm font-medium">{{ $coach->role }}</p>
                            @if($coach->belt)
                                <div class="mt-2">
                                    <flux:badge size="sm" color="sky">{{ $coach->belt }}</flux:badge>
                                </div>
                            @endif
                            @if($coach->bio)
                                <p class="mt-3 text-slate-muted text-sm leading-relaxed">{{ $coach->bio }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================================= --}}
    {{-- ACHIEVEMENTS --}}
    {{-- ============================================= --}}
    <section class="relative py-16 sm:py-20 lg:py-28 bg-slate-text overflow-hidden">
        {{-- Grain overlay --}}
        <div class="absolute inset-0 bg-grain opacity-50 pointer-events-none"></div>
        {{-- Wind lines --}}
        <div class="absolute inset-0 opacity-10" style="background-image: repeating-linear-gradient(-45deg, transparent, transparent 10px, rgb(14 165 233 / 0.15) 10px, rgb(14 165 233 / 0.15) 12px)"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-section-heading
                title="Naši uspjesi"
                subtitle="Brojke koje govore o predanosti i trudu naših članova."
                :light="true"
                tag="POSTIGNUĆA"
            />

            <div class="mt-12 grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
                <x-stat-counter value="120+" label="Zlatnih medalja" :light="true" />
                <x-stat-counter value="85+" label="Srebrnih medalja" :light="true" />
                <x-stat-counter value="150+" label="Brončanih medalja" :light="true" />
                <x-stat-counter value="12" label="Državnih prvenstava" :light="true" />
            </div>
        </div>
    </section>
</x-layouts.public>
