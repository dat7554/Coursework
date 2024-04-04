<?php
//TODO: delete image
//TODO: keep input in fills if errors

session_start();
include_once('connection.php');
include_once('common_function.php');

//check user
if (!isset($_SESSION['email'])) {
    header('location: index.php');
    exit();
}

//check if postID in the URL
if (!isset($_GET['id'])) {
    header('location: index.php');
    exit();
}

//retrieve postID from URL
$postID = $_GET['id'];

//fetch the post data from the database
$sql = "SELECT * FROM post WHERE postID = :postID";
$statement = $pdo->prepare($sql);
$statement->bindParam('postID', $postID, PDO::PARAM_INT);
$statement->execute();
$post = $statement->fetch(PDO::FETCH_ASSOC);

//check if post existed and user is the post creator or admin
if (!$post or ($post['userID'] != $_SESSION['userID'] && $_SESSION['user_roleID'] != 1)) {
    header('location: index.php');
    exit();
}
?>
<html lang="en">
<head>
    <title>Edit post</title>
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
                <td><input type="text" name="txt_title" value="<?php echo htmlspecialchars($post['title']); ?>"/></td>
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
                <td><textarea style="resize: none; width: 100%; height: 150px" name="textarea_content"><?php echo htmlspecialchars($post['content']);?></textarea></td>
            </tr>
            <?php
            if ($post['image']) {
                echo "
            <tr>
                <td>Current Image</td>
                <td><img alt='current image in the post' src='" . $post['image'] . "' width='50%'></td>
            </tr>";
            }
            ?>
            <tr>
                <td>Upload image <br>(<b>.png</b>, <b>.jpeg</b>, <b>.jpg</b>)</td>
                <td>
                    <input type="file" name="image">
                </td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" value="Save Changes" name="btn_submit"></td>
            </tr>
        </table>
    </form>

    <?php //check if the form is submitted to edit the post
    if (isset($_POST['btn_submit'])) {
        //retrieve data from the form
        $title = $_POST['txt_title'];
        $content = $_POST['textarea_content'];
        $moduleID = $_POST['moduleID'];
        $update_userID = ($post['userID'] != $_SESSION['userID']) ? $_SESSION['userID'] : null; //if user is the post creator, no need to display the username again

        if (isset($title, $content, $moduleID)) {
            //check if any of the required fields are empty
            if (empty($title) or empty($content) or empty($moduleID)) {
                echo "Please fill in all required fields";
                exit();
            } else {
                $sql = "UPDATE post SET update_userID = :update_userID, title = :title, content = :content, moduleID = :moduleID, update_date = NOW()";

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
                        $max_file_size = 5242880; // 5MB
                        if ($file_size > $max_file_size) {
                            $errors[] = 'File must be under 5MB';
                        }

                        //move uploaded file to desired location
                        if (!empty($errors)) {
                            foreach ($errors as $error) {
                                echo $error;
                            }
                            exit();
                        }

                        $image = 'images/post/' . $file_name;
                        if (!move_uploaded_file($file_tmp, "images/post/$file_name")) {
                            echo "Error occurred while moving the uploaded file";
                            exit();
                        }
                        $sql .= ", image = :image";
                        echo "Image uploaded successfully <br>";
                    } else {
                        echo "Error occurred during file upload";
                        exit();
                    }
                }

                //update post in database
                $sql .= " WHERE postID = :postID";
                $statement = $pdo->prepare($sql);
                $statement->bindParam('title', $title, PDO::PARAM_STR);
                $statement->bindParam('content', $content, PDO::PARAM_STR);
                $statement->bindParam('moduleID', $moduleID, PDO::PARAM_INT);
                $statement->bindParam('postID', $postID, PDO::PARAM_INT);
                $statement->bindParam(':update_userID', $update_userID, PDO::PARAM_INT);

                //bind image param only if image uploaded successfully
                if (!empty($image)) {
                    $statement->bindParam(':image', $image, PDO::PARAM_STR);
                }

                if ($statement->execute()) {
                    echo "Updated successfully<br>";
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