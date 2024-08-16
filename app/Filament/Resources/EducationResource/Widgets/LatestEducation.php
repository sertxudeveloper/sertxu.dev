<?php

namespace App\Filament\Resources\EducationResource\Widgets;

use App\Models\Education;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestEducation extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(Education::query()->orderByDesc('id')->take(4))
            ->columns([
                TextColumn::make('title'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->paginated(false);
    }
}
