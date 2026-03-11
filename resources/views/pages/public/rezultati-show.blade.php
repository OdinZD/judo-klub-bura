<x-layouts.public>
    <x-page-hero :title="$competition->name" :subtitle="$competition->location" />

    <section class="py-16 sm:py-20 lg:py-28">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Breadcrumb --}}
            <nav class="mb-8 flex items-center gap-2 text-sm text-slate-muted">
                <a href="{{ route('results') }}" wire:navigate class="hover:text-bura-500 transition-colors">Rezultati</a>
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                <span class="truncate">{{ $competition->name }}</span>
            </nav>

            {{-- Competition header --}}
            <div class="mb-10">
                <div class="flex items-center gap-3 mb-4">
                    <flux:badge size="sm" color="sky">{{ $competition->date->format('d.m.Y.') }}</flux:badge>
                    @if($competition->location)
                        <flux:text size="sm">{{ $competition->location }}</flux:text>
                    @endif
                </div>
                @if($competition->description)
                    <p class="text-slate-muted leading-relaxed">{!! nl2br(e($competition->description)) !!}</p>
                @endif
            </div>

            {{-- Results grouped by placement --}}
            @php
                $grouped = $competition->results->sortBy(fn ($r) => array_search($r->placement->value, ['gold', 'silver', 'bronze', 'fifth', 'seventh', 'participation']))->groupBy(fn ($r) => $r->placement->value);
            @endphp

            @if($grouped->count() > 0)
                <div class="space-y-6">
                    @foreach($grouped as $placement => $results)
                        @php $type = \App\Enums\PlacementType::from($placement); @endphp
                        <div>
                            <h3 class="font-display font-semibold text-lg text-slate-text flex items-center gap-2 mb-3">
                                <flux:badge size="sm" :color="$type->color()">{{ $type->label() }}</flux:badge>
                                <span class="text-sm font-normal text-slate-muted">({{ $results->count() }})</span>
                            </h3>
                            <div class="bg-white rounded-xl border border-bura-100 overflow-hidden">
                                <table class="w-full">
                                    <thead class="bg-bura-50/50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-muted">Sportaš</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-muted">Kategorija</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-bura-50">
                                        @foreach($results as $result)
                                            <tr>
                                                <td class="px-4 py-3 text-sm font-medium text-slate-text">{{ $result->athlete_name }}</td>
                                                <td class="px-4 py-3 text-sm text-slate-muted">{{ $result->weight_category ?? '—' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16">
                    <p class="text-slate-muted">Još nema unesenih rezultata za ovo natjecanje.</p>
                </div>
            @endif

            {{-- Back link --}}
            <div class="mt-12 pt-8 border-t border-bura-100">
                <a href="{{ route('results') }}" wire:navigate class="inline-flex items-center text-sm font-medium text-bura-500 hover:text-bura-600 transition-colors">
                    <svg class="mr-1 size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
                    Sva natjecanja
                </a>
            </div>
        </div>
    </section>
</x-layouts.public>
