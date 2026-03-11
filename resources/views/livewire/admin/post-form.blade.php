<div class="mx-auto max-w-4xl p-6 lg:p-8">
    {{-- Header --}}
    <div class="mb-8">
        <flux:button href="{{ route('admin.posts.index') }}" variant="ghost" size="sm" icon="arrow-left" wire:navigate class="mb-4">
            Natrag
        </flux:button>
        <flux:heading size="xl">{{ $isEditing ? 'Uredi objavu' : 'Nova objava' }}</flux:heading>
    </div>

    {{-- Flash message --}}
    @if(session('message'))
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 rounded-xl text-sm">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit="save" class="space-y-8">
        <div class="bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700 p-6 space-y-5">
            <flux:heading size="sm">Detalji objave</flux:heading>

            <flux:input wire:model="title" label="Naslov" placeholder="Naslov objave" required />

            <flux:textarea wire:model="content" label="Sadržaj" placeholder="Sadržaj objave..." rows="10" required />

            <flux:textarea wire:model="excerpt" label="Sažetak (opcionalno)" placeholder="Kratak sažetak za prikaz na popisu..." rows="2" />

            <div class="grid sm:grid-cols-2 gap-5">
                <flux:input wire:model="published_at" label="Datum objave" type="date" />
                <div class="flex items-end">
                    <flux:checkbox wire:model="is_published" label="Objavljeno" description="Objava će biti vidljiva na javnoj stranici." />
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex items-center justify-end gap-3">
            <flux:button href="{{ route('admin.posts.index') }}" variant="ghost" wire:navigate>Odustani</flux:button>
            <flux:button type="submit" variant="primary">
                {{ $isEditing ? 'Spremi promjene' : 'Kreiraj objavu' }}
            </flux:button>
        </div>
    </form>
</div>
