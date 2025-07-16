<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuthLogResource\Pages;
use App\Filament\Resources\AuthLogResource\RelationManagers;
use App\Models\AuthLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Tables\Columns\TextColumn;

class AuthLogResource extends Resource
{
    protected static ?string $model = AuthLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'System Logs';
    protected static ?string $label = 'Login/Logout Logs';
    protected static ?string $pluralLabel = 'Login/Logout Logs';
    protected static ?int $navigationSort = 100;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('causer.name')
                    ->label('Name')
                    ->searchable()
                    ->formatStateUsing(fn ($state) => $state ?? 'System'),
                TextColumn::make('properties.role')
                    ->label('Role')
                    ->badge(),
                TextColumn::make('description')
                    ->label('Action')
                    ->color(fn ($state) => $state === 'logged in' ? 'success' : 'danger')
                    ->weight('bold'),
                TextColumn::make('created_at')
                    ->label('Time')
                    ->since()
                    ->tooltip(fn ($record) => $record->created_at->toDayDateTimeString()),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('description')
                    ->label('Action')
                    ->options([
                        'logged in' => 'Logged In',
                        'logged out' => 'Logged Out',
                    ]),
                Tables\Filters\SelectFilter::make('properties->role')
                    ->label('Role')
                    ->options([
                        'admin' => 'Admin',
                        'clinic' => 'Clinic',
                        'user' => 'User',
                ]),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListAuthLogs::route('/'),
            // 'create' => Pages\CreateAuthLog::route('/create'),
            // 'edit' => Pages\EditAuthLog::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) \App\Models\AuthLog::count();
    }

    public static function getNavigationBadgeColor(): string | array | null
    {
        return 'gray';
    }
}
