<x-layouts.public>
    <x-page-hero title="Rezultati" subtitle="Naši uspjesi na natjecanjima diljem Hrvatske i regije." />

    <section class="py-16 sm:py-20 lg:py-28">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @php $competitions = \App\Models\Competition::published()->with('results')->withCount('results')->orderByDesc('date')->paginate(12); @endphp

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($competitions as $competition)
                    @php
                        $medalCounts = $competition->results
                            ->groupBy(fn ($r) => $r->placement->value)
                            ->map->count();
                    @endphp
                    <a href="{{ route('results.show', $competition) }}" wire:navigate class="group bg-white rounded-2xl p-6 border border-bura-100 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-bura-500/10">
                        <div class="flex items-center gap-2 mb-3">
                            <flux:badge size="sm" color="sky">{{ $competition->date->format('d.m.Y.') }}</flux:badge>
                            @if($competition->location)
                                <flux:text size="sm">{{ $competition->location }}</flux:text>
                            @endif
                        </div>
                        <h3 class="font-display font-semibold text-lg text-slate-text group-hover:text-bura-600 transition-colors">
                            {{ $competition->name }}
                        </h3>

                        {{-- Medal summary --}}
                        @if($competition->results_count > 0)
                            <div class="mt-3 flex flex-wrap gap-2">
                                @if($medalCounts->get('gold', 0) > 0)
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-amber-600 bg-amber-50 px-2 py-1 rounded-full">
                                        <svg class="size-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm14 3c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1v-1h14v1z"/></svg>
                                        {{ $medalCounts->get('gold') }}x zlato
                                    </span>
                                @endif
                                @if($medalCounts->get('silver', 0) > 0)
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-zinc-600 bg-zinc-100 px-2 py-1 rounded-full">
                                        <svg class="size-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm14 3c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1v-1h14v1z"/></svg>
                                        {{ $medalCounts->get('silver') }}x srebro
                                    </span>
                                @endif
                                @if($medalCounts->get('bronze', 0) > 0)
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-orange-600 bg-orange-50 px-2 py-1 rounded-full">
                                        <svg class="size-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm14 3c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1v-1h14v1z"/></svg>
                                        {{ $medalCounts->get('bronze') }}x bronca
                                    </span>
                                @endif
                            </div>
                        @endif
                    </a>
                @empty
                    <div class="sm:col-span-2 lg:col-span-3 text-center py-16">
                        <svg class="size-12 mx-auto text-bura-200 mb-4" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 0 1-.982-3.172M9.497 14.25a7.454 7.454 0 0 0 .981-3.172M5.25 4.236c-.996.178-1.768-.767-1.768-1.768 0-1.044.774-2.004 1.768-2.177m0 3.945a48.354 48.354 0 0 1 15.5-.01m-15.5.01v-.5c0-1.044.774-2.004 1.768-2.177m13.732 2.677c.996.178 1.768-.767 1.768-1.768 0-1.044-.774-2.004-1.768-2.177m0 3.945v-.5c0-1.044-.774-2.004-1.768-2.177" />
                        </svg>
                        <h3 class="font-display font-semibold text-lg text-slate-text">Nema rezultata</h3>
                        <p class="mt-1 text-slate-muted">Uskoro ćemo objaviti rezultate natjecanja.</p>
                    </div>
                @endforelse
            </div>

            @if($competitions->hasPages())
                <div class="mt-10">
                    {{ $competitions->links() }}
                </div>
            @endif
        </div>
    </section>
</x-layouts.public>
