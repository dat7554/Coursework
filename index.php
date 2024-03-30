<?php
//TODO: htmlspecialchars

session_start();
include_once('connection.php');
include_once('common_function.php');
?>

<html lang="en">
<head>
    <title>Forum</title>
</head>
<body>
<center>
<?php
//header
include('header.php');

//body
$sql = "SELECT moduleID, name, views, creator, create_date, update_date FROM module";
$statement = $pdo->prepare($sql);

//check if the query executed successfully
if ($statement->execute()) {
    //fetch and display the results in HTML table
    echo "<table border='1px'>
            <tr>
                <td width='500px' style='text-align: center'>Module</td>
                <td width='100px' style='text-align: center'>Views</td>
                <td width='100px' style='text-align: center'>Creator</td>
                <td width='100px' style='text-align: center'>Date created</td>
                <td width='100px' style='text-align: center'>Date updated</td>
            </tr>";

    //loop through each row of the result set
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>
                <td><a style='text-decoration: none' href='module.php?id={$row['moduleID']}'>{$row['name']}</a></td>
                <td>{$row['views']}</td>
                <td>{$row['creator']}</td>
                <td>{$row['create_date']}</td>
                <td>{$row['update_date']}</td>
              </tr>";
    }
    echo "</table>"; //close the HTML table
} else {
    //handle the case where the query fails
    echo "Error: " . $sql . "<br>" . $statement->errorInfo()[2];
}

include_once('sign_out.php');
?>
</center>
</body>
</html>