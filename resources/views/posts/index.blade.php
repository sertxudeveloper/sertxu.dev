<x-app-layout>
    <section id="blog" x-data class="py-28 md:py-36 border-t border-neutral-900">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center" x-reveal>
                <h2 class="font-heading text-3xl md:text-4xl font-bold text-neutral-300">Blog</h2>
                <span class="bg-coral inline-block h-0.5 rounded-full w-12 mt-4"></span>
                <p class="text-neutral-400 text-base mt-3">
                    Thoughts on development, infrastructure, and the tools I use.
                </p>
            </div>

            <div class="blog-grid grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5 mt-10" x-reveal-children>
                @foreach($posts as $post)
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
                @endforeach
            </div>

            @if($posts->hasPages())
                <div class="mt-12 px-2 mx-auto" x-reveal>
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </section>
</x-app-layout>
