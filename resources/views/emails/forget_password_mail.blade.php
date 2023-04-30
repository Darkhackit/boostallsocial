<x-mail::message>
# Dear {{$user->email}}

We have received a request to reset the password for your Boostallsocial account. To complete the password reset process, please enter the following confirmation code on our website/app:

Confirmation Code: {{ $user->code }}

If you did not request a password reset, please ignore this email.

This code is only valid for the next 24 hours. If you do not confirm your account within this time frame, you will need to request a new confirmation code.

If you have any questions or concerns, please contact our customer support team at +233556277839.

Thank you for using Boostallsocial!

Best regards,
{{ config('app.name') }}
</x-mail::message>
