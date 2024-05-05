<?php
//require_once('backend/db.php');
//require_once('./frontend/register.php');

session_start();

if (isset($_POST['submit'])) {
    $password = $_POST['password'];
    get_user($password);

    $users = get_user($username);
    if (!empty($users)) {
        $user = $users[0];
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: dashboard.php");
            exit;
        } else {
            $login_error = "Invalid username or password.";
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

</head>

<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if (isset($login_error)) echo "<p class='error'>$login_error</p>"; ?>
        <form action="index.php" method="post">
            <div class="input-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
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