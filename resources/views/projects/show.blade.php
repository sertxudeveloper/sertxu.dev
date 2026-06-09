<x-app-layout>
    <div class="grain-overlay"></div>

    <!-- Post Header -->
    <section class="relative pt-32 pb-12 bg-grid overflow-hidden">
        <div class="glow-top-right"></div>
        <div class="glow-bottom-left"></div>

        <div class="max-w-4xl mx-auto px-6 py-6">
            <div x-data x-reveal>
                <div class="flex items-center gap-3 mb-6">
                    @if($project->is_published)
                        <time datetime="{{ $project->created_at }}" class="text-neutral-400 text-sm font-mono">{{ $project->created_at->format('d M Y') }}</time>
                    @else
                        <span class="text-sm text-neutral-300">Unpublished</span>
                    @endif
                </div>

                <div x-data x-reveal>
                    <!-- Tags -->
                    <div class="flex flex-wrap gap-2 mb-10">
                        @foreach($project->tags as $tag)
                            <a href="{{ route('projects.index', ['tag' => $tag->slug]) }}"
                               class="px-2.5 py-1 text-xs font-mono bg-ocean/30 text-neutral-300 rounded">
                                {{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <h1 class="font-heading text-3xl md:text-4xl lg:text-5xl font-bold text-neutral-200 leading-tight">
                    {{ $project->title }}
                </h1>

                <p class="text-neutral-400 text-lg mt-4 leading-relaxed whitespace-pre-wrap">{{ $project->excerpt }}</p>

                <div class="flex flex-wrap gap-4 mt-12">
                    @if($project->website)
                        <a href="{{ $project->website }}" class="inline-flex items-center gap-2 px-6 py-3 bg-coral text-white rounded-lg text-sm font-medium hover:bg-coral transition-all hover:shadow-[0_0_10px_#FF3047]" rel="noopener noreferrer">
                            Website
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14M12 5l7 7-7 7" />
                            </svg>
                        </a>
                    @endif

                    @if($project->repository)
                        <a href="{{ $project->repository }}" class="inline-flex items-center gap-2 px-6 py-3 border border-neutral-700 text-neutral-200 rounded-lg text-sm font-medium hover:border-neutral-500 transition-all" rel="noopener noreferrer">
                            Source Code
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6M15 3h6v6M10 14 21 3" />
                            </svg>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Project Screenshot -->
    <section class="py-8">
        <div class="max-w-4xl mx-auto px-6">
            <div x-data x-reveal class="border border-neutral-800 rounded-lg overflow-hidden">
                @if($project->hasMedia('thumbnail'))
                    <img src="{{ $project->getFirstMedia('thumbnail')->getUrl('poster') }}" alt="{{ $project->title }}" class="h-full w-full object-cover object-center">
                @endif
            </div>
        </div>
    </section>

    <!-- Project Content -->
    <section class="pt-12 pb-6">
        <div class="max-w-4xl mx-auto px-6">
            <div x-data x-reveal class="post-content">
                @markdown($project->text ?? '')
            </div>
        </div>
    </section>
</x-app-layout>
