<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- ✅ Announcements section --}}
            @php
                $announcements = \App\Models\Announcement::latest()->take(5)->get();
            @endphp

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">📢 Clinic Announcements</h2>

                @forelse ($announcements as $announcement)
                    <div class="mb-4">
                        <h3 class="text-md font-bold">{{ $announcement->title }}</h3>
                        <p class="text-sm text-gray-700">{{ $announcement->body }}</p>
                        <p class="text-xs text-gray-500 mt-1">Posted {{ $announcement->created_at->diffForHumans() }}</p>
                    </div>
                    <hr class="my-2">
                @empty
                    <p class="text-sm text-gray-500">No announcements available.</p>
                @endforelse
            </div>

            {{-- ✅ Upcoming Appointment Section --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
                @livewire('dashboard-upcoming-appointment')
            </div>

            {{-- Your other dashboard content --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <!-- <x-welcome /> -->
            </div>

        </div>
    </div>
</x-app-layout>
