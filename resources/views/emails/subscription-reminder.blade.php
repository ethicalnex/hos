@component('mail::message')
# Subscription Renewal Reminder

Hello,

Your subscription for **{{ $renewal->hospital->name }}** will renew on **{{ $renewal->renewal_date->format('M j, Y') }}**.

Please ensure your payment method is up to date to avoid service interruption.

@component('mail::button', ['url' => url("/hospital/subscription/renew/{$renewal->id}")])
Renew Now
@endcomponent

Thank you for choosing us!

Best regards,  
The EthicalNex Team
@endcomponent