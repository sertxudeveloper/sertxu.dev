@props(['project'])

@php
    $media = $project->getFirstMedia('thumbnail');
@endphp

<li class="h-48 m-2 relative group overflow-hidden rounded-md">
    <div class="h-full transform transition duration-500 ease-in-out group-hover:scale-110 *:object-cover *:h-full *:w-full">
        @if($media) <img src="{{ $media->getUrl('thumbnail') }}" alt="{{ $project->title }}">@endif
    </div>
    <a href="{{ route('projects.show', [$project->slug]) }}" wire:navigate
       class="absolute h-full left-0 text-white top-0 w-full bg-black/85 hover:no-underline opacity-0 group-hover:opacity-100 transform transition ease-in-out duration-200">
        <figure class="flex flex-col h-full items-center justify-center text-center w-full p-4 space-y-1">
            <h3 class="border-0 sm:border-b-2 sm:mb-2 sm:pb-2 text-xl w-full leading-6">{{ $project->title }}</h3>
            <p class="leading-5 px-2 sm:block text-sm">{{ $project->excerpt }}</p>
        </figure>
    </a>
</li>
