<?php

declare(strict_types=1);

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use Filament\Resources\Pages\CreateRecord;
use Override;

final class CreatePost extends CreateRecord
{
    #[Override]
    protected static string $resource = PostResource::class;
}
