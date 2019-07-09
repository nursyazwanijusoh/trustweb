<!DOCTYPE html>
<html>
<head>
    <title>trUSt Feedback Response</title>
</head>
<body>
<h2>Greetings from trUSt</h2>
<br/>
Regarding your feedback below:
<br/>
<div style="border: green solid; border-radius: 5px; padding: 5px; margin: 5px;">
<b>{{ $fb->title }}</b><br />
{{ $fb->content }}
</div>
<br />
Response from the admin
<div style="border: blue dashed; border-radius: 5px; padding: 5px; margin: 5px;">
{{ $fb->remark }}
</div>
</body>

</html>
