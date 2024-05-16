<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include(__DIR__ . '/../backend/db_credentials.php');
require_once(__DIR__ . '/../backend/db.php');

// Check if the user is logged in, otherwise redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit;
}

// Get user information from the session or the database based on the user ID
$user_id = $_SESSION['user_id'];
$user = get_user_by_id($user_id);
$user = $user[0]; // Get the first user from the array

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

// Get the avatar path for the logged-in user
$avatar_path = isset($user['avatar']) ? $user['avatar'] : '';
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Coiny&family=Sono:wght@200..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="style.css">


</head>

<body>
    <div class="dashboard-container">
        <div class="dashboard">
            <header>
                <img class="avatar" src="<?php echo isset($avatar_path) ? htmlspecialchars($avatar_path) : 'http://localhost/Labb4A/frontend/uploads/' . (isset($user['avatar']) ? $user['avatar'] : ''); ?>" alt="Avatar">
            </header>


            <h1>Welcome back, <?php echo $user['username']; ?>!</h1>
            <a href="profile.php">Update Profile</a>
            <a href="create_post.php">Create Post</a>
            <a href="blog.php">Blog</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
</body>

</html>