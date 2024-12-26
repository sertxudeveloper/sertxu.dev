<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Tags\Tag;

final class Posts extends Component
{
    use WithPagination;

    #[Url]
    public string $query = '';

    #[Url]
    public string $tag = '';

    /**
     * Render the component.
     */
    public function render(): View
    {
        return view('livewire.posts', [
            'posts' => $this->posts(),
            'selectedTag' => Tag::findFromString($this->tag) ?? null,
        ]);
    }

    /**
     * Clear the selected tag.
     */
    public function clearSelectedTag(): void
    {
        $this->tag = '';
    }

    /**
     * Get the posts.
     */
    private function posts(): LengthAwarePaginator
    {
        return Post::published()
            ->when($this->tag, fn (Builder $query) => $query->withAnyTags($this->tag))
            ->when($this->query, fn (Builder $query) => $query->where(function (Builder $query) {
                $query->where('title', 'like', "%{$this->query}%")
                    ->orWhere('text', 'like', "%{$this->query}%");
            }))
            ->orderByDesc('published_at')
            ->paginate(perPage: 16);
    }

    /**
     * Get the pagination view.
     */
    public function paginationView(): string
    {
        return 'livewire::simple-pages';
    }
}
