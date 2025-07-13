<?php

namespace App\Filament\Resources\ClinicServiceResource\Pages;

use App\Filament\Resources\ClinicServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewClinicService extends ViewRecord
{
    protected static string $resource = ClinicServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
