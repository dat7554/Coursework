<?php
//TODO: add captcha before submit button
//TODO: create a header -- echo "<a style='text-decoration: none' href='profile.php?id=$id'><b>" . @$_SESSION['email'] . "</b></a>";

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
    <form method="post">
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
            <td></td>
            <td><input type="submit" value="Create the post" name="btn_submit"/></td>
        </tr>
        </table>
    </form>
    </center>
    </body>
    </html>
    <?php
    if (isset($_POST['btn_submit'])) {
        echo $_POST['txt_title']."<br>".$_POST['textarea_content']; //testing
    }

    if (@$_GET['action']=='sign_out') {
        session_destroy();
        header('location: index.php');
    }
} else {header('location: index.php');}
