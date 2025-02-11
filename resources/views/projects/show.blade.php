@php
    $media = $project->getFirstMedia('thumbnail');
@endphp

<x-app-layout>
    <div class="fixed bg-dark-200 bg-doodles h-full w-full -z-10"></div>
    <div class="bg-dark-100 max-w-(--breakpoint-lg) mt mx-auto px-4 lg:px-12 py-12 lg:my-48">
        <div class="mb-4">
            @isset($media) <img src="{{ $media->getUrl('poster') }}" alt="{{ $project->title }}" class="max-w-(--breakpoint-md) w-full mx-auto">@endif
        </div>

        <h1 class="text-4xl font-medium text-neutral-200 leading-snug">{{ $project->title }}</h1>

        @if($project->website)
            <div class="flex space-x-2 items-center py-1">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 inline-block text-neutral-300">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418" />
                </svg>
                <a href="{{ $project->website }}" class="text-coral hover:underline leading-none">{{ $project->website }}</a>
            </div>
        @endif

        <div class="mb-8 mt-3 space-x-3 flex items-center">
            <ul class="space-x-1">
                @foreach($project->tags as $tag)
                    <li class="inline-block">
                        <a href="{{ route('projects.index', ['tag' => $tag->slug]) }}" class="text-neutral-100 border border-ocean bg-ocean/20 px-2.5 py-0.5 rounded-full text-xs leading-tight hover:underline">{{ $tag->name }}</a>
                    </li>
                @endforeach
            </ul>
        </div>

        <section class="markup border-t border-neutral-700 pt-8">
            @markdown($project->text ?? '')
        </section>
    </div>
</x-app-layout>
