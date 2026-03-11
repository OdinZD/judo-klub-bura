<div class="mx-auto max-w-4xl p-6 lg:p-8">
    {{-- Header --}}
    <div class="mb-8">
        <flux:button href="{{ route('admin.competitions.index') }}" variant="ghost" size="sm" icon="arrow-left" wire:navigate class="mb-4">
            Natrag
        </flux:button>
        <flux:heading size="xl">{{ $isEditing ? 'Uredi natjecanje' : 'Novo natjecanje' }}</flux:heading>
    </div>

    {{-- Flash message --}}
    @if(session('message'))
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 rounded-xl text-sm">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit="save" class="space-y-8">
        {{-- Competition details --}}
        <div class="bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700 p-6 space-y-5">
            <flux:heading size="sm">Detalji natjecanja</flux:heading>

            <flux:input wire:model="name" label="Naziv" placeholder="Npr. Državno prvenstvo 2026" required />

            <div class="grid sm:grid-cols-2 gap-5">
                <flux:input wire:model="date" label="Datum" type="date" required />
                <flux:input wire:model="location" label="Lokacija" placeholder="Npr. Zagreb, Hrvatska" />
            </div>

            <flux:textarea wire:model="description" label="Opis (opcionalno)" placeholder="Kratki opis natjecanja..." rows="3" />

            <flux:checkbox wire:model="is_published" label="Objavljeno" description="Natjecanje će biti vidljivo na javnoj stranici." />
        </div>

        {{-- Results section --}}
        <div class="bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700 p-6 space-y-5">
            <flux:heading size="sm">Rezultati</flux:heading>

            {{-- Existing results --}}
            @if(count($results) > 0)
                <div class="space-y-2">
                    @foreach($results as $index => $result)
                        <div wire:key="result-{{ $index }}" class="flex items-center gap-3 p-3 rounded-lg bg-zinc-50 dark:bg-zinc-750">
                            <div class="flex-1 min-w-0 grid sm:grid-cols-3 gap-2 text-sm">
                                <span class="font-medium text-zinc-900 dark:text-zinc-100">{{ $result['athlete_name'] }}</span>
                                <span class="text-zinc-600 dark:text-zinc-400">{{ $result['weight_category'] ?: '—' }}</span>
                                @php $placementType = \App\Enums\PlacementType::from($result['placement']); @endphp
                                <flux:badge size="sm" :color="$placementType->color()">{{ $placementType->label() }}</flux:badge>
                            </div>
                            <flux:button
                                wire:click="removeResult({{ $index }})"
                                variant="ghost"
                                size="sm"
                                icon="x-mark"
                                class="text-red-400 hover:text-red-600 shrink-0"
                            />
                        </div>
                    @endforeach
                </div>
            @else
                <flux:text size="sm" class="text-center py-4">Nema dodanih rezultata. Koristite obrazac ispod za dodavanje.</flux:text>
            @endif

            {{-- Add result form --}}
            <div class="border-t border-zinc-200 dark:border-zinc-600 pt-5">
                <flux:text size="sm" class="mb-3 font-medium">Dodaj rezultat</flux:text>
                <div class="flex items-end gap-3 flex-wrap">
                    <div class="flex-1 min-w-[150px]">
                        <flux:input wire:model="newAthleteName" placeholder="Ime sportaša" size="sm" />
                    </div>
                    <div class="w-32">
                        <flux:input wire:model="newWeightCategory" placeholder="Kategorija" size="sm" />
                    </div>
                    <div class="w-40">
                        <flux:select wire:model="newPlacement" size="sm">
                            @foreach($placements as $p)
                                <flux:select.option value="{{ $p->value }}">{{ $p->label() }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>
                    <flux:button wire:click="addResult" variant="primary" size="sm" icon="plus">Dodaj</flux:button>
                </div>
                @error('newAthleteName') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex items-center justify-end gap-3">
            <flux:button href="{{ route('admin.competitions.index') }}" variant="ghost" wire:navigate>Odustani</flux:button>
            <flux:button type="submit" variant="primary">
                {{ $isEditing ? 'Spremi promjene' : 'Kreiraj natjecanje' }}
            </flux:button>
        </div>
    </form>
</div>
