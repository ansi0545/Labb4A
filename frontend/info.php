<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the key "user_id" exists in the $_SESSION array
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $user = get_user_by_id($user_id);

    // Check if the key 0 exists in the $user array
    if (isset($user[0])) {
        $user = $user[0]; // Get the first user from the array
    }
    // Rest of the code to fetch and display user info
}