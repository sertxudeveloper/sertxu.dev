<?php

namespace App\Filament\Resources\StudiesResource\Pages;

use App\Filament\Resources\StudiesResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageStudies extends ManageRecords
{
    protected static string $resource = StudiesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
