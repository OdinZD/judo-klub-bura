@props(['icon', 'title', 'description'])

<div {{ $attributes->merge(['class' => 'group relative bg-white border border-bura-100 rounded-2xl p-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-bura-500/10 overflow-hidden']) }}>
    {{-- Wind lines on hover --}}
    <div class="absolute top-0 right-0 w-32 h-32 bg-wind-lines opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

    <div class="relative">
        <div class="size-12 flex items-center justify-center rounded-xl bg-bura-50 text-bura-500 group-hover:bg-bura-500 group-hover:text-white transition-all duration-300 mb-4">
            <flux:icon :name="$icon" class="size-6" />
        </div>

        <h3 class="font-display font-semibold text-lg text-slate-text mb-2">{{ $title }}</h3>
        <p class="text-slate-muted text-sm leading-relaxed">{{ $description }}</p>
    </div>
</div>
