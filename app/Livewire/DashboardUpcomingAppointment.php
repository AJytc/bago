<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardUpcomingAppointment extends Component
{
    public $upcoming;

    public function mount()
    {
        $this->upcoming = Appointment::where('email', Auth::user()->email)
            ->where('appointment_datetime', '>=', Carbon::now())
            ->orderBy('appointment_datetime', 'asc')
            ->first();
    }

    public function render()
    {
        return view('livewire.dashboard-upcoming-appointment');
    }
}
