<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

final class AppLayout extends Component
{
    /**
     * Create the component instance.
     */
    public function __construct(
        public readonly ?string $title = null,
        public readonly ?string $description = null,
        public readonly ?string $canonical = null,
        public readonly ?string $ogImage = null,

        /**
         * @var array{name: string, url: string}|null
         */
        public readonly ?array $breadcrumbs = null,

        /**
         * @var array{headline: string, description: string, image: string, datePublished: string, dateModified: string, url: string}|null
         */
        public readonly ?array $schemaArticle = null,
    ) {}

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.app');
    }
}
