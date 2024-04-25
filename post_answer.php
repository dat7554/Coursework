<?php
if (isset($_POST['btn_submit_answer'])) {
    $content = $_POST['textarea_content'];
    $userID = $_SESSION['userID'];

    if (isset($content)) {
        if (empty($content)) {
            echo "Please fill in all required fields";
            exit();
        }
        //prepare sql statement
        $sql = "INSERT INTO answer (userID, postID, content) VALUES (:userID, :postID, :content)";
        $statement = $pdo->prepare($sql);

        //bind parameters
        $statement->bindParam(':userID', $userID, PDO::PARAM_INT);
        $statement->bindParam(':postID', $_GET['id'], PDO::PARAM_INT);
        $statement->bindParam(':content', $content, PDO::PARAM_STR);

        //execute statement
        if ($statement->execute()) {
            header("Location:post.php?id={$_GET['id']}");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $statement->errorInfo()[2];
        }
    }
}