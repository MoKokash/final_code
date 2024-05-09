<?php
session_start();
include("dbcon.php");

if(isset($_POST["login_now_btn"])) {
    if(!empty(trim($_POST["email"])) && !empty(trim($_POST["password"]))) {
        $email = mysqli_real_escape_string($con, trim($_POST["email"]));
        $password = mysqli_real_escape_string($con, $_POST["password"]);

        $login_query = "SELECT * FROM user WHERE email='$email' AND password='$password' LIMIT 1";
        $login_query_runn = mysqli_query($con, $login_query);

        if(mysqli_num_rows($login_query_runn) > 0) {
            $row = mysqli_fetch_array($login_query_runn);

            if($row["verify_status"] == "1") {
                $_SESSION['authenticated'] =  TRUE;
                $_SESSION['auth_user'] = [
                    'username'=> $row['name'],
                    'email'=> $row['email'],
                    'phone'=> $row['phone'],
                ];
                $_SESSION['status'] = 'You Are Logged In Successfully'; 
                header('Location: dashboard.php');
                exit();
            } else {
                $_SESSION['status'] = 'Please Verify Your Email Address to login'; 
                header('Location: login.php');
                exit();
            }
        } else {
            $_SESSION['status'] = 'Invalid email or password';
            header('Location: login.php');
            exit();
        }
    } else {
        $_SESSION['status'] = 'All Fields are Mandatory'; // if all fields are empty .
        header('Location: login.php');
        exit();
    }
}
?>
