<x-layouts.public>
    <x-page-hero title="Kontakt" subtitle="Imate pitanje ili se želite pridružiti? Javite nam se." />

    <section class="py-16 sm:py-20 lg:py-28">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-5 gap-12 lg:gap-16">
                {{-- Contact form (3 cols) --}}
                <div class="lg:col-span-3">
                    <x-section-heading
                        title="Pošaljite upit"
                        subtitle="Ispunite obrazac i javit ćemo vam se u najkraćem mogućem roku."
                        :centered="false"
                        tag="OBRAZAC"
                    />

                    <div class="mt-8">
                        <livewire:contact-form />
                    </div>
                </div>

                {{-- Sidebar info (2 cols) --}}
                <div class="lg:col-span-2 space-y-8">
                    {{-- Location --}}
                    <div>
                        <h3 class="font-display font-semibold text-lg text-slate-text flex items-center gap-2 mb-4">
                            <svg class="size-5 text-bura-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 0 1 15 0Z" />
                            </svg>
                            Lokacija
                        </h3>
                        <p class="text-slate-muted text-sm leading-relaxed">
                            Sportska dvorana Bura<br>
                            Obala 12<br>
                            21000 Split, Hrvatska
                        </p>
                    </div>

                    {{-- Contact details --}}
                    <div>
                        <h3 class="font-display font-semibold text-lg text-slate-text flex items-center gap-2 mb-4">
                            <svg class="size-5 text-bura-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                            </svg>
                            Kontakt podaci
                        </h3>
                        <ul class="space-y-2 text-sm">
                            <li>
                                <a href="tel:+385911234567" class="text-slate-muted hover:text-bura-500 transition-colors">+385 91 123 4567</a>
                            </li>
                            <li>
                                <a href="mailto:info@judo-bura.hr" class="text-slate-muted hover:text-bura-500 transition-colors">info@judo-bura.hr</a>
                            </li>
                        </ul>
                    </div>

                    {{-- Training schedule summary --}}
                    <div>
                        <h3 class="font-display font-semibold text-lg text-slate-text flex items-center gap-2 mb-4">
                            <svg class="size-5 text-bura-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            Raspored treninga
                        </h3>
                        @php $scheduleGroups = \App\Models\TrainingGroup::where('is_active', true)->orderBy('sort_order')->with('sessions')->get(); @endphp
                        <div class="space-y-3 text-sm">
                            @foreach($scheduleGroups as $group)
                                <div class="flex justify-between">
                                    <span class="text-slate-muted">{{ $group->name }} ({{ $group->age_range }})</span>
                                    <span class="font-medium text-slate-text">
                                        {{ $group->sessions->map(fn ($s) => $s->day_of_week->shortLabel().' '.$s->start_time.'-'.$s->end_time)->join(', ') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Map placeholder --}}
                    <div class="aspect-[4/3] rounded-2xl bg-bura-50 border border-bura-100 flex items-center justify-center">
                        <div class="text-center">
                            <svg class="size-12 text-bura-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z" />
                            </svg>
                            <span class="text-sm text-slate-muted">Karta lokacije</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>
