<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Post;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\NoReturn;
use Livewire\Component;

final class Posts extends Component
{
    public Collection $items;

    public string $nextCursor = '';

    public bool $hasMore = false;

    #[NoReturn]
    public function loadMore(): void
    {
        $paginator = $this->query();
        $this->nextCursor = $paginator->nextCursor()?->encode() ?? '';
        $this->hasMore = $paginator->hasMorePages();
        $this->items->push(...$paginator->items());
    }

    public function mount(): void
    {
        $paginator = $this->query();
        $this->nextCursor = $paginator->nextCursor()?->encode() ?? '';
        $this->hasMore = $paginator->hasMorePages();
        $this->items = collect($paginator->items());
    }

    public function render(): View
    {
        return view('livewire.posts');
    }

    private function query(): CursorPaginator
    {
        return Post::published()
            ->cursorPaginate(perPage: 8, cursor: $this->nextCursor);
    }
}
