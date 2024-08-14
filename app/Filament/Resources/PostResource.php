<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Jobs\GeneratePostThumbnail;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->columnSpan('full')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Set $set, $state) use ($form) {
                        // If operating on an existing record, don't update the slug.
                        if ($form->getOperation() == 'create') {
                            $set('slug', Str::slug($state));
                        }
                    }),

                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->prefix('/blog/')
                    ->readOnlyOn('edit'),

                Forms\Components\Toggle::make('is_draft')
                    ->inline(false),

                Forms\Components\Textarea::make('excerpt')
                    ->columnSpan('full')
                    ->required(),

                Forms\Components\MarkdownEditor::make('content')
                    ->columnSpan('full')
                    ->required(),

                Forms\Components\FileUpload::make('thumbnail')
                    ->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),

                Tables\Columns\TextColumn::make('views'),

                Tables\Columns\TextColumn::make('published_at')
                    ->date('j M Y')
                    ->placeholder('Unpublished')
                    ->sortable(),

                Tables\Columns\ImageColumn::make('thumbnail')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
                BulkAction::make('generateThumbnail')
                    ->requiresConfirmation()
                    ->action(fn (Collection $records) => $records->each(fn(Post $post) => GeneratePostThumbnail::dispatch($post))),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePosts::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
