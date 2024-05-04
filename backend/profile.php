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
