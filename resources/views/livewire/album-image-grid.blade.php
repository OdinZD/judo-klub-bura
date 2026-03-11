<div
    x-data="albumLightbox({{ $images->pluck('image_url', 'id')->values()->toJson() }}, {{ $images->pluck('caption', 'id')->values()->toJson() }})"
>
    {{-- Masonry grid --}}
    <div class="columns-2 sm:columns-3 lg:columns-4 gap-3">
        @foreach($images as $index => $image)
            <div
                wire:key="img-{{ $image->id }}"
                class="break-inside-avoid mb-3 group relative rounded-xl overflow-hidden cursor-pointer"
                x-on:click="open({{ $index }})"
            >
                <img
                    src="{{ $image->thumbnail_url }}"
                    alt="{{ $image->caption ?? '' }}"
                    class="w-full rounded-xl"
                    loading="lazy"
                >

                {{-- Hover overlay --}}
                @if($image->caption)
                    <div class="absolute inset-0 bg-slate-text/0 group-hover:bg-slate-text/40 transition-all duration-300 flex items-end">
                        <div class="w-full p-3 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                            <span class="text-white text-sm font-medium">{{ $image->caption }}</span>
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($images->hasPages())
        <div class="mt-10">
            {{ $images->links() }}
        </div>
    @endif

    {{-- Lightbox --}}
    <template x-teleport="body">
        <div
            x-show="isOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[99] flex items-center justify-center"
            x-on:keydown.escape.window="close()"
            x-on:keydown.arrow-left.window="prev()"
            x-on:keydown.arrow-right.window="next()"
            style="display: none;"
        >
            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-black/90 backdrop-blur-sm" x-on:click="close()"></div>

            {{-- Counter --}}
            <div class="absolute top-4 left-4 z-10 text-white/80 text-sm font-medium">
                <span x-text="(currentIndex + 1) + ' / ' + images.length"></span>
            </div>

            {{-- Close button --}}
            <button
                x-on:click="close()"
                class="absolute top-4 right-4 z-10 text-white/80 hover:text-white transition-colors p-2"
            >
                <svg class="size-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>

            {{-- Prev button --}}
            <button
                x-on:click="prev()"
                x-show="currentIndex > 0"
                class="absolute left-2 sm:left-4 z-10 p-3 text-white/70 hover:text-white transition-colors rounded-full hover:bg-white/10"
            >
                <svg class="size-8 sm:size-10" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
            </button>

            {{-- Next button --}}
            <button
                x-on:click="next()"
                x-show="currentIndex < images.length - 1"
                class="absolute right-2 sm:right-4 z-10 p-3 text-white/70 hover:text-white transition-colors rounded-full hover:bg-white/10"
            >
                <svg class="size-8 sm:size-10" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
            </button>

            {{-- Image --}}
            <div class="relative z-10 max-w-[90vw] max-h-[85vh] flex flex-col items-center justify-center">
                <img
                    x-bind:src="currentImage"
                    x-bind:alt="currentCaption"
                    class="max-h-[85vh] max-w-[90vw] object-contain rounded-lg"
                >
                <p
                    x-show="currentCaption"
                    x-text="currentCaption"
                    class="mt-3 text-white/90 text-center text-sm"
                ></p>
            </div>
        </div>
    </template>
</div>
