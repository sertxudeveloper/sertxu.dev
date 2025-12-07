<?php

declare(strict_types=1);

namespace App\Filament\Resources\ExperienceResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\ExperienceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditExperience extends EditRecord
{
    protected static string $resource = ExperienceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
