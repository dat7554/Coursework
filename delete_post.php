<?php
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
$statement->bindParam(':postID', $postID, PDO::PARAM_INT);
$statement->execute();
$post = $statement->fetch(PDO::FETCH_ASSOC);

//check if post existed and user is the post creator
if (!$post or $post['userID'] != $_SESSION['userID']) {
    header('location: index.php');
    exit();
}

//delete post from database
$sql = "DELETE FROM post WHERE postID = :postID";
$statement = $pdo->prepare($sql);
$statement->bindParam(':postID', $postID, PDO::PARAM_INT);
if ($statement->execute()) {
    header('Location: index.php');
    exit();
} else {
    echo "Error deleting the post.";
}
