<div>
    @if($selectedTag)
        <div class="justify-center items-center mb-10 space-x-2 flex">
            <span class="text-neutral-300 text-sm">Showing projects with tag:</span>
            <span wire:click="clearSelectedTag" class="text-neutral-100 border border-ocean bg-ocean/20 px-3 py-1 rounded-full text-sm leading-tight flex items-center cursor-pointer">{{ $selectedTag->name }}<x-icons.times class="h-3 pl-2"/></span>
        </div>
    @endif

    <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
        @foreach($projects as $project)
            <x-project :project="$project" />
        @endforeach
    </ul>

    @if($projects->hasPages())
        <div class="mt-6 px-2">
            {{ $projects->links() }}
        </div>
    @endif
</div>
