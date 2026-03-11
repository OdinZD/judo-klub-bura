@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand name="Judo Klub Bura" {{ $attributes }}>
        <x-slot name="logo">
            <img src="{{ asset('images/bura-logo.jpeg') }}" alt="Judo Klub Bura" class="size-8 rounded-md object-cover">
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="Judo Klub Bura" {{ $attributes }}>
        <x-slot name="logo">
            <img src="{{ asset('images/bura-logo.jpeg') }}" alt="Judo Klub Bura" class="size-8 rounded-md object-cover">
        </x-slot>
    </flux:brand>
@endif
