<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ClinicService;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentPendingMail;

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
        $startDate = now()->startOfDay(); // Start checking from today
        $until = $service->active_until ?? now()->addWeeks(4)->startOfDay();
        $days = $service->days_of_week ?? [];

        while ($startDate->lte($until)) {
            $isValidDay = in_array($startDate->format('l'), $days);

            if ($isValidDay) {
                if ($startDate->isToday()) {
                    // Check if current time is before service's end time
                    $currentTime = now();
                    $endTimeToday = Carbon::createFromTimeString($service->end_time);

                    // If current time has not passed the end time, allow today
                    if ($currentTime->lt($endTimeToday)) {
                        $dates[] = $startDate->format('Y-m-d');
                    }
                } elseif ($startDate->isAfter(now())) {
                    // Future dates are valid if they match allowed weekdays
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
        $appointment = Appointment::create([
            'user_id' => Auth::id(),
            'clinic_service_id' => $this->service->id,
            'appointment_datetime' => $selectedDateTime,
            'surname' => $this->surname,
            'first_name' => $this->first_name,
            'middle_initial' => $this->middle_initial,
            'email' => $this->email,
            'course' => $this->course,
        ]);

        // ✅ Send pending confirmation email
        Mail::to($appointment->email)->send(new AppointmentPendingMail($appointment));
        
        session()->flash('success', 'Appointment booked successfully! 📌 Reminder: This clinic is for enrolled students only.');
        return redirect()->route('appointments.index');
    }

    public function render()
    {
        return view('livewire.book-appointment')->layout('layouts.app');
    }
}
