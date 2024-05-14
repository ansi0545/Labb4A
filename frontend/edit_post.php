<?php
session_start();
require_once(__DIR__ . '/../backend/db.php');
include 'content.php';
include 'menu.php';
include 'info.php';

$post_id = $_GET['id'] ?? null;
$title = '';
$content = '';

if (!$post_id) {
    error_log("Post ID is not set");
    header("Location: blog.php");
    exit;
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch the post data from the database
$postSql = 'SELECT * FROM post WHERE id = ?';
$postStmt = mysqli_prepare($connection, $postSql);
mysqli_stmt_bind_param($postStmt, 'i', $post_id);
mysqli_stmt_execute($postStmt);
$postResult = mysqli_stmt_get_result($postStmt);
$post = mysqli_fetch_assoc($postResult);
mysqli_stmt_close($postStmt);

if (!$post) {
    error_log("No post found with ID $post_id");
    header("Location: blog.php");
    exit;
}

// If the post does not exist or does not belong to the logged in user, redirect to the blog page
if ($post['user_id'] !== $_SESSION['user_id']) {
    header("Location: blog.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image_path = $post['image_path'];
    $category_id = $_POST['category'];

    // Check if a new image file has been uploaded
    if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["image"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_path = $target_file;
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

    // Update the post
    $stmt = $connection->prepare("UPDATE post SET title = ?, content = ?, image_path = ?, category_id = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $title, $content, $image_path, $category_id, $post_id);
    $stmt->execute();

    header("Location: blog.php");
    exit;
}

// Fetch the categories from the database
$categorySql = 'SELECT * FROM categories ORDER BY name ASC';
$categoryResult = mysqli_query($connection, $categorySql);
$categories = mysqli_fetch_all($categoryResult, MYSQLI_ASSOC);
mysqli_free_result($categoryResult);
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
    <title>Edit Post</title>
</head>

<body>
    <form method="POST" enctype="multipart/form-data">
        <header>
            <img class="avatar" src="<?php echo isset($avatar_path) ? htmlspecialchars($avatar_path) : 'http://localhost/Labb4A/frontend/uploads/' . (isset($user['avatar']) ? $user['avatar'] : ''); ?>" alt="Avatar">
        </header>
        <h1>Edit post</h1>
        <label for="title">Title</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>">

        <label for="content">Content</label>
        <textarea id="content" name="content"><?php echo htmlspecialchars($post['content']); ?></textarea>

        <label for="category">Category</label>

        <select id="category" name="category">
            <?php foreach ($categories as $category) : ?>
                <?php $selected = ($category['id'] === $post['category_id']) ? 'selected' : ''; ?>

                <option value="<?php echo $category['id']; ?>" <?php echo $selected; ?>>
                    <?php echo htmlspecialchars($category['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="image">Image</label>
        <input type="file" id="image" name="image">

        <button type="submit">Update post</button>
        <a href="dashboard.php">Dashboard</a>
    </form>
</body>

</html>