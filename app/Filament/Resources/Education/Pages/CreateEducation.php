<?php

declare(strict_types=1);

namespace App\Filament\Resources\Education\Pages;

use App\Filament\Resources\Education\EducationResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateEducation extends CreateRecord
{
    protected static string $resource = EducationResource::class;
}
