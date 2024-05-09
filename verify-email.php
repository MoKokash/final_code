<?php
session_start();
include 'dbcon.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $verify_query = "SELECT verfication_token, verify_status FROM user WHERE verfication_token='$token' LIMIT 1";
    $verify_query_run = mysqli_query($con, $verify_query);

    if (mysqli_num_rows($verify_query_run) > 0) {
        $row = mysqli_fetch_assoc($verify_query_run);
        if ($row['verify_status'] == "0") {
            $check_token = $row['verfication_token'];
            $update_query = "UPDATE user SET verify_status='1' WHERE verfication_token='$check_token' LIMIT 1";
            $update_query_run = mysqli_query($con, $update_query);

            if ($update_query_run) {
                // Set session variable for verification status
                $_SESSION['verification_status'] = "verified";
                // Redirect to login page with verification status parameter
                header("Location: login.php?status=verified");
                exit();
            } else {
                $_SESSION['status'] = "Verification Failed";
                header("Location: login.php?status=failed");
                exit();
            }
        } else {
            $_SESSION['status'] = "Email Already Verified, Log in";
            header('Location: login.php?status=already_verified');
            exit();
        }
    } else {
        $_SESSION['status'] = "Token Does Not Exist";
        header('Location: login.php?status=token_not_exist');
        exit();
    }
} else {
    $_SESSION['status'] = "Token Not Provided";
    header('Location: login.php?status=token_not_provided');
    exit();
}
?>
