<?php
require_once('../backend/db.php');

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user = get_user_by_id($user_id);

// Handle file upload if a file has been uploaded
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
    $target_dir = 'uploads/';
    $target_file = $target_dir . basename($_FILES['avatar']['name']);

    // Check if the directory exists, if not, create it
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Move the uploaded file
    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $target_file)) {

        $avatar_path = $target_file; // Save the path of the uploaded file

        // Update the user's avatar in the database
        change_avatar($avatar_path, $user_id);
    } else {
        echo 'Sorry, there was an error uploading your file.';
    }
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $username = $_POST['username'];
    $new_password = !empty($_POST['new_password']) ? $_POST['new_password'] : null;
    update_user_profile($user_id, $username, $new_password);
    $profile_updated = true;
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

    <title>Profile</title>

</head>

<body>
    <div class="profile-container">
        <h1>Profile</h1>
        <?php if (isset($profile_updated)) echo "<p class='success'>Profile updated.</p>"; ?>
        <?php if (isset($avatar_path)) echo "<p class='success'>Avatar updated.</p>"; ?>
        <img class="avatar" src="<?php echo isset($avatar_path) ? htmlspecialchars($avatar_path) : 'http://localhost/Labb4A/frontend/uploads/' . (isset($user['avatar']) ? $user['avatar'] : ''); ?>" alt="Avatar">

        <form action="profile.php" method="post" enctype="multipart/form-data">
            <div class="input-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo isset($user['username']) ? $user['username'] : ''; ?>" required>
            </div>
            <div class="input-group">
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password">
            </div>
            <div class="input-group">
                <label for="avatar">Avatar:</label>
                <input type="file" id="avatar" name="avatar">
            </div>
            <div class="input-group">
                <button type="submit" name="submit">Update Profile</button>
            </div>
            <a href="dashboard.php">Dashboard</a>
            <?php include 'footer.php'; ?>
    </div>

    </form>

    </div>
</body>

</html>