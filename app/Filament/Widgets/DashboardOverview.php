<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\User;
use App\Models\ClinicService;
use App\Models\Appointment;

class DashboardOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Card::make('Total Users', User::count())
                ->description('View all users')
                ->url(route('filament.admin.resources.users.index')) // 🔗 clickable
                ->color('success'),

            Card::make('Clinic Services', ClinicService::count())
                ->description('Manage clinic services')
                ->url(route('filament.admin.resources.clinic-services.index')) // 🔗 clickable
                ->color('info'),

            Card::make('Appointments Today', Appointment::whereDate('created_at', now())->count())
                ->description('View today\'s appointments')
                ->url(route('filament.admin.resources.appointments.index')) // 🔗 clickable
                ->color('primary'),
        ];
    }
    protected static ?int $sort = 1; // Top of the dashboard
}
