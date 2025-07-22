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

    // ✅ Holiday-related properties
    public $isHoliday = false;
    public $holidayName = null;

    public function mount(ClinicService $service)
    {
        $this->service = $service;
        $this->availableDates = $this->generateAvailableDates($service);
    }

    public function updatedSelectedDate($value)
    {
        $this->checkIfHoliday($value);
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
        $startDate = now()->startOfDay();
        $until = $service->active_until ?? now()->addWeeks(4)->startOfDay();
        $days = $service->days_of_week ?? [];

        while ($startDate->lte($until)) {
            $isValidDay = in_array($startDate->format('l'), $days);

            if ($isValidDay) {
                if ($startDate->isToday()) {
                    $currentTime = now();
                    $endTimeToday = Carbon::createFromTimeString($service->end_time);

                    if ($currentTime->lt($endTimeToday)) {
                        $dates[] = $startDate->format('Y-m-d');
                    }
                } elseif ($startDate->isAfter(now())) {
                    $dates[] = $startDate->format('Y-m-d');
                }
            }
            $startDate->addDay();
        }

        return $dates;
    }

    private function checkIfHoliday($date)
    {
        try {
            $response = file_get_contents("https://date.nager.at/api/v3/PublicHolidays/" . now()->year . "/PH");
            $holidays = collect(json_decode($response, true));

            $match = $holidays->firstWhere('date', $date);

            if ($match) {
                $this->isHoliday = true;
                $this->holidayName = $match['localName'];
            } else {
                $this->isHoliday = false;
                $this->holidayName = null;
            }
        } catch (\Exception $e) {
            $this->isHoliday = false;
            $this->holidayName = null;
        }
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

        // 🚫 Prevent booking on a holiday
        if ($this->isHoliday) {
            $this->addError('selected_date', 'You cannot book on a holiday: ' . $this->holidayName);
            return;
        }

        $selectedDateTime = $this->selected_date . ' ' . $this->selected_time;

        $exists = Appointment::where('clinic_service_id', $this->service->id)
            ->where('appointment_datetime', $selectedDateTime)
            ->exists();

        if ($exists) {
            $this->addError('selected_time', 'This time slot is already booked.');
            return;
        }

        $dayCount = Appointment::where('clinic_service_id', $this->service->id)
            ->whereDate('appointment_datetime', $this->selected_date)
            ->count();

        if ($dayCount >= $this->service->available_slots) {
            session()->flash('error', 'All slots for this date are full. Please choose another day.');
            return;
        }

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

        Mail::to($appointment->email)->send(new AppointmentPendingMail($appointment));

        session()->flash('success', 'Appointment booked successfully! 📌 Reminder: This clinic is for enrolled students only.');
        return redirect()->route('appointments.index');
    }

    public function render()
    {
        return view('livewire.book-appointment')->layout('layouts.app');
    }
}
