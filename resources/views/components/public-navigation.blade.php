<flux:header container sticky class="bg-white/80 backdrop-blur-lg border-b border-bura-100/50 z-50">
    <flux:sidebar.toggle class="lg:hidden mr-2" icon="bars-2" inset="left" />

    <a href="{{ route('home') }}" wire:navigate class="flex items-center gap-3">
        <img src="{{ asset('images/bura-logo.jpeg') }}" alt="Judo Klub Bura" class="size-10 rounded-xl object-cover">
        <div>
            <span class="font-display font-bold text-lg leading-none text-slate-text">Bura</span>
            <span class="block text-xs text-slate-muted leading-tight">Judo Klub</span>
        </div>
    </a>

    <flux:navbar class="-mb-px max-lg:hidden ml-8">
        <flux:navbar.item :href="route('home')" :current="request()->routeIs('home')" wire:navigate>
            Naslovnica
        </flux:navbar.item>
        <flux:navbar.item :href="route('about')" :current="request()->routeIs('about')" wire:navigate>
            O nama
        </flux:navbar.item>
        <flux:navbar.item :href="route('news')" :current="request()->routeIs('news', 'news.show')" wire:navigate>
            Novosti
        </flux:navbar.item>
        <flux:navbar.item :href="route('results')" :current="request()->routeIs('results', 'results.show')" wire:navigate>
            Rezultati
        </flux:navbar.item>
        <flux:navbar.item :href="route('gallery')" :current="request()->routeIs('gallery', 'gallery.album')" wire:navigate>
            Galerija
        </flux:navbar.item>
        <flux:navbar.item :href="route('contact')" :current="request()->routeIs('contact')" wire:navigate>
            Kontakt
        </flux:navbar.item>
    </flux:navbar>

    <flux:spacer />

    @auth
        <flux:button variant="subtle" href="{{ route('dashboard') }}" wire:navigate class="max-sm:hidden">
            Dashboard
        </flux:button>
    @else
        <flux:button variant="subtle" href="{{ route('login') }}" wire:navigate class="max-sm:hidden">
            Prijava
        </flux:button>
    @endauth

    <flux:button variant="primary" href="{{ route('contact') }}" wire:navigate class="max-sm:hidden hover:scale-[1.02] transition-transform">
        Pridruži se
    </flux:button>
</flux:header>

{{-- Mobile sidebar --}}
<flux:sidebar collapsible="mobile" sticky class="lg:hidden border-e border-bura-100 bg-white">
    <flux:sidebar.header>
        <a href="{{ route('home') }}" wire:navigate class="flex items-center gap-3">
            <img src="{{ asset('images/bura-logo.jpeg') }}" alt="Judo Klub Bura" class="size-9 rounded-xl object-cover">
            <div>
                <span class="font-display font-bold text-base leading-none">Bura</span>
                <span class="block text-xs text-slate-muted">Judo Klub</span>
            </div>
        </a>
        <flux:sidebar.collapse />
    </flux:sidebar.header>

    <flux:sidebar.nav>
        <flux:sidebar.item :href="route('home')" :current="request()->routeIs('home')" wire:navigate>
            Naslovnica
        </flux:sidebar.item>
        <flux:sidebar.item :href="route('about')" :current="request()->routeIs('about')" wire:navigate>
            O nama
        </flux:sidebar.item>
        <flux:sidebar.item :href="route('news')" :current="request()->routeIs('news', 'news.show')" wire:navigate>
            Novosti
        </flux:sidebar.item>
        <flux:sidebar.item :href="route('results')" :current="request()->routeIs('results', 'results.show')" wire:navigate>
            Rezultati
        </flux:sidebar.item>
        <flux:sidebar.item :href="route('gallery')" :current="request()->routeIs('gallery', 'gallery.album')" wire:navigate>
            Galerija
        </flux:sidebar.item>
        <flux:sidebar.item :href="route('contact')" :current="request()->routeIs('contact')" wire:navigate>
            Kontakt
        </flux:sidebar.item>
    </flux:sidebar.nav>

    <flux:spacer />

    <div class="p-4 space-y-2">
        @auth
            <flux:button variant="subtle" href="{{ route('dashboard') }}" wire:navigate class="w-full">
                Dashboard
            </flux:button>
        @else
            <flux:button variant="subtle" href="{{ route('login') }}" wire:navigate class="w-full">
                Prijava
            </flux:button>
        @endauth

        <flux:button variant="primary" href="{{ route('contact') }}" wire:navigate class="w-full">
            Pridruži se
        </flux:button>
    </div>
</flux:sidebar>
