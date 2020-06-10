@component('mail::message')
# trUSt Diary data

Refer attachment

@component('mail::button', ['url' => {{ route('report.gwd.summary')}}])
Go to report page
@endcomponent

You're welcome,<br>
**trUSt Diary**
@endcomponent
