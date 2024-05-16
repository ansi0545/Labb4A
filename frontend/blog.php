<?php
session_start();


require_once(__DIR__ . '/../backend/db.php');


if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

$loggedInUserId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

include 'content.php';
include 'menu.php';
include 'info.php';



$sql = "SELECT p.*, c.name AS category_name FROM post p LEFT JOIN categories c ON p.category_id = c.id WHERE 1=1";


if (isset($_GET['id'])) {
    $post_id = $_GET['id'];

    $sql .= " AND p.id = ?";

    $statement = mysqli_prepare($connection, $sql);

    mysqli_stmt_bind_param($statement, "i", $post_id);
} else if (isset($_GET['user_id'])) {

    $user_id = $_GET['user_id'];
    $sql .= " AND p.user_id = ?";
    $statement = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($statement, "i", $user_id);
} else if (isset($_GET['category_id'])) {
    // If a category_id filter is provided, fetch posts belonging to that category
    $category_id = $_GET['category_id'];
    $sql .= " AND p.category_id = ?";
    $statement = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($statement, "i", $category_id);
} else {
    // If no specific filter is provided, fetch all posts
    $sql .= " ORDER BY p.id DESC";
    $statement = mysqli_prepare($connection, $sql);
}


mysqli_stmt_execute($statement);


$result = mysqli_stmt_get_result($statement);

if ($result) {
    $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);

    mysqli_free_result($result);
} else {
    echo "Error fetching posts: " . mysqli_error($connection);
}


mysqli_stmt_close($statement);

// Check if the 'avatar' key is set in the $user array

$avatar_path = isset($user['avatar']) ? htmlspecialchars($user['avatar']) : '';


session_write_close();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Coiny&family=Sono:wght@200..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Blog</title>
</head>

<body>
    <section class="blogposts-container">
        <header class="header">
            <img class="avatar" src="<?php echo isset($avatar_path) ? htmlspecialchars($avatar_path) : 'http://localhost/Labb4A/frontend/uploads/' . (isset($user['avatar']) ? $user['avatar'] : ''); ?>" alt="Avatar">
            <h1>Vovvebloggen</h1>
        </header>

        <main class="main">
            <aside>
                <ul>
                    <!-- Dynamically load categories -->
                    <?php
                    // Sort categories so that 'Allt om hundar' is always at the top
                    usort($categories, function ($a, $b) {
                        if ($a['name'] === 'Allt om hundar') {
                            return -1;
                        }
                        if ($b['name'] === 'Allt om hundar') {
                            return 1;
                        }
                        return strcmp($a['name'], $b['name']);
                    });
                    foreach ($categories as $category) {
                        $style = $category['name'] === 'Allt om hundar' ? 'background-image: ' . $categoryColors[$category['name']] : 'background-color: ' . ($categoryColors[$category['name']] ?? '#000');
                        echo '<li class="category"><a href="?category_id=' . $category['id'] . '" class="btn btn-category" style="' . htmlspecialchars($style) . '">' . htmlspecialchars($category['name']) . '</a></li>';
                    }
                    ?>
                </ul>
            </aside>

            <section class="blog-container">
                <?php if (empty($posts)) : ?>
                    <p>No posts found.</p>
                <?php else : ?>
                    <ul class="blog-list">
                        <?php foreach ($posts as $post) : ?>
                            <li class="blogPosts">
                                <div class="post">
                                    <h2><?php echo htmlspecialchars($post['title']); ?></h2>
                                    <p><?php echo htmlspecialchars($post['content']); ?></p>

                                    <p>Category: <?php echo htmlspecialchars($post['category_name']); ?></p>
                                    <?php if (!empty($post['image_path'])) : ?>
                                        <img src="<?php echo htmlspecialchars($post['image_path']); ?>" alt="Blog Image">
                                    <?php endif; ?>
                                    <?php // If the logged-in user is the author of the post, display the "Edit" and "Delete" buttons
                                    if ($post['user_id'] == $loggedInUserId) {
                                        echo '<a href="edit_post.php?id=' . $post['id'] . '">Edit</a>';
                                        echo '<a href="delete_post.php?id=' . $post['id'] . '" onclick="return confirm(\'Are you sure you want to delete this post?\')">Delete</a>';
                                    }
                                    ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <a href="<?php echo isset($_SESSION['user_id']) ? 'dashboard.php' : '/login.php'; ?>">Dashboard</a>
                <a href="<?php echo isset($_SESSION['user_id']) ? 'create_post.php' : '/login.php'; ?>">Create post</a>

                <?php include 'footer.php'; ?>
            </section>

        </main>
    </section>
</body>

</html>
<?php

if ($connection) {
    mysqli_close($connection);
}
?>