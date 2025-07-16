@component('mail::message')
# Appointment {{ ucfirst($action) }}

Hello {{ $appointment->first_name }},

Your appointment for **{{ $appointment->clinicService->name ?? 'a service' }}** on  
**{{ \Carbon\Carbon::parse($appointment->appointment_datetime)->format('M d, Y h:i A') }}**  
has been **{{ $action }}**.

@if ($action === 'rescheduled')
Please review the new schedule and confirm your availability.
@endif

Thank you for using our clinic booking system!

Regards,<br>
{{ config('app.name') }}
@endcomponent
