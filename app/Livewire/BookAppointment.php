<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ClinicService;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BookAppointment extends Component
{
    public ClinicService $service;

    public $surname, $first_name, $middle_initial, $email, $course;
    public $selected_date, $selected_time;

    public $availableDates = [];
    public $availableTimes = [];

    public function mount(ClinicService $service)
    {
        $this->service = $service;
        $this->availableDates = $this->generateAvailableDates($service);
    }

    public function updatedSelectedDate($value)
    {
        $this->fetchAvailableTimes();
    }

    public function fetchAvailableTimes()
    {
        $start = Carbon::parse($this->service->start_time);
        $end = Carbon::parse($this->service->end_time);
        $interval = $this->service->time_interval;

        $times = [];

        while ($start->lt($end)) {
            $time = $start->format('H:i');

            $isTaken = Appointment::where('clinic_service_id', $this->service->id)
                ->whereDate('appointment_datetime', $this->selected_date)
                ->whereTime('appointment_datetime', $time)
                ->exists();

            if (!$isTaken) {
                $times[] = $time;
            }

            $start->addMinutes($interval);
        }

        $this->availableTimes = $times;
    }

    private function generateAvailableDates($service)
    {
        $dates = [];
        $startDate = now()->startOfDay(); // today
        $until = $service->active_until ?? now()->addWeeks(4)->startOfDay();
        $days = $service->days_of_week ?? [];

        while ($startDate->lte($until)) {
            if (
                $startDate->isToday() || $startDate->isAfter(now()) // today or future
            ) {
                if (in_array($startDate->format('l'), $days)) {
                    $dates[] = $startDate->format('Y-m-d');
                }
            }

            $startDate->addDay();
        }

        return $dates;
    }

    public function bookAppointment()
    {
        $this->validate([
            'surname' => 'required|string',
            'first_name' => 'required|string',
            'middle_initial' => 'nullable|string|max:1',
            'email' => 'required|email',
            'course' => 'required|string',
            'selected_date' => 'required|date',
            'selected_time' => 'required',
        ]);

        // 🚫 Prevent booking in the past
        if (Carbon::parse($this->selected_date)->lt(now()->startOfDay())) {
            $this->addError('selected_date', 'You cannot book a past date.');
            return;
        }

        $selectedDateTime = $this->selected_date . ' ' . $this->selected_time;

        // Check if time is taken
        $exists = Appointment::where('clinic_service_id', $this->service->id)
            ->where('appointment_datetime', $selectedDateTime)
            ->exists();

        if ($exists) {
            $this->addError('selected_time', 'This time slot is already booked.');
            return;
        }

        // Check if daily limit reached
        $dayCount = Appointment::where('clinic_service_id', $this->service->id)
            ->whereDate('appointment_datetime', $this->selected_date)
            ->count();

        if ($dayCount >= $this->service->available_slots) {
            session()->flash('error', 'All slots for this date are full. Please choose another day.');
            return;
        }

        // Save
        Appointment::create([
            'user_id' => Auth::id(),
            'clinic_service_id' => $this->service->id,
            'appointment_datetime' => $selectedDateTime,
            'surname' => $this->surname,
            'first_name' => $this->first_name,
            'middle_initial' => $this->middle_initial,
            'email' => $this->email,
            'course' => $this->course,
        ]);

        session()->flash('success', 'Appointment booked successfully!');
        return redirect()->route('appointments.index');
    }

    public function render()
    {
        return view('livewire.book-appointment')->layout('layouts.app');
    }
}
