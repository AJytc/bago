<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Appointment;
use Carbon\Carbon;

class MedstaffDashboard extends Component
{
    public function render()
    {
        $now = Carbon::now();
        $next24h = Carbon::now()->addDay();

        return view('livewire.medstaff-dashboard', [
            'todayCount' => Appointment::whereDate('appointment_datetime', Carbon::today())->count(),
            'pendingCount' => Appointment::where('status', 'pending')->count(),
            'upcomingWeekCount' => Appointment::whereBetween('appointment_datetime', [$now, Carbon::today()->addDays(7)])->count(),
            'completedCount' => Appointment::where('status', 'completed')->count(),
            'rejectedCount' => Appointment::where('status', 'rejected')->count(),

            // 📣 Notifications
            'upcomingAppointments' => Appointment::whereBetween('appointment_datetime', [$now, $next24h])->get(),
            'pendingAppointments' => Appointment::where('status', 'pending')->get(),
        ]);
    }
}
