@component('mail::message')
# Hello {{ $appointment->first_name }} {{ $appointment->surname }},

This is a friendly reminder that your appointment is scheduled for:

@component('mail::panel')
**{{ \Carbon\Carbon::parse($appointment->appointment_datetime)->timezone('Asia/Manila')->format('F j, Y \a\t g:i A') }}**
@endcomponent

Please be on time. Thank you for your patience!

@endcomponent
