<?php

declare(strict_types=1);

namespace App\Filament\Resources\Posts\Widgets;

use App\Filament\Resources\Posts\PostResource;
use App\Models\Post;
use Filament\Actions\Action;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

final class LatestPosts extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(Post::query()->orderByDesc('id')->take(4))
            ->columns([
                TextColumn::make('title')->limit(30)->tooltip(fn (Post $post) => $post->title),
                IconColumn::make('is_published')->boolean(),
            ])
            ->recordActions([
                Action::make('edit')
                    ->icon(FilamentIcon::resolve('actions::edit-action') ?? 'heroicon-m-pencil-square')
                    ->action(fn (Post $record) => redirect()->to(PostResource::getUrl('edit', ['record' => $record]))),
            ]);
    }
}
