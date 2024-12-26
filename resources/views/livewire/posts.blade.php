<div>
    <div class="mx-auto w-1/2 mb-4">
        <input type="text" wire:model.live.debounce="query"
               class="bg-dark-100 border border-zinc-700 w-full px-4 py-2.5 text-sm rounded-lg text-zinc-300 outline-none focus:border-ocean" placeholder="Search posts by title or content...">
    </div>

    @if($selectedTag)
        <div class="justify-center items-center mb-10 space-x-2 flex">
            <span class="text-neutral-300 text-sm">Showing posts with tag:</span>
            <span wire:click="clearSelectedTag" class="text-neutral-100 border border-ocean bg-ocean/20 px-3 py-1 rounded-full text-sm leading-tight flex items-center cursor-pointer">{{ $selectedTag->name }}<x-icons.times class="h-3 pl-2"/></span>
        </div>
    @endif

    <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
        @foreach($posts as $post)
            <x-post :post="$post" />
        @endforeach
    </ul>

    @if($posts->hasPages())
        <div class="mt-6 px-2">
            {{ $posts->links() }}
        </div>
    @endif
</div>
