<x-filament::widget>
    <x-filament::card>
        <h2 class="text-lg font-bold mb-4">📢 Latest Announcements</h2>

        @forelse ($this->getAnnouncements() as $announcement)
            <div class="mb-4">
                <h3 class="text-md font-semibold">{{ $announcement->title }}</h3>
                <p class="text-sm text-gray-600 line-clamp-3">{{ $announcement->body }}</p>
                <p class="text-xs text-gray-400 mt-1">Posted {{ $announcement->created_at->diffForHumans() }}</p>
            </div>
            <hr class="my-2">
        @empty
            <p class="text-sm text-gray-500">No announcements at this time.</p>
        @endforelse
    </x-filament::card>
</x-filament::widget>
