<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_service_id',
        'appointment_datetime',
        'surname',
        'first_name',
        'middle_initial',
        'email',
        'course',
        'status',
    ];

    public function clinicService()
    {
        return $this->belongsTo(ClinicService::class);
    }
}
