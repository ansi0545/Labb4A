<?php
session_start();
require_once(__DIR__ . '/backend/db.php');

include 'frontend/menu.php';

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
<meta charset="UTF-8">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Coiny&family=Sono:wght@200..800&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" href="frontend/style.css">
</head>
<body>
    <div class="login-container">
        <h2>Welcome</h2>
        <h3>Latest Posts</h3>
        <?php foreach($latest_posts as $post): ?>
            <a href="frontend/post.php?id=<?php echo $post['id']; ?>"><?php echo $post['title']; ?></a>
        <?php endforeach; ?>
        <h3>Newest Blogger</h3>
        <a href="frontend/blogger.php?id=<?php echo $newest_blogger['id']; ?>"><?php echo $newest_blogger['username']; ?></a>
        <h3>Bloggers</h3>
        <?php foreach($bloggers as $blogger): ?>
            <a href="frontend/blogger.php?id=<?php echo $blogger['id']; ?>"><?php echo $blogger['username']; ?></a>
        <?php endforeach; ?>
        <?php if (isset($login_error)) echo "<p class='error'>$login_error</p>"; ?>
        <form action="index.php" method="post">
            <div class="input-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="input-group">
                <button type="submit" name="submit">Login</button>
            </div>
        </form>
    </div>
</body>

</html>