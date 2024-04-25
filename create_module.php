<?php
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
<?php
//header
include('header.php');
?>

<div class="container my-3" style="width: 50%;">
    <form method="post">
        <label for="inputModuleName" class="form-label">Module Name</label>
        <input type="text" name="txt_name" id="inputModuleName" class="form-control">
        <input style="margin-top: 15px" type="submit" value="Create the module" name="btn_submit"/>
    </form>
</div>

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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</body>
</html>