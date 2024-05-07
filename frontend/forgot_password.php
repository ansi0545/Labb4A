<?php
require_once('backend/db.php');

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    request_password_reset($email);
    $reset_sent = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../style.css">
    <title>Forgot Password</title>

</head>
<body>
    <div class="login-container">
        <h2>Forgot Password</h2>
        <?php if (isset($reset_sent)) echo "<p class='success'>Password reset email sent.</p>"; ?>
        <form action="forgot_password.php" method="post">
            <div class="input-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-group">
                <button type="submit" name="submit">Send Password Reset Email</button>
            </div>
        </form>
    </div>
</body>
</html>