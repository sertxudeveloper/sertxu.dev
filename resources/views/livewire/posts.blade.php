<div>
    @if($tag)
        <div class="justify-center items-center mb-10 space-x-2 flex">
            <span class="text-neutral-300 text-sm">Showing post with tag:</span>
            <span wire:click="clearSelectedTag" class="text-neutral-100 border border-ocean bg-ocean/20 px-3 py-1 rounded-full text-sm leading-tight flex items-center">{{ $tag }}<x-icons.times class="h-3 pl-2"/></span>
        </div>
    @endif
  
    <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
        @foreach($this->items as $post)
            <x-post :post="$post" />
        @endforeach
    </ul>

    @if($this->hasMore)
        <div class="text-center mt-4">
            <button x-data x-intersect="$wire.loadMore" @click="$wire.loadMore"
                    class="py-2 px-24 text-white bg-ocean rounded-md hover:bg-ocean-dark">Load More</button>
        </div>
    @endif
</div>
