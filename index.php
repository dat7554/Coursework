<?php
session_start();
include_once('connection.php');
include_once('common_function.php');
?>

<html lang="en">
<head>
    <title>Forum</title>

</head>
<body>

<?php
if (@$_SESSION['userID'] == null) {
    echo "<form method='POST'> <!-- action='login.php' -->
    <table cellpadding='10'>
        <tr style='background:lightblue;'>
            <td width='20%'>Info</td>
            <td>Value</td>
        </tr>
        <tr>
            <td>Email (*)</td>
            <td><input type='text' placeholder='Type here' name='txt_email'/><br/>
            </td>
        </tr>
        <tr>
            <td>Password (*)</td>
            <td><input type='password' name='txt_pass'/></td>
        </tr>
        <tr>
            <td></td>
            <td><input type='submit' value='Login' name='btn_submit'/></td>
        </tr>
    </table>
</form>";
} else {
    echo "<p>Welcome, " . @$_SESSION['email'] . " . <a href='sign_out.php'>Sign out</a></p>";
}
?>

</body>
</html>