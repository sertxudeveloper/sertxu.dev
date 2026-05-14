<!-- Blog -->
<section id="blog" class="py-28 md:py-36 border-t border-border/30">
    <div class="max-w-5xl mx-auto px-6">
        <div class="reveal text-center">
            <h2 class="font-heading text-3xl md:text-4xl font-bold text-stone-300">Blog</h2>
            <span class="accent-line mt-4"></span>
            <p class="text-stone-400 text-base mt-3">
                Thoughts on development, infrastructure, and the tools I use.
            </p>
        </div>

        <div class="blog-grid grid grid-cols-1 md:grid-cols-2 gap-5 mt-10">
            @foreach($posts as $post)
                <div class="reveal">
                    <article class="bg-bg-surface rounded-xl border border-border p-6 h-full">
                        <a href="{{ route('posts.show', $post->slug) }}" class="absolute inset-0 z-10"></a>
                        <div class="flex items-center gap-2.5 text-text-muted/50 text-xs font-mono mb-4">
                            <span class="w-2 h-2 rounded-full bg-brand-red"></span>
                            <time datetime="{{ $post->published_at->toDateString() }}">
                                {{ $post->published_at->format('M d, Y') }}
                            </time>
                        </div>
                        <h3 class="font-heading text-lg font-bold text-text-primary mb-2">
                            {{ $post->title }}
                        </h3>
                        <p class="text-text-muted text-sm leading-relaxed mb-4">
                            {{ $post->excerpt }}
                        </p>
                    </article>
                </div>
            @endforeach
        </div>

        <div class="reveal mt-8">
            <a href="#" class="inline-flex items-center gap-2 text-sm text-text-muted hover:text-text-primary transition-colors font-medium">
                View all articles
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14M12 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>
</section>
