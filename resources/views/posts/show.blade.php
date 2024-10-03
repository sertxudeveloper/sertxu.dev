<x-app-layout>
    <div class="fixed bg-dark-200 bg-doodles h-full w-full -z-10"></div>
    <div class="bg-dark-100 max-w-screen-lg mt mx-auto px-10 py-12 lg:my-48">
        <h1 class="text-4xl font-medium text-neutral-200 leading-snug">{{ $post->title }}</h1>
        <div class="flex flex-col md:flex-row md:items-center mb-4 mt-3 md:space-x-3 md:space-y-2">
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

        <section class="markup border-neutral-700 border-t pt-8">
            @markdown($post->text ?? '')
        </section>
    </div>
</x-app-layout>
