<div class="mx-auto max-w-4xl p-6 lg:p-8">
    {{-- Header --}}
    <div class="mb-8">
        <flux:button href="{{ route('admin.gallery.index') }}" variant="ghost" size="sm" icon="arrow-left" wire:navigate class="mb-4">
            Natrag
        </flux:button>
        <flux:heading size="xl">{{ $isEditing ? 'Uredi album' : 'Novi album' }}</flux:heading>
    </div>

    {{-- Flash message --}}
    @if(session('message'))
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 rounded-xl text-sm">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit="save" class="space-y-8">
        {{-- Album details --}}
        <div class="bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700 p-6 space-y-5">
            <flux:heading size="sm">Detalji albuma</flux:heading>

            <flux:input wire:model="title" label="Naslov" placeholder="Npr. Državno prvenstvo 2025" required />

            <flux:textarea wire:model="description" label="Opis" placeholder="Kratki opis albuma..." rows="3" />

            <div class="grid sm:grid-cols-2 gap-5">
                <flux:input wire:model="event_date" label="Datum događaja" type="date" required />

                <flux:select wire:model="category" label="Kategorija" placeholder="Odaberite kategoriju">
                    @foreach($categories as $cat)
                        <flux:select.option value="{{ $cat->value }}">{{ $cat->label() }}</flux:select.option>
                    @endforeach
                </flux:select>
            </div>

            <flux:checkbox wire:model="is_published" label="Objavljeno" description="Album će biti vidljiv na javnoj stranici." />
        </div>

        {{-- Image upload --}}
        <div class="bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700 p-6 space-y-5">
            <flux:heading size="sm">Fotografije</flux:heading>

            {{-- Drag & drop zone --}}
            <div
                x-data="{ dragging: false }"
                x-on:dragover.prevent="dragging = true"
                x-on:dragleave.prevent="dragging = false"
                x-on:drop.prevent="dragging = false; $refs.fileInput.files = $event.dataTransfer.files; $refs.fileInput.dispatchEvent(new Event('change'))"
                class="relative border-2 border-dashed rounded-xl p-8 text-center transition-colors"
                x-bind:class="dragging ? 'border-blue-400 bg-blue-50 dark:bg-blue-900/20' : 'border-zinc-300 dark:border-zinc-600 hover:border-zinc-400'"
            >
                <input
                    x-ref="fileInput"
                    type="file"
                    wire:model="newImages"
                    multiple
                    accept="image/*"
                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                >
                <div class="pointer-events-none">
                    <flux:icon name="cloud-arrow-up" class="size-10 mx-auto text-zinc-400 mb-3" />
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">
                        <span class="font-medium text-blue-600 dark:text-blue-400">Kliknite za odabir</span>
                        ili povucite fotografije ovdje
                    </p>
                    <p class="mt-1 text-xs text-zinc-500">JPG, PNG, WebP do 10 MB</p>
                </div>
            </div>

            {{-- Upload progress --}}
            <div wire:loading wire:target="newImages" class="text-sm text-blue-600 dark:text-blue-400 flex items-center gap-2">
                <svg class="size-4 animate-spin" viewBox="0 0 24 24" fill="none">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                Učitavanje fotografija...
            </div>

            {{-- New images preview --}}
            @if($newImages)
                <div>
                    <flux:text size="sm" class="mb-3">Nove fotografije ({{ count($newImages) }})</flux:text>
                    <div class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-5 gap-3">
                        @foreach($newImages as $index => $image)
                            <div class="relative aspect-square rounded-lg overflow-hidden bg-zinc-100 dark:bg-zinc-700">
                                <img src="{{ $image->temporaryUrl() }}" alt="" class="size-full object-cover">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Existing images grid (edit mode) --}}
            @if($isEditing && $existingImages->count() > 0)
                <div>
                    <flux:text size="sm" class="mb-3">Postojeće fotografije ({{ $existingImages->count() }})</flux:text>
                    <div class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-5 gap-3">
                        @foreach($existingImages as $image)
                            <div wire:key="existing-{{ $image->id }}" class="relative group aspect-square rounded-lg overflow-hidden bg-zinc-100 dark:bg-zinc-700">
                                <img src="{{ $image->thumbnail_url }}" alt="{{ $image->caption }}" class="size-full object-cover">

                                {{-- Cover indicator --}}
                                @if($album?->cover_image_path === $image->thumbnail_path)
                                    <div class="absolute top-1.5 left-1.5">
                                        <span class="inline-flex items-center justify-center size-6 rounded-full bg-amber-500 text-white">
                                            <svg class="size-3.5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                            </svg>
                                        </span>
                                    </div>
                                @endif

                                {{-- Hover actions --}}
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-all duration-200 flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100">
                                    <button
                                        type="button"
                                        wire:click="setCover({{ $image->id }})"
                                        class="p-2 rounded-full bg-white/90 hover:bg-white text-amber-600 transition-colors"
                                        title="Postavi kao naslovnu"
                                    >
                                        <svg class="size-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                        </svg>
                                    </button>
                                    <button
                                        type="button"
                                        wire:click="removeImage({{ $image->id }})"
                                        wire:confirm="Obrisati ovu fotografiju?"
                                        class="p-2 rounded-full bg-white/90 hover:bg-white text-red-600 transition-colors"
                                        title="Obriši"
                                    >
                                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @error('newImages.*')
                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Submit --}}
        <div class="flex items-center justify-end gap-3">
            <flux:button href="{{ route('admin.gallery.index') }}" variant="ghost" wire:navigate>Odustani</flux:button>
            <flux:button type="submit" variant="primary">
                {{ $isEditing ? 'Spremi promjene' : 'Kreiraj album' }}
            </flux:button>
        </div>
    </form>
</div>
