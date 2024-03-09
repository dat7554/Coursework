<?php
//TODO: add captcha before submit button
//TODO: create a header -- echo "<a style='text-decoration: none' href='profile.php?id=$id'><b>" . @$_SESSION['email'] . "</b></a>";
//TODO: fix the image upload

session_start();
include_once('connection.php');
include_once('common_function.php');
if (@$_SESSION['email']) {
    ?>
    <html lang="en">
    <head>
        <title>Create a post</title>
    </head>
    <body>
    <center><strong><a href="index.php">Home</a></strong> | <a href='index.php?action=sign_out'>Sign out</a>
    <form method="post" enctype="multipart/form-data"> <!-- as user upload file -->
        <table cellpadding="10">
            <tr style="background:lightblue;">
                <td width="25%"></td>
                <td></td>
            </tr>
            <tr>
                <td>Post title</td>
                <td><input type="text" name="txt_title"/></td>
            </tr>
            <tr>
                <td>Content</td>
                <td><textarea style="resize: none;" name="textarea_content"></textarea></td>
            </tr>
            <tr>
                <td>Upload image <br>(<b>.png</b>, <b>.jpeg</b>, <b>.jpg</b>)</td>
                <td>
                    <input type="file" name="image">
                </td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" value="Create the post" name="btn_submit"/></td>
            </tr>
        </table>
    </form>
    </center>
    </body>
    </html>
    <?php
    if (isset($_POST['image'])) {
        $extension = array('.png', '.jpeg', '.jpg');
        $file_name = basename($_FILES['image']['name']); // basename() may prevent filesystem traversal attacks
        $file_extension = strtolower(pathinfo($file_name,PATHINFO_EXTENSION));
        $file_size = $_FILES['image']['size'];
        $file_tmp = $_FILES['image']['tmp'];
        $errors = array();

        if (in_array($file_extension, $extension) === false) {
            $errors[] = 'Please check the file extension';
        }

        if ($file_size > 5242880) {
            $errors[] = 'File must be under 5MB';
        }

        if (empty($error)) {
            move_uploaded_file($file_tmp, "images/post/$file_name");

        } else {
            foreach ($errors as $error) {
                echo $error;
            }
        }
    }

    //if (isset($_POST['btn_submit'])) {}

    if (@$_GET['action']=='sign_out') {
        session_destroy();
        header('location: index.php');
    }
} else {header('location: index.php');}