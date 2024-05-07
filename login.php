<?php
session_start();
require_once(__DIR__ . '/backend/db.php');

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="frontend/style.css">
</head>

<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if (isset($login_error)) echo "<p class='error'>$login_error</p>"; ?>
        <form action="login.php" method="post">
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
        <p>Don't have an account? <a href="frontend/register.php">Register here</a></p>
    </div>
</body>

</html>