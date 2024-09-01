<div>
    @if($tag)
        <div class="mb-8 text-center">
            <span class="text-neutral-300 mr-2">Filtering by tag:</span>
            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-ocean text-white">
                {{ $tag->name }}
                <a href="{{ route('projects.index', request()->except('tag')) }}"
                   class="ml-2 text-white hover:text-neutral-300">
                    &times;
                </a>
            </span>
        </div>
    @endif

    <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
        @foreach($projects as $project)
            <x-project :project="$project" />
        @endforeach
    </ul>

    @if($this->hasMore)
        <div class="text-center mt-4">
            <button x-data x-intersect="$wire.loadMore" @click="$wire.loadMore"
                    class="py-2 px-24 text-white bg-ocean rounded-md hover:bg-ocean-dark">Load More</button>
        </div>
    @endif
</div>
