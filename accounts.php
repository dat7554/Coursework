<?php
session_start();
include_once('connection.php');
include_once('common_function.php');

if (!$_SESSION['user_roleID'] == 1) {
    header('location: index.php');
    exit();
}
?>
<html lang="en">
<head>
    <title>User accounts</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<?php
//header
include('header.php');

//body
$sql = "SELECT u.*, r.role 
        FROM user u
        LEFT JOIN user_role r ON u.user_roleID = r.user_roleID";

if ($statement = $pdo->query($sql)) {
    //fetch and display the results in HTML table ?>
    <div class="container my-3">
        <h1>Account list</h1>
        <table class="table align-middle">
            <thead class="table-dark">
            <tr>
                <td>Index</td>
                <td>Role</td>
                <td>Email</td>
                <td>Username</td>
                <td>Personal description</td>
                <td>Date register</td>
                <td>Date updated</td>
                <td></td>
                <td></td>
            </tr>
            </thead>
            <tbody>
            <?php //initialize index counter
            $index = 1;

            //loop through each row of the result set
            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {?>
                <tr>
                    <td><?php echo $index ?></td>
                    <td><?php echo $row['role']?></td>
                    <td><?php echo $row['email']?></td>
                    <td><?php echo $row['username']?></td>
                    <td><?php echo $row['personal_description']?></td>
                    <td><?php echo $row['register_date']?></td>
                    <td><?php echo $row['update_date']?></td>
                    <td style='text-align: center'><a href='edit_account.php?id=<?php echo $row['userID']?>'>Edit</a></td>
                    <?php
                    //check admin row, not to display delete
                    if ($row['user_roleID'] != 1) {
                        echo "<td style='text-align: center'><a href='deactivate_account.php?id={$row['userID']}'>Delete</a></td>";
                    } else {
                        echo "<td style='text-align: center'>-</td>";
                    } ?>
                </tr>
                <?php $index++; //increment index counter
            } ?>
            </tbody>
        </table>
    </div>
<?php
} else {
    //handle the case where the query fails
    echo "Error: " . $sql . "<br>" . $statement->errorInfo()[2];
}
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</body>
</html>