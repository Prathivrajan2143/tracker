<!DOCTYPE html>
<html>
<head>
    <title>Organization Invite</title>
</head>
<body>
    <p>Hi there,</p>
    <p>You have been invited to join the organization. Please use the following link to complete the process:</p>
    <p><a href="{{ route('apiroute') }}">{{ $inviteUrl }}</a></p>
    <p>This link will expire in 15 minutes.</p>
</body>
</html>
