<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
    <style>
        a.btn{
            padding: 15px;
            background: #0da265 !important;
            border-color: #0da265 !important;
            color: #fff !important;
            text-decoration: none;
            border-radius: 5px;
            box-shadow: 0px 2px 2px #bbb;
        }
    </style>
</head>
<body>
<h2>Account Verification & Password</h2>

<p>Dear {{$user->name}},</p>
<p>An account has been created on your behalf on the Association of African Universities Monitoring,
    Evaluation and Learning application portal.</p>
<p>The login credentials are listed below.</p>
<p>
    Email: {{$user->email}}<br>
    Password : {{$user->email}}
</p>
<br>
<p>Best regards.</p>

</body>
</html>