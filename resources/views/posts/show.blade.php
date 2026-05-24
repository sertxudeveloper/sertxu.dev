<x-app-layout>
    <div class="grain-overlay"></div>

    <!-- Post Header -->
    <section class="relative pt-32 pb-12 bg-grid overflow-hidden">
        <div class="glow-top-right"></div>
        <div class="glow-bottom-left"></div>

        <div class="max-w-4xl mx-auto px-6">
            <div class="reveal">
                <a href="#" class="inline-flex items-center gap-2 text-sm text-neutral-400 hover:text-neutral-200 transition-colors font-mono mb-8">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 12H5M12 19l-7-7 7-7" />
                    </svg>
                    Back to blog
                </a>

                <div class="flex items-center gap-3 mb-6">
                    @if($post->is_published)
                        <time datetime="{{ $post->published_at }}" class="text-neutral-400 text-sm font-mono">{{ $post->published_at->format('d M Y') }}</time>
                    @else
                        <span class="text-sm text-neutral-300">Unpublished</span>
                    @endif

                    <span class="text-neutral-500 text-sm font-mono">·</span>
                    <span class="text-neutral-400 text-sm font-mono">{{ ceil(str($post->text)->wordCount() / 240) }} min read</span>
                </div>

                <h1 class="font-heading text-3xl md:text-4xl lg:text-5xl font-bold text-neutral-200 leading-tight">
                    {{ $post->title }}
                </h1>

                <p class="text-neutral-400 text-lg mt-4 leading-relaxed">
                    {{ $post->excerpt }}
                </p>
            </div>
        </div>
    </section>

    <!-- Post Content -->
    <section class="py-12 border-t border-neutral-900">
        <div class="max-w-4xl mx-auto px-6">
            <div class="reveal post-content">
                @markdown($post->text ?? '')
            </div>
        </div>
    </section>

    <!-- Post Footer: Tags & Navigation -->
    <section class="py-12 border-t border-border/30">
        <div class="max-w-4xl mx-auto px-6">
            <div class="reveal">
                <!-- Tags -->
                <div class="flex flex-wrap gap-2 mb-10">
                    @foreach($post->tags as $tag)
                        <a href="{{ route('posts.index', ['tag' => $tag->slug]) }}"
                           class="px-2.5 py-1 text-xs font-mono bg-ocean/30 text-neutral-300 rounded">
                            {{ $tag->name }}
                        </a>
                    @endforeach
                </div>

                @if($relatedPosts->isNotEmpty())
                    <div class="grid grid-cols-2 gap-4 mb-10">
                        @foreach($relatedPosts as $post)
                            <div class="reveal">
                                <article class="bg-neutral-900 rounded-xl border border-neutral-800 p-6 h-full cursor-pointer relative transition-all duration-300 hover:-translate-y-1.5">
                                    <a href="{{ route('posts.show', $post->slug) }}" class="absolute inset-0 z-10"></a>
                                    <div class="flex items-center gap-2.5 text-neutral-400 text-xs font-mono mb-4">
                                        <span class="w-2 h-2 rounded-full bg-coral"></span>
                                        <time datetime="{{ $post->published_at->toDateString() }}">
                                            {{ $post->published_at->format('M d, Y') }}
                                        </time>
                                    </div>
                                    <h3 class="font-heading text-lg font-bold text-neutral-300 mb-2">
                                        {{ $post->title }}
                                    </h3>
                                    <p class="text-neutral-400 text-sm leading-relaxed mb-4">
                                        {{ $post->excerpt }}
                                    </p>
                                    <div class="flex flex-wrap gap-1.5 z-10 relative">
                                        @foreach($post->tags as $tag)
                                            <a href="{{ route('posts.index', ['tag' => $tag->slug]) }}"
                                               class="px-2.5 py-1 text-xs font-mono bg-ocean/30 text-neutral-300 rounded">
                                                {{ $tag->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Prev / Next -->
                {{--<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="#" class="group block p-5 bg-bg-surface rounded-xl border border-border hover:border-text-muted/30 transition-all">
                        <div class="flex items-center gap-2 text-text-muted/40 text-xs font-mono mb-2">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 12H5M12 19l-7-7 7-7" />
                            </svg>
                            Previous
                        </div>
                        <h4 class="font-heading text-sm font-bold text-text-primary group-hover:text-brand-red transition-colors">
                            Deploying Laravel with Zero Downtime
                        </h4>
                        <time datetime="2026-02-28" class="text-text-muted/40 text-xs font-mono mt-1 block">February 28, 2026</time>
                    </a>

                    <a href="#" class="group block p-5 bg-bg-surface rounded-xl border border-border hover:border-text-muted/30 transition-all md:text-right">
                        <div class="flex items-center gap-2 text-text-muted/40 text-xs font-mono mb-2 md:justify-end">
                            Next
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14M12 5l7 7-7 7" />
                            </svg>
                        </div>
                        <h4 class="font-heading text-sm font-bold text-text-primary group-hover:text-brand-red transition-colors">
                            Full-stack Development in 2026
                        </h4>
                        <time datetime="2026-04-02" class="text-text-muted/40 text-xs font-mono mt-1 block">April 2, 2026</time>
                    </a>
                </div>--}}
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // ─── Scroll Reveal ───
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('revealed');
                    }
                });
            }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });

            document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

            // ─── Nav scroll effect ───
            const navbar = document.getElementById('navbar');

            window.addEventListener('scroll', () => {
                if (window.scrollY > 40) {
                    navbar.classList.add('backdrop-blur-md', 'bg-neutral-950/85', 'border-b', 'border-neutral-500');
                    //navbar.style.borderBottom = '1px solid rgba(30,30,34,0.5)';
                } else {
                    navbar.classList.remove('backdrop-blur-md', 'bg-neutral-950/85', 'border-b', 'border-neutral-500');
                    //navbar.style.borderBottom = 'none';
                }
            });

            // ─── Mobile menu ───
            const menuBtn = document.getElementById('menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');
            const iconOpen = document.getElementById('menu-icon-open');
            const iconClose = document.getElementById('menu-icon-close');

            menuBtn.addEventListener('click', () => {
                const isOpen = !mobileMenu.classList.contains('hidden');
                mobileMenu.classList.toggle('hidden');
                iconOpen.classList.toggle('hidden');
                iconClose.classList.toggle('hidden');
            });

            document.querySelectorAll('#mobile-menu a').forEach(link => {
                link.addEventListener('click', () => {
                    mobileMenu.classList.add('hidden');
                    iconOpen.classList.remove('hidden');
                    iconClose.classList.add('hidden');
                });
            });
        });
    </script>
</x-app-layout>
