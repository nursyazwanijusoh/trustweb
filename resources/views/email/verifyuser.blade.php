<!DOCTYPE html>
<html>
<head>
    <title>trUSt Registration Confirmation</title>
</head>
<body>
<h2>Welcome to trUSt, {{$user['name']}}</h2>
<br/>
Please click on the below link to verify your email account
<br/>
<a href="{{url('user/verify', $user->verifyUser->token)}}">Verify Email</a>
</body>

</html>
