<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Actions\PublishPostAction;
use App\Filament\Resources\PostResource\Pages;
use App\Jobs\CreateOgImageJob;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

final class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Split::make([
                    Forms\Components\Section::make([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, $state) use ($form): void {
                                // If operating on an existing record, don't update the slug.
                                if ($form->getOperation() === 'create') {
                                    $set('slug', Str::slug($state));
                                }
                            }),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->prefix('/blog/'),

                        Forms\Components\SpatieTagsInput::make('tags'),

                        Forms\Components\MarkdownEditor::make('text')
                            ->fileAttachmentsDirectory('posts')
                            ->required(),
                    ]),

                    Forms\Components\Section::make([

                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('preview')
                                ->icon('heroicon-o-eye')
                                ->outlined()
                                ->url(fn (Post $record): string => $record->url(), shouldOpenInNewTab: true),
                        ])
                            ->hiddenOn('create')
                            ->fullWidth(),

                        Forms\Components\DateTimePicker::make('published_at')
                            ->nullable(),

                        Forms\Components\Toggle::make('is_published')
                            ->inline(false),

                        Forms\Components\Toggle::make('posted_on_twitter')
                            ->inline(false)
                            ->helperText('If enabled won\'t post.'),

                        Forms\Components\Toggle::make('posted_on_dev')
                            ->inline(false)
                            ->helperText('If enabled won\'t post.'),

                        Forms\Components\Toggle::make('posted_on_threads')
                            ->inline(false)
                            ->helperText('If enabled won\'t post.'),
                    ])->grow(false),
                ])->from('xl')->columnSpan('full'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->limit(70)->sortable(),
                Tables\Columns\TextColumn::make('published_at')->dateTime()->placeholder('Unpublished')->sortable(),
                Tables\Columns\IconColumn::make('is_published')->boolean(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('preview')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Post $record): string => $record->url(), shouldOpenInNewTab: true),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('schedule')
                        ->action(function (Post $post): void {
                            if ($post->published_at) {
                                return;
                            }

                            $post->update(['published_at' => Post::nextFreePublishDate()]);
                        })
                        ->hidden(fn (Post $post): bool => $post->published_at || $post->is_published),

                    Tables\Actions\Action::make('unschedule')
                        ->action(function (Post $post): void {
                            $post->update(['published_at' => null]);
                        })
                        ->hidden(fn (Post $post): bool => ! $post->published_at || $post->is_published),

                    Tables\Actions\Action::make('publish now')
                        ->action(function (Post $post, PublishPostAction $publishPostAction): void {
                            $publishPostAction->execute($post);
                        })
                        ->hidden(fn (Post $post) => $post->is_published),

                    Tables\Actions\Action::make('unpublish')
                        ->action(function (Post $post): void {
                            $post->update(['is_published' => false, 'published_at' => null]);
                        })
                        ->hidden(fn (Post $post): bool => ! $post->is_published),

                    Tables\Actions\Action::make('generate thumbnail')
                        ->action(fn (Post $post) => CreateOgImageJob::dispatch($post)),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->outlined(),
                Tables\Actions\ForceDeleteBulkAction::make()->outlined(),
                Tables\Actions\RestoreBulkAction::make()->outlined(),
                Tables\Actions\BulkAction::make('generate thumbnails')
                    ->action(fn (Collection $records) => $records->each(fn (Post $post) => CreateOgImageJob::dispatch($post))),
            ])
            ->defaultSort(fn ($query) => $query->orderBy('is_published', 'asc')->orderBy('published_at', 'desc'));
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->title;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'text'];
    }
}
