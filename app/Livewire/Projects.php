<?php

namespace App\Livewire;

use App\Models\Project;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\NoReturn;
use Livewire\Component;
use Spatie\Tags\Tag;

class Projects extends Component
{
    public Collection $items;
    public string $nextCursor = '';
    public bool $hasMore = false;

    #[NoReturn]
    public function loadMore(): void
    {
        $paginator = $this->projects();
        $this->nextCursor = $paginator->nextCursor()?->encode() ?? '';
        $this->hasMore = $paginator->hasMorePages();
        $this->items->push(...$paginator->items());
    }

    public function mount(): void
    {
        $paginator = $this->projects();
        $this->nextCursor = $paginator->nextCursor()?->encode() ?? '';
        $this->hasMore = $paginator->hasMorePages();
        $this->items = collect($paginator->items());
    }

    public function render(): View
    {
        return view('livewire.projects', [
            'projects' => $this->items,
            'tag' => request()->has('tag') ? Tag::findFromString(request('tag')) : null,
        ]);
    }

    protected function projects(): CursorPaginator
    {
        return Project::published()
            ->when(request()->has('tag'), fn($query) => $query->withAnyTags(request('tag')))
            ->cursorPaginate(perPage: 8, cursor: $this->nextCursor);
    }
}
