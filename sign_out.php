<?php
if (@$_GET['action']=='sign_out') {
    session_destroy();
    header('location: index.php');
}