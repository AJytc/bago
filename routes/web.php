<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Models\ClinicService;
use App\Models\Appointment;
use App\Http\Controllers\AppointmentBookingController;
use App\Livewire\BookAppointment;
use App\Livewire\MedstaffDashboard;
use App\Http\Controllers\Medstaff\AppointmentActionController;
use Illuminate\Http\Request;

Route::get('/', function () {
    return redirect()->route('login');
});

// 🔐 Authenticated + verified users (Jetstream)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // 📌 Main Dashboard (based on role)
    Route::get('/dashboard', function () {
        $user = Auth::user();

        if ($user->hasRole('admin')){
            return view('admin.dashboard');
        }

        if ($user->hasRole('medstaff')){
            return app(\App\Livewire\MedstaffDashboard::class)->render();
        }

        if ($user->hasRole('user')){
            return view('dashboard');
        }

        abort(403);
    })->name('dashboard');

    // 🩺 MedStaff-specific Appointments page
    Route::get('/medstaff/appointments', function (Request $request) {
        if (!Auth::user()?->hasRole('medstaff')) {
            abort(403);
        }

        $appointments = Appointment::with('clinicService')
            ->when($request->status, fn ($query) => $query->where('status', $request->status))
            ->latest()
            ->get();

        return view('medstaff.appointments', compact('appointments'));
    })->name('medstaff.appointments');

    // ✅ Accept/Reject appointment actions
    Route::post('/medstaff/appointments/{appointment}/accept', [AppointmentActionController::class, 'accept'])
        ->name('medstaff.appointments.accept');

    Route::post('/medstaff/appointments/{appointment}/reject', [AppointmentActionController::class, 'reject'])
        ->name('medstaff.appointments.reject');

    Route::post('/medstaff/appointments/{appointment}/complete', [AppointmentActionController::class, 'complete'])
        ->name('medstaff.appointments.complete');

    // ✅ Reschedule routes
    Route::get('/medstaff/appointments/{appointment}/reschedule', [AppointmentActionController::class, 'edit'])
        ->name('medstaff.appointments.reschedule');

    Route::post('/medstaff/appointments/{appointment}/reschedule', [AppointmentActionController::class, 'update'])
        ->name('medstaff.appointments.reschedule.update');
});

// 👤 Authenticated User Appointments
Route::middleware(['auth'])->group(function () {
    // 🗓 Show all available clinic services (user home appointment page)
    Route::get('/appointments', function () {
        $services = \App\Models\ClinicService::all();
        return view('appointments', compact('services'));
    })->name('appointments.index');

    // 📄 Show the booking form for a specific service
    Route::get('/appointments/book/{service}', \App\Livewire\BookAppointment::class)
        ->name('appointments.book');

    // ✅ Handle form submission (store booking)
    Route::post('/appointments/book/{service}', [AppointmentBookingController::class, 'store']);
});