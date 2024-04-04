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

    //loop through each row of the result set
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>
                <td style='text-align: center'>n</td>
                <td style='text-align: center'>role</td>
                <td>{$row['email']}</td>
                <td>{$row['username']}</td>
                <td>{$row['personal_description']}</td>
                <td>{$row['register_date']}</td>
                <td>{$row['update_date']}</td>
                <td style='text-align: center' href='edit_user.php?id={$row['userID']}'><a>Edit</a></td>";

        //check admin row, not to display delete
        if (!$row['user_roleID'] == 1) {
            echo "<td style='text-align: center'><a>Delete</a></td>";
        }

        echo "</tr>";
    }
    echo "</table>"; //close the HTML table
} else {
    //handle the case where the query fails
    echo "Error: " . $sql . "<br>" . $statement->errorInfo()[2];
}
?>
</center>
</body>
</html>