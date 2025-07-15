<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Announcement;

class AnnouncementsWidget extends Widget
{
    protected static string $view = 'filament.widgets.announcements-widget';
    protected int|string|array $columnSpan = 'full'; // full-width widget

    public function getAnnouncements()
    {
        return Announcement::latest()->take(5)->get();
    }
}
