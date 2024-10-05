<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Post;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\NoReturn;
use Livewire\Attributes\Url;
use Livewire\Component;
use Spatie\Tags\Tag;

final class Posts extends Component
{
    public Collection $items;

    public string $nextCursor = '';

    public bool $hasMore = false;
    
    #[Url]
    public string $tag = '';

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
        $this->items = collect();
        $this->loadMore();
    }

    public function render(): View
    {
        return view('livewire.posts', [
            'selectedTag' => Tag::findFromString($this->tag) ?? null,
        ]);
    }
    
    public function clearSelectedTag(): void
    {
        $this->tag = '';
        
        $this->items = collect();
        $this->loadMore();
    }

    private function query(): CursorPaginator
    {
        return Post::published()
            ->when(!empty($this->tag), fn ($query) => $query->withAllTags($this->tag))
            ->cursorPaginate(perPage: 8, cursor: $this->nextCursor);
    }
}
