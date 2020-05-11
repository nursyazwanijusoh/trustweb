@component('mail::message')
# trUSt Feedback Response

Dear {{ $name }},

Thank you for your feedback titled **{{ $thef->title }}**.

Here's what you have written previously:

@component('mail::panel')
{!! nl2br($thef->content) !!}
@endcomponent

And here's the response by given by the admin:

@component('mail::panel')
{!! nl2br($thef->remark) !!}
@endcomponent

Thanks!
@endcomponent
