<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClinicServiceResource\Pages;
use App\Filament\Resources\ClinicServiceResource\RelationManagers;
use App\Models\ClinicService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ImageColumn;

class ClinicServiceResource extends Resource
{
    protected static ?string $model = ClinicService::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';
    protected static ?string $navigationGroup = 'Clinic Management';
    protected static ?string $navigationLabel = 'Clinic Services';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),

                FileUpload::make('image_url')
                    ->label('Service Image')
                    ->directory('services') // will save under storage/app/public/services
                    ->image()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->imageEditor()
                    ->imagePreviewHeight('250')
                    ->maxSize(10240), // in KB (2MB)

                CheckboxList::make('days_of_week')
                    ->label('Available Days')
                    ->options([
                        'Monday' => 'Monday',
                        'Tuesday' => 'Tuesday',
                        'Wednesday' => 'Wednesday',
                        'Thursday' => 'Thursday',
                        'Friday' => 'Friday',
                        'Saturday' => 'Saturday',
                        'Sunday' => 'Sunday',
                    ])
                    ->columns(2)
                    ->required(),

                TimePicker::make('start_time')->label('Start Time')->required(),
                TimePicker::make('end_time')->label('End Time')->required(),

                TextInput::make('available_slots')->numeric()->minValue(1)->required(),
                TextInput::make('time_interval')->numeric()->label('Interval (minutes)')->minValue(1)->required(),

                DatePicker::make('active_until')->label('Available Until (optional)'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),

                ImageColumn::make('image_url')
                ->label('Image')
                ->disk('public')
                ->circular(), // or ->square()

                TextColumn::make('days_of_week')
                    ->label('Days')
                    ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', $state) : $state),

                TextColumn::make('start_time')->label('Start'),
                TextColumn::make('end_time')->label('End'),
                TextColumn::make('available_slots')->label('Slots'),
                TextColumn::make('time_interval')->label('Interval (min)'),
                TextColumn::make('active_until')->label('Expires')->date(),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListClinicServices::route('/'),
            'create' => Pages\CreateClinicService::route('/create'),
            'view' => Pages\ViewClinicService::route('/{record}'),
            'edit' => Pages\EditClinicService::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) \App\Models\ClinicService::count();
    }

    public static function getNavigationBadgeColor(): string | array | null
    {
        return 'info'; // 🔷
    }
}
