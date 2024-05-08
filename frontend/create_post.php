<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Include your database connection file here
require_once(__DIR__ . '/../backend/db.php');

$errors = [];
$title = '';
$content = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Validate title
    if (empty($title)) {
        $errors[] = 'Title is required';
    }

    // Validate content
    if (empty($content)) {
        $errors[] = 'Content is required';
    }

    // Check if there are no errors
    if (empty($errors)) {
        // Insert post into database
        $sql = 'INSERT INTO post (title, content, user_id) VALUES (?, ?, ?)'; // Changed 'posts' to 'post'
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, 'ssi', $title, $content, $_SESSION['user_id']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Handle file upload if a file was uploaded
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $target_dir = 'uploads/';
            $target_file = $target_dir . basename($_FILES['image']['name']);

            // Check if the directory exists, if not, create it
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            // Move the uploaded file
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                echo 'The file ' . basename($_FILES['image']['name']) . ' has been uploaded.';
            } else {
                echo 'Sorry, there was an error uploading your file.';
            }
        }

        // Redirect to the blog page
        header('Location: blog.php');
        exit();
    }
}
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
    <title>Create Post</title>
</head>

<body>
   


    <!-- Display errors -->
    <?php if (!empty($errors)) : ?>
        <div class="errors">
            <?php foreach ($errors as $error) : ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Post form -->
    <form method="POST" enctype="multipart/form-data">
    <h1>Create Post</h1>
        <label for="title">Title</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>">

        <label for="content">Content</label>
        <textarea id="content" name="content"><?php echo htmlspecialchars($content); ?></textarea>

        <label for="image">Image</label>
        <input type="file" id="image" name="image">

        <button type="submit">Create Post</button>
    </form>
</body>

</html>