<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            All Appointments
        </h2>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
            <h3 class="text-lg font-bold mb-4">Appointments</h3>

            @if($appointments->count())
                <table class="min-w-full text-sm text-left border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-3 py-2 border">Surname</th>
                            <th class="px-3 py-2 border">First Name</th>
                            <th class="px-3 py-2 border">M.I.</th>
                            <th class="px-3 py-2 border">Email</th>
                            <th class="px-3 py-2 border">Course</th>
                            <th class="px-3 py-2 border">Service</th>
                            <th class="px-3 py-2 border">Date & Time</th>
                            <th class="px-3 py-2 border">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appt)
                            <tr class="border-t">
                                <td class="px-3 py-2">{{ $appt->surname }}</td>
                                <td class="px-3 py-2">{{ $appt->first_name }}</td>
                                <td class="px-3 py-2">{{ $appt->middle_initial }}</td>
                                <td class="px-3 py-2">{{ $appt->email }}</td>
                                <td class="px-3 py-2">{{ $appt->course }}</td>
                                <td class="px-3 py-2">{{ $appt->service_name }}</td>
                                <td class="px-3 py-2">{{ \Carbon\Carbon::parse($appt->appointment_datetime)->format('M d, Y h:i A') }}</td>
                                <td class="px-3 py-2">{{ ucfirst($appt->status) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No appointments found.</p>
            @endif
        </div>
    </div>
</x-app-layout>
