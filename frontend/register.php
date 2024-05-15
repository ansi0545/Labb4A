<?php
require_once(__DIR__ . '/../backend/db.php');

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $email = $_POST['email']; // Added this line
    $password = $_POST['password'];

    // Check if username already exists
    $users = get_user($username);
    if (!empty($users)) {
        $register_error = "Username already exists.";
    } else {
        add_user($username, $email, $password); // Updated this line
        header("Location: ../login.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Coiny&family=Sono:wght@200..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Register</title>
</head>

<body>
    <div class="register-container">

        <?php
        if (isset($register_error)) {
            echo "<p class='error'>$register_error</p>";
        }
        ?>
        <form action="register.php" method="post">
            <h1>Register</h1>
            <div class="input-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group"> <!-- Added this block -->
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="input-group">
                <button type="submit" name="submit">Register</button>
            </div>
            <p>Already have an account? <a href="/login.php">Login here</a></p>
            <?php include 'footer.php'; ?>
        </form>


    </div>
</body>

</html>