<?php

declare(strict_types=1);

namespace App\Filament\Resources\Experiences\Pages;

use App\Filament\Resources\Experiences\ExperienceResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateExperience extends CreateRecord
{
    protected static string $resource = ExperienceResource::class;
}
