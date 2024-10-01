<?php

declare(strict_types=1);

namespace App\Filament\Resources\ProjectResource\Widgets;

use App\Filament\Resources\ProjectResource;
use App\Models\Project;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;

final class LatestProjects extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(Project::query()->orderByDesc('id')->take(4))
            ->columns([
                Tables\Columns\TextColumn::make('title')->limit(30)->tooltip(fn (Model $post) => $post->title),
                Tables\Columns\IconColumn::make('is_published')->boolean(),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->icon(FilamentIcon::resolve('actions::edit-action') ?? 'heroicon-m-pencil-square')
                    ->action(fn (Project $record) => redirect()->to(ProjectResource::getUrl('edit', ['record' => $record]))),
            ]);
    }
}
