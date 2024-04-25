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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<center>
<?php
//header

include('header.php');

include('post_answer.php');

//body
if ($_GET['id']) {
    $sql = "SELECT p.*, u.username AS create_username, u2.username AS update_username 
            FROM post p 
            LEFT JOIN user u ON p.userID = u.userID 
            LEFT JOIN user u2 ON p.update_userID = u2.userID 
            WHERE p.postID = :postID";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':postID', $_GET['id'], PDO::PARAM_STR);

    if ($statement->execute()) {
        $row = $statement->fetch(PDO::FETCH_ASSOC); ?>

        <!-- Post section -->
        </center>
        <div class='post-container'>
            <table>
                <tr>
                    <td colspan="2"><h1><?php echo htmlspecialchars($row['title']); ?></h1></td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 20px;" colspan="2">
                        <?php echo htmlspecialchars($row['content']); ?>
                        <?php if (!empty($row['image'])) {
                            echo "<br><img style='padding: 20px' alt='image uploaded by the creator' src=".$row['image']." width='50%'>";
                        } ?>
                    </td>
                </tr>
                <tr>
                    <td width="65%">
                        <?php if (@$_SESSION['email'] && ($row['userID'] == $_SESSION['userID'] or $_SESSION['user_roleID'] == 1)) {
                            echo "<a>Share</a> <a href='edit_post.php?id=".$row['postID']."'>Edit</a> <a href='delete_post.php?id=".$row['postID']."'>Delete</a>";
                        } else {
                            echo "<a>Share</a>";
                        } ?>
                    </td>
                    <td>
                        <table style="float: right" cellspacing="10" cellpadding="5">
                            <tr>
                                <?php if ($row['update_date']) {
                                    echo "<td>Updated ".htmlspecialchars($row['update_date'])."</td>";
                                } else {
                                    echo "<td></td>";
                                } ?>
                                <td>Asked <?php echo htmlspecialchars($row['create_date']); ?></td>
                            </tr>
                            <tr>
                                <?php if ($row['update_date'] && $row['update_userID'] != null) {
                                    echo "<td>".htmlspecialchars($row['update_username'])."</td>";
                                } else {
                                    echo "<td></td>";
                                } ?>
                                <td><?php echo htmlspecialchars($row['create_username']); ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
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
                if ($answer_statement->rowCount() > 0) {
                    echo "<div class='post-container'>
                    <h2>Answers</h2>";
                    while ($answer_row = $answer_statement->fetch(PDO::FETCH_ASSOC)) {?>
                        <table style="border-bottom: 1px solid #d9d9d9; padding: 20px;">
                            <tr>
                                <td style="padding: 20px;" width="900px" colspan="2"><?php echo htmlspecialchars($answer_row['content']);?></td>
                            </tr>
                            <tr>
                                <td width="65%">
                                    <?php if (@$_SESSION['email'] && ($answer_row['userID'] == $_SESSION['userID'] or $_SESSION['user_roleID'] == 1)) {
                                        echo "<a>Share</a> <a>Edit</a> <a>Delete</a>"; //edit: href='edit_answer.php?id=".$answer_row['answerID']."'
                                    } else {
                                        echo "<a>Share</a>";
                                    } ?>
                                </td>
                                <td>
                                    <table style="float: right" cellspacing="10" cellpadding="5">
                                        <tr>
                                            <?php if ($answer_row['update_date']) {
                                                echo "<td>Updated ".htmlspecialchars($answer_row['update_date'])."</td>";
                                            } else {
                                                echo "<td></td>";
                                            } ?>
                                            <td>Asked <?php echo htmlspecialchars($answer_row['create_date']); ?></td>
                                        </tr>
                                        <tr>
                                            <?php if ($answer_row['update_date'] && $answer_row['update_userID'] != null) {
                                                echo "<td>".htmlspecialchars($answer_row['update_username'])."</td>";
                                            } else {
                                                echo "<td></td>";
                                            } ?>
                                            <td><?php echo htmlspecialchars($answer_row['answer_username']); ?></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    <?php }
                }
            } else {
                echo "<p>Error: Database query for answers failed</p>";
            }
            ?>

        <!-- Post answer section -->
        <?php
        if (@$_SESSION['email']) {
            echo "<div class='post-container'>
                <form method='post'>
                    <table style='padding: 20px;'>
                        <tr>
                            <td><h2>Your Answer</h2></td>
                        </tr>
                        <tr>
                            <td><textarea style='resize: none' name='textarea_content' rows='10' cols='100'></textarea></td>
                        </tr>
                        <tr>
                            <td><input type='submit' name='btn_submit_answer' value='Post Your Answer'></td>
                        </tr>
                    </table>
                </form>
            </div>";
        }
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