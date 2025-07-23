@component('mail::message')
# Hello {{ $user->name }},

Welcome to the **Clinic Online Scheduling System**!  
Your account has been created and you're now ready to log in using the credentials below:

@component('mail::panel')
**Email:** {{ $user->email }}  
**Password:** {{ $plainPassword }}
@endcomponent

> 📧 **Please verify your email before logging in.**  
> 🔒 **We recommend changing your password after your first login.**

@component('mail::button', ['url' => url('/login')])
Login Now
@endcomponent

Thanks,  
{{ config('app.name') }}
@endcomponent
