<?php
//TODO: dynamic title
//TODO: editable module

session_start();
include_once('connection.php');
include_once('common_function.php');
?>
<html lang="en">
<head>
    <title>
        <?php
        echo "Dynamic title"
        ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<?php
//header
include('header.php');

//body
$sql = "SELECT p.*, u.username, m.name
        FROM post p 
        LEFT JOIN module m ON p.moduleID = m.moduleID
        LEFT JOIN user u ON p.userID = u.userID 
        WHERE p.moduleID = :moduleID";
$statement = $pdo->prepare($sql);
$statement->bindParam(':moduleID', $_GET['id'], PDO::PARAM_STR);

//check if the query executed successfully
if ($statement->execute()) {
    //fetch and display the results in HTML table?>

    <div class="container my-3">
        <div class="jumbotron">
            <h1>Posts</h1>
            <table class="table align-middle">
                <thead class="table-dark align-middle">
                <tr>
                    <td class="col-5">Title</td>
                    <td>Creator</td>
                    <td>Date created</td>
                    <td>Date updated</td>
                </tr>
                </thead>
                <tbody class="table-group-divider">
                <?php //loop through each row of the result set
                while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {?>
                <tr>
                    <td scope="row"><a href='post.php?id=<?php echo htmlspecialchars($row['postID'])?>'><?php echo htmlspecialchars($row['title'])?></a></td>
                    <td>
                        <?php if (!empty($row['username'])) { ?>
                            <a href='profile.php?user_id=<?php echo $row['userID'];?>'>
                                <img src="images/profile/user.jpg" height="55px" alt="user profile image"><?php echo htmlspecialchars($row['username']); ?>
                            </a>
                        <?php } else { ?>
                            <img src="images/profile/user.jpg" height="55px" alt="user profile image">[DELETED USER]
                        <?php } ?>
                    </td>
                    <td><?php echo $row['create_date']?></td>
                    <td><?php echo $row['update_date']?></td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
<?php } else {
    //handle the case where the query fails
    echo "Error: " . $sql . "<br>" . $statement->errorInfo()[2];
}
?>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>