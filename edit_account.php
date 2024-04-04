<?php
//TODO: display the current user_roleID into the default value of role dropdown
//TODO: change info-value layout
//TODO: add avatar function to edit_account.php, database.php
//TODO: change pass (if possible)

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
$statement->bindParam('userID', $userID, PDO::PARAM_INT);
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
</head>
<body>
<center>

    <?php
    //header
    include('header.php');
    ?>

    <form method="post" enctype="multipart/form-data"> <!-- as user upload file -->
        <table cellpadding="10">
            <tr style="background:lightblue;">
                <td width="25%">Info</td>
                <td>Value</td>
            </tr>
            <?php
            if ($_SESSION['user_roleID'] != 1) {
                exit();
            }

            echo "<tr>
                <td>Role</td>
                <td>
                    <select name='user_roleID'>";
            //retrieve and display all roles in a dropdown menu
            $sql = "SELECT * FROM user_role";
            $statement = $pdo->query($sql);

            if ($statement) {
                while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='" . $row['user_roleID'] . "'>" . $row['role'] . "</option>";
                }
            } else {
                echo "Error fetching roles.";
            }

            echo "</select>
                </td>
            </tr>";
            ?>

            <!-- <?php
            if ($user['image']) {
                echo "
            <tr>
                <td>Current Image</td>
                <td><img alt='current image in the post' src='" . $user['image'] . "' width='50%'></td>
            </tr>";
            }
            ?>
            <tr>
                <td>Upload profile avatar <br>(<b>.png</b>, <b>.jpeg</b>, <b>.jpg</b>)</td>
                <td>
                    <input type="file" name="image">
                </td>
            </tr>-->
            <tr>
                <td>Email</td>
                <td><input type="email" name="txt_email" value="<?php echo htmlspecialchars($user['email']); ?>"/></td>
            </tr>
            <tr>
                <td>Username</td>
                <td><input type="text" name="txt_username" value="<?php echo htmlspecialchars($user['username']); ?>"></td>
            </tr>
            <tr>
                <td>Personal description</td>
                <td><textarea style="resize: none; width: 100%; height: 150px" name="textarea_description"><?php echo htmlspecialchars($user['personal_description']);?></textarea></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" value="Save Changes" name="btn_submit"/></td>
            </tr>