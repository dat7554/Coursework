<?php
//TODO: keep input in fills if errors
//TODO: add captcha before submit button
//TODO: htmlspecialchars

session_start();
include_once('connection.php');
include_once('common_function.php');

//check user
if (!isset($_SESSION['email'])) {
    header('location: index.php');
    exit();
}

?>
<html lang="en">
<head>
    <title>Create a post</title>
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
                <td><textarea style="resize: none; width: 100%; height: 150px" name="textarea_content"></textarea></td>
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
    $userID = $_SESSION['userID'];

    if (isset($title, $content, $moduleID)) {
        //check if any of the required fields are empty
        if (empty($title) or empty($content) or empty($moduleID)) {
            echo "Please fill in all required fields";
        } else {
            //check if an image file has been uploaded
            if (!empty($_FILES['image']['name'])) {
                //handle image upload
                if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $extension = array('png', 'jpeg', 'jpg');
                    $file_name = basename($_FILES['image']['name']); //basename() may prevent filesystem traversal attacks
                    $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
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
                        $image = 'images/post/' . $file_name;
                        if (move_uploaded_file($file_tmp, "images/post/$file_name")) {
                            echo "Image uploaded successfully";
                        } else {
                            echo "Error occurred while moving the uploaded file";
                        }
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
}
?>
</center>
</body>
</html>