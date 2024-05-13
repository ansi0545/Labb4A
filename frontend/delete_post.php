<?php
session_start();
require_once(__DIR__ . '/../backend/db.php');

if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    // Redirect to the blog page if the post ID is not provided
    header("Location: blog.php");
    exit;
}

$postId = $_GET['id'];
$userId = $_SESSION['user_id'];

// Prepare the SQL statement with a placeholder for the post ID
$stmt = $connection->prepare("DELETE FROM post WHERE id = ? AND user_id = ?");

// Bind the post ID to the placeholder in the prepared statement
$stmt->bind_param("ii", $postId, $userId);

// Execute the prepared statement
$stmt->execute();

// Redirect back to the blog page
header("Location: blog.php");
exit;
?>