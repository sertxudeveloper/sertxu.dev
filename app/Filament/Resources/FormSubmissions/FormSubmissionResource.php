<?php

declare(strict_types=1);

namespace App\Filament\Resources\FormSubmissions;

use App\Filament\Resources\FormSubmissions\Pages\ListFormSubmissions;
use App\Filament\Resources\FormSubmissions\Pages\ViewFormSubmission;
use App\Models\FormSubmission;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use UnitEnum;

final class FormSubmissionResource extends Resource
{
    protected static ?string $model = FormSubmission::class;

    protected static string|UnitEnum|null $navigationGroup = 'Forms';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-inbox';

    protected static ?int $navigationSort = 0;

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Submission Details')
                    ->schema([
                        TextEntry::make('name'),

                        TextEntry::make('email'),

                        TextEntry::make('message')
                            ->columnSpanFull(),
                    ]),
            ])->columns(1);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFormSubmissions::route('/'),
            'view' => ViewFormSubmission::route('/{record}'),
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
                TextColumn::make('name')
                    ->searchable(),

                TextColumn::make('email')
                    ->searchable(),

                TextColumn::make('message')
                    ->limit(80)
                    ->searchable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                Action::make('view')
                    ->icon('heroicon-o-eye')
                    ->url(fn (FormSubmission $record): string => FormSubmissionResource::getUrl('view', ['record' => $record])),

                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make()->outlined(),
                ForceDeleteBulkAction::make()->outlined(),
                RestoreBulkAction::make()->outlined(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
