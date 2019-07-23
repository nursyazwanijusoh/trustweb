<!DOCTYPE html>
<html>
<head>
    <title>trUSt Registration Notification</title>
</head>
<body>
<h2>Dear {{ $rejecteduser->name }}</h2>
<br/>
@if($rejecteduser->action == 'Reject')
Your trUSt registration has been rejected.
@else
Your trUSt account has been deleted.
@endif
<br/><br/>
Remark from admin:
<p>
  {{ nl2br($rejecteduser->remark) }}
</p>

</body>

</html>
