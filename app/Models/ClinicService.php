<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClinicService extends Model
{
    protected $fillable = [
        'name',
        'image_url',
        'days_of_week',
        'start_time',
        'end_time',
        'available_slots',
        'time_interval',
        'active_until',
    ];

    protected $casts = [
        'days_of_week' => 'array',
        'start_time' => 'string',
        'end_time' => 'string',
        'active_until' => 'date',
    ];
}   
