<?php

declare(strict_types=1);

namespace App\Filament\Resources\Projects\Pages;

use App\Filament\Resources\Projects\ProjectResource;
use Filament\Resources\Pages\CreateRecord;
use Override;

final class CreateProject extends CreateRecord
{
    #[Override]
    protected static string $resource = ProjectResource::class;
}
