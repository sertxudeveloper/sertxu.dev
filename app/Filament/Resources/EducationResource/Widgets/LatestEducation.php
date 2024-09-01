<?php

namespace App\Filament\Resources\EducationResource\Widgets;

use App\Filament\Resources\EducationResource;
use App\Models\Education;
use Filament\Support\Facades\FilamentIcon;
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
                Tables\Actions\Action::make('edit')
                    ->icon(FilamentIcon::resolve('actions::edit-action') ?? 'heroicon-m-pencil-square')
                    ->action(fn(Education $record) => redirect()->to(EducationResource::getUrl('edit', ['record' => $record]))),
            ]);
    }
}
