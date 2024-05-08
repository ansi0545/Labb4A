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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Coiny&family=Sono:wght@200..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Blog</title>

    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <section class="blogposts-container">
        <header class="header">
            <h1>Vovvebloggen</h1>
        </header>

        <main class="main">
            <aside>
                <ul>
                    <li class="category"><button class="btn btn-all-categories">Allt om hundar</button></li>
                    <li class="category">
                        <button class="btn btn-category" style="background-color:#3b82f6">Hundträning</button>
                    </li>
                    <li class="category">
                        <button class="btn btn-category" style="background-color: #16a34a">Hundfoder</button>
                    </li>
                    <li class="category">
                        <button class="btn btn-category" style="background-color: #ef4444">Barmarksdrag</button>
                    </li>
                    <li class="category">
                        <button class="btn btn-category" style="background-color: #eab308">Hundutrustning</button>
                    </li>
                    <li class="category">
                        <button class="btn btn-category" style="background-color: #db2777">Agility</button>
                    </li>
                    <li class="category">
                        <button class="btn btn-category" style="background-color: #14b8a6">Rallylydnad</button>
                    </li>
                </ul>
            </aside>

            <section>
                <ul class="blog-list">
                    <li class="blogPosts">
                        <?php foreach ($posts as $post) : ?>
                            <div class="post">
                                <h2><?php echo htmlspecialchars($post['title']); ?></h2>
                                <p><?php echo htmlspecialchars($post['content']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </li>
                    <!-- <a href="create_post.php">Create a new post</a> -->
            </section>

        </main>
        </div> <!-- end container -->



</body>

</html>