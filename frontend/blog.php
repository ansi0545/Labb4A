<?php
// Include your database connection file here
require_once(__DIR__ . '/../backend/db.php');

// Fetch all posts from the database
$sql = 'SELECT * FROM post ORDER BY id DESC';
$result = mysqli_query($connection, $sql);
$posts = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_free_result($result);
mysqli_close($connection);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Blog</title>
   
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Blog</h1>

    <!-- List of posts -->
    <?php foreach ($posts as $post) : ?>
        <div class="post">
            <h2><?php echo htmlspecialchars($post['title']); ?></h2>
            <p><?php echo htmlspecialchars($post['content']); ?></p>
        </div>
    <?php endforeach; ?>

    <a href="create_post.php">Create a new post</a>
</body>
</html>