<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $navigationIcon = 'heroicon-o-code-bracket';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Project')
                    ->columns()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->columnSpanFull()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, $state) use ($form) {
                                // If operating on an existing record, don't update the slug.
                                if ($form->getOperation() == 'create') {
                                    $set('slug', Str::slug($state));
                                }
                            }),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->prefix('/projects/')
                            ->readOnlyOn('edit'),

                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\Toggle::make('is_published')
                                    ->inline(false),

                                Forms\Components\Toggle::make('is_featured')
                                    ->inline(false)
                                    ->helperText('Appears always on top of the list.'),
                            ]),

                        Forms\Components\TextInput::make('website')
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('excerpt')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\SpatieTagsInput::make('tags')
                            ->columnSpanFull(),

                        Forms\Components\MarkdownEditor::make('text')
                            ->columnSpanFull(),

                        Forms\Components\SpatieMediaLibraryFileUpload::make('thumbnail')
                            ->collection('thumbnail')
                            ->responsiveImages()
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
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
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('is_published')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('preview')
                    ->icon('heroicon-o-eye')
                    ->url(fn(Project $record) => route('projects.show', $record), shouldOpenInNewTab: true),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->outlined(),
                Tables\Actions\ForceDeleteBulkAction::make()->outlined(),
                Tables\Actions\RestoreBulkAction::make()->outlined(),
            ]);
    }
}
