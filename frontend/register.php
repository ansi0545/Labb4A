<?php
require_once(__DIR__ . '/../backend/db.php');

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if username already exists
    $users = get_user($username);
    if (!empty($users)) {
        $register_error = "Username already exists.";
    } else {
        add_user($username, $password);
        header("Location: login.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>

<body>
    <div class="register-container">
        <h2>Register</h2>
        <?php
        if (isset($register_error)) {
            echo "<p class='error'>$register_error</p>";
        }
        ?>
        <form action="register.php" method="post">
            <div class="input-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="input-group">
                <button type="submit" name="submit">Register</button>
            </div>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>

</html>