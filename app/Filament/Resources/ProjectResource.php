<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(6)
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->columnSpan(4)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Set $set, $state) use ($form) {
                        // If operating on an existing record, don't update the slug.
                        if ($form->getOperation() == 'create') {
                            $set('slug', Str::slug($state));
                        }
                    }),

                Forms\Components\DateTimePicker::make('published_at')
                    ->columnSpan(2)
                    ->nullable(),

                Forms\Components\TextInput::make('slug')
                    ->columnSpan(3)
                    ->required()
                    ->prefix('/projects/')
                    ->readOnlyOn('edit'),

                Forms\Components\TextInput::make('website')
                    ->columnSpan(3),

                Forms\Components\Textarea::make('excerpt')
                    ->rows(2)
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\MarkdownEditor::make('content')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\FileUpload::make('thumbnail')
                    ->required()
                    ->openable()
                    ->columnSpan('full')
                    ->directory('projects'),
            ]);
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
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageProjects::route('/'),
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
