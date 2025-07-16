<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            MedStaff Dashboard
        </h2>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
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
    </div>
</x-app-layout>
