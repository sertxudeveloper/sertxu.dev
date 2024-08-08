<?php

namespace App\Filament\Resources\ExperienceResource\Widgets;

use App\Models\Experience;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\Summarizers\Count;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Query\Builder;

class LatestExperiences extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(Experience::query()->orderByDesc('id')->take(4))
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
