<div>
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
