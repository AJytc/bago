<?php

namespace App\Http\Controllers\Medstaff;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentActionController extends Controller
{
    public function accept(Appointment $appointment)
    {
        $appointment->status = 'accepted';
        $appointment->save();

        return back()->with('success', 'Appointment accepted.');
    }

    public function reject(Appointment $appointment)
    {
        $appointment->status = 'rejected';
        $appointment->save();

        return back()->with('success', 'Appointment rejected.');
    }

    public function complete(Appointment $appointment)
    {
        if ($appointment->status !== 'accepted') {
            return back()->with('error', 'Only accepted appointments can be marked as completed.');
        }

        $appointment->status = 'completed';
        $appointment->save();

        return back()->with('success', 'Appointment marked as completed.');
    }

    public function edit(Appointment $appointment)
    {
        return view('medstaff.reschedule', compact('appointment'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $request->validate([
            'appointment_datetime' => 'required|date|after:now',
        ]);

        $appointment->appointment_datetime = $request->appointment_datetime;
        $appointment->status = 'rescheduled';
        $appointment->save();

        return redirect()->route('medstaff.appointments')->with('success', 'Appointment rescheduled successfully.');
    }
}
