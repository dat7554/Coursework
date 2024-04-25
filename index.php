<?php
//TODO: htmlspecialchars

session_start();
include_once('connection.php');
include_once('common_function.php');
?>

<html lang="en">
<head>
    <title>Forum</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
                <td>{$row['username']}</td>
                <td>{$row['create_date']}</td>
                <td>{$row['update_date']}</td>
              </tr>";
    }
    echo "</table>"; //close the HTML table
} else {
    //handle the case where the query fails
    echo "Error: " . $sql . "<br>" . $statement->errorInfo()[2];
}

include('mail.php');
include_once('sign_out.php');
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>