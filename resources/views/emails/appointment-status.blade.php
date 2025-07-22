@component('mail::message')
# Appointment {{ ucfirst($action) }}

Hello {{ $appointment->first_name }},

@if ($action === 'rescheduled' && $appointment->original_appointment_datetime)
Your appointment for **{{ $appointment->clinicService->name ?? 'a service' }}** has been rescheduled.

@component('mail::panel')
**Original:** {{ \Carbon\Carbon::parse($appointment->original_appointment_datetime)->timezone('Asia/Manila')->format('M d, Y \\a\\t h:i A') }}  
**New:** {{ \Carbon\Carbon::parse($appointment->appointment_datetime)->timezone('Asia/Manila')->format('M d, Y \\a\\t h:i A') }}
@endcomponent

Please review the new schedule and confirm your availability.

@else
Your appointment for **{{ $appointment->clinicService->name ?? 'a service' }}** has been **{{ $action }}**.

@component('mail::panel')
**Date & Time:** {{ \Carbon\Carbon::parse($appointment->appointment_datetime)->timezone('Asia/Manila')->format('M d, Y \\a\\t h:i A') }}
@endcomponent
@endif

Thank you for using our clinic booking system!

Regards,  
{{ config('app.name') }}
@endcomponent
