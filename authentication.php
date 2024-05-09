<?php 
session_start();

if (!isset($_SESSION["authenticated"]) && !isset($_SESSION["auth_user"]))
{
    $_SESSION['status'] = 'Please Login to Access User Dashboard';
    header('Location: login.php');
    exit();
}
?>
