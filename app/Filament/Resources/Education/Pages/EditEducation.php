<?php

declare(strict_types=1);

namespace App\Filament\Resources\Education\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Education\EducationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditEducation extends EditRecord
{
    protected static string $resource = EducationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
