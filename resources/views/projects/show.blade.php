<x-app-layout>
    <div class="py-12 gap-6 mx-auto max-w-screen-lg">
        <div class="mb-4">
            {{ $project->getFirstMedia('thumbnail') }}
        </div>
        <h1 class="text-4xl font-medium text-neutral-200 leading-snug">{{ $project->title }}</h1>

        @if($project->website)
            <div class="flex space-x-2 items-center py-1">
                <x-heroicon-o-globe-alt class="w-5 h-5 inline-block text-neutral-300" />
                <a href="{{ $project->website }}" class="text-coral hover:underline leading-none">{{ $project->website }}</a>
            </div>
        @endif

        <div class="mb-4 mt-3 space-x-3 flex items-center">
            <ul class="space-x-1">
                @foreach($project->tags as $tag)
                    <li class="inline-block">
                        <a href="{{ route('projects.index', ['tag' => $tag->slug]) }}" class="text-neutral-100 border border-ocean bg-ocean/20 px-2.5 py-0.5 rounded-full text-xs leading-tight hover:underline">{{ $tag->name }}</a>
                    </li>
                @endforeach
            </ul>
        </div>

        <section class="markup border-t border-neutral-700 pt-4">
            @markdown($project->text ?? '')
        </section>
    </div>
</x-app-layout>
