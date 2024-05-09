<?php 
$page_title = "Login Form";
include('includes/header.php'); 
include('includes/navbar.php');
session_start();


if (isset($_SESSION["authenticated"]))
{

    $_SESSION['status'] = 'You Are Already Login';
    header('Location: dashboard.php');
    exit(0);

}
// Check if verification status parameter is set
if(isset($_GET['status'])) {
    $verification_status = $_GET['status'];
    switch ($verification_status) {
        case 'verified':
            echo "<div class='alert alert-success'>Your account has been successfully verified!</div>";
            break;
        case 'failed':
            echo "<div class='alert alert-danger'>Verification failed. Please try again.</div>";
            break;
        case 'already_verified':
            echo "<div class='alert alert-info'>Email address is already verified. You can log in.</div>";
            break;
        case 'token_not_exist':
            echo "<div class='alert alert-warning'>Token does not exist.</div>";
            break;
        case 'token_not_provided':
            echo "<div class='alert alert-warning'>Token not provided.</div>";
            break;
        default:
            break;
    }
}
?>

<div class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <?php
                if(isset($_SESSION['status'])) {
                    ?>
                    <div class="success-alert">
                        <h5><?= $_SESSION['status'] ?></h5>
                    </div>
                    <?php
                    unset($_SESSION['status']);
                }
                ?>
                <div class="card-shadow">
                    <div class="card-header">
                        <h5>Login Form</h5>
                    </div>
                    <div class="card-body">
                        <form action="logincode.php" method="POST">
                            <div class="form-group">
                                <label for="emailAddress">Email Address</label>
                                <input type="text" name="email" class='form-control'>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" class='form-control'>
                            </div>
                            <div class="form-group" style="margin-top: 20px;">
                                <button class="button" name ="login_now_btn" type="submit" class="btn btn-primary">Login Now</button>
                            </div>
                            <!-- Add "Sign in with Google" button -->
                            <div class="form-group" style="margin-top: 20px;">
                                <a href="google-login.php" class="btn btn-danger">Sign in with Google</a>
                            </div>
                            <!-- Add "Sign in with Facebook" button -->
                            <div class="form-group" style="margin-top: 20px;">
                                <a href="facebook-login.php" class="btn btn-primary">Sign in with Facebook</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
