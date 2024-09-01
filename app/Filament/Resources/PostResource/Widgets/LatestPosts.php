<?php

namespace App\Filament\Resources\PostResource\Widgets;

use App\Filament\Resources\PostResource;
use App\Models\Post;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;

class LatestPosts extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(Post::query()->orderByDesc('id')->take(4))
            ->columns([
                Tables\Columns\TextColumn::make('title')->limit(30)->tooltip(fn(Model $post) => $post->title),
                Tables\Columns\IconColumn::make('is_published')->boolean(),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->icon(FilamentIcon::resolve('actions::edit-action') ?? 'heroicon-m-pencil-square')
                    ->action(fn(Post $record) => redirect()->to(PostResource::getUrl('edit', ['record' => $record]))),
            ]);
    }
}
