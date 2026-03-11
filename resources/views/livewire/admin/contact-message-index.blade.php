<div class="mx-auto max-w-7xl p-6 lg:p-8">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <flux:heading size="xl">Poruke</flux:heading>
            <flux:text class="mt-1">
                Poruke s kontakt obrasca.
                @if($unreadCount > 0)
                    <flux:badge size="sm" color="red">{{ $unreadCount }} nepročitanih</flux:badge>
                @endif
            </flux:text>
        </div>
    </div>

    {{-- Messages list --}}
    <div class="space-y-3">
        @forelse($messages as $message)
            <div wire:key="msg-{{ $message->id }}" class="bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                {{-- Message header row --}}
                <div
                    class="flex items-center gap-4 p-4 cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-750 transition-colors"
                    wire:click="toggleExpand({{ $message->id }})"
                >
                    {{-- Unread indicator --}}
                    <div class="shrink-0">
                        @if(! $message->is_read)
                            <span class="block size-2.5 rounded-full bg-blue-500"></span>
                        @else
                            <span class="block size-2.5 rounded-full bg-transparent"></span>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <flux:heading size="sm" class="truncate {{ ! $message->is_read ? 'font-bold' : '' }}">
                                {{ $message->name }}
                            </flux:heading>
                            <flux:text size="sm" class="truncate">{{ $message->email }}</flux:text>
                        </div>
                        <flux:text size="sm" class="truncate mt-0.5">{{ $message->subject }}</flux:text>
                    </div>

                    {{-- Date --}}
                    <flux:text size="sm" class="shrink-0">{{ $message->created_at->format('d.m.Y. H:i') }}</flux:text>

                    {{-- Actions --}}
                    <div class="flex items-center gap-1 shrink-0" wire:click.stop>
                        <flux:button
                            wire:click="toggleRead({{ $message->id }})"
                            variant="ghost"
                            size="sm"
                            :icon="$message->is_read ? 'envelope' : 'envelope-open'"
                            :tooltip="$message->is_read ? 'Označi kao nepročitano' : 'Označi kao pročitano'"
                        />
                        <flux:button
                            wire:click="deleteMessage({{ $message->id }})"
                            wire:confirm="Jeste li sigurni da želite obrisati ovu poruku?"
                            variant="ghost"
                            size="sm"
                            icon="trash"
                            tooltip="Obriši"
                            class="text-red-500 hover:text-red-700"
                        />
                    </div>
                </div>

                {{-- Expanded message body --}}
                @if($expandedId === $message->id)
                    <div class="px-4 pb-4 pt-0 ml-10 border-t border-zinc-100 dark:border-zinc-700">
                        <div class="pt-4 text-sm text-zinc-700 dark:text-zinc-300 whitespace-pre-line">{{ $message->message }}</div>
                        <div class="mt-3">
                            <flux:button
                                href="mailto:{{ $message->email }}?subject=Re: {{ $message->subject }}"
                                variant="ghost"
                                size="sm"
                                icon="envelope"
                            >
                                Odgovori
                            </flux:button>
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div class="text-center py-16">
                <flux:icon name="envelope" class="size-12 mx-auto text-zinc-300 dark:text-zinc-600 mb-4" />
                <flux:heading size="sm">Nema poruka</flux:heading>
                <flux:text class="mt-1">Još nema pristiglih poruka s kontakt obrasca.</flux:text>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($messages->hasPages())
        <div class="mt-6">
            {{ $messages->links() }}
        </div>
    @endif
</div>
