<p>Hello {{ $appointment->first_name }} {{ $appointment->middle_initial }} {{ $appointment->surname }},</p>

<p>We have received your appointment request for:</p>

<ul>
    <li><strong>Service:</strong> {{ $appointment->clinicService->name }}</li>
    <li><strong>Date:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_datetime)->timezone('Asia/Manila')->toFormattedDateString() }}</li>
    <li><strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_datetime)->timezone('Asia/Manila')->format('h:i A') }}</li>
</ul>

<p>🕒 Your appointment is currently <strong>pending approval</strong>.</p>

<p>You will receive another email once your appointment has been approved or declined.</p>

<p>Thank you for using the Clinic Online Scheduling system!</p>