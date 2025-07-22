<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            All Appointments
        </h2>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
            <h3 class="text-lg font-bold mb-4">Appointments</h3>

            {{-- ✅ Flash success message --}}
            @if (session('success'))
                <div class="mb-4 p-2 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- ✅ Filter by Status --}}
            <form method="GET" action="{{ route('medstaff.appointments') }}" class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Filter by Status:</label>
                <select name="status" onchange="this.form.submit()" class="border border-gray-300 rounded px-3 py-1 pr-8 text-sm">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>Accepted</option>
                    <option value="rescheduled" {{ request('status') === 'rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </form>

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
                            <th class="px-3 py-2 border">Actions</th>
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
                                <td class="px-3 py-2">{{ $appt->clinicService->name ?? 'N/A' }}</td>
                                <td class="px-3 py-2">{{ \Carbon\Carbon::parse($appt->appointment_datetime)->format('M d, Y h:i A') }}</td>
                                <td class="px-3 py-2 capitalize">{{ $appt->status }}</td>
                                <td class="px-3 py-2 text-center">
                                    @if ($appt->status === 'pending')
                                        <div class="flex justify-center space-x-2">
                                            <form action="{{ route('medstaff.appointments.accept', $appt) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-xs">
                                                    Accept
                                                </button>
                                            </form>

                                            <form action="{{ route('medstaff.appointments.reject', $appt) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">
                                                    Reject
                                                </button>
                                            </form>

                                            <form action="{{ route('medstaff.appointments.reschedule', $appt) }}" method="GET">
                                                <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-xs">
                                                    Reschedule
                                                </button>
                                            </form>
                                        </div>
                                    @elseif ($appt->status === 'accepted' || $appt->status === 'rescheduled')
                                        <div class="flex justify-center space-x-2">
                                            <form action="{{ route('medstaff.appointments.complete', $appt) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs">
                                                    Mark as Completed
                                                </button>
                                            </form>

                                            <form action="{{ route('medstaff.appointments.reschedule', $appt) }}" method="GET">
                                                <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-xs">
                                                    Reschedule
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-600 capitalize">{{ $appt->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No appointments found.</p>
            @endif
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
