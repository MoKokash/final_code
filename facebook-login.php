<?php
// Set your Facebook App ID and App Secret
$facebook_app_id = '363052160097779';
$facebook_app_secret = '7b49be200a7203b1fdc7d41821cdb6e9';

// Check if the login dialog response is received
if (isset($_GET['code'])) {
    // Exchange code for access token
    $code = $_GET['code'];
    $redirect_uri = urlencode('http://localhost/project_email/facebook-login.php');
    // Note: Add state parameter here
    $state_param = uniqid(); // Generate a unique state parameter
    $token_url = "https://graph.facebook.com/v19.0/oauth/access_token?client_id={$facebook_app_id}&redirect_uri={$redirect_uri}&client_secret={$facebook_app_secret}&code={$code}&state={$state_param}";
    $response = file_get_contents($token_url);
    $params = json_decode($response, true);

    // Verify token
    if (isset($params['access_token'])) {
        // Store access token in session
        session_start();
        $_SESSION['access_token'] = $params['access_token'];
        $_SESSION['authenticated'] = true; // Set the authenticated flag
        $_SESSION['status'] = 'You Are Logged In Successfully'; // Set login status message

        // Use the access token to fetch user information from Facebook Graph API
        $graph_url = "https://graph.facebook.com/me?fields=id,name,email&access_token={$params['access_token']}";
        $user_info = json_decode(file_get_contents($graph_url), true);

        // Store user information in session
        $_SESSION['fb_user'] = [
            'name' => $user_info['name'],
            'email' => isset($user_info['email']) ? $user_info['email'] : null, // Handle case where email might not be provided
        ];

        // Insert user information into the database
        include 'dbcon.php'; // Include your database connection file
        $name = mysqli_real_escape_string($con, $_SESSION['fb_user']['name']);
        $email = isset($_SESSION['fb_user']['email']) ? mysqli_real_escape_string($con, $_SESSION['fb_user']['email']) : null;
        $verification_token = md5(uniqid(rand(), true)); // Generate a verification token

        // Perform the database insert operation
        if ($email) {
            // Check if the user already exists in the database
            $existing_user_query = "SELECT * FROM user WHERE email = '$email'";
            $existing_user_result = mysqli_query($con, $existing_user_query);
            if (mysqli_num_rows($existing_user_result) > 0) {
                // User already exists, update verification token
                $update_query = "UPDATE user SET verification_token = '$verification_token' WHERE email = '$email'";
                mysqli_query($con, $update_query);
            } else {
                // User does not exist, insert new user
                $insert_query = "INSERT INTO user (name, email, verification_token) VALUES ('$name', '$email', '$verification_token')";
                mysqli_query($con, $insert_query);
            }
        }

        // Redirect to the dashboard
        header('Location: dashboard.php');
        exit();
    } else {
        echo "Access token not found!";
        exit();
    }
}

// If the code is not received, redirect to login dialog
$redirect_uri = urlencode('http://localhost/project_email/facebook-login.php');
// Note: Add state parameter here
$state_param = uniqid(); // Generate a unique state parameter
$login_url = "https://www.facebook.com/v19.0/dialog/oauth?client_id={$facebook_app_id}&redirect_uri={$redirect_uri}&state={$state_param}";
?>

<!-- HTML for the Facebook login button -->
<a href="<?php echo $login_url; ?>" class="fb-login-button">Login with Facebook</a>
