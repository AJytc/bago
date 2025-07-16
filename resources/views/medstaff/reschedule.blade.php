<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Reschedule Appointment
        </h2>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
            <form method="POST" action="{{ route('medstaff.appointments.reschedule.update', $appointment) }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">New Date & Time</label>
                    <input type="datetime-local" name="appointment_datetime" class="mt-1 block w-full border rounded p-2"
                           value="{{ old('appointment_datetime', \Carbon\Carbon::parse($appointment->appointment_datetime)->format('Y-m-d\TH:i')) }}" required>
                    @error('appointment_datetime')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-2">
                    <a href="{{ route('medstaff.appointments') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Cancel</a>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
