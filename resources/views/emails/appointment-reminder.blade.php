<p>Hello {{ $appointment->first_name }} {{ $appointment->surname }},</p>

<p>This is a reminder that your appointment is scheduled for:</p>

<p><strong>{{ \Carbon\Carbon::parse($appointment->appointment_datetime)->format('F j, Y \a\t g:i A') }}</strong></p>

<p>Please be on time. Thank you!</p>
