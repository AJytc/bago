<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Appointment;
use Carbon\Carbon;

class MedstaffDashboard extends Component
{
    public function render()
    {
        $today = Carbon::today();
        $endOfWeek = Carbon::today()->addDays(7);

        return view('livewire.medstaff-dashboard', [
            'todayCount' => Appointment::whereDate('appointment_datetime', $today)->count(),
            'pendingCount' => Appointment::where('status', 'pending')->count(),
            'upcomingWeekCount' => Appointment::whereBetween('appointment_datetime', [$today, $endOfWeek])->count(),
            'completedCount' => Appointment::where('status', 'completed')->count(),
            'rejectedCount' => Appointment::where('status', 'rejected')->count(),
        ]);
    }
}
