<x-mail::message>
# Dear {{$user->email}}

Thank you for signing up for Boostallsocial! To complete your registration, please enter the following confirmation code on our website/app:

Confirmation Code: {{ $user->code }}

This code is only valid for the next 24 hours. If you do not confirm your account within this time frame, you will need to request a new confirmation code.

If you did not sign up for Boostallsocial, please ignore this email.

Thank you for using Boostallsocial!

Best regards,

Boostallsocial Team
</x-mail::message>
