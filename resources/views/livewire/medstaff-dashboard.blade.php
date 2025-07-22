<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            MedStaff Dashboard
        </h2>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        {{-- 📊 Summary Widgets --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-blue-100 text-blue-800 p-4 rounded shadow">
                <h3 class="font-bold text-lg">Today’s Appointments</h3>
                <p class="text-3xl mt-2">{{ $todayCount }}</p>
            </div>

            <div class="bg-yellow-100 text-yellow-800 p-4 rounded shadow">
                <h3 class="font-bold text-lg">Pending Appointments</h3>
                <p class="text-3xl mt-2">{{ $pendingCount }}</p>
            </div>

            <div class="bg-purple-100 text-purple-800 p-4 rounded shadow">
                <h3 class="font-bold text-lg">Upcoming 7 Days</h3>
                <p class="text-3xl mt-2">{{ $upcomingWeekCount }}</p>
            </div>

            <div class="bg-green-100 text-green-800 p-4 rounded shadow">
                <h3 class="font-bold text-lg">Completed Appointments</h3>
                <p class="text-3xl mt-2">{{ $completedCount }}</p>
            </div>

            <div class="bg-red-100 text-red-800 p-4 rounded shadow">
                <h3 class="font-bold text-lg">Rejected Appointments</h3>
                <p class="text-3xl mt-2">{{ $rejectedCount }}</p>
            </div>
        </div>

        {{-- 🔔 Notifications & Reminders --}}
        <div class="mt-12">
            <h2 class="text-2xl font-semibold mb-6 flex items-center gap-2">
                Notifications & Reminders
            </h2>

            {{-- ⏰ Upcoming Appointments in 24 Hours --}}
            <div class="mb-10">
                <h3 class="text-lg font-bold text-blue-700 mb-3">Upcoming Appointments (Next 24 Hours)</h3>
                @forelse ($upcomingAppointments as $appt)
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg shadow-sm mb-3">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between">
                            <div>
                                <p class="font-semibold text-gray-800">
                                    {{ $appt->surname }}, {{ $appt->first_name }}
                                    <span class="text-sm font-normal text-gray-600">— {{ $appt->clinicService->name ?? 'Service' }}</span>
                                </p>
                                <p class="text-sm text-gray-600">
                                    🕒 {{ \Carbon\Carbon::parse($appt->appointment_datetime)->format('M d, Y h:i A') }}
                                </p>
                            </div>
                            <span class="inline-block mt-2 sm:mt-0 px-3 py-1 bg-blue-200 text-blue-800 text-sm font-medium rounded-full">
                                Upcoming
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-600">No upcoming appointments in the next 24 hours.</p>
                @endforelse
            </div>

            {{-- ⚠️ Pending Appointments --}}
            <div>
                <h3 class="text-lg font-bold text-yellow-700 mb-3">Pending Appointments</h3>
                @forelse ($pendingAppointments as $appt)
                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg shadow-sm mb-3">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between">
                            <div>
                                <p class="font-semibold text-gray-800">
                                    {{ $appt->surname }}, {{ $appt->first_name }}
                                    <span class="text-sm font-normal text-gray-600">— {{ $appt->clinicService->name ?? 'Service' }}</span>
                                </p>
                                <p class="text-sm text-gray-600">
                                    📅 Requested on {{ $appt->created_at->format('M d, Y h:i A') }}
                                </p>
                            </div>
                            <span class="inline-block mt-2 sm:mt-0 px-3 py-1 bg-yellow-200 text-yellow-800 text-sm font-medium rounded-full">
                                Pending
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-600">No pending appointments.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ✅ About Our Clinic --}}
    <div class="w-full bg-white border-t border-gray-200 py-12 px-6 sm:px-12">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                <span>👩‍⚕️</span> About Our Clinic
            </h2>

            <div class="border-l-4 border-blue-400 pl-6 mb-6">
                <p class="text-gray-700 text-base sm:text-lg leading-relaxed mb-3">
                    Our clinic is dedicated to providing accessible, student-friendly healthcare services with a focus on quality,
                    compassion, and convenience. Whether it’s a routine check-up, a consultation, or an emergency concern, we’re here to help.
                </p>
                <p class="text-gray-700 text-base sm:text-lg leading-relaxed">
                    Located on campus and operated by licensed professionals, we aim to support your well-being every step of the way.
                </p>
            </div>

            <div class="flex flex-wrap justify-center gap-10 mt-10 text-gray-800 text-base sm:text-lg text-center">
                <div class="flex flex-col items-center gap-2 min-w-[220px]">
                    <span class="text-2xl">🕒</span>
                    <div>
                        <strong>Clinic Hours:</strong><br>
                        Monday – Thursday, 8:00 AM – 5:00 PM
                    </div>
                </div>

                <div class="flex flex-col items-center gap-2 min-w-[240px]">
                    <span class="text-2xl">✉️</span>
                    <div>
                        <strong>Email:</strong><br>
                        ccsfpclinic@gmail.com
                    </div>
                </div>

                <div class="flex flex-col items-center gap-2 min-w-[260px]">
                    <span class="text-2xl">📍</span>
                    <div>
                        <strong>Location:</strong><br>
                        Room 105, 1st Floor, Civic Center, San Isidro, City of San Fernando, Pampanga, Philippines
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
