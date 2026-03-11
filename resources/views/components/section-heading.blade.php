@props([
    'title',
    'subtitle' => null,
    'centered' => true,
    'light' => false,
    'tag' => null,
])

<div {{ $attributes->merge(['class' => $centered ? 'text-center' : '']) }}>
    {{-- Gradient accent bar --}}
    <div class="flex {{ $centered ? 'justify-center' : '' }} mb-4">
        <div class="w-12 h-1 rounded-full bg-bura-gradient"></div>
    </div>

    @if($tag)
        <span class="inline-block text-xs font-semibold uppercase tracking-widest {{ $light ? 'text-bura-400' : 'text-bura-500' }} mb-3">{{ $tag }}</span>
    @endif

    <h2 class="font-display font-bold text-3xl lg:text-4xl tracking-tight {{ $light ? 'text-white' : 'text-slate-text' }}">
        {{ $title }}
    </h2>

    @if($subtitle)
        <p class="mt-4 text-lg {{ $light ? 'text-white/60' : 'text-slate-muted' }} {{ $centered ? 'max-w-2xl mx-auto' : 'max-w-2xl' }}">
            {{ $subtitle }}
        </p>
    @endif
</div>
