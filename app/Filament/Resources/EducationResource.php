<?php

namespace App\Filament\Resources;

use App\Models\Education;
use App\Filament\Resources\EducationResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;use function Symfony\Component\String\u;

class EducationResource extends Resource
{
    protected static ?string $model = Education::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan('full'),

                Forms\Components\MarkdownEditor::make('description')
                    ->required()
                    ->toolbarButtons([
                        'bold',
                        'bulletList',
                        'italic',
                        'strike',
                        'link',
                        'redo',
                        'undo',
                    ])
                    ->maxLength(255)
                    ->columnSpan('full'),

                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\DatePicker::make('started_at')
                            ->required(),

                        Forms\Components\DatePicker::make('ended_at'),

                        Forms\Components\TextInput::make('location')
                            ->required()
                            ->maxLength(50),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),

                Tables\Columns\TextColumn::make('started_at')
                    ->date('M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('ended_at')
                    ->date('M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('location')
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
            'index' => Pages\ManageEducation::route('/'),
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
