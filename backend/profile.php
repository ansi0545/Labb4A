<?php
require_once('backend/db.php');

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user = get_user_by_id($user_id);

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $new_password = !empty($_POST['new_password']) ? $_POST['new_password'] : null;
    update_user_profile($user_id, $username, $email, $new_password);
    $profile_updated = true;
}

if (isset($_FILES['avatar'])) {
    upload_profile_picture($user_id, $_FILES['avatar']);
    $avatar_updated = true;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>

</head>

<body>
    <div class="profile-container">
        <h2>Profile</h2>
        <?php if (isset($profile_updated)) echo "<p class='success'>Profile updated.</p>"; ?>
        <?php if (isset($avatar_updated)) echo "<p class='success'>Avatar updated.</p>"; ?>
        <img src="uploads/<?php echo $user['avatar']; ?>" alt="Avatar">
        <form action="profile.php" method="post" enctype="multipart/form-data">
            <div class="input-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>" required>
            </div>
            <div class="input-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>
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
        </form>
    </div>
</body>

</html>