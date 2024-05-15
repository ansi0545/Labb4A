<?php
session_start();
require_once(__DIR__ . '/backend/db.php');

include 'frontend/menu.php';
include 'frontend/content.php';
include 'frontend/info.php';


// Fetch latest posts and newest blogger
$latest_posts = get_latest_posts();
$newest_blogger = get_newest_blogger();
$bloggers = get_all_bloggers();

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user = get_user($username);
    if ($user && $user['email'] == $email) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: frontend/dashboard.php");
            exit;
        } else {
            $login_error = "Invalid username, email or password.";
        }
    } else {
        $login_error = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Coiny&family=Sono:wght@200..800&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="frontend/style.css">
    <title>Welcome</title>
</head>

<body>
    <div class="login-container">
        <h2>Welcome to vovvebloggen!</h2>
        <h3>Latest Posts</h3>
        <?php foreach ($latest_posts as $post) : ?>
            <!-- Ändra länkarna för att inkludera ID:et för varje inlägg -->
            <a href="/frontend/blog.php?id=<?php echo $post['id']; ?>"><?php echo $post['title']; ?></a>
        <?php endforeach; ?>
        <h3>Newest Blogger</h3>
        <?php if (isset($newest_blogger['id'])) : ?>
            <a href="/frontend/blog.php?user_id=<?php echo $newest_blogger['id']; ?>"><?php echo $newest_blogger['username']; ?></a>
        <?php endif; ?>

        <h3>Bloggers</h3>
        <?php foreach ($bloggers as $blogger) : ?>
            <?php if (isset($blogger['id'])) : ?>
                <a href="/frontend/blog.php?user_id=<?php echo $blogger['id']; ?>"><?php echo $blogger['username']; ?></a>
            <?php endif; ?>
        <?php endforeach; ?>

        <?php if (isset($login_error)) echo "<p class='error'>$login_error</p>"; ?>
        <p> <a href="/login.php">Login here</a></p>
        <p>Don't have an account? <a href="frontend/register.php">Register here</a></p>
        <?php include 'frontend/footer.php'; ?>
    </div>
   
</body>

</html>
