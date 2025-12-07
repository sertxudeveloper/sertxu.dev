<?php

declare(strict_types=1);

namespace App\Filament\Resources\Education\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Education\EducationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListEducation extends ListRecords
{
    protected static string $resource = EducationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
