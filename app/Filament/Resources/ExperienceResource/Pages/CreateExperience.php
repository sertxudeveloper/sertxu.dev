<?php

declare(strict_types=1);

namespace App\Filament\Resources\ExperienceResource\Pages;

use App\Filament\Resources\ExperienceResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateExperience extends CreateRecord
{
    protected static string $resource = ExperienceResource::class;
}
