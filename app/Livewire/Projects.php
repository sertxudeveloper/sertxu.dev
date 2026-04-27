<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Project;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Tags\Tag;

final class Projects extends Component
{
    use WithPagination;

    #[Url]
    public string $tag = '';

    /**
     * Render the component.
     */
    public function render(): View
    {
        return view('livewire.projects', [
            'projects' => $this->projects(),
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
     * Get the pagination view.
     */
    public function paginationView(): string
    {
        return 'livewire::simple-pages';
    }

    /**
     * Get the projects.
     */
    private function projects(): LengthAwarePaginator
    {
        return Project::query()
            ->wherePublished()
            ->when($this->tag, fn ($query) => $query->withAnyTags($this->tag))
            ->defaultOrder()
            ->paginate(perPage: 12);
    }
}
