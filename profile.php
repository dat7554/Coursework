<?php
session_start();
include_once('connection.php');
include_once('common_function.php');

if (!isset($_GET['user_id'])) {
    header('location: index.php');
    exit();
}

$userID = $_GET['user_id'];

$sql = "SELECT u.*, r.role, p.postID, p.title, p.create_date
            FROM user u
            LEFT JOIN user_role r ON u.user_roleID = r.user_roleID
            LEFT JOIN post p ON u.userID = p.userID
            WHERE u.userID = :userID";
$statement = $pdo->prepare($sql);
$statement->bindParam(':userID', $userID, PDO::PARAM_STR);
?>
<html lang="en">
<head>
    <title>Profile</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<?php
//header
include('header.php');

//body
if ($statement->execute()) {
    $row = $statement->fetch(PDO::FETCH_ASSOC);?>
    <div class='container my-3' style="width: 70%;">
        <div class="jumbo">
            <table class="table table-borderless">
                <tr>
                    <td>
                        <h1 class="display-3"><img src='images/profile/user.jpg' height='55px' alt='user profile image'><?php echo ($row['username']); ?></h1>
                    </td>
                    <td class="align-middle" align="right" style="padding-right: 0;">
                        <?php
                        if (isset($_SESSION['userID']) && ($_SESSION['user_roleID'] == 1 || $row['userID'] == $_SESSION['userID'])) { ?>
                            <a href='edit_account.php?id=<?php echo $row['userID']?>'><button class="btn btn-primary">Edit account</button></a>
                        <?php }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="lead"><?php echo $row['role']?></p>
                    </td>
                    <td class="align-middle" align="right" style="padding-right: 0;">
                        <p>Member since: <?php echo $row['register_date']?></p>
                    </td>
                </tr>
            </table>
            <hr class="my-3">
            <div class=" container mb-3">
                <h3>About</h3>
                <div class="card text-dark bg-light">
                    <div class="card-body">
                        <p class="card-text"><?php echo $row['personal_description']?></p>
                    </div>
                </div>
            </div>
            <div class=" container mb-3">
                <h3>My posts</h3>
                <table class="table align-middle">
                    <?php
                    $post_statement = $pdo->prepare($sql);
                    $post_statement->bindParam(':userID', $userID, PDO::PARAM_INT);

                    if ($post_statement->execute()){
                    //loop through each row of the result set
                        while ($row_post = $post_statement->fetch(PDO::FETCH_ASSOC)) {?>
                            <tr>
                                <td width="70%"><a href='post.php?id=<?php echo $row_post['postID']?>'><?php echo $row_post['title']?></a></td>
                                <td align="right"><?php echo $row_post['create_date']?></td>
                            </tr>
                        <?php }
                    } ?>
                </table>
            </div>
            <div class=" container mb-3">
                <h3>My answers</h3>
                <table class="table align-middle">
                    <?php
                    $answer_sql = "SELECT p.title, a.postID AS answer_postID, a.create_date 
                                        FROM answer a
                                        LEFT JOIN post p ON a.postID = p.postID
                                        WHERE a.userID = :userID";
                    $answer_statement = $pdo->prepare($answer_sql);
                    $answer_statement->bindParam(':userID', $userID, PDO::PARAM_INT);

                    //loop through each row of the result set
                    if ($answer_statement->execute()) {
                        while ($row_answer = $answer_statement->fetch(PDO::FETCH_ASSOC)) {?>
                            <tr>
                                <td width="70%"><a href='post.php?id=<?php echo $row_answer['answer_postID']?>'><?php echo $row_answer['title']?></a></td>
                                <td align="right"><?php echo $row_answer['create_date']?></td>
                            </tr>
                        <?php }
                    } ?>
                </table>
            </div>
        </div>
    </div>
<?php } else {
    echo "<p style='color: red'>Error: Database query for answers failed</p>";
}
?>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>