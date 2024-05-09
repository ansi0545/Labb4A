<?php
require_once(__DIR__ . '/../backend/db.php');

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

$posts = [];
$filter = isset($_GET['filter']) ? $_GET['filter'] : null;

if ($filter) {
    if ($filter === 'Allt om hundar') {
        // Execute the query to fetch all posts without filtering by category
        $result = mysqli_query($connection, 'SELECT p.*, c.name AS category_name FROM post p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC');
    } else {
        // Prepare the SQL statement with a placeholder for the category name
        $stmt = $connection->prepare("SELECT p.*, c.name AS category_name FROM post p LEFT JOIN categories c ON p.category_id = c.id WHERE c.name = ? ORDER BY p.id DESC");
        // Bind the $filter variable to the placeholder in the prepared statement
        $stmt->bind_param("s", $filter);
        // Execute the prepared statement
        $stmt->execute();
        // Get the result of the query
        $result = $stmt->get_result();
    }
} else {
    // Execute the default query to fetch all posts
    $result = mysqli_query($connection, 'SELECT p.*, c.name AS category_name FROM post p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC');
}

if ($result) {
    $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);
    // Free the memory associated with the result
    mysqli_free_result($result);
} else {
    echo "Error fetching posts: " . mysqli_error($connection);
}

// Fetch categories for the sidebar
$categories = [];
$categorySql = 'SELECT * FROM categories ORDER BY id ASC';
$categoryResult = mysqli_query($connection, $categorySql);
if ($categoryResult) {
    $categories = mysqli_fetch_all($categoryResult, MYSQLI_ASSOC);
    mysqli_free_result($categoryResult);
} else {
    echo "Error fetching categories: " . mysqli_error($connection);
}

// Define category colors manually
$categoryColors = [
    'Allt om hundar' => 'linear-gradient(135deg, #3b82f6, #ef4444, #16a34a, #eab308)',
    'Hundträning' => '#3b82f6',
    'Hundfoder' => '#16a34a',
    'Barmarksdrag' => '#ef4444',
    'Hundutrustning' => '#eab308',
    'Agility' => '#db2777',
    'Rallylydnad' => '#14b8a6',
];

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
            <h1>Vovvebloggen</h1>
        </header>

        <main class="main">
            <aside>
                <ul>
                    <!-- Dynamically load categories -->
                    <?php
                    foreach ($categories as $category) {
                        $style = $category['name'] === 'Allt om hundar' ? 'background-image: ' . $categoryColors[$category['name']] : 'background-color: ' . ($categoryColors[$category['name']] ?? '#000');
                        echo '<li class="category"><a href="?filter=' . urlencode($category['name']) . '" class="btn btn-category" style="' . htmlspecialchars($style) . '">' . htmlspecialchars($category['name']) . '</a></li>';
                    }
                    ?>
                </ul>
            </aside>

            <section>
                <ul class="blog-list">
                    <?php foreach ($posts as $post) : ?>
                        <li class="blogPosts">
                            <div class="post">
                                <h2><?php echo htmlspecialchars($post['title']); ?></h2>
                                <p><?php echo htmlspecialchars($post['content']); ?></p>
                                <p>Category: <?php echo htmlspecialchars($post['category_name']); ?></p>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <a href="create_post.php">Create a new post</a>
            </section>

        </main>
    </section>
</body>

</html>
<?php

// Close the connection
if ($connection) {
    mysqli_close($connection);
}
?>