<x-mail::message>
#  Dear {{$user->email}},

    I am delighted to inform you that your referral has made a deposit on our website, and as promised, you are now eligible for a referral bonus of 5% of their deposit amount.

    Thank you for introducing {{ config('app.name') }} to our services and for your continued support. We believe that our customers are our best advocates, and we value your confidence in us.

    Your referral bonus has been credited to your account, and you can use it towards your next purchase on our website. We hope this bonus will be a token of our appreciation for your trust in our services.

    If you have any questions or concerns, please feel free to reach out to our customer support team. We are always here to assist you.

    Thank you again for your referral, and we look forward to serving you and your friends in the future.

    Best regards,
{{ config('app.name') }}
</x-mail::message>
