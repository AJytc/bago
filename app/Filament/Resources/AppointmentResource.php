<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Filament\Resources\AppointmentResource\RelationManagers;
use App\Models\Appointment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use App\Models\ClinicService;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Appointments';
    protected static ?string $navigationLabel = 'Manage Appointments';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('clinic_service_id')
                    ->label('Service')
                    ->relationship('clinicService', 'name')
                    ->required(),

                DateTimePicker::make('appointment_datetime')->required(),

                TextInput::make('surname')->required(),
                TextInput::make('first_name')->required(),
                TextInput::make('middle_initial')->nullable(),
                TextInput::make('email')->email()->required(),

                TextInput::make('course')
                    ->label('Course')
                    ->required(),

                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->default('pending')
                    ->disabledOn('edit')
                    ->label('Status'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('surname')
                    ->label('Surname')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('first_name')
                    ->label('First Name')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('middle_initial')
                    ->label('M.I.')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('course')
                    ->label('Course')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('clinicService.name')
                    ->label('Service')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('appointment_datetime')
                    ->label('Appointment Date & Time')
                    ->dateTime()
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'primary' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ])
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('clinic_service_id')
                    ->label('Service')
                    ->options(ClinicService::all()->pluck('name', 'id')),
                SelectFilter::make('course')
                    ->label('Course')
                    ->options([
                        'BSIT' => 'BSIT',
                        'BSAIS' => 'BSAIS',
                        'BSBA' => 'BSBA',
                        'BEED' => 'BEED',
                        'BECED' => 'BECED',
                        'BSED - Major in English' => 'BSED - Major in English',
                        'BSED - Major in Science' => 'BSED - Major in Science',
                        'BSED - Major in Math' => 'BSED - Major in Math',
        ]),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('Reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(fn (Appointment $record) => $record->update(['status' => 'rejected']))
                    ->requiresConfirmation()
                    ->visible(fn (Appointment $record) => $record->status !== 'rejected'),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            // 'edit' => Pages\EditAppointment::route('/{record}/edit'),
            'view' => Pages\ViewAppointment::route('/{record}'),
        ];
    }
}
