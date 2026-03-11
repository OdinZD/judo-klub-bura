<div class="mx-auto max-w-5xl p-6 lg:p-8">
    {{-- Header --}}
    <div class="mb-8">
        <flux:heading size="xl">Raspored treninga</flux:heading>
        <flux:text class="mt-1">Upravljajte grupama i terminima treninga.</flux:text>
    </div>

    {{-- Existing groups --}}
    <div class="space-y-6">
        @foreach($groups as $group)
            <div wire:key="group-{{ $group->id }}" class="bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                {{-- Group header --}}
                <div class="flex items-center gap-4 p-4 border-b border-zinc-100 dark:border-zinc-700">
                    @if($editingGroupId === $group->id)
                        {{-- Edit mode --}}
                        <div class="flex-1 grid sm:grid-cols-4 gap-3">
                            <flux:input wire:model="editGroupName" placeholder="Naziv" size="sm" />
                            <flux:input wire:model="editGroupAgeRange" placeholder="Dobna skupina" size="sm" />
                            <flux:input wire:model="editGroupIcon" placeholder="Ikona (npr. heart)" size="sm" />
                            <flux:input wire:model="editGroupIconColor" placeholder="Boja (npr. text-rose-500)" size="sm" />
                        </div>
                        <div class="flex items-center gap-1 shrink-0">
                            <flux:button wire:click="saveGroup" variant="primary" size="sm" icon="check">Spremi</flux:button>
                            <flux:button wire:click="cancelEditGroup" variant="ghost" size="sm" icon="x-mark">Odustani</flux:button>
                        </div>
                    @else
                        {{-- View mode --}}
                        <div class="size-10 flex items-center justify-center rounded-full {{ str_replace('text-', 'bg-', $group->icon_color) }}/10 {{ $group->icon_color }} shrink-0">
                            <flux:icon :name="$group->icon" class="size-5" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <flux:heading size="sm">{{ $group->name }}</flux:heading>
                                <flux:text size="sm">{{ $group->age_range }}</flux:text>
                                @if(! $group->is_active)
                                    <flux:badge size="sm" color="zinc">Neaktivno</flux:badge>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-1 shrink-0">
                            <flux:button wire:click="moveGroup({{ $group->id }}, 'up')" variant="ghost" size="sm" icon="chevron-up" tooltip="Pomakni gore" />
                            <flux:button wire:click="moveGroup({{ $group->id }}, 'down')" variant="ghost" size="sm" icon="chevron-down" tooltip="Pomakni dolje" />
                            <flux:button wire:click="toggleGroupActive({{ $group->id }})" variant="ghost" size="sm" :icon="$group->is_active ? 'eye-slash' : 'eye'" :tooltip="$group->is_active ? 'Deaktiviraj' : 'Aktiviraj'" />
                            <flux:button wire:click="startEditGroup({{ $group->id }})" variant="ghost" size="sm" icon="pencil-square" tooltip="Uredi" />
                            <flux:button wire:click="deleteGroup({{ $group->id }})" wire:confirm="Obrisati ovu grupu i sve njene termine?" variant="ghost" size="sm" icon="trash" tooltip="Obriši" class="text-red-500 hover:text-red-700" />
                        </div>
                    @endif
                </div>

                {{-- Sessions --}}
                <div class="p-4">
                    @if($group->sessions->count() > 0)
                        <div class="space-y-2">
                            @foreach($group->sessions as $session)
                                <div wire:key="session-{{ $session->id }}" class="flex items-center justify-between text-sm py-1.5 px-3 rounded-lg bg-zinc-50 dark:bg-zinc-750">
                                    <span class="text-zinc-700 dark:text-zinc-300">{{ $session->day_of_week->label() }}</span>
                                    <div class="flex items-center gap-3">
                                        <span class="font-medium text-zinc-900 dark:text-zinc-100">{{ $session->start_time }} - {{ $session->end_time }}</span>
                                        <flux:button wire:click="removeSession({{ $session->id }})" wire:confirm="Obrisati ovaj termin?" variant="ghost" size="sm" icon="x-mark" class="text-red-400 hover:text-red-600" />
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <flux:text size="sm" class="text-center py-2">Nema dodanih termina.</flux:text>
                    @endif

                    {{-- Add session form --}}
                    @if($addingSessionGroupId === $group->id)
                        <div class="mt-3 flex items-end gap-3 p-3 rounded-lg border border-zinc-200 dark:border-zinc-600 bg-zinc-50 dark:bg-zinc-750">
                            <flux:select wire:model="newSessionDay" label="Dan" size="sm" class="w-40">
                                @foreach($days as $day)
                                    <flux:select.option value="{{ $day->value }}">{{ $day->label() }}</flux:select.option>
                                @endforeach
                            </flux:select>
                            <flux:input wire:model="newSessionStart" label="Od" type="time" size="sm" class="w-28" />
                            <flux:input wire:model="newSessionEnd" label="Do" type="time" size="sm" class="w-28" />
                            <flux:button wire:click="addSession" variant="primary" size="sm" icon="plus">Dodaj</flux:button>
                            <flux:button wire:click="cancelAddSession" variant="ghost" size="sm">Odustani</flux:button>
                        </div>
                    @else
                        <div class="mt-3">
                            <flux:button wire:click="showAddSession({{ $group->id }})" variant="ghost" size="sm" icon="plus">
                                Dodaj termin
                            </flux:button>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    {{-- Add new group form --}}
    <div class="mt-8 bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700 p-6">
        <flux:heading size="sm" class="mb-4">Nova grupa</flux:heading>
        <form wire:submit="addGroup" class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <flux:input wire:model="newGroupName" label="Naziv" placeholder="Npr. Mali judasi" required />
            <flux:input wire:model="newGroupAgeRange" label="Dobna skupina" placeholder="Npr. 5 - 10 godina" required />
            <flux:input wire:model="newGroupIcon" label="Ikona" placeholder="heart, fire, bolt..." />
            <flux:input wire:model="newGroupIconColor" label="Boja ikone" placeholder="text-rose-500" />
            <div class="sm:col-span-2 lg:col-span-4">
                <flux:button type="submit" variant="primary" icon="plus">Dodaj grupu</flux:button>
            </div>
        </form>
    </div>
</div>
