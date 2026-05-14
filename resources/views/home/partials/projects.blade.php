<!-- Projects -->
<section id="projects" class="py-28 md:py-36 border-t border-border/30">
    <div class="max-w-5xl mx-auto px-6">
        <div class="reveal text-center">
            <h2 class="font-heading text-3xl md:text-4xl font-bold text-stone-300">Projects</h2>
            <span class="accent-line mt-4"></span>
            <p class="text-stone-400 text-base mt-3">
                Selected projects I've built and contributed to.
            </p>
        </div>

        <div class="portfolio-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mt-10">
            @foreach($projects as $project)
                <div class="reveal">
                    <div class="project-card bg-bg-surface rounded-xl border border-border h-full cursor-pointer group">
                        <a href="{{ route('projects.show', $project) }}" class="absolute inset-0 z-10"></a>

                        <div class="w-full h-44 rounded-t-lg overflow-hidden">
                            @if($project->hasMedia('thumbnail'))
                                <img src="{{ $project->getFirstMedia('thumbnail')->getUrl('thumbnail') }}" alt="{{ $project->title }}">
                            @endif
                        </div>

                        <div class="p-4">
                            <h3 class="font-heading text-lg font-bold text-neutral-300 mb-1.5">
                                {{ $project->title }}
                            </h3>

                            <p class="text-neutral-400 text-sm leading-relaxed mb-4">
                                {{ $project->excerpt }}
                            </p>

                            <div class="flex flex-wrap gap-1.5 z-10 relative">
                                @foreach($project->tags as $tag)
                                    <a href="{{ route('projects.index', ['tag' => $tag->slug]) }}"
                                       class="px-2.5 py-1 text-xs font-mono bg-brand-blue/[0.3] text-neutral-300 rounded">
                                        {{ $tag->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
