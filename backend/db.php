<?php
require_once('db_credentials.php');

// Establish connection to the database
$connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

function add_user($username, $password)
{
    global $connection;

    // Create SQL query
    $sql = 'INSERT INTO user (username, password) VALUES (?,?)';
    // Prepare the query
    $statement = mysqli_prepare($connection, $sql);

    // Bind parameters
    mysqli_stmt_bind_param($statement, "ss", $username, $password);

    // Execute the query
    mysqli_stmt_execute($statement);

    // Close the statement
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

function get_users()
{
    global $connection;
    $sql = 'SELECT * FROM user';
    $statement = mysqli_prepare($connection, $sql);
    mysqli_stmt_execute($statement);
    $result = get_result($statement);
    mysqli_stmt_close($statement);
    return $result;
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

function get_password($id)
{
    global $connection;
    $sql = 'SELECT password FROM user WHERE id=?';
    $statement = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($statement, "i", $id);
    mysqli_stmt_execute($statement);
    $result = get_result($statement);
    mysqli_stmt_close($statement);
    return $result;
}

function get_images($id)
{
    global $connection;
    $sql = 'SELECT filename, description FROM image WHERE postId=?';
    $statement = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($statement, "i", $id);
    mysqli_stmt_execute($statement);
    $result = get_result($statement);
    mysqli_stmt_close($statement);
    return $result;
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
