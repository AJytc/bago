<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-gray-800 leading-tight">
            Welcome, {{ Auth::user()->name }}!
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ✅ Next Appointment --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-2 text-gray-800">Your Next Appointment</h3>
                @livewire('dashboard-upcoming-appointment')
            </div>

            {{-- ✅ Announcements --}}
            @php
                $announcements = \App\Models\Announcement::latest()->take(5)->get();
            @endphp

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Clinic Announcements</h3>

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

    {{-- ✅ About Our Clinic (Restored to White) --}}
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
