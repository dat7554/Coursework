<?php
//TODO: display role

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
<center>
<?php
//header
include('header.php');

//body
$sql = "SELECT * FROM user";

if ($statement = $pdo->query($sql)) {
    //fetch and display the results in HTML table
    echo "<table border='1px'>
            <tr>
                <td width='10px' style='text-align: center'>Index</td>
                <td width='100px' style='text-align: center'>Role</td>
                <td width='200px' style='text-align: center'>Email</td>
                <td width='200px' style='text-align: center'>Username</td>
                <td width='400px' style='text-align: center'>Personal description</td>
                <td width='100px' style='text-align: center'>Date register</td>
                <td width='100px' style='text-align: center'>Date updated</td>
                <td width='100px' style='text-align: center'></td>
                <td width='100px' style='text-align: center'></td>
            </tr>";

    //initialize index counter
    $index = 1;

    //loop through each row of the result set
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>
                <td style='text-align: center'>{$index}</td>
                <td style='text-align: center'>role</td>
                <td>{$row['email']}</td>
                <td>{$row['username']}</td>
                <td>{$row['personal_description']}</td>
                <td>{$row['register_date']}</td>
                <td>{$row['update_date']}</td>
                <td style='text-align: center'><a href='edit_account.php?id={$row['userID']}'>Edit</a></td>";

        //check admin row, not to display delete
        if ($row['user_roleID'] != 1) {
            echo "<td style='text-align: center'><a>Delete</a></td>";
        } else {
            echo "<td style='text-align: center'>-</td>";
        }

        echo "</tr>";

        //increment index counter
        $index++;
    }
    echo "</table>"; //close the HTML table
} else {
    //handle the case where the query fails
    echo "Error: " . $sql . "<br>" . $statement->errorInfo()[2];
}
?>
</center>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</body>
</html>