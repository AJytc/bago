<div class="bg-white shadow rounded-lg p-6">
    <h2 class="text-lg font-semibold mb-4">Next Appointment</h2>

    @if($upcoming)
        <div class="space-y-1">
            <p><strong>Service:</strong> {{ $upcoming->service_name }}</p>
            <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($upcoming->appointment_datetime)->format('F d, Y') }}</p>
            <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($upcoming->appointment_datetime)->format('h:i A') }}</p>
            <p><strong>Status:</strong>
                <span class="@if($upcoming->status === 'Accepted') text-green-600 @elseif($upcoming->status === 'Pending') text-yellow-600 @elseif($upcoming->status === 'Rejected') text-red-600 @endif font-semibold">
                    {{ $upcoming->status }}
                </span>
            </p>
        </div>
    @else
        <p class="text-gray-500">You have no upcoming appointments.</p>
    @endif
</div>
