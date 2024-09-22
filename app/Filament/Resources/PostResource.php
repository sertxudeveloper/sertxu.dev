<?php

namespace App\Filament\Resources;

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
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;
    protected static ?string $navigationGroup = 'Content';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Post')
                    ->columns()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->columnSpanFull()
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, $state) use ($form) {
                                // If operating on an existing record, don't update the slug.
                                if ($form->getOperation() == 'create') {
                                    $set('slug', Str::slug($state));
                                }
                            }),

                        Forms\Components\Grid::make(4)
                            ->schema([
                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->columnSpan(2)
                                    ->prefix('/blog/')
                                    ->readOnlyOn('edit'),

                                Forms\Components\DateTimePicker::make('published_at')
                                    ->nullable(),

                                Forms\Components\Toggle::make('is_published')->inline(false),
                            ]),

                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Toggle::make('posted_on_twitter')
                                    ->inline(false)
                                    ->helperText('If enabled won\'t post.'),

                                Forms\Components\Toggle::make('posted_on_medium')
                                    ->inline(false)
                                    ->helperText('If enabled won\'t post.'),

                                Forms\Components\Toggle::make('posted_on_dev')
                                    ->inline(false)
                                    ->helperText('If enabled won\'t post.'),
                            ]),

                        Forms\Components\SpatieTagsInput::make('tags')
                            ->columnSpanFull(),

                        Forms\Components\MarkdownEditor::make('text')
                            ->columnSpanFull()
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('posts')
                            ->required(),
                    ]),
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
                    ->url(fn (Post $record) => $record->url(), shouldOpenInNewTab: true),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('schedule')
                        ->action(function (Post $post) {
                            if ($post->published_at) return;

                            $post->update(['published_at' => Post::nextFreePublishDate()]);
                        })
                        ->hidden(fn (Post $post) => $post->published_at || $post->is_published),

                    Tables\Actions\Action::make('unschedule')
                        ->action(function (Post $post) {
                            $post->update(['published_at' => null]);
                        })
                        ->hidden(fn (Post $post) => !$post->published_at || $post->is_published),

                    Tables\Actions\Action::make('publish now')
                        ->action(function (Post $post) {
                            $post->update(['is_published' => true, 'published_at' => now()]);
                        })
                        ->hidden(fn (Post $post) => $post->is_published),

                    Tables\Actions\Action::make('unpublish')
                        ->action(function (Post $post) {
                            $post->update(['is_published' => false, 'published_at' => null]);
                        })
                        ->hidden(fn (Post $post) => !$post->is_published),

                    Tables\Actions\Action::make('generate thumbnail')
                        ->action(fn (Post $post) => CreateOgImageJob::dispatch($post)),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->outlined(),
                Tables\Actions\ForceDeleteBulkAction::make()->outlined(),
                Tables\Actions\RestoreBulkAction::make()->outlined(),
            ]);
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