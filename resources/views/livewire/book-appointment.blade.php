<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Book Appointment: {{ $service->name }}</h2>
    </x-slot>

    @if (session('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show"
             class="fixed top-4 right-4 z-50 bg-green-100 text-green-800 border border-green-300 px-4 py-2 rounded shadow">
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if (session('error'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show"
             class="fixed top-4 right-4 z-50 bg-red-100 text-red-800 border border-red-300 px-4 py-2 rounded shadow mt-2">
            <p class="text-sm font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <div class="max-w-xl mx-auto mt-6 p-6 bg-white shadow rounded-lg">
        <form wire:submit.prevent="bookAppointment" class="space-y-4">
            <input wire:model="surname" class="w-full border p-2 rounded" placeholder="Surname">
            @error('surname') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            <input wire:model="first_name" class="w-full border p-2 rounded" placeholder="First Name">
            @error('first_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            <input wire:model="middle_initial" class="w-full border p-2 rounded" placeholder="Middle Initial" maxlength="1">
            @error('middle_initial') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            <input wire:model="email" type="email" class="w-full border p-2 rounded" placeholder="Email">
            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            <select wire:model="course" class="input input-bordered w-full mt-2">
                <option value="">Select Course</option>
                <option value="BSIT">BSIT</option>
                <option value="BSAIS">BSAIS</option>
                <option value="BSBA">BSBA</option>
                <option value="BEED">BEED</option>
                <option value="BECED">BECED</option>
                <option value="BSED - Major in English">BSED - Major in English</option>
                <option value="BSED - Major in Science">BSED - Major in Science</option>
                <option value="BSED - Major in Math">BSED - Major in Math</option>
            </select>
            @error('course') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            <label class="block mt-4">Select Date</label>
            <select wire:model="selected_date" wire:change="$set('selected_date', $event.target.value)" class="w-full border p-2 rounded">
                <option value="">Choose a date</option>
                @foreach($availableDates as $date)
                    <option value="{{ $date }}">{{ \Carbon\Carbon::parse($date)->format('F j, Y (l)') }}</option>
                @endforeach
            </select>
            @error('selected_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            <label class="block mt-4">Select Time</label>
            <select wire:model="selected_time" class="w-full border p-2 rounded">
                <option value="">Choose a time</option>
                @foreach($availableTimes as $time)
                    <option value="{{ $time }}">{{ $time }}</option>
                @endforeach
            </select>
            @error('selected_time') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Book Now</button>
        </form>
    </div>
</div>
