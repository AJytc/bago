<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Appointments
        </h2>
    </x-slot>

    {{-- ✅ Floating Success Message (like Filament toast) --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)"
             x-show="show"
             class="fixed top-4 right-4 z-50 bg-green-100 text-green-800 border border-green-300 px-4 py-2 rounded shadow">
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($services as $service)
                    @php
                        $isExpired = $service->active_until && \Carbon\Carbon::parse($service->active_until)->isPast();
                        $formattedStart = \Carbon\Carbon::createFromFormat('H:i:s', $service->start_time)->format('h:i A');
                        $formattedEnd = \Carbon\Carbon::createFromFormat('H:i:s', $service->end_time)->format('h:i A');
                        $imageUrl = $service->image_url ? asset('storage/' . $service->image_url) : 'https://via.placeholder.com/300x180';
                    @endphp

                    <a href="{{ route('appointments.book', $service->id) }}"
                        class="block bg-white rounded-xl shadow-md p-4 hover:shadow-lg transition relative group {{ $isExpired ? 'pointer-events-none opacity-50' : '' }}">
                        <img src="{{ $imageUrl }}" alt="Service Image"
                            class="w-full h-40 object-cover rounded mb-3">

                        <h3 class="text-lg font-bold">{{ $service->name }}</h3>

                        <p class="text-sm text-gray-600 mb-1">
                            Days:
                            {{ is_array($service->days_of_week) ? implode(', ', $service->days_of_week) : 'N/A' }}
                        </p>

                        <p class="text-sm text-gray-600 mb-1">
                            Time: {{ $formattedStart }} – {{ $formattedEnd }}
                        </p>

                        @if ($isExpired)
                            <span class="absolute top-2 right-2 bg-red-100 text-red-600 text-xs font-semibold px-2 py-1 rounded">
                                This service is no longer active.
                            </span>
                        @endif
                    </a>
                @endforeach
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
