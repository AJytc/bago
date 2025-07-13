<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ClinicService;
use App\Models\Appointment;
use Carbon\Carbon;

class AppointmentBookingController extends Controller
{
    // Show booking form for a selected service
    public function create(ClinicService $service)
    {
        $availableDates = $this->generateAvailableDates($service);
        return view('appointments.book', compact('service', 'availableDates'));
    }

    // Store booking
    public function store(Request $request, ClinicService $service)
    {
        $request->validate([
            'surname' => 'required|string',
            'first_name' => 'required|string',
            'middle_initial' => 'nullable|string|max:1',
            'email' => 'required|email',
            'selected_date' => 'required|date',
            'selected_time' => 'required',
        ]);

        $selectedDateTime = $request->selected_date . ' ' . $request->selected_time;

        // 🛑 Check if time slot already taken
        $exists = Appointment::where('clinic_service_id', $service->id)
            ->where('appointment_datetime', $selectedDateTime)
            ->exists();

        if ($exists) {
            return back()->withErrors(['selected_time' => 'This time slot is already booked.']);
        }

        // 🛑 Check if daily limit reached
        $dayCount = Appointment::where('clinic_service_id', $service->id)
            ->whereDate('appointment_datetime', $request->selected_date)
            ->count();

        if ($dayCount >= $service->available_slots) {
            return redirect()->back()
                ->with('error', 'All slots for this date are full. Please choose another day.');
        }

        // ✅ Save appointment
        Appointment::create([
            'user_id' => auth()->id(), // optional if Jetstream user
            'clinic_service_id' => $service->id,
            'appointment_datetime' => $selectedDateTime,
            'surname' => $request->surname,
            'first_name' => $request->first_name,
            'middle_initial' => $request->middle_initial,
            'email' => $request->email,
            'course' => $request->course,
        ]);

        return redirect()->route('appointments.index')->with('success', 'Appointment booked successfully!');
    }

    // Generate valid dates based on clinic_service config
    private function generateAvailableDates($service)
    {
        $dates = [];
        $today = now()->startOfDay();
        $until = $service->active_until ?? now()->addWeeks(4)->startOfDay();
        $days = $service->days_of_week; // array of days e.g., ['Monday', 'Tuesday']

        while ($today->lte($until)) {
            if (in_array($today->format('l'), $days)) {
                $dates[] = $today->format('Y-m-d');
            }
            $today->addDay();
        }

        return $dates;
    }

    // Return available time slots for selected date
    public function getAvailableTimes(ClinicService $service, $date)
    {
        $start = Carbon::parse($service->start_time);
        $end = Carbon::parse($service->end_time);
        $interval = $service->time_interval;

        $times = [];

        while ($start->lt($end)) {
            $time = $start->format('H:i');

            $isTaken = Appointment::where('clinic_service_id', $service->id)
                ->whereDate('appointment_datetime', $date)
                ->whereTime('appointment_datetime', $time)
                ->exists();

            if (!$isTaken) {
                $times[] = $time;
            }

            $start->addMinutes($interval);
        }

        return response()->json($times);
    }
}
