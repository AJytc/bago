<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Book Appointment: {{ $service->name }}</h2>
    </x-slot>

    {{-- ✅ Floating Success Notification --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show"
             class="fixed top-4 right-4 z-50 bg-green-100 text-green-800 border border-green-300 px-4 py-2 rounded shadow">
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif

    {{-- ❌ Floating Error Notification --}}
    @if (session('error'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show"
             class="fixed top-4 right-4 z-50 bg-red-100 text-red-800 border border-red-300 px-4 py-2 rounded shadow mt-2">
            <p class="text-sm font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <div class="max-w-xl mx-auto mt-6 p-6 bg-white shadow rounded-lg">
        <form method="POST" action="{{ url('/appointments/book/' . $service->id) }}">
            @csrf

            <div class="space-y-4">
                {{-- 👤 User Inputs --}}
                <input name="surname" class="w-full border p-2 rounded" placeholder="Surname" required>
                <input name="first_name" class="w-full border p-2 rounded" placeholder="First Name" required>
                <input name="middle_initial" class="w-full border p-2 rounded" placeholder="Middle Initial" maxlength="1">
                <input name="email" type="email" class="w-full border p-2 rounded" placeholder="Email" required>

                {{-- 🎓 Course Selection --}}
                <select name="course" required class="input input-bordered w-full mt-2">
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

                {{-- 📅 Date Selection --}}
                <label class="block mt-4">Select Date</label>
                <select name="selected_date" class="w-full border p-2 rounded" onchange="fetchTimes(this.value)" required>
                    <option value="">Choose a date</option>
                    @foreach($availableDates as $date)
                        <option value="{{ $date }}">{{ \Carbon\Carbon::parse($date)->format('F j, Y (l)') }}</option>
                    @endforeach
                </select>

                {{-- 🕒 Time Selection --}}
                <label class="block mt-4">Select Time</label>
                <select name="selected_time" id="time-options" class="w-full border p-2 rounded" required>
                    <option value="">Select a date first</option>
                </select>

                <button class="mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Book Now</button>
            </div>
        </form>
    </div>

    <script>
        function fetchTimes(date) {
            fetch(`/api/appointments/available-times/{{ $service->id }}/${date}`)
                .then(res => res.json())
                .then(times => {
                    const timeSelect = document.getElementById('time-options');
                    timeSelect.innerHTML = '';
                    if (times.length === 0) {
                        timeSelect.innerHTML = '<option>No slots available</option>';
                        return;
                    }
                    times.forEach(time => {
                        const option = document.createElement('option');
                        option.value = time;
                        option.textContent = time;
                        timeSelect.appendChild(option);
                    });
                });
        }
    </script>
</x-app-layout>
