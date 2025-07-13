<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Models\ClinicService;
use App\Http\Controllers\AppointmentBookingController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();

        if ($user->hasRole('admin')){
            return view('admin.dashboard');
        }

        if ($user->hasRole('user')){
            return view('dashboard');
        }

        abort(403);
    })->name('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/appointments', function () {
        $services = \App\Models\ClinicService::all(); // 👈 fetch services from database
        return view('appointments', compact('services')); // 👈 pass to view
    })->name('appointments');
});

Route::middleware(['auth'])->group(function () {
    // 🗓 Show all available clinic services (user home appointment page)
    Route::get('/appointments', function () {
        $services = \App\Models\ClinicService::all();
        return view('appointments', compact('services'));
    })->name('appointments.index');

    // 📄 Show the booking form for a specific service
    Route::get('/appointments/book/{service}', [AppointmentBookingController::class, 'create'])
        ->name('appointments.book');

    // ✅ Handle form submission (store booking)
    Route::post('/appointments/book/{service}', [AppointmentBookingController::class, 'store']);
});