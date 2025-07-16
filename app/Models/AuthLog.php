<?php

namespace App\Models;

use Spatie\Activitylog\Models\Activity;

class AuthLog extends Activity
{
    protected $table = 'activity_log';
}
