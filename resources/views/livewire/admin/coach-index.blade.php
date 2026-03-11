<div class="mx-auto max-w-5xl p-6 lg:p-8">
    {{-- Header --}}
    <div class="mb-8">
        <flux:heading size="xl">Treneri</flux:heading>
        <flux:text class="mt-1">Upravljajte trenerskim timom.</flux:text>
    </div>

    {{-- Coaches list --}}
    <div class="space-y-3 mb-8">
        @forelse($coaches as $coach)
            <div wire:key="coach-{{ $coach->id }}" class="flex items-center gap-4 p-4 bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700">
                {{-- Photo --}}
                <div class="shrink-0 size-14 rounded-full overflow-hidden bg-gradient-to-br from-bura-100 to-adriatic-100 flex items-center justify-center">
                    @if($coach->photo_url)
                        <img src="{{ $coach->photo_url }}" alt="{{ $coach->name }}" class="size-full object-cover">
                    @else
                        <svg class="size-6 text-bura-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <flux:heading size="sm">{{ $coach->name }}</flux:heading>
                        @if($coach->belt)
                            <flux:badge size="sm" color="sky">{{ $coach->belt }}</flux:badge>
                        @endif
                        @if(! $coach->is_active)
                            <flux:badge size="sm" color="zinc">Neaktivan</flux:badge>
                        @endif
                    </div>
                    <flux:text size="sm">{{ $coach->role }}</flux:text>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-1 shrink-0">
                    <flux:button wire:click="moveCoach({{ $coach->id }}, 'up')" variant="ghost" size="sm" icon="chevron-up" tooltip="Pomakni gore" />
                    <flux:button wire:click="moveCoach({{ $coach->id }}, 'down')" variant="ghost" size="sm" icon="chevron-down" tooltip="Pomakni dolje" />
                    <flux:button wire:click="edit({{ $coach->id }})" variant="ghost" size="sm" icon="pencil-square" tooltip="Uredi" />
                    <flux:button wire:click="deleteCoach({{ $coach->id }})" wire:confirm="Obrisati ovog trenera?" variant="ghost" size="sm" icon="trash" tooltip="Obriši" class="text-red-500 hover:text-red-700" />
                </div>
            </div>
        @empty
            <div class="text-center py-16">
                <flux:icon name="users" class="size-12 mx-auto text-zinc-300 dark:text-zinc-600 mb-4" />
                <flux:heading size="sm">Nema trenera</flux:heading>
                <flux:text class="mt-1">Dodajte prvog trenera koristeći obrazac ispod.</flux:text>
            </div>
        @endforelse
    </div>

    {{-- Add/Edit form --}}
    <div class="bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700 p-6">
        <flux:heading size="sm" class="mb-4">{{ $editingId ? 'Uredi trenera' : 'Novi trener' }}</flux:heading>
        <form wire:submit="save" class="space-y-5">
            <div class="grid sm:grid-cols-3 gap-5">
                <flux:input wire:model="name" label="Ime i prezime" placeholder="Npr. Marko Jurić" required />
                <flux:input wire:model="role" label="Uloga" placeholder="Npr. Glavni trener" required />
                <flux:input wire:model="belt" label="Pojas" placeholder="Npr. 4. dan" />
            </div>

            <flux:textarea wire:model="bio" label="Biografija" placeholder="Kratka biografija trenera..." rows="3" />

            <div class="grid sm:grid-cols-2 gap-5">
                <div>
                    <flux:label>Fotografija</flux:label>
                    <input type="file" wire:model="photo" accept="image/*" class="mt-1 text-sm text-zinc-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-zinc-100 file:text-zinc-700 hover:file:bg-zinc-200" />
                    @error('photo') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="flex items-end">
                    <flux:checkbox wire:model="is_active" label="Aktivan" description="Prikazuj na javnoj stranici." />
                </div>
            </div>

            <div class="flex items-center gap-3">
                <flux:button type="submit" variant="primary">
                    {{ $editingId ? 'Spremi promjene' : 'Dodaj trenera' }}
                </flux:button>
                @if($editingId)
                    <flux:button wire:click="cancelEdit" variant="ghost">Odustani</flux:button>
                @endif
            </div>
        </form>
    </div>
</div>
