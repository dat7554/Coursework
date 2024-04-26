<?php
//TODO: add share function

session_start();
include_once('connection.php');
include_once('common_function.php');
?>

<html lang="en">
<head>
    <title>Post</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<?php
//header

include('header.php');

include('post_answer.php');

//body
if ($_GET['id']) {
    $sql = "SELECT p.*, m.name, u.username AS create_username, u2.username AS update_username 
            FROM post p 
            LEFT JOIN module m ON p.moduleID = m.moduleID
            LEFT JOIN user u ON p.userID = u.userID 
            LEFT JOIN user u2 ON p.update_userID = u2.userID 
            WHERE p.postID = :postID";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':postID', $_GET['id'], PDO::PARAM_STR);

    if ($statement->execute()) {
        $row = $statement->fetch(PDO::FETCH_ASSOC); ?>

        <!-- Post section -->
        <div class='container my-3' style="width: 70%;">
            <div class="jumbo">
                <h1 class="display-3"><?php echo htmlspecialchars($row['title']); ?></h1>
                <p class="lead">Module: <?php echo $row['name']?></p>
                <div class="card text-dark bg-light">
                    <div class="card-body">
                        <p class="card-text">
                            <?php echo htmlspecialchars($row['content']);
                            if (!empty($row['image'])) {
                                echo "<br><img style='padding: 20px' alt='image uploaded by the creator' src=".$row['image']." width='50%'>";
                            } ?>
                        </p>
                    </div>
                </div>
                <table width="100%">
                    <tr>
                        <td class="custom">
                            <?php if (@$_SESSION['email'] && ($row['userID'] == $_SESSION['userID'] or $_SESSION['user_roleID'] == 1)) {
                                echo "<a>Share</a> <a href='edit_post.php?id=".$row['postID']."'>Edit</a> <a href='delete_post.php?id=".$row['postID']."'>Delete</a>";
                            } else {
                                echo "<a>Share</a>";
                            } ?>
                        </td>
                        <td>
                            <table cellpadding="5%" class="to_right">
                                <tr class="custom">
                                    <?php if ($row['update_date']) {
                                        echo "<td>Updated ".$row['update_date']."</td>";
                                    } else {
                                        echo "<td></td>";
                                    } ?>
                                    <td>Asked <?php echo $row['create_date']; ?></td>
                                </tr>
                                <tr class="custom">
                                    <?php if ($row['update_date'] && $row['update_userID'] != null) {
                                        echo "<td><a href='profile.php?user_id={$row['update_userID']}'><img src='images/profile/user.jpg' height='55px' alt='user profile image'>".htmlspecialchars($row['update_username'])."</a></td>";
                                    } else {
                                        echo "<td></td>";
                                    } ?>
                                    <td>
                                        <?php if (!empty($row['create_username'])) { ?>
                                            <a href='profile.php?user_id=<?php echo $row['userID'];?>'>
                                                <img src="images/profile/user.jpg" height="55px" alt="user profile image"><?php echo htmlspecialchars($row['create_username']); ?>
                                            </a>
                                        <?php } else { ?>
                                            <img src="images/profile/user.jpg" height="55px" alt="user profile image">[DELETED USER]
                                        <?php } ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Answer section -->
        <?php
        $answer_sql = "SELECT a.*, u.username AS answer_username, u2.username AS update_username 
                    FROM answer a 
                    LEFT JOIN user u ON a.userID = u.userID 
                    LEFT JOIN user u2 ON a.update_userID = u2.userID 
                    WHERE a.postID = :postID";
        $answer_statement = $pdo->prepare($answer_sql);
        $answer_statement->bindParam(':postID', $_GET['id'], PDO::PARAM_STR);

        if ($answer_statement->execute()) {
            if ($answer_statement->rowCount() > 0) {?>
                <div class='container my-sm-3' style="width: 70%;">
                    <div class="jumbo">
                        <h3 class="display-6">Answers</h3>
                        <?php while ($answer_row = $answer_statement->fetch(PDO::FETCH_ASSOC)) {?>
                            <div class="card text-dark bg-light">
                                <div class="card-body">
                                    <p class="card-text"><?php echo htmlspecialchars($answer_row['content']);?></p>
                                </div>
                            </div>
                            <table width="100%">
                                <tr>
                                    <td class="custom">
                                        <?php if (@$_SESSION['email'] && ($answer_row['userID'] == $_SESSION['userID'] or $_SESSION['user_roleID'] == 1)) {
                                            echo "<a>Share</a> <a href='edit_answer.php?id=".$answer_row['answerID']."'>Edit</a> <a>Delete</a>";
                                            } else {
                                            echo "<a>Share</a>";
                                        } ?>
                                    </td>
                                    <td>
                                        <table cellpadding="5%" class="to_right">
                                            <tr class="custom">
                                                <?php if ($answer_row['update_date']) {
                                                    echo "<td>Updated ".$answer_row['update_date']."</td>";
                                                } else {
                                                    echo "<td></td>";
                                                } ?>
                                                <td>Asked <?php echo $answer_row['create_date']; ?></td>
                                            </tr>
                                            <tr class="custom">
                                                <?php if ($answer_row['update_date'] && $answer_row['update_userID'] != null) {
                                                    echo "<td><a href='profile.php?user_id={$row['update_userID']}'><img src='images/profile/user.jpg' height='55px' alt='user profile image'>".htmlspecialchars($answer_row['update_username'])."</a></td>";
                                                } else {
                                                    echo "<td></td>";
                                                } ?>
                                                <td>
                                                    <?php if (!empty($answer_row['answer_username'])) { ?>
                                                        <a href='profile.php?user_id=<?php echo $answer_row['userID'];?>'>
                                                            <img src="images/profile/user.jpg" height="55px" alt="user profile image"><?php echo htmlspecialchars($answer_row['answer_username']); ?>
                                                        </a>
                                                    <?php } else { ?>
                                                        <img src="images/profile/user.jpg" height="55px" alt="user profile image">[DELETED USER]
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <hr class="my-3">
                        <?php } ?>
                    </div>
                </div>
            <?php }
        } else {
            echo "<p>Error: Database query for answers failed</p>";
        }
        ?>

        <!-- Post answer section -->
        <?php
        if (@$_SESSION['email']) {?>
            <div class=" container mb-3" style="width: 70%;">
                <form method='post'>
                    <label for="formTextarea" class="form-label">Your Answer</label>
                    <textarea name='textarea_content' class="form-control" id="formTextarea" rows="3"></textarea>
                    <input class="btn btn-primary" style="margin-top: 15px" type='submit' name='btn_submit_answer' value='Post Your Answer'>
                </form>
            </div>
        <?php }
    } else {
        //handle the case where the query fails
        echo "Error: " . $sql . "<br>" . $statement->errorInfo()[2];
    }
} else {
    header('location: index.php');
}
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</body>
</html>