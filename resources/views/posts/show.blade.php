@php
    $title = $post->title . ' - sertxu.dev';
    $ogImage = $post->getFirstMediaUrl('thumbnail', 'thumbnail') ?: null;
    $postUrl = route('posts.show', $post);
@endphp

<x-app-layout
    :title="$title"
    :description="$post->excerpt"
    :canonical="$postUrl"
    :ogImage="$ogImage"
    :breadcrumbs="[
        ['name' => 'Home', 'url' => route('home')],
        ['name' => 'Blog', 'url' => route('posts.index')],
        ['name' => $post->title, 'url' => $postUrl],
    ]"
    :schemaArticle="[
        'headline' => $post->title,
        'description' => $post->excerpt,
        'image' => $ogImage,
        'datePublished' => $post->published_at?->toIso8601String(),
        'dateModified' => $post->updated_at->toIso8601String(),
        'url' => $postUrl,
    ]"
>
    <div class="grain-overlay"></div>

    <!-- Post Header -->
    <section class="relative pt-32 pb-12 bg-grid overflow-hidden">
        <div class="glow-top-right"></div>
        <div class="glow-bottom-left"></div>

        <div class="max-w-4xl mx-auto px-6 py-6">
            <div x-data x-reveal>
                <div class="flex items-center gap-3 mb-6">
                    @if($post->is_published)
                        <time datetime="{{ $post->published_at }}" class="text-neutral-400 text-sm font-mono">{{ $post->published_at->format('d M Y') }}</time>
                    @else
                        <span class="text-sm text-neutral-300">Unpublished</span>
                    @endif

                    <span class="text-neutral-500 text-sm font-mono">·</span>
                    <span class="text-neutral-400 text-sm font-mono">{{ $post->minutes_to_read }} min read</span>
                </div>

                <h1 class="font-heading text-3xl md:text-4xl lg:text-5xl font-bold text-neutral-200 leading-tight">
                    {{ $post->title }}
                </h1>

                <p class="text-neutral-400 text-lg mt-4 leading-relaxed whitespace-pre-wrap">{{ $post->excerpt }}</p>

                <div class="mt-6" x-data x-reveal>
                    <!-- Tags -->
                    <div class="flex flex-wrap gap-2 mb-10">
                        @foreach($post->tags as $tag)
                            <a href="{{ route('posts.index', ['tag' => $tag->slug]) }}"
                               class="px-2.5 py-1 text-xs font-mono bg-ocean/30 text-neutral-300 rounded">
                                {{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Post Content -->
    <section class="pt-12 pb-6 border-t border-neutral-900">
        <div class="max-w-4xl mx-auto px-6">
            <div class="post-content" x-data x-reveal>
                @markdown($post->text ?? '')
            </div>
        </div>
    </section>

    <!-- Post Footer: Related posts -->
    <section class="mb-12">
        <div class="max-w-4xl mx-auto px-6">
            <div x-data x-reveal>
                @if($relatedPosts->isNotEmpty())
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-10">
                        @foreach($relatedPosts as $post)
                            <div x-reveal>
                                <article class="bg-neutral-900 rounded-xl border border-neutral-800 p-6 h-full cursor-pointer relative transition-all duration-300 hover:-translate-y-1.5">
                                    <a href="{{ route('posts.show', $post->slug) }}" class="absolute inset-0 z-10"></a>
                                    <div class="flex items-center gap-2.5 text-neutral-400 text-xs font-mono mb-4">
                                        <span class="w-2 h-2 rounded-full bg-coral"></span>
                                        <time datetime="{{ $post->published_at->toDateString() }}">
                                            {{ $post->published_at->format('M d, Y') }}
                                        </time>
                                    </div>
                                    <h3 class="font-heading text-lg font-bold text-neutral-300 mb-2">
                                        {{ $post->title }}
                                    </h3>
                                    <p class="text-neutral-400 text-sm leading-relaxed mb-4">
                                        {{ $post->excerpt }}
                                    </p>
                                    <div class="flex flex-wrap gap-1.5 z-10 relative">
                                        @foreach($post->tags as $tag)
                                            <a href="{{ route('posts.index', ['tag' => $tag->slug]) }}"
                                               class="px-2.5 py-1 text-xs font-mono bg-ocean/30 text-neutral-300 rounded">
                                                {{ $tag->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>
</x-app-layout>
