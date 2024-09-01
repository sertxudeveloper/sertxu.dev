@props(['project'])

<li class="h-32 sm:h-56 lg:h-48 m-2 relative group overflow-hidden rounded-md">
    <div class="h-full transform transition duration-500 ease-in-out group-hover:scale-110 *:object-cover *:h-full *:w-auto">
        {{ $project->getFirstMedia('thumbnail') }}
    </div>
    <a href="{{ route('projects.show', [$project->slug]) }}"
       class="absolute h-full left-0 text-white top-0 w-full bg-black/85 hover:no-underline opacity-0 group-hover:opacity-100 transform transition ease-in-out duration-200">
        <figure class="flex flex-col h-full items-center justify-center text-center w-full p-4">
            <h3 class="border-0 sm:border-b-2 sm:mb-2 sm:pb-2 text-xl w-full leading-6">{{ $project->title }}</h3>
            <p class="hidden leading-5 px-2 sm:block text-sm">{{ $project->excerpt }}</p>
        </figure>
    </a>
</li>
