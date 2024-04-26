<?php
include_once('connection.php');
include_once('common_function.php');
session_start();

//check user
if (!isset($_SESSION['email'])) {
    header('location: index.php');
    exit();
}

//check if answerID in the URL
if (!isset($_GET['id'])) {
    header('location: index.php');
    exit();
}

//fetch the answer details from the database
$answerID = $_GET['id'];
$sql = "SELECT * FROM answer WHERE answerID = :answerID";
$statement = $pdo->prepare($sql);
$statement->bindParam(':answerID', $answerID, PDO::PARAM_INT);

if ($statement->execute()) {
    $answer = $statement->fetch(PDO::FETCH_ASSOC);

    //check if the answer exists
    if (!$answer) {
        echo "Answer not found.";
        exit();
    }

    //check if the current user is the owner of the answer or has admin privileges
    if ($_SESSION['userID'] != $answer['userID'] && $_SESSION['user_roleID'] != 1) {
        header('location: index.php');
        exit();
    }

    //check if the form is submitted
    if (isset($_POST['btn_submit_answer'])) {
        $content = $_POST['textarea_content'];
        $update_userID = $_SESSION['userID'];

        if (isset($content)) {
            if (empty($content)) {
                echo "<p style='color: red'>Please fill in all required fields</p>";
                exit();
            }

            //prepare sql statement
            $sql = "UPDATE answer SET content = :content, update_userID = :update_userID, update_date = NOW() WHERE answerID = :answerID";
            $statement = $pdo->prepare($sql);
            $statement->bindParam(':content', $content, PDO::PARAM_STR);
            $statement->bindParam(':update_userID', $update_userID, PDO::PARAM_STR);
            $statement->bindParam(':answerID', $answerID, PDO::PARAM_INT);

            if ($statement->execute()) {
                echo "<p style='color: red'>Updated successfully</p><br>";
            } else {
                echo "Error: " . $sql . "<br>" . $statement->errorInfo()[2];
            }
        }
    }
}
?>

<html lang="en">
<head>
    <title>Update answer</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<?php
//header
include('header.php');?>

<div class=" container mb-3">
    <form method='post'>
        <label for="formTextarea" class="form-label">Your Answer</label>
        <textarea name='textarea_content' class="form-control" id="formTextarea" rows="3"><?php echo htmlspecialchars($answer['content']);?></textarea>
        <input style="margin-top: 15px" type='submit' name='btn_submit_answer' value='Post Your Answer'>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</body>