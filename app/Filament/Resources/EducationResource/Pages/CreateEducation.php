<?php

declare(strict_types=1);

namespace App\Filament\Resources\EducationResource\Pages;

use App\Filament\Resources\EducationResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateEducation extends CreateRecord
{
    protected static string $resource = EducationResource::class;
}
