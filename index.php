<?php
session_start();
include_once('connection.php');
include_once('common_function.php');
include_once('sign_out.php');
?>

<html lang="en">
<head>
    <title>Forum</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<?php
//header
include('header.php');

//body
$sql = "SELECT m.*, u.username
        FROM module m 
        LEFT JOIN user u ON m.userID = u.userID";
$statement = $pdo->prepare($sql);

//check if the query executed successfully
if ($statement->execute()) {
    //fetch and display the results in HTML table ?>

<div class="container my-3" style="width: 70%">
    <div class="jumbotron">
        <h1>Threads category</h1>
        <table class="table align-middle">
            <thead class="table-dark">
            <tr>
                <th class="col-5" scope="col">Module</th>
                <th>Creator</th>
                <th>Date created</th>
                <th>Date updated</th>
                <?php
                if (isset($_SESSION['userID']) && $_SESSION['user_roleID'] == 1) { ?>
                    <td></td>
                <?php } ?>
            </tr>
            </thead>
            <tbody>
            <?php //loop through each row of the result set
            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) { ?>
                <tr>
                    <th>
                        <a href='module.php?id=<?php echo htmlspecialchars($row['moduleID'])?>'>
                            <?php echo htmlspecialchars($row['name'])?>
                        </a>
                    </th>
                    <td><a href='profile.php?user_id=<?php echo $row['userID'];?>'><img src="images/profile/user.jpg" height="55px" alt="user profile image"><?php echo htmlspecialchars($row['username'])?></a></td>
                    <td><?php echo $row['create_date']?></td>
                    <td><?php echo $row['update_date']?></td>
                    <?php
                    if (isset($_SESSION['userID']) && $_SESSION['user_roleID'] == 1) { ?>
                        <td>
                            <a href='edit_module.php?id=<?php echo $row['moduleID']?>'><button class="btn btn-primary">Edit module</button></a>
                        </td>
                    <?php } ?>
                </tr>
            <?php }?>
            </tbody>
        </table>
    </div>
<?php }

include('mail.php');
?>
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>