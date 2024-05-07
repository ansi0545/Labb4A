<?php
require_once('db_credentials.php');
$connection = mysqli_connect('127.0.0.1', 'root', '', 'vovvebloggen');

function get_users($username)
{
    global $connection;
    $statement = mysqli_prepare($connection, 'SELECT * FROM user WHERE username=?');
    mysqli_stmt_bind_param($statement, "s", $username);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    mysqli_stmt_close($statement);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function get_user($username)
{
    global $connection; // Assuming $conn is your database connection

    $query = "SELECT * FROM user WHERE username = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc(); // This will return the first user in the result set
    } else {
        return false;
    }
}

function add_user($username, $email, $password)
{
    global $connection;
    $password = password_hash($password, PASSWORD_DEFAULT);
    $statement = mysqli_prepare($connection, 'INSERT INTO user (username, email, password) VALUES (?, ?, ?)');
    mysqli_stmt_bind_param($statement, "sss", $username, $email, $password);
    mysqli_stmt_execute($statement);
    mysqli_stmt_close($statement);
}

// Funktion för att hämta användarinformation baserat på användar-ID
function get_user_by_id($user_id)
{
    global $connection;
    $sql = 'SELECT * FROM user WHERE id=?';
    $statement = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($statement, "i", $user_id);
    mysqli_stmt_execute($statement);
    $result = get_result($statement);
    mysqli_stmt_close($statement);
    return $result;
}

function change_avatar($avatar, $user_id)
{
    global $connection;
    $statement = mysqli_prepare($connection, 'UPDATE user SET avatar=? WHERE id=?');
    mysqli_stmt_bind_param($statement, "si", $avatar, $user_id);
    mysqli_stmt_execute($statement);
    mysqli_stmt_close($statement);
}


function update_user_profile($user_id, $username, $email, $new_password = null)
{
    global $connection;
    $sql = 'UPDATE user SET username=?, email=?';
    $params = [$username, $email];
    $types = 'ss'; // username and email are strings

    if ($new_password !== null) {
        $sql .= ', password=?';
        $params[] = password_hash($new_password, PASSWORD_DEFAULT);
        $types .= 's'; // password is a string
    }

    $sql .= ' WHERE id=?';
    $params[] = $user_id;
    $types .= 'i'; // id is an integer

    $statement = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($statement, $types, ...$params);
    mysqli_stmt_execute($statement);
    mysqli_stmt_close($statement);
}


function upload_profile_picture($user_id, $file)
{
    $target_dir = "uploads/";

    // Check if the directory exists
    if (!file_exists($target_dir)) {
        // If not, create the directory
        mkdir($target_dir, 0777, true);
    }

    // Now proceed with moving the uploaded file
    $target_file = $target_dir . basename($file["name"]);
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        // Update the user's avatar in the database
        change_avatar($target_file, $user_id);
        echo "The file " . basename($file["name"]) . " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

function request_password_reset($email)
{
    global $connection;
    $reset_code = bin2hex(random_bytes(20)); // Generate a random reset code
    $sql = 'UPDATE user SET reset_code=? WHERE email=?';
    $statement = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($statement, "ss", $reset_code, $email);
    mysqli_stmt_execute($statement);
    mysqli_stmt_close($statement);
    // TODO: Send the reset code to the user's email address
}

function reset_password($email, $reset_code, $new_password)
{
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
function get_result($statement)
{
    $rows = array();
    $result = mysqli_stmt_get_result($statement);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
    }
    return $rows;
}
