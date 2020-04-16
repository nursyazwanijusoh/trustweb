@component('mail::message')
# MCO Travel Permit Application

There is a new request for MCO travel permit by {{ $name }}

@component('mail::button', ['url' => $url])
View request lists to approve
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
