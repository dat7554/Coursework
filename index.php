<?php
session_start();
include_once('connection.php');
include_once('common_function.php');
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
            </tr>
            </thead>
            <tbody class="table-group-divider">
            <?php //loop through each row of the result set
            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) { ?>
                <tr>
                    <th><a href='module.php?id=<?php echo htmlspecialchars($row['moduleID'])?>'><?php echo htmlspecialchars($row['name'])?></a></th>
                    <td><a href='profile.php?user_id=<?php echo $row['userID'];?>'><img src="images/profile/user.jpg" height="55px" alt="user profile image"><?php echo htmlspecialchars($row['username'])?></a></td>
                    <td><?php echo $row['create_date']?></td>
                    <td><?php echo $row['update_date']?></td>
                </tr>
            <?php }?>
            </tbody>
        </table>
    </div>
<?php }

include('mail.php');
include_once('sign_out.php');
?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>