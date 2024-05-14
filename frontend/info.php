<?php
// info.php
$user_id = $_SESSION['user_id'];
$user = get_user_by_id($user_id);
$user = $user[0]; // Get the first user from the array
// Rest of the code to fetch and display user info