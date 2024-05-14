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


function update_user_profile($user_id, $username, $new_password = null)
{
    global $connection;
    $sql = 'UPDATE user SET username=?';
    $params = [$username];
    $types = 's'; // username and email are strings

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


function upload_profile_picture($user_id, $avatar)
{
    // Assuming you have a $connection variable for your database connection
    global $connection;

    $target_dir = 'uploads/';
    $target_file = $target_dir . basename($avatar["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($avatar["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($avatar["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($avatar["tmp_name"], $target_file)) {

            $sql = "UPDATE user SET avatar = ? WHERE id = ?";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param("si", $target_file, $user_id);
            $stmt->execute();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
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

function get_post_by_id($post_id)
{
    global $connection;

    $stmt = $connection->prepare("SELECT * FROM post WHERE id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();

    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function get_latest_posts($limit = 10)
{
    global $connection;
    $sql = 'SELECT * FROM post ORDER BY created DESC LIMIT ?';
    $statement = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($statement, "i", $limit);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    mysqli_stmt_close($statement);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function get_all_bloggers()
{
    global $connection;
    $sql = 'SELECT * FROM user';
    $statement = mysqli_prepare($connection, $sql);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    mysqli_stmt_close($statement);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function get_newest_blogger()
{
    global $connection;
    $sql = 'SELECT * FROM user ORDER BY created DESC LIMIT 1';
    $statement = mysqli_prepare($connection, $sql);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    mysqli_stmt_close($statement);
    return mysqli_fetch_assoc($result);
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
    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        $user_id = $user['id'];
        update_user_profile($user_id, $user['username'], $new_password);
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
