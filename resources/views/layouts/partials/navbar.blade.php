<!-- Navigation -->
<nav id="navbar"
     x-data="{ scrolled: false, open: false }"
     x-init="$watch('open', value => document.body.classList.toggle('overflow-hidden', value))"
     @scroll.window="scrolled = window.scrollY > 50"
     @keydown.escape.window="open = false"
     :class="scrolled || open ? 'backdrop-blur-md bg-neutral-950/85 border-b-neutral-900!' : 'bg-transparent'"
     class="fixed top-0 left-0 right-0 z-50 border-b border-b-transparent transition-all duration-300"
     @click.away="open = false">
    <div class="max-w-5xl mx-auto px-6 h-16 flex items-center justify-between">
        <a href="{{ route('home') }}">
            <img src="{{ asset('favicon.svg') }}" alt="Sertxu Dev" class="h-10">
        </a>

        <div class="hidden md:flex items-center gap-8">
            <x-navbar.item :href="route('home')" :is-active="request()->routeIs('home')">Home</x-navbar.item>
            <x-navbar.item :href="route('projects.index')" :is-active="request()->routeIs('projects.*')">Projects</x-navbar.item>
            <x-navbar.item :href="route('posts.index')" :is-active="request()->routeIs('posts.*')">Blog</x-navbar.item>
            <a href="{{ route('home') }}#contact" class="px-5 py-2 text-[#e02a3f] rounded-lg text-sm font-medium hover:bg-[#e02a3f] hover:text-neutral-200 border border-[#e02a3f] transition-all">Contact</a>
        </div>

        <button id="menu-btn"
                @click="open = !open"
                class="md:hidden text-text-primary p-2"
                aria-label="Toggle menu"
                :aria-expanded="open">
            <svg x-show="!open" width="24" height="24" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
                <line x1="4" y1="6" x2="20" y2="6" />
                <line x1="4" y1="12" x2="20" y2="12" />
                <line x1="4" y1="18" x2="20" y2="18" />
            </svg>

            <svg x-show="open" x-cloak width="24" height="24" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
                <line x1="4" y1="4" x2="20" y2="20" />
                <line x1="20" y1="4" x2="4" y2="20" />
            </svg>
        </button>
    </div>

    <div id="mobile-menu"
         x-show="open"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="transform opacity-0 -translate-y-2"
         x-transition:enter-end="transform opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="transform opacity-100 translate-y-0"
         x-transition:leave-end="transform opacity-0 -translate-y-2"
         class="md:hidden border-t border-neutral-600 bg-neutral-950/95 backdrop-blur-lg">
        <div class="max-w-5xl mx-auto px-6 py-4 flex flex-col gap-3">
            <x-navbar.item :href="route('home')" :is-active="request()->routeIs('home')" class="py-2">Home</x-navbar.item>
            <x-navbar.item :href="route('projects.index')" :is-active="request()->routeIs('projects.*')" class="py-2">Projects</x-navbar.item>
            <x-navbar.item :href="route('posts.index')" :is-active="request()->routeIs('posts.*')" class="py-2">Blog</x-navbar.item>
            <x-navbar.item :href="route('home') . '#contact'" class="py-2">Contact</x-navbar.item>
        </div>
    </div>
</nav>
