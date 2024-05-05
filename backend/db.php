<?php
require_once(__DIR__ . '/db_credentials.php');
$connection = mysqli_connect('127.0.0.1', 'root', '', 'vovvebloggen');

function get_user($username) {
    global $connection;
    $statement = mysqli_prepare($connection, 'SELECT * FROM user WHERE username=?');
    mysqli_stmt_bind_param($statement, "s", $username);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    mysqli_stmt_close($statement);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function add_user($username, $password) {
    global $connection;
    $password = password_hash($password, PASSWORD_DEFAULT);
    $statement = mysqli_prepare($connection, 'INSERT INTO user (username, password) VALUES (?, ?)');
    mysqli_stmt_bind_param($statement, "ss", $username, $password);
    mysqli_stmt_execute($statement);
    mysqli_stmt_close($statement);
}

function change_avatar($avatar, $user_id) {
    global $connection;
    $statement = mysqli_prepare($connection, 'UPDATE user SET avatar=? WHERE id=?');
    mysqli_stmt_bind_param($statement, "si", $avatar, $user_id);
    mysqli_stmt_execute($statement);
    mysqli_stmt_close($statement);
}

function update_user_profile($user_id, $username, $email, $new_password = null) {
    global $connection;
    $sql = 'UPDATE user SET username=?, email=?';
    $params = [$username, $email];
    if ($new_password !== null) {
        $sql .= ', password=?';
        $params[] = password_hash($new_password, PASSWORD_DEFAULT);
    }
    $sql .= ' WHERE id=?';
    $params[] = $user_id;
    $statement = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($statement, str_repeat('s', count($params)), ...$params);
    mysqli_stmt_execute($statement);
    mysqli_stmt_close($statement);
}

function upload_profile_picture($user_id, $file) {
    // Assuming $file is an array with file info, as from $_FILES['fieldname']
    $filename = $file['name'];
    $filetmp = $file['tmp_name'];
    $destination = "uploads/" . $filename;
    move_uploaded_file($filetmp, $destination);
    change_avatar($filename, $user_id);
}

function request_password_reset($email) {
    global $connection;
    $reset_code = bin2hex(random_bytes(20)); // Generate a random reset code
    $sql = 'UPDATE user SET reset_code=? WHERE email=?';
    $statement = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($statement, "ss", $reset_code, $email);
    mysqli_stmt_execute($statement);
    mysqli_stmt_close($statement);
    // TODO: Send the reset code to the user's email address
}

function reset_password($email, $reset_code, $new_password) {
    global $connection;
    $sql = 'SELECT * FROM user WHERE email=? AND reset_code=?';
    $statement = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($statement, "ss", $email, $reset_code);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    mysqli_stmt_close($statement);
    if (count($result) === 1) {
        $user_id = $result[0]['id'];
        update_user_profile($user_id, $result[0]['username'], $email, $new_password);
    } else {
        // TODO: Handle the error case where the reset code is incorrect
    }
}
?>