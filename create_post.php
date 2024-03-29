<?php
//TODO: keep input in fills if errors
//TODO: add captcha before submit button
//TODO: create a header -- echo "<a style='text-decoration: none' href='profile.php?id=$id'><b>" . @$_SESSION['email'] . "</b></a>";
//TODO: query userID to db as creator
//TODO: htmlspecialchars
//TODO: edit the size of content textarea

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
                <td>Module</td>
                <td>
                    <select name="moduleID">
                        <?php
                        //retrieve and display all modules in a dropdown menu
                        $sql = "SELECT moduleID, name FROM module";
                        $statement = $pdo->query($sql);

                        if ($statement) {
                            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='" . $row['moduleID'] . "'>" . $row['name'] . "</option>";
                            }
                        } else {
                            echo "Error fetching modules.";
                        }
                        ?>
                    </select>
                </td>
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

<?php
if (isset($_POST['btn_submit'])) {
    //retrieve data from the form
    $title = $_POST['txt_title'];
    $content = $_POST['textarea_content'];
    $moduleID = $_POST['moduleID'];

    if (isset($title) && isset($content) && isset($moduleID)) {
        //check if an image file has been uploaded
        if (!empty($_FILES['image']['name'])) {
            //handle image upload
            if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $extension = array('png', 'jpeg', 'jpg');
                $file_name = basename($_FILES['image']['name']); //basename() may prevent filesystem traversal attacks
                $file_extension = strtolower(pathinfo($file_name,PATHINFO_EXTENSION));
                $file_size = $_FILES['image']['size'];
                $file_tmp = $_FILES['image']['tmp_name'];

                $errors = array();

                //validate file extension
                if (!in_array($file_extension, $extension)) {
                    $errors[] = 'Please check the file extension';
                }

                //validate file size
                if ($file_size > 5242880) {
                    $errors[] = 'File must be under 5MB';
                }

                //move uploaded file to desired location
                if (empty($errors)) {
                    $image = 'images/post/'.$file_name;
                    move_uploaded_file($file_tmp, "images/post/$file_name");
                    echo "Image uploaded successfully";
                } else {
                    foreach ($errors as $error) {
                        echo $error;
                    }
                }
            } else {
                echo "Error occurred during file upload";
            }
        } else {
            //no image uploaded, set image parameter to NULL
            $image = "";
        }

        //query to retrieve userID based on email
        $user_email = $_SESSION['email'];
        $user_sql = "SELECT userID FROM user WHERE email = :email";
        $statement_user = $pdo->prepare($user_sql);
        $statement_user->bindParam(':email', $user_email, PDO::PARAM_STR);
        $statement_user->execute();
        $row = $statement_user->fetch(PDO::FETCH_ASSOC);
        $userID = $row['userID'];

        //prepare sql statement
        $sql = "INSERT INTO post (userID, moduleID, title, content, image) VALUES (:userID, :moduleID, :title, :content, :image)";
        $statement = $pdo->prepare($sql);

        //bind parameters
        $statement->bindParam(':userID', $userID, PDO::PARAM_INT);
        $statement->bindParam(':moduleID', $moduleID, PDO::PARAM_INT);
        $statement->bindParam(':title', $title, PDO::PARAM_STR);
        $statement->bindParam(':content', $content, PDO::PARAM_STR);
        $statement->bindParam(':image', $image, PDO::PARAM_STR);

        //execute statement
        if ($statement->execute()) {
            echo "Post created";
        } else {
            echo "Error: " . $sql . "<br>" . $statement->errorInfo()[2];
        }
    }
}

include_once('sign_out.php');

} else {header('location: index.php');}
?>
</center>
</body>
</html>