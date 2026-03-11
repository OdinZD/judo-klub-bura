@props(['value', 'label', 'light' => false])

<div {{ $attributes }}>
    <span class="font-display font-bold text-3xl {{ $light ? 'text-bura-400' : 'text-bura-500' }}">{{ $value }}</span>
    <span class="block text-sm {{ $light ? 'text-white/60' : 'text-slate-muted' }} mt-1">{{ $label }}</span>
</div>
