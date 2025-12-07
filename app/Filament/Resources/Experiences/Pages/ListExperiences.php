<?php

declare(strict_types=1);

namespace App\Filament\Resources\Experiences\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Experiences\ExperienceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListExperiences extends ListRecords
{
    protected static string $resource = ExperienceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
