<x-app-layout>
    <div class="fixed bg-dark-200 bg-doodles h-full w-full -z-10"></div>
    <div class="bg-dark-100 max-w-(--breakpoint-lg) mx-auto px-4 lg:px-12 py-12 lg:mt-48 lg:mb-12">
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
    </div>

    @if($relatedPosts->isNotEmpty())
        <section class="max-w-(--breakpoint-lg) mx-auto px-4 lg:px-0 py-12 lg:mb-48">
            <h3 class="text-3xl text-neutral-200 text-center font-medium uppercase font-heading">Related Posts</h3>
            <div class="border-b-2 border-ocean w-32 mx-auto mt-2 mb-10"></div>

            <ul class="grid grid-cols-1 lg:grid-cols-3">
                @foreach($relatedPosts as $post)
                    <x-post :post="$post" />
                @endforeach
            </ul>
        </section>
    @endif
</x-app-layout>
