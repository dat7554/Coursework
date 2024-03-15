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
<center>
<?php
//header
if (@$_SESSION['email']) { //check session
    $email = $_SESSION['email'];
    $sql = "SELECT userID FROM user WHERE email = :email";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':email', $email, PDO::PARAM_STR);
    $statement->execute();
    $id = $statement->fetch()['userID'];

    echo "<strong><a href='index.php'>Home</a> | <a href='create_post.php'>Create a post</a></strong>";
    echo "<p>Welcome, <a style='text-decoration: none' href='profile.php?id=$id'><b>" . @$_SESSION['email'] . "</b></a> | <a href='index.php?action=sign_out'>Sign out</a></p>";
} else { //header public view
    echo "<strong><a href='index.php'>Home</a> | <a href='sign_in.php'>Sign in</a> | <a href='sign_up.php'>Sign up</a></strong>";
}
?>

<table border="1px">
    <tr>
        <td width="500px" style="text-align: center">Module</td>
        <td width="100px" style="text-align: center">Views</td>
        <td width="100px" style="text-align: center">Creator</td>
        <td width="100px" style="text-align: center">Date created</td>
        <td width="100px" style="text-align: center">Date updated</td>
    </tr>
</table>
</center>
</body>
</html>
<?php
$sql = "SELECT name, views, creator, create_date, update_date FROM module";

include_once('sign_out.php');
?>