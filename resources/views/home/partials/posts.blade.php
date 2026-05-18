<!-- Blog -->
<section id="blog" class="py-28 md:py-36 border-t border-neutral-900">
    <div class="max-w-5xl mx-auto px-6">
        <div class="reveal text-center">
            <h2 class="font-heading text-3xl md:text-4xl font-bold text-neutral-300">Blog</h2>
            <span class="accent-line mt-4"></span>
            <p class="text-neutral-400 text-base mt-3">
                Thoughts on development, infrastructure, and the tools I use.
            </p>
        </div>

        <div class="blog-grid grid md:grid-cols-2 grid-cols-1 gap-5 mt-10">
            @foreach($posts as $post)
                <div class="reveal">
                    <article class="bg-neutral-900 rounded-xl border border-neutral-800 p-6 h-full cursor-pointer relative transition-all duration-300 hover:-translate-y-1.5">
                        <a href="{{ route('posts.show', $post->slug) }}" class="absolute inset-0 z-10"></a>
                        <div class="flex items-center gap-2.5 text-neutral-400 text-xs font-mono mb-4">
                            <span class="w-2 h-2 rounded-full bg-[#FF3047]"></span>
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
                                   class="px-2.5 py-1 text-xs font-mono bg-[#0035FF]/30 text-neutral-300 rounded">
                                    {{ $tag->name }}
                                </a>
                            @endforeach
                        </div>
                    </article>
                </div>
            @endforeach
        </div>

        <div class="reveal mt-8 text-center">
            <a href="#" class="inline-flex items-center gap-2 px-6 py-3 border border-neutral-700 text-neutral-200 rounded-lg text-sm font-medium hover:border-neutral-500 transition-all">
                View all articles
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14M12 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>
</section>
