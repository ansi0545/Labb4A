<?php
define("DB_SERVER", "127.0.0.1");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "vovvebloggen");

$connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

function add_user($username, $password)
{
    global $connection;
    $sql = 'INSERT INTO user (username, password) VALUES (?,?)';
    $statement = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($statement, "ss", $username, $password);
    mysqli_stmt_execute($statement);
    mysqli_stmt_close($statement);
}

function get_result($statement)
{
    $rows = array();
    $result = mysqli_stmt_get_result($statement);
    if($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
    }
    return $rows;
}

function get_user($username)
{
    global $connection;
    $sql = 'SELECT * FROM user WHERE username=?';
    $statement = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($statement, "s", $username);
    mysqli_stmt_execute($statement);
    $result = get_result($statement);
    mysqli_stmt_close($statement);
    return $result;
}

function get_user_by_id($user_id) {
    global $connection;
    $sql = 'SELECT * FROM user WHERE id=?';
    $statement = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($statement, "i", $user_id);
    mysqli_stmt_execute($statement);
    $result = get_result($statement);
    mysqli_stmt_close($statement);
    return $result;
}

function update_user_profile($user_id, $username, $email, $new_password = null) {
    global $connection;
    // Update user's profile information and password if provided
}

function upload_profile_picture($user_id, $file) {
    // Upload user's profile picture to the server and save the filename in the database
}

function request_password_reset($email) {
    // Send email with password reset link to the user's email address
}

function reset_password($email, $reset_code, $new_password) {
    // Reset user's password in the database
}

function change_avatar($filename, $id)
{
    global $connection;
    $sql = 'UPDATE user SET image=? WHERE id=?';
    $statement = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($statement, "si", $filename, $id);
    $result = mysqli_stmt_execute($statement);
    mysqli_stmt_close($statement);
    return $result;
}

function delete_post($id)
{
    global $connection;
    $sql = 'DELETE FROM post WHERE id=?';
    $statement = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($statement, "i", $id);
    $result = mysqli_stmt_execute($statement);
    mysqli_stmt_close($statement);
    return $result;
}

function import($filename, $dropOldTables=FALSE)
{
    global $connection;
    if ($dropOldTables) {
        $query = 'SHOW TABLES';
        $result = mysqli_query($connection, $query);
        if ($result) {
            while ($row = mysqli_fetch_row($result)) {
                $query = 'DROP TABLE ' . $row[0];
                if (mysqli_query($connection, $query))
                    echo 'Table <strong>' . $row[0] . '</strong> dropped<br>';
            }
        }
    }
    $query = '';
    $lines = file($filename);
    foreach ($lines as $line) {
        if (substr($line, 0, 2) == '--' || $line == '')
            continue;

        $query .= $line;

        if (substr(trim($line), -1, 1) == ';') {
            if (!mysqli_query($connection, $query))
                echo "<br>Error in query: <strong>$query</strong><br><br>";

            $query = '';
        }
    }
    echo 'Import finished!<br>';
}
?>
