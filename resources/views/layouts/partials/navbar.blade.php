<!-- Navigation -->
<nav id="navbar" class="fixed top-0 left-0 right-0 z-50 bg-transparent border-b-transparent transition-all duration-300">
    <div class="max-w-5xl mx-auto px-6 h-16 flex items-center justify-between">
        <a href="#">
            <img src="{{ asset('favicon.svg') }}" alt="Sertxu Dev" class="h-10">
        </a>
        <div class="hidden md:flex items-center gap-8">
            <a href="{{ route('home') }}" class="text-sm text-[#e02a3f] underline underline-offset-[6px] transition-colors">Home</a>
            <a href="#about" class="text-sm text-neutral-400 hover:text-text-primary transition-colors">About</a>
            <a href="#projects" class="text-sm text-neutral-400 hover:text-text-primary transition-colors">Projects</a>
            <a href="#blog" class="text-sm text-neutral-400 hover:text-text-primary transition-colors">Blog</a>
            {{--<a href="#footer" class="px-5 py-2 bg-brand-red text-white rounded-lg text-sm font-medium hover:bg-[#e02a3f] transition-all hover:shadow-[0_0_20px_rgba(255,48,71,0.25)]">Contact</a>--}}
            <a href="#footer" class="px-5 py-2 text-[#e02a3f] rounded-lg text-sm font-medium hover:bg-[#e02a3f] hover:text-neutral-200 border border-[#e02a3f] transition-all">Contact</a>
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
