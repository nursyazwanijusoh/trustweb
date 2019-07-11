<!DOCTYPE html>
<html>
<head>
    <title>trUSt Registration Notification</title>
</head>
<body>
<h2>Dear {{ $rejecteduser->name }}</h2>
<br/>
Your trUSt registration has been {{ $rejecteduser->action }}.
<br/><br/>
Remark from admin:
<p>
  {{ nl2br($rejecteduser->remark) }}
</p>

</body>

</html>
