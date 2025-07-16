<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

use Filament\Widgets\Widget;
use App\Models\AuthLog;

class RecentAuthLogs extends BaseWidget
{
    protected static ?string $heading = '🕒 Recent Login/Logout Logs';
    protected static ?int $sort = 1;
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                AuthLog::with('causer')
                    ->where('log_name', 'auth')
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('causer.name')
                    ->label('Name')
                    ->formatStateUsing(fn ($state) => $state ?? 'System')
                    ->searchable(),

                Tables\Columns\TextColumn::make('properties.role')
                    ->label('Role')
                    ->badge(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Action')
                    ->icon(fn ($state) => $state === 'logged in' ? 'heroicon-o-arrow-right-circle' : 'heroicon-o-arrow-left-circle')
                    ->color(fn ($state) => $state === 'logged in' ? 'success' : 'danger')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Time')
                    ->since()
                    ->tooltip(fn ($record) => $record->created_at->toDayDateTimeString()),
            ]);
    }
}
