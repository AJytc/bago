@component('mail::message')
# Hello {{ $appointment->first_name }} {{ $appointment->middle_initial }} {{ $appointment->surname }},

We have received your appointment request for:

@component('mail::panel')
**Service:** {{ $appointment->clinicService->name }}  
**Date:** {{ \Carbon\Carbon::parse($appointment->appointment_datetime)->timezone('Asia/Manila')->toFormattedDateString() }}  
**Time:** {{ \Carbon\Carbon::parse($appointment->appointment_datetime)->timezone('Asia/Manila')->format('h:i A') }}
@endcomponent

🕒 Your appointment is currently **pending approval**.

You will receive another email once your appointment has been approved or declined.

Thanks for using the **Clinic Online Scheduling System**.

@endcomponent
