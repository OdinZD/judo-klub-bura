@props(['title', 'subtitle' => null])

<section class="relative py-20 lg:py-28 bg-bura-gradient-soft overflow-hidden">
    {{-- Wind lines --}}
    <div class="absolute inset-0 bg-wind-lines"></div>

    {{-- Blurred accent orbs --}}
    <div class="absolute top-0 right-1/4 w-96 h-96 bg-bura-500/5 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-1/4 w-80 h-80 bg-adriatic-500/5 rounded-full blur-3xl"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <x-section-heading :title="$title" :subtitle="$subtitle" :centered="true" />
    </div>
</section>
