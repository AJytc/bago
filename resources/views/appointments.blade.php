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
</x-app-layout>
