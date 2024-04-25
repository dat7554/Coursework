<?php
include_once('connection.php');
include_once('common_function.php');
session_start();

//check if user is logged in and has necessary permissions
if (isset($_SESSION['email']) && $_SESSION['user_roleID'] = 1) {
    //check if user ID is provided in the request
    if (isset($_GET['id'])) {
        $userID = $_GET['id'];

        //update the active status of the user to 0 (inactive)
        $sql = "UPDATE user SET active = 0 WHERE userID = :userID";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':userID', $userID, PDO::PARAM_INT);

        if ($statement->execute()) {
            if ($_SESSION['user_roleID'] = 1) {
                header('location: accounts.php');
            } else {
                header('location: index.php');
            }
            exit();
        } else {
            //handle the case where the update query fails
            echo "Error deactivating the account.";
        }
    }
} else {
    //redirect to the login page if user is not logged in or doesn't have necessary permissions
    header('Location: sign_in.php');
    exit();
}
