<!DOCTYPE html>
<html>
<head>
    <title>Organization Invite</title>
</head>
<body>
    <p>Hi there,</p>
    <p>You have been invited to join the organization. Please use the following link to complete the process:</p>
    <p><a href="{{ $inviteUrl }}">{{ $inviteUrl }}</a></p>
    <p>Here is your user name: {{ $admin_name }}</p>
    <p>Here is your temporary password: {{ $temporaryPassword }}</p>
    <p>The password will expire in 15 minutes.</p>
</body>
</html>
