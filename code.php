<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
include('dbcon.php');
require 'vendor/autoload.php'; // Include PHPMailer autoloader or require necessary files

function sendmail_verify($name, $recipient_email, $verfication_token) {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->Host = 'smtp.gmail.com';
    $mail->Username = 'mohammadkokash5@gmail.com';
    $mail->Password = 'bfqhglsgjluownkx';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->setFrom('mohammadkokash5@gmail.com', 'Your Name');
    $mail->addAddress($recipient_email); // Send verification email to the entered email address
    $mail->isHTML(true);
    $mail->Subject = 'Email Verification From Mohammad Kokash';
    $email_template = "<h2>You Have Registered</h2><br></br>
        <a href='http://localhost/project_email/verify-email.php?token=$verfication_token'>Click Me</a>";
    $mail->Body = $email_template;
    
    if ($mail->send()) {
        return true; // Email sent successfully
    } else {
        return false; // Email sending failed
    }
}

if(isset($_POST["register_btn"])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $verfication_token = md5(rand());

    $check_email_query = "SELECT email From user WHERE email= '$email' LIMIT 1";
    $check_email_result = mysqli_query($con, $check_email_query);
    
    if(mysqli_num_rows($check_email_result) > 0) {
        $_SESSION['status'] = "Email Id already Exists";
        header("location: register.php");
        exit();
    } else {
        $query = "INSERT INTO user (name, email, password, verfication_token) VALUES ('$name', '$email', '$password', '$verfication_token')";
        $query_run = mysqli_query($con, $query);

        if($query_run) {
            if (sendmail_verify($name, $email, $verfication_token)) {
                $_SESSION['status'] = 'Registration Successful. Verification email sent!';
                header('Location: register.php');
                exit();
            } else {
                $_SESSION['status'] = 'Failed to send verification email. Please try again later.';
                header('Location: register.php');
                exit();
            }
        } else {
            $_SESSION['status'] = 'Registration Failed';
            header('Location: register.php');
            exit();
        }
    }
}
?>
