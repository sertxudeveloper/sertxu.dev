<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Toggle;
use App\Filament\Resources\PostResource\Pages\ListPosts;
use App\Filament\Resources\PostResource\Pages\CreatePost;
use App\Filament\Resources\PostResource\Pages\EditPost;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\BulkAction;
use App\Actions\PublishPostAction;
use App\Filament\Resources\PostResource\Pages;
use App\Jobs\CreateOgImageJob;
use App\Models\Post;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

final class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Content';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    protected static ?int $navigationSort = 0;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Flex::make([
                    Section::make([
                        TextInput::make('title')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, $state) use ($schema): void {
                                // If operating on an existing record, don't update the slug.
                                if ($schema->getOperation() === 'create') {
                                    $set('slug', Str::slug($state));
                                }
                            }),

                        TextInput::make('slug')
                            ->required()
                            ->prefix('/blog/'),

                        SpatieTagsInput::make('tags'),

                        MarkdownEditor::make('text')
                            ->fileAttachmentsDirectory('posts')
                            ->required(),
                    ]),

                    Section::make([

                        Actions::make([
                            Action::make('preview')
                                ->icon('heroicon-o-eye')
                                ->outlined()
                                ->url(fn (Post $record): string => $record->url(), shouldOpenInNewTab: true),
                        ])
                            ->hiddenOn('create')
                            ->fullWidth(),

                        DateTimePicker::make('published_at')
                            ->nullable(),

                        Toggle::make('is_published')
                            ->inline(false),

                        Toggle::make('posted_on_twitter')
                            ->inline(false)
                            ->helperText('If enabled won\'t post.'),

                        Toggle::make('posted_on_dev')
                            ->inline(false)
                            ->helperText('If enabled won\'t post.'),

                        Toggle::make('posted_on_threads')
                            ->inline(false)
                            ->helperText('If enabled won\'t post.'),
                    ])->grow(false),
                ])->from('xl')->columnSpan('full'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPosts::route('/'),
            'create' => CreatePost::route('/create'),
            'edit' => EditPost::route('/{record}/edit'),
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
                TextColumn::make('title')->limit(70)->sortable(),
                TextColumn::make('published_at')->dateTime()->placeholder('Unpublished')->sortable(),
                IconColumn::make('is_published')->boolean(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                Action::make('preview')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Post $record): string => $record->url(), shouldOpenInNewTab: true),

                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),

                ActionGroup::make([
                    Action::make('schedule')
                        ->action(function (Post $post): void {
                            if ($post->published_at) {
                                return;
                            }

                            $post->update(['published_at' => Post::nextFreePublishDate()]);
                        })
                        ->hidden(fn (Post $post): bool => $post->published_at || $post->is_published),

                    Action::make('unschedule')
                        ->action(function (Post $post): void {
                            $post->update(['published_at' => null]);
                        })
                        ->hidden(fn (Post $post): bool => ! $post->published_at || $post->is_published),

                    Action::make('publish now')
                        ->action(function (Post $post, PublishPostAction $publishPostAction): void {
                            $publishPostAction->execute($post);
                        })
                        ->hidden(fn (Post $post) => $post->is_published),

                    Action::make('unpublish')
                        ->action(function (Post $post): void {
                            $post->update(['is_published' => false, 'published_at' => null]);
                        })
                        ->hidden(fn (Post $post): bool => ! $post->is_published),

                    Action::make('generate thumbnail')
                        ->action(fn (Post $post) => CreateOgImageJob::dispatch($post)),
                ]),
            ])
            ->toolbarActions([
                DeleteBulkAction::make()->outlined(),
                ForceDeleteBulkAction::make()->outlined(),
                RestoreBulkAction::make()->outlined(),
                BulkAction::make('generate thumbnails')
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
