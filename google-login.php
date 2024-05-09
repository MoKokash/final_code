<?php
// Include necessary files
require_once 'vendor/autoload.php';
include 'dbcon.php'; // Include your database connection file
session_start();

// Google API credentials
$clientID = '645190769680-6br1199pefq31dhurfdpcgtfuu3fsj79.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUri = 'http://localhost/project_email/google-login.php';

// Create Google API client
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

// Authenticate code from Google OAuth Flow
if (isset($_GET['code'])) {
    try {
        // Fetch access token
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

        // Check if access token is fetched successfully
        if (isset($token['access_token'])) {
            $accessToken = $token['access_token'];

            // Get profile info from Google API using access token
            $client->setAccessToken($accessToken);
            $google_oauth = new Google\Service\Oauth2($client);
            $google_account_info = $google_oauth->userinfo->get();

            // Extract email and name from Google API response
            $email = $google_account_info->email;
            $name = $google_account_info->name;

            // Insert user data into the database
            if (isset($email) && isset($con)) {
                $verfication_token = md5(rand()); // Generate verification token
                $verify_status = 0; // Initial verification status

                // SQL query to insert user data
                $insert_query = "INSERT INTO user (name, email, verfication_token, verify_status) 
                                 VALUES ('$name', '$email', '$verfication_token', 1)";
                
                // Execute the query
                $insert_result = mysqli_query($con, $insert_query);

                if ($insert_result) {
                    // Set session variables
                    $_SESSION['authenticated'] = true;
                    $_SESSION['auth_user'] = [
                        'username' => $name,
                        'email' => $email,
                    ];
                    // Redirect to dashboard
                    header('Location: dashboard.php');
                    exit;
                } else {
                    // Error handling for insertion failure
                    echo "Failed to insert user data into the database.";
                }
            } else {
                // Error handling for missing email or database connection
                echo "Error: Missing email or database connection.";
            }
        } else {
            // Error handling for failed access token retrieval
            echo "Failed to fetch access token.";
        }
    } catch (Exception $e) {
        // Error handling
        echo "Error: " . $e->getMessage();
    }
} else {
    // Redirect to Google OAuth consent screen for authentication
    echo "<a href='".$client->createAuthUrl()."'>Google Login</a>";
}
?>
