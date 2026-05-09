<x-app-layout>
    <div class="fixed bg-dark-200 bg-doodles h-full w-full -z-10"></div>
    <div class="bg-dark-100 max-w-(--breakpoint-lg) mt mx-auto px-4 lg:px-12 py-12 lg:my-48">
        <h1 class="text-4xl font-medium text-neutral-200 leading-snug">{{ $post->title }}</h1>
        <div class="flex flex-col md:flex-row md:items-center mb-4 mt-3 md:space-x-3 space-y-2 md:space-y-0">
            @if($post->is_published)
                <span class="text-sm text-neutral-300">Published at {{ $post->published_at->format('d M Y') }}</span>
            @else
                <span class="text-sm text-neutral-300">Unpublished</span>
            @endif
            <span class="text-neutral-300 hidden md:block">&middot;</span>
            <ul class="space-x-1">
                @foreach($post->tags as $tag)
                    <li class="inline-block">
                        <a href="{{ route('posts.index', ['tag' => $tag->slug]) }}" class="text-neutral-100 border border-ocean bg-ocean/20 px-2.5 py-0.5 rounded-full text-xs leading-tight hover:underline">{{ $tag->name }}</a>
                    </li>
                @endforeach
            </ul>
        </div>

        <section class="markup images-centered border-neutral-700 border-t pt-8">
            @markdown($post->text ?? '')
        </section>

        @if($relatedPosts->isNotEmpty())
            <section class="border-neutral-700 border-t pt-8 mt-8">
                <h3 class="text-xl text-neutral-200 font-medium mb-6">Related Posts</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($relatedPosts as $relatedPost)
                        <a href="{{ route('posts.show', $relatedPost) }}" class="block group">
                            @if($relatedPost->getFirstMedia('thumbnail'))
                                <img
                                    src="{{ $relatedPost->getFirstMedia('thumbnail')->getUrl('thumbnail') }}"
                                    alt="{{ $relatedPost->title }}"
                                    class="w-full h-40 object-cover rounded-lg mb-3"
                                >
                            @else
                                <div class="w-full h-40 bg-neutral-800 rounded-lg mb-3 flex items-center justify-center">
                                    <span class="text-neutral-500 text-sm">No image</span>
                                </div>
                            @endif
                            <h4 class="text-neutral-200 group-hover:text-ocean transition-colors">{{ $relatedPost->title }}</h4>
                            <span class="text-sm text-neutral-400">{{ $relatedPost->published_at->format('d M Y') }}</span>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</x-app-layout>
