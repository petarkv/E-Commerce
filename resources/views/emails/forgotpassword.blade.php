<html>
    <head>
        <title>Forgot Password Email</title>
    </head>
    <body>
        <table>
            <tr><td>Dear {{ $name }} {{ $surname }}!</td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td>Your account has been successfully updated.<br>
            Your account information is as below with the new password:</td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td>Email: {{ $email }}</td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td>New Password: {{ $password }}</td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td>Thanks & Regards,</td></tr>
            <tr><td>MyShop</td></tr>            

    </body>
</html>