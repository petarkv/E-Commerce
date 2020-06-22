<html>
    <head>
        <title>Welcome Email</title>
    </head>
    <body>
        <table>
            <tr><td>Dear {{ $name }} {{ $surname }}!</td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td>Your account has been successfully activated.<br>
            Your account information is as below:</td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td>Email: {{ $email }}</td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td>Password: ****** (as chosen by You)</td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td>Thanks & Regards,</td></tr>
            <tr><td>MyShop</td></tr>            

    </body>
</html>