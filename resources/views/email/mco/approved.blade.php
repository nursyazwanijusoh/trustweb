@component('mail::message')
# MCO Travel Permit Approved

MCO Request has been approved

@component('mail::button', ['url' => $url])
View Requests
@endcomponent

@component('mail::button', ['url' => $donlod])
Download Travel Permit
@endcomponent

Thanks, and stay safe!<br>
{{ config('app.name') }}
@endcomponent
