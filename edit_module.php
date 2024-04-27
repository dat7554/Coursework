<?php
include_once('connection.php');
include_once('common_function.php');
session_start();

//check user
if (!isset($_SESSION['email']) or $_SESSION['user_roleID'] != 1) {
    header('location: index.php');
    exit();
}

//check if answerID in the URL
if (!isset($_GET['id'])) {
    header('location: index.php');
    exit();
}

$moduleID = $_GET['id'];

//fetch the post data from the database
$sql = "SELECT * FROM module WHERE moduleID = :moduleID";
$statement = $pdo->prepare($sql);
$statement->bindParam('moduleID', $moduleID, PDO::PARAM_INT);
$statement->execute();
$module = $statement->fetch(PDO::FETCH_ASSOC);

//check if post existed and user is the post creator or admin
if (!$module) {
    header('location: index.php');
    exit();
}
?>
<html lang="en">
<head>
    <title>Edit module</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<?php
//header
include('header.php');
?>
<div class="container my-3" style="width: 50%">
    <h1>Edit module</h1>
    <form method="post">
        <div class="mb-3">
            <label for="inputName" class="form-label">Module Name</label>
            <input type="text" name="txt_name" id="inputName" class="form-control" value="<?php echo htmlspecialchars($module['name']); ?>">
        </div>
        <div class="mb-3">
            <input class="btn btn-primary" type="submit" value="Save Changes" name="btn_submit"/>
        </div>
    </form>

    <?php
    //check if the form is submitted to edit the module name
    if (isset($_POST['btn_submit'])) {
        //retrieve data from the form
        $module_name = $_POST['txt_name'];

        if (isset($module_name)) {
            if (empty($module_name)) {
            echo "<p style='color: red'>Please fill in the required field</p>";
            exit();
            } else {
                $sql = "UPDATE module SET name = :name, update_date = NOW() WHERE moduleID = :moduleID";
                $statement = $pdo->prepare($sql);
                $statement->bindParam(':name', $module_name, PDO::PARAM_STR);
                $statement->bindParam(':moduleID', $moduleID, PDO::PARAM_INT);

                if ($statement->execute()) {
                    echo "<p style='color: red'>Updated successfully</p><br>";
                }
            }
        }
    }
    ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>