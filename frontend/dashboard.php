<?php
session_start();

// Kontrollera om användaren är inloggad, annars omdirigera till inloggningssidan
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Hämta användarinformation från sessionen eller databasen baserat på användar-ID
$user_id = $_SESSION['user_id'];
// Här behöver du använda funktionen get_user_by_id() som definierats i user_functions.php
$user = get_user_by_id($user_id);

// Om användaren inte finns, kan du visa ett felmeddelande och logga ut dem
if (!$user) {
    // Visa felmeddelande och logga ut användaren
    session_destroy();
    header("Location: login.php");
    exit;
}

// Här kan du välja vad du vill visa på användarens dashboard baserat på deras information

// Visa användarens information (t.ex. användarnamn, e-post, profilbild)
echo "<h1>Welcome back, {$user['username']}!</h1>";
echo "<p>Email: {$user['email']}</p>";
// Om du har en bildsparad i databasen, kan du visa den här

// Lägg till länkar för att utföra olika åtgärder, t.ex. uppdatera profil, skapa inlägg, logga ut
echo "<a href='profile.php'>Update Profile</a><br>";
echo "<a href='create_post.php'>Create Post</a><br>";
echo "<a href='logout.php'>Logout</a>";

// Visa användarens senaste inlägg, om det finns
// Exempel: Hämta användarens inlägg från databasen och visa dem

?>
