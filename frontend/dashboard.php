<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include(__DIR__ . '/../backend/db_credentials.php');
require_once(__DIR__ . '/../backend/db.php');



// Check if the user is logged in, otherwise redirect to the login page
if (!isset($_SESSION['user_id'])) {
    //header("Location: login.php");
    exit;
}

// Get user information from the session or the database based on the user ID
$user_id = $_SESSION['user_id'];
$user = get_user_by_id($user_id);

// If the user does not exist, you can display an error message and log them out
if (!$user) {
    // Display an error message and log out the user
    session_destroy();
    //header("Location: login.php");
    exit;
}

// Check if the 'username' key is set in the $user array
if (!isset($user['username'])) {
    $user['username'] = 'Guest';
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../style.css">
</head>

<body>
    <div class="dashboard">
        <h1>Welcome back, <?php echo $user['username']; ?>!</h1>

        <a href="profile.php">Update Profile</a>
        <a href="create_post.php">Create Post</a>
        <a href="logout.php">Logout</a>
    </div>
</body>

</html>