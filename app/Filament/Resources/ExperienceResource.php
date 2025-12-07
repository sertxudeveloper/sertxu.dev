<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use App\Filament\Resources\ExperienceResource\Pages\ListExperiences;
use App\Filament\Resources\ExperienceResource\Pages\CreateExperience;
use App\Filament\Resources\ExperienceResource\Pages\EditExperience;
use App\Filament\Resources\ExperienceResource\Pages;
use App\Models\Experience;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class ExperienceResource extends Resource
{
    protected static ?string $model = Experience::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Content';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-briefcase';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Experience')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->columnSpanFull()
                            ->maxLength(255),

                        MarkdownEditor::make('description')
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
                            ->columnSpanFull(),

                        Grid::make(3)
                            ->schema([
                                DatePicker::make('started_at')
                                    ->required(),

                                DatePicker::make('ended_at'),

                                TextInput::make('location')
                                    ->required()
                                    ->maxLength(50),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable(),

                TextColumn::make('started_at')
                    ->date('M Y')
                    ->sortable(),

                TextColumn::make('ended_at')
                    ->date('M Y')
                    ->placeholder('Present')
                    ->sortable(),

                TextColumn::make('location')
                    ->searchable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make()->outlined(),
                ForceDeleteBulkAction::make()->outlined(),
                RestoreBulkAction::make()->outlined(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExperiences::route('/'),
            'create' => CreateExperience::route('/create'),
            'edit' => EditExperience::route('/{record}/edit'),
        ];
    }
}
