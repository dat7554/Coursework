<?php
//TODO: delete image

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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<?php
//header
include('header.php');
?>

<div class="container my-3" style="width: 50%">
    <h1>Create a post</h1>
    <form method="post" enctype="multipart/form-data"> <!-- as user upload file -->
        <div class="mb-3">
            <label for="inputPostTitle" class="form-label">Post title</label>
            <input type="text" name="txt_title" id="inputPostTitle" class="form-control" value="<?php echo htmlspecialchars($post['title']); ?>">
        </div>
        <div class="mb-3">
            <label for="selectModule" class="form-label">Module: </label>
            <select name="moduleID" id="selectModule">
                <?php
                //retrieve and display all modules in a dropdown menu
                $sql = "SELECT moduleID, name FROM module";
                $statement = $pdo->query($sql);

                if ($statement) {
                    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='" . $row['moduleID'] . "' ";

                        //display current module as default
                        if ($row['moduleID'] == $post['moduleID']) {
                            echo "selected = 'selected'";
                        }

                        echo ">" . $row['name'] . "</option>";
                    }
                } else {
                    echo "Error fetching modules.";
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="inputPostContent" class="form-label">Content</label>
            <textarea name='textarea_content' class="form-control" id="inputPostContent" rows="5"><?php echo htmlspecialchars($post['content']);?></textarea>
        </div>
        <div class="mb-3">
            <label for="displayImage" class="form-label">Current Image</label>
            <img class="form-control" alt='current image in the post' src='<?php echo $post['image'] ?>' width='50%'>
        </div>
        <div class="mb-3">
            <label for="formFile" class="form-label">Upload image (<b>.png</b>, <b>.jpeg</b>, <b>.jpg</b>)</label>
            <input class="form-control" name="image" type="file" id="formFile">
        </div>
        <div class="mb-3">
            <input class="btn btn-primary" type="submit" value="Save Changes" name="btn_submit"/>
        </div>
    </form>
</div>

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
            $statement->bindParam(':title', $title, PDO::PARAM_STR);
            $statement->bindParam(':content', $content, PDO::PARAM_STR);
            $statement->bindParam(':moduleID', $moduleID, PDO::PARAM_INT);
            $statement->bindParam(':postID', $postID, PDO::PARAM_INT);
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</body>
</html>