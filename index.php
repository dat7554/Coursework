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

//TODO: complete the link to profile
echo '<a href="index.php">Home</a> | <a href="profile.php?id=$id">Profile</a>';
if (@$_SESSION['email']) {

    $sql = 'SELECT * FROM user';
    $statement = $pdo->prepare($sql);
    $statement->execute();
    $rows = $statement->rowCount();
    $row = $statement->fetch();
    $id = $row['userID'];

    echo "<p>Welcome, <strong>" . @$_SESSION['email'] . "</strong> | <a href='index.php?action=sign_out'>Sign out</a></p>";
} else {
    echo "<a href='sign_in.php'>Sign in</a> | <a href='sign_up.php'>Sign up</a>";
}
?>

</body>
</html>
<?php
if (@$_GET['action']=='sign_out') {
    session_destroy();
    header('location: index.php');
}
?>