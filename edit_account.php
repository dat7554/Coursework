<?php

session_start();
include_once('connection.php');
include_once('common_function.php');

//check user
if (!isset($_SESSION['email'])) {
    header('location: index.php');
    exit();
}

//check if userID in the URL
if (!isset($_GET['id'])) {
    header('location: index.php');
    exit();
}

//retrieve postID from URL
$userID = $_GET['id'];

//fetch the post data from the database
$sql = "SELECT * FROM user WHERE userID = :userID";
$statement = $pdo->prepare($sql);
$statement->bindParam(':userID', $userID, PDO::PARAM_INT);
$statement->execute();
$user = $statement->fetch(PDO::FETCH_ASSOC);

//check if user has the authority to edit
if ($userID != $_SESSION['userID'] && $_SESSION['user_roleID'] != 1) {
    header('location: index.php');
    exit();
}
?>
<html lang="en">
<head>
    <title>Edit account</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

<?php //header
include('header.php');
?>

<div class="container my-3" style="width: 50%">
    <h1>Edit account</h1>
    <form method="post">
        <div class="mb-3">
            <?php if ($_SESSION['user_roleID'] == 1) {?>
                <label for="selectRole" class="form-label">Role: </label>
                <select name='user_roleID' id="selectRole">
                    <?php
                    $sql = "SELECT * FROM user_role";
                    $statement = $pdo->query($sql);

                    if ($statement) {
                        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='" . $row['user_roleID'] . "' ";

                            //display current role as default
                            if ($row['user_roleID'] == $user['user_roleID']) {
                                echo "selected = 'selected'";
                            }

                            echo ">" . $row['role'] . "</option>";
                        }
                    } else {
                        echo "<p style='color: red'>Error fetching roles.</p>";
                    }
                    ?>
                </select>
            <?php } else {
                $user_roleID = $user['user_roleID'];
            }?>
        </div>
        <div class="mb-3">
            <label for="inputEmail" class="form-label">Email</label>
            <input class="form-control" name="txt_email" type="text" id="inputEmail" value="<?php echo htmlspecialchars($user['email']); ?>">
        </div>
        <div class="mb-3">
            <label for="inputUsername" class="form-label">Username</label>
            <input class="form-control" name="txt_username" type="text" id="inputUsername" value="<?php echo htmlspecialchars($user['username']); ?>">
        </div>
        <div class="mb-3">
            <label for="inputDescription" class="form-label">Personal description</label>
            <textarea name='textarea_description' class="form-control" id="inputDescription" rows="5"><?php echo htmlspecialchars($user['personal_description']);?></textarea>
        </div>
        <div class="mb-3">
            <input class="btn btn-primary" type="submit" value="Save Changes" name="btn_submit"/>
        </div>
    </form>

<?php //check if the form is submitted to edit the post
if (isset($_POST['btn_submit'])) {
    //retrieve data from the form
    $email = $_POST['txt_email'];
    $username = $_POST['txt_username'];
    $personal_description = $_POST['textarea_description'];

    if (isset($user_roleID, $email, $username, $personal_description)) {
        if (empty($user_roleID) or empty($email) or empty($username)) {
            echo "<p style='color: red'>Please fill in all required fields</p>";
            exit();
        } else {
            $sql = "UPDATE user SET user_roleID = :user_roleID, email = :email, username = :username, personal_description = :personal_description, update_date = NOW()";


            //update post in database
            $sql .= " WHERE userID = :userID";
            $statement = $pdo->prepare($sql);
            $statement->bindParam(':user_roleID', $user_roleID, PDO::PARAM_INT);
            $statement->bindParam(':email', $email, PDO::PARAM_STR);
            $statement->bindParam(':username', $username, PDO::PARAM_STR);
            $statement->bindParam(':personal_description', $personal_description, PDO::PARAM_STR);
            $statement->bindParam(':userID', $userID, PDO::PARAM_INT);
        }

        if ($statement->execute()) {
            echo "<p style='color: red'>Updated successfully</p><br>";
        } else {
            echo "Error: " . $sql . "<br>" . $statement->errorInfo()[2];
        }
    }
}
?>
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>