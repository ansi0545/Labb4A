<?php
// user_functions.php

require_once('db_credentials.php');

// Funktion för att lägga till en ny användare
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

// Funktion för att hämta användarinformation baserat på användarnamn
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

// Funktion för att hämta användarinformation baserat på användar-ID
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

// Funktion för att uppdatera användarprofil
function update_user_profile($user_id, $username, $email, $new_password = null) {
    global $connection;
    // Uppdatera användarens profilinformation och lösenord om det finns
}

// Funktion för att ladda upp användarprofilbild
function upload_profile_picture($user_id, $file) {
    // Ladda upp användarens profilbild till servern och spara filnamnet i databasen
}

// Funktion för att generera en återställningskod och skicka en återställningslänk till användarens e-post
function request_password_reset($email) {
    // Skicka e-post med återställningslänk till användarens e-postadress
}

// Funktion för att återställa lösenordet med en giltig återställningskod
function reset_password($email, $reset_code, $new_password) {
    // Återställ användarens lösenord i databasen
}

// Funktion för att hämta resultat från en SQL-förfrågan
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
?>
