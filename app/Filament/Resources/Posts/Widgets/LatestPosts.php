<?php

declare(strict_types=1);

namespace App\Filament\Resources\Posts\Widgets;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\Action;
use App\Filament\Resources\Posts\PostResource;
use App\Models\Post;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;

final class LatestPosts extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(Post::query()->orderByDesc('id')->take(4))
            ->columns([
                TextColumn::make('title')->limit(30)->tooltip(fn (Model $post) => $post->title),
                IconColumn::make('is_published')->boolean(),
            ])
            ->recordActions([
                Action::make('edit')
                    ->icon(FilamentIcon::resolve('actions::edit-action') ?? 'heroicon-m-pencil-square')
                    ->action(fn (Post $record) => redirect()->to(PostResource::getUrl('edit', ['record' => $record]))),
            ]);
    }
}
