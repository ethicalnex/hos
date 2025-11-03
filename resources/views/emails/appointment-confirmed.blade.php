<h2>Appointment Confirmed</h2>
<p>Dear {{ $appointment->patient->name }},</p>
<p>Your appointment is confirmed for:</p>
<ul>
    <li><strong>Date:</strong> {{ $appointment->scheduled_time->format('F j, Y') }}</li>
    <li><strong>Time:</strong> {{ $appointment->scheduled_time->format('g:i A') }}</li>
    <li><strong>Doctor:</strong> {{ $appointment->doctor->name }}</li>
    <li><strong>Service:</strong> {{ $appointment->service->name }}</li>
</ul>