@component('mail::message')
# Appointment {{ ucfirst($action) }}

Hello {{ $appointment->first_name }},

@if ($action === 'rescheduled' && $appointment->original_appointment_datetime)
Your appointment for **{{ $appointment->clinicService->name ?? 'a service' }}** has been rescheduled.

**Original Appointment Date & Time:**  
{{ \Carbon\Carbon::parse($appointment->original_appointment_datetime)->format('M d, Y h:i A') }}

**New Appointment Date & Time:**  
{{ \Carbon\Carbon::parse($appointment->appointment_datetime)->format('M d, Y h:i A') }}

Please review the new schedule and confirm your availability.

@else
Your appointment for **{{ $appointment->clinicService->name ?? 'a service' }}** on  
**{{ \Carbon\Carbon::parse($appointment->appointment_datetime)->format('M d, Y h:i A') }}**  
has been **{{ $action }}**.
@endif

Thank you for using our clinic booking system!

Regards,<br>
{{ config('app.name') }}
@endcomponent
