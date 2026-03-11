<div class="mx-auto max-w-7xl p-6 lg:p-8">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <flux:heading size="xl">Rezultati natjecanja</flux:heading>
            <flux:text class="mt-1">Upravljajte natjecanjima i rezultatima.</flux:text>
        </div>
        <flux:button href="{{ route('admin.competitions.create') }}" variant="primary" icon="plus" wire:navigate>
            Novo natjecanje
        </flux:button>
    </div>

    {{-- Competitions list --}}
    <div class="space-y-3">
        @forelse($competitions as $competition)
            <div wire:key="comp-{{ $competition->id }}" class="flex items-center gap-4 p-4 bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700">
                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <flux:heading size="sm" class="truncate">{{ $competition->name }}</flux:heading>
                    </div>
                    <div class="flex items-center gap-3 mt-1">
                        <flux:text size="sm">{{ $competition->date->format('d.m.Y.') }}</flux:text>
                        @if($competition->location)
                            <flux:text size="sm">{{ $competition->location }}</flux:text>
                        @endif
                        <flux:text size="sm">{{ $competition->results_count }} rezultata</flux:text>
                        @if($competition->is_published)
                            <flux:badge size="sm" color="green">Objavljeno</flux:badge>
                        @else
                            <flux:badge size="sm" color="zinc">Skica</flux:badge>
                        @endif
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-1 shrink-0">
                    <flux:button
                        wire:click="togglePublish({{ $competition->id }})"
                        variant="ghost"
                        size="sm"
                        :icon="$competition->is_published ? 'eye-slash' : 'eye'"
                        :tooltip="$competition->is_published ? 'Sakrij' : 'Objavi'"
                    />
                    <flux:button
                        href="{{ route('admin.competitions.edit', $competition) }}"
                        variant="ghost"
                        size="sm"
                        icon="pencil-square"
                        tooltip="Uredi"
                        wire:navigate
                    />
                    <flux:button
                        wire:click="deleteCompetition({{ $competition->id }})"
                        wire:confirm="Jeste li sigurni da želite obrisati ovo natjecanje? Svi rezultati će biti izbrisani."
                        variant="ghost"
                        size="sm"
                        icon="trash"
                        tooltip="Obriši"
                        class="text-red-500 hover:text-red-700"
                    />
                </div>
            </div>
        @empty
            <div class="text-center py-16">
                <flux:icon name="trophy" class="size-12 mx-auto text-zinc-300 dark:text-zinc-600 mb-4" />
                <flux:heading size="sm">Nema natjecanja</flux:heading>
                <flux:text class="mt-1">Započnite dodavanjem prvog natjecanja.</flux:text>
                <div class="mt-4">
                    <flux:button href="{{ route('admin.competitions.create') }}" variant="primary" icon="plus" wire:navigate>
                        Novo natjecanje
                    </flux:button>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($competitions->hasPages())
        <div class="mt-6">
            {{ $competitions->links() }}
        </div>
    @endif
</div>
