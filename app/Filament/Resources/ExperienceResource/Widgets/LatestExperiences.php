<?php

declare(strict_types=1);

namespace App\Filament\Resources\ExperienceResource\Widgets;

use App\Filament\Resources\ExperienceResource;
use App\Models\Experience;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

final class LatestExperiences extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(Experience::query()->orderByDesc('id')->take(4))
            ->columns([
                TextColumn::make('title'),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->icon(FilamentIcon::resolve('actions::edit-action') ?? 'heroicon-m-pencil-square')
                    ->action(fn (Experience $record) => redirect()->to(ExperienceResource::getUrl('edit', ['record' => $record]))),
            ]);
    }
}
