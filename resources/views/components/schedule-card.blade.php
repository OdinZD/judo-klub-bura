@props(['title', 'ageRange', 'icon', 'iconColor' => 'text-bura-500', 'times'])

<flux:card class="p-6">
    <div class="flex items-center gap-3 mb-4">
        <div class="size-10 flex items-center justify-center rounded-full {{ str_replace('text-', 'bg-', $iconColor) }}/10 {{ $iconColor }}">
            <flux:icon :name="$icon" class="size-5" />
        </div>
        <div>
            <h3 class="font-display font-semibold text-slate-text">{{ $title }}</h3>
            <p class="text-xs text-slate-muted">{{ $ageRange }}</p>
        </div>
    </div>

    <flux:separator />

    <ul class="mt-4 space-y-2">
        @foreach($times as $time)
            <li class="flex justify-between text-sm">
                <span class="text-slate-muted">{{ $time['day'] }}</span>
                <span class="font-medium text-slate-text">{{ $time['time'] }}</span>
            </li>
        @endforeach
    </ul>
</flux:card>
