<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-gray-800 leading-tight">
            👋 Welcome, {{ Auth::user()->name }}!
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ✅ Next Appointment --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-2">Your Next Appointment</h3>
                @livewire('dashboard-upcoming-appointment')
            </div>

            {{-- ✅ Announcements --}}
            @php
                $announcements = \App\Models\Announcement::latest()->take(5)->get();
            @endphp

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Clinic Announcements</h3>

                @forelse ($announcements as $announcement)
                    <div class="mb-4">
                        <h4 class="text-md font-bold text-gray-800">{{ $announcement->title }}</h4>
                        <p class="text-sm text-gray-700">{{ $announcement->body }}</p>
                        <p class="text-xs text-gray-500 mt-1">Posted {{ $announcement->created_at->diffForHumans() }}</p>
                        @if (!$loop->last)
                            <hr class="my-3">
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No announcements available.</p>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
