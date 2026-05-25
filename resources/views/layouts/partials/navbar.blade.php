<!-- Navigation -->
<nav id="navbar" x-data="{ scrolled: false }" @scroll.window="scrolled = window.scrollY > 50"
     :class="scrolled ? 'backdrop-blur-md bg-neutral-950/85 border-b-neutral-900!' : 'bg-transparent'"
     class="fixed top-0 left-0 right-0 z-50 border-b border-b-transparent transition-all duration-300">
    <div class="max-w-5xl mx-auto px-6 h-16 flex items-center justify-between">
        <a href="{{ route('home') }}">
            <img src="{{ asset('favicon.svg') }}" alt="Sertxu Dev" class="h-10">
        </a>

        <div class="hidden md:flex items-center gap-8">
            <x-navbar.item :href="route('home')" :is-active="request()->routeIs('home')">Home</x-navbar.item>
            <x-navbar.item :href="route('projects.index')" :is-active="request()->routeIs('projects.*')">Projects</x-navbar.item>
            <x-navbar.item :href="route('posts.index')" :is-active="request()->routeIs('posts.*')">Blog</x-navbar.item>
            <a href="/#contact" class="px-5 py-2 text-[#e02a3f] rounded-lg text-sm font-medium hover:bg-[#e02a3f] hover:text-neutral-200 border border-[#e02a3f] transition-all">Contact</a>
        </div>

        <button id="menu-btn" class="md:hidden text-text-primary p-2" aria-label="Toggle menu">
            <svg id="menu-icon-open" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
                <line x1="4" y1="6" x2="20" y2="6" />
                <line x1="4" y1="12" x2="20" y2="12" />
                <line x1="4" y1="18" x2="20" y2="18" />
            </svg>
            <svg id="menu-icon-close" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" class="hidden">
                <line x1="4" y1="4" x2="20" y2="20" />
                <line x1="20" y1="4" x2="4" y2="20" />
            </svg>
        </button>
    </div>

    <div id="mobile-menu" class="hidden md:hidden border-t border-border/50 bg-bg-base/95 backdrop-blur-lg">
        <div class="max-w-5xl mx-auto px-6 py-4 flex flex-col gap-3">
            <a href="#about" class="text-text-muted hover:text-text-primary transition-colors py-2">About</a>
            <a href="#projects" class="text-text-muted hover:text-text-primary transition-colors py-2">Projects</a>
            <a href="#blog" class="text-text-muted hover:text-text-primary transition-colors py-2">Blog</a>
            <a href="#footer" class="text-brand-red font-medium py-2">Contact</a>
        </div>
    </div>
</nav>
