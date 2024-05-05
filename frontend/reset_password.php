<?php
require_once('backend/db.php');

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $reset_code = $_POST['reset_code'];
    $new_password = $_POST['new_password'];
    reset_password($email, $reset_code, $new_password);
    $password_reset = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>

</head>
<body>
    <div class="login-container">
        <h2>Reset Password</h2>
        <?php if (isset($password_reset)) echo "<p class='success'>Password reset successful.</p>"; ?>
        <form action="reset_password.php" method="post">
            <div class="input-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="reset_code">Reset Code:</label>
                <input type="text" id="reset_code" name="reset_code" required>
            </div>
            <div class="input-group">
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            <div class="input-group">
                <button type="submit" name="submit">Reset Password</button>
            </div>
        </form>
    </div>
</body>
</html>