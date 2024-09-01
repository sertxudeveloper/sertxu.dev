@extends('layout')

@section('content')
    <div class="py-12 gap-6 mx-auto max-w-screen-lg">
        <h1 class="text-4xl font-medium text-neutral-200 leading-snug">{{ $post->title }}</h1>
        <div class="mb-4 mt-3 space-x-3 flex items-center">
            @if($post->is_published)
                <span class="text-sm text-neutral-300">Published at {{ $post->published_at->format('d M Y') }}</span>
            @else
                <span class="text-sm text-neutral-300">Unpublished</span>
            @endif
            <span class="text-neutral-300">&middot;</span>
            <ul class="space-x-1">
                @foreach($post->tags as $tag)
                    <li class="inline-block">
                        <a href="{{ route('posts.index', ['tag' => $tag->slug]) }}" class="text-neutral-100 border border-ocean bg-ocean/20 px-2.5 py-0.5 rounded-full text-xs leading-tight hover:underline">{{ $tag->name }}</a>
                    </li>
                @endforeach
            </ul>
        </div>

        <section class="markup border-t border-neutral-700 pt-4">
            @markdown($post->text ?? '')
        </section>
    </div>
@endsection
