@props([
    'post' => null,
])

@php
    $media = $post->getFirstMedia('thumbnail');
@endphp

<li class="m-2 bg-dark-100 rounded-b-md hover:scale-105 transform transition hover:z-10">
    <a href="{{ route('posts.show', [$post->slug]) }}" wire:navigate>
        <div class="h-[171px] flex items-center overflow-hidden rounded-t-md">
            @if($media) <img src="{{ $media->getUrl('thumbnail') }}" alt="{{ $post->title }}">@endif
        </div>
        <div class="p-5 text-neutral-300">
            <p class="mb-1 text-sm">{{ $post->published_at->format('F j, Y') }}</p>
            <h3 class="mb-1 text-lg font-medium truncate" title="{{ $post->title }}">{{ $post->title }}</h3>
            <p class="text-sm line-clamp-2">{{ Str::limit($post->text) }}</p>
        </div>
    </a>
</li>
