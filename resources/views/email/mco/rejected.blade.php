@component('mail::message')
# MCO Travel Permit Rejected

Your request for travel permit has been denied.

@component('mail::button', ['url' => $url])
View request list
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
