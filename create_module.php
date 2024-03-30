<?php
//TODO: add view count & add to database
//TODO: keep input in fills if errors
//TODO: add captcha before submit button
//TODO: htmlspecialchars
//TODO: consider to change the creator to userID

session_start();
include_once('connection.php');
include_once('common_function.php');

if (isset($_SESSION['email'])) {
    //retrieve user role based on session email
    $sql = "SELECT user_roleID FROM user WHERE email = :email";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':email', $_SESSION['email'] , PDO::PARAM_STR);
    $statement->execute();
    $userRole = $statement->fetch(PDO::FETCH_ASSOC);

    //check if the user has the admin role
    if ($userRole && $userRole['user_roleID'] == 1) {
?>
<html lang="en">
<head>
    <title>Create a module</title>
</head>
<body>
<center>
<?php
//header
include('header.php');
?>
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
} else {header('location: index.php');}
?>
</center>
</body>
</html>