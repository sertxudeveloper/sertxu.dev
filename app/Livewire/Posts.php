<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
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
     * Clear the selected tag.
     */
    public function clearSelectedTag(): void
    {
        $this->tag = '';
    }

    /**
     * Get the pagination view.
     */
    public function paginationView(): string
    {
        return 'livewire::simple-pages';
    }

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
     * Get the post's excerpt.
     */
    protected function excerpt(): Attribute
    {
        return Attribute::make(
            get: fn () => Cache::remember(
                key: "post_excerpt_{$this->id}",
                ttl: now()->addWeek(),
                callback: fn () => Str::limit($this->text, 150)
            ),
        );
    }

    /**
     * Get the posts.
     */
    private function posts(): LengthAwarePaginator
    {
        return Post::query()
            ->wherePublished()
            ->with('media')
            ->when($this->tag, fn (Builder $query) => $query->withAnyTags($this->tag))
            ->when($this->query, fn (Builder $query) => $query->where(function (Builder $query) {
                $query->where('title', 'like', "%{$this->query}%")
                    ->orWhere('text', 'like', "%{$this->query}%");
            }))
            ->orderByDesc('published_at')
            ->paginate(perPage: 16);
    }
}
