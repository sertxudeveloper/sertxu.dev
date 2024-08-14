@props(['post'])

<li class="m-2 bg-dark-100 rounded-b-md hover:scale-105 transform transition hover:z-10">
    <a href="{{ route('blog.show', [$post->slug]) }}">
        <div class="h-[171px] flex items-center overflow-hidden rounded-t-md">
            <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}" class="">
        </div>
        <div class="p-5 text-gray-300">
            <p class="mb-1 text-sm">{{ $post->published_at->format('F j, Y') }}</p>
            <h3 class="mb-1 text-lg font-medium truncate" title="{{ $post->title }}">{{ $post->title }}</h3>
            <p class="text-sm line-clamp-2">{{ $post->excerpt }}</p>
        </div>
    </a>
</li>
