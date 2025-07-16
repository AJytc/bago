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
        <div class="mt-10">
            <h2 class="text-xl font-semibold mb-4">🔔 Notifications & Reminders</h2>

            {{-- ⏰ Upcoming Appointments in 24 Hours --}}
            <div class="mb-6">
                <h3 class="text-lg font-bold text-blue-700">⏰ Upcoming Appointments (next 24 hours)</h3>
                @forelse ($upcomingAppointments as $appt)
                    <div class="p-3 bg-blue-50 rounded mb-2 border border-blue-200">
                        <strong>{{ $appt->surname }}, {{ $appt->first_name }}</strong> —
                        {{ $appt->clinicService->name ?? 'Service' }} at
                        <span class="font-semibold">{{ \Carbon\Carbon::parse($appt->appointment_datetime)->format('M d, Y h:i A') }}</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-600">No upcoming appointments in the next 24 hours.</p>
                @endforelse
            </div>

            {{-- ⚠️ Pending Appointments --}}
            <div>
                <h3 class="text-lg font-bold text-yellow-700">⚠️ Pending Appointments</h3>
                @forelse ($pendingAppointments as $appt)
                    <div class="p-3 bg-yellow-50 rounded mb-2 border border-yellow-200">
                        <strong>{{ $appt->surname }}, {{ $appt->first_name }}</strong> —
                        {{ $appt->clinicService->name ?? 'Service' }} —
                        <span class="text-sm text-gray-700">Requested on {{ $appt->created_at->format('M d, Y h:i A') }}</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-600">No pending appointments.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
