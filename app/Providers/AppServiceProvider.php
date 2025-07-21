<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Appointment;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentReminder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Log::info('Running appointment reminder check...');

        $appointments = Appointment::whereBetween('appointment_datetime', [
            now()->addHour()->startOfMinute(),
            now()->addHour()->endOfMinute(),
        ])->whereNull('reminder_sent_at')->get();

        Log::info('Found appointments: ' . $appointments->count());

        foreach ($appointments as $appointment) {
            Log::info('Sending reminder to: ' . $appointment->email);
            Mail::to($appointment->email)->send(new AppointmentReminder($appointment));
            $appointment->reminder_sent_at = now();
            $appointment->save();
        }
    }
}
