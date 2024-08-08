<?php

namespace App\Filament\Resources\ExperienceResource\Widgets;

use App\Models\Experience;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestExperiences extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(Experience::query()->orderByDesc('id'))
            ->columns([
                TextColumn::make('title'),

                TextColumn::make('started_at')
                    ->date('M Y'),

                TextColumn::make('location'),
            ]);
    }
}
