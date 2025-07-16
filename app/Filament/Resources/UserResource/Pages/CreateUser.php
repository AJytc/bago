<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Mail\WelcomeUserMail;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
    
    protected string $generatedPassword;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // If using name parts:
        // $data['name'] = $data['last_name'] . ', ' . $data['first_name'];

        $this->generatedPassword = Str::random(10);
        $data['password'] = Hash::make($this->generatedPassword);

        return $data;
    }

    protected function afterCreate(): void
    {
        // event(new Registered($this->record)); // ✅ This sends the email verification

        Mail::to($this->record->email)->send(
            new WelcomeUserMail($this->record, $this->generatedPassword)
        );
    }
}
