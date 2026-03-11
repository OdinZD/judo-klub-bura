{{-- Wave transition --}}
<div class="relative">
    <svg class="w-full h-24 sm:h-32 lg:h-40" viewBox="0 0 1440 160" fill="none" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0,64 C360,128 720,0 1080,80 C1260,120 1380,100 1440,96 L1440,160 L0,160 Z" fill="#0F172A" fill-opacity="0.3"/>
        <path d="M0,96 C240,48 480,128 720,80 C960,32 1200,112 1440,72 L1440,160 L0,160 Z" fill="#0F172A" fill-opacity="0.5"/>
        <path d="M0,128 C180,96 360,144 540,112 C720,80 900,136 1080,104 C1260,72 1380,120 1440,108 L1440,160 L0,160 Z" fill="#0F172A"/>
    </svg>
</div>

<footer class="bg-slate-text relative overflow-hidden">
    {{-- Grain overlay --}}
    <div class="absolute inset-0 bg-grain opacity-50 pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
            {{-- Brand --}}
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex size-10 items-center justify-center rounded-xl bg-bura-gradient text-white">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17.7 7.7a7.5 7.5 0 1 0-10.6 10.6"/>
                            <path d="m9.4 4.6-2.1-.3-.3-2.1"/>
                            <path d="M14.6 19.4l2.1.3.3 2.1"/>
                        </svg>
                    </div>
                    <div>
                        <span class="font-display font-bold text-lg text-white leading-none">Bura</span>
                        <span class="block text-xs text-white/50">Judo Klub</span>
                    </div>
                </div>
                <p class="text-white/60 text-sm leading-relaxed">
                    Snaga jadranske bure. Treniramo judo za sve uzraste u modernom okruženju s naglaskom na disciplinu, poštovanje i zajedništvo.
                </p>
            </div>

            {{-- Navigation --}}
            <div>
                <h4 class="font-display font-semibold text-white mb-4">Navigacija</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('home') }}" wire:navigate class="text-white/60 hover:text-bura-400 transition-colors text-sm">Naslovnica</a></li>
                    <li><a href="{{ route('about') }}" wire:navigate class="text-white/60 hover:text-bura-400 transition-colors text-sm">O nama</a></li>
                    <li><a href="{{ route('gallery') }}" wire:navigate class="text-white/60 hover:text-bura-400 transition-colors text-sm">Galerija</a></li>
                    <li><a href="{{ route('contact') }}" wire:navigate class="text-white/60 hover:text-bura-400 transition-colors text-sm">Kontakt</a></li>
                </ul>
            </div>

            {{-- Contact info --}}
            <div>
                <h4 class="font-display font-semibold text-white mb-4">Kontakt</h4>
                <ul class="space-y-3">
                    <li class="flex items-start gap-3">
                        <svg class="size-5 text-bura-400 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 0 1 15 0Z" />
                        </svg>
                        <span class="text-white/60 text-sm">Sportska dvorana Bura<br>Obala 12, 21000 Split</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <svg class="size-5 text-bura-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                        </svg>
                        <a href="tel:+385911234567" class="text-white/60 hover:text-bura-400 transition-colors text-sm">+385 91 123 4567</a>
                    </li>
                    <li class="flex items-center gap-3">
                        <svg class="size-5 text-bura-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                        </svg>
                        <a href="mailto:info@judo-bura.hr" class="text-white/60 hover:text-bura-400 transition-colors text-sm">info@judo-bura.hr</a>
                    </li>
                </ul>
            </div>

            {{-- Social --}}
            <div>
                <h4 class="font-display font-semibold text-white mb-4">Pratite nas</h4>
                <div class="flex gap-3">
                    <a href="#" class="flex size-10 items-center justify-center rounded-lg bg-white/10 text-white/60 hover:bg-bura-500 hover:text-white transition-all">
                        <svg class="size-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="#" class="flex size-10 items-center justify-center rounded-lg bg-white/10 text-white/60 hover:bg-bura-500 hover:text-white transition-all">
                        <svg class="size-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"/></svg>
                    </a>
                    <a href="#" class="flex size-10 items-center justify-center rounded-lg bg-white/10 text-white/60 hover:bg-bura-500 hover:text-white transition-all">
                        <svg class="size-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </a>
                </div>
            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="mt-12 pt-8 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-white/40 text-sm">&copy; {{ date('Y') }} Judo Klub Bura. Sva prava pridržana.</p>
            <a href="{{ route('login') }}" class="text-white/20 hover:text-white/40 transition-colors text-xs">Admin</a>
        </div>
    </div>
</footer>
