<?php
//TODO: check function to make sure it is the admin who access this site
//TODO: link to index when admin is logged in
//TODO: add view count & add to database
//TODO: keep input in fills if errors
//TODO: add captcha before submit button

session_start();
include_once('connection.php');
include_once('common_function.php');
if (@$_SESSION['email']) {
?>
<html lang="en">
<head>
    <title>Create a module</title>
</head>
<body>
<center><strong><a href="index.php">Home</a></strong> | <a href='index.php?action=sign_out'>Sign out</a>
    <form method="post">
        <table cellpadding="10">
            <tr style="background:lightblue;">
                <td width="25%"></td>
                <td></td>
            </tr>
            <tr>
                <td>Module name</td>
                <td><input type="text" name="txt_name"/></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" value="Create the module" name="btn_submit"/></td>
            </tr>
        </table>
    </form>

<?php
if (isset($_POST['btn_submit'])) {
    $name = $_POST['txt_name'];
    $user = $_SESSION['username'];

    if (isset($name)) {
        try {
            //prepare sql statement
            $sql = "INSERT INTO module (name, creator) VALUES (:name, :creator)";
            $statement = $pdo->prepare($sql);

            //bind parameters
            $statement->bindParam(':name', $name, PDO::PARAM_STR);
            $statement->bindParam(':creator', $user, PDO::PARAM_STR);

            //execute statement
            if ($statement->execute()) {
                echo "Module created";
            } else {
                echo "Error: " . $sql . "<br>" . $statement->errorInfo()[2];
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Module name cannot be empty";
    }
}

include_once('sign_out.php');

} else {header('location: index.php');}
?>
</center>
</body>
</html>