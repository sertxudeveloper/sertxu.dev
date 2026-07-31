<?php

declare(strict_types=1);

namespace App\Filament\Resources\Education\Pages;

use App\Filament\Resources\Education\EducationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Override;

final class EditEducation extends EditRecord
{
    #[Override]
    protected static string $resource = EducationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
