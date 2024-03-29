<?php
//TODO: edit the size of content textarea

session_start();
include_once('connection.php');
include_once('common_function.php');

//check user
if (!$_SESSION['email']) {
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

//check if post existed and user is the post creator
if (!$post or $post['userID'] != $_SESSION['userID']) {
    header('location: index.php');
    exit();
}

//check if the form is submitted to edit the post
if (isset($_POST['btn_submit'])) {
    $title = $_POST['txt_title'];
    $content = $_POST['textarea_content'];
    $moduleID = $_POST['moduleID'];

    //update post in database
    $sql = "UPDATE post SET title = :title, content = :content, moduleID = :moduleID, update_date = NOW() WHERE postID = :postID";
    $statement = $pdo->prepare($sql);
    $statement->bindParam('title', $title, PDO::PARAM_STR);
    $statement->bindParam('content', $content, PDO::PARAM_STR);
    $statement->bindParam('moduleID', $moduleID, PDO::PARAM_INT);
    $statement->bindParam('postID', $postID, PDO::PARAM_INT);

    if ($statement->execute()) {
        echo "Updated successfully";
    } else {
        echo "Error updating the post.";
    }
}
?>

<html lang="en">
<head>
    <title>Edit post</title>
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
                <td><textarea style="resize: none;" name="textarea_content"><?php echo htmlspecialchars($post['content']); ?></textarea></td>
            </tr>
            <tr>
                <td>Current Image</td>
                <td><img alt="current image in the post" src='<?php echo $post['image'];?>' width="50%"></td>
            </tr>
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
</body>
</html>