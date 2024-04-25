<?php
//TODO: add view count & add to database
//TODO: keep input in fills if errors
//TODO: add captcha before submit button
//TODO: htmlspecialchars
//TODO: consider to change the creator to userID

session_start();
include_once('connection.php');
include_once('common_function.php');

if (!$_SESSION['user_roleID'] == 1) {
    header('location: index.php');
    exit();
}
?>
<html lang="en">
<head>
    <title>Create a module</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
            $sql = "INSERT INTO module (name, userID) VALUES (:name, :userID)";
            $statement = $pdo->prepare($sql);

            //bind parameters
            $statement->bindParam(':name', $name, PDO::PARAM_STR);
            $statement->bindParam(':userID', $_SESSION['userID'], PDO::PARAM_STR);

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
?>
</center>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</body>
</html>