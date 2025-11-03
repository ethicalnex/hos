@component('mail::message')
# Your Free Trial is Ending

Hello,

Your free trial for **{{ $hospital->name }}** will end today.

To continue using our services, please upgrade your subscription.

@component('mail::button', ['url' => url('/hospital/subscription')])
Upgrade Plan
@endcomponent

Thank you for choosing us!

Best regards,  
The EthicalNex Team
@endcomponent