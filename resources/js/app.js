document.addEventListener('alpine:init', () => {
    window.Alpine.data('windLetters', () => ({
        init() {
            const letters = this.$el.querySelectorAll('[data-wind-letter]');
            setInterval(() => {
                letters.forEach((letter, i) => {
                    setTimeout(() => {
                        letter.classList.add('animate-bura-gust');
                        letter.addEventListener('animationend', () => {
                            letter.classList.remove('animate-bura-gust');
                        }, { once: true });
                    }, i * 30);
                });
            }, 3000);
        },
    }));

    window.Alpine.data('albumLightbox', (images = [], captions = []) => ({
    images,
    captions,
    currentIndex: 0,
    isOpen: false,

    get currentImage() {
        return this.images[this.currentIndex] || '';
    },

    get currentCaption() {
        return this.captions[this.currentIndex] || '';
    },

    open(index) {
        this.currentIndex = index;
        this.isOpen = true;
        document.body.style.overflow = 'hidden';
        this.$nextTick(() => this.preloadAdjacent());
    },

    close() {
        this.isOpen = false;
        document.body.style.overflow = '';
    },

    prev() {
        if (this.currentIndex > 0) {
            this.currentIndex--;
            this.preloadAdjacent();
        }
    },

    next() {
        if (this.currentIndex < this.images.length - 1) {
            this.currentIndex++;
            this.preloadAdjacent();
        }
    },

    preloadAdjacent() {
        [this.currentIndex - 1, this.currentIndex + 1].forEach(i => {
            if (i >= 0 && i < this.images.length) {
                const img = new Image();
                img.src = this.images[i];
            }
        });
    },
}));
});
