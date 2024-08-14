@props(['project'])

<li class="h-32 sm:h-56 lg:h-48 m-2 relative group overflow-hidden rounded-md">
    <img src="{{ $project->thumbnail_url }}" alt="{{ $project->title }}" class="h-full object-cover transform transition duration-500 ease-in-out group-hover:scale-110" loading="lazy">
    <a href="{{ route('projects.show', [$project->slug]) }}"
       class="absolute h-full left-0 text-white top-0 w-full bg-black bg-opacity-75 hover:no-underline opacity-0 group-hover:opacity-100 transform transition ease-in-out duration-200">
        <figure class="flex flex-col h-full items-center justify-center text-center w-full p-4">
            <h2 class="border-0 sm:border-b-2 sm:mb-2 sm:pb-2 text-2xl w-full leading-6">{{ $project->title }}</h2>
            <p class="hidden leading-5 px-2 sm:block text-sm">{{ $project->excerpt }}</p>
        </figure>
    </a>
</li>
