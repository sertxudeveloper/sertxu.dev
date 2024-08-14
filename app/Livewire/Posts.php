<?php

namespace App\Livewire;

use App\Models\Post;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\NoReturn;
use Livewire\Component;

class Posts extends Component
{
    public Collection $items;
    public string $nextCursor = '';
    public bool $hasMore = false;

    #[NoReturn]
    public function loadMore(): void
    {
        $paginator = $this->posts();
        $this->nextCursor = $paginator->nextCursor()?->encode() ?? '';
        $this->hasMore = $paginator->hasMorePages();
        $this->items->push(...$paginator->items());
    }

    public function mount(): void
    {
        $paginator = $this->posts();
        $this->nextCursor = $paginator->nextCursor()?->encode() ?? '';
        $this->hasMore = $paginator->hasMorePages();
        $this->items = collect($paginator->items());
    }

    public function render(): View
    {
        return view('livewire.posts', [
            'posts' => $this->items,
        ]);
    }

    protected function posts(): CursorPaginator
    {
        return Post::query()->active()->defaultOrder()->cursorPaginate(perPage: 8, cursor: $this->nextCursor);
    }
}
