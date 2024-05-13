<?php
session_start();

// Kontrollera om användaren är inloggad
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Inkludera din databasanslutningsfil här
require_once(__DIR__ . '/../backend/db.php');

$errors = [];
$title = '';
$content = '';
$category_id = 0; // Lägg till detta

// Hämta kategorier för dropdown
$categorySql = 'SELECT * FROM categories ORDER BY name ASC';
$categoryResult = mysqli_query($connection, $categorySql);
$categories = mysqli_fetch_all($categoryResult, MYSQLI_ASSOC);

// Kontrollera om formuläret har skickats
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category_id = $_POST['category']; // Hämta vald kategori

    // Validera titel
    if (empty($title)) {
        $errors[] = 'Titel är obligatorisk';
    }

    // Validera innehåll
    if (empty($content)) {
        $errors[] = 'Innehåll är obligatoriskt';
    }

    $image_path = '';
    // Hantera filuppladdning om en fil har laddats upp
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $target_dir = 'uploads/';
        $target_file = $target_dir . basename($_FILES['image']['name']);

        // Kontrollera om katalogen finns, om inte, skapa den
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Flytta den uppladdade filen
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            echo 'Filen ' . basename($_FILES['image']['name']) . ' har laddats upp.';
            $image_path = $target_file; // This is the path of the uploaded file
        } else {
            echo 'Tyvärr, det blev ett fel vid uppladdningen av din fil.';
        }
    }

    // Kontrollera om det inte finns några fel
    if (empty($errors)) {
        // Infoga inlägg i databasen inklusive kategori
        $sql = 'INSERT INTO post (title, content, user_id, category_id, image_path) VALUES (?, ?, ?, ?, ?)';
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, 'ssiis', $title, $content, $_SESSION['user_id'], $category_id, $image_path);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);



        // Omdirigera till bloggsidan
        header('Location: blog.php');
        exit();
    }
}
// Get user information from the session or the database based on the user ID
$user_id = $_SESSION['user_id'];
$user = get_user_by_id($user_id);
$user = $user[0]; // Get the first user from the array

// If the user does not exist, you can display an error message and log them out
if (!$user) {
    // Display an error message and log out the user
    session_destroy();
    //header("Location: login.php");
    exit;
}

// Check if the 'username' key is set in the $user array
if (!isset($user['username'])) {
    $user['username'] = 'Guest';
}

// Get the avatar path for the logged-in user
$avatar_path = isset($user['avatar']) ? $user['avatar'] : '';
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

</head>

<body>


    <!-- Visa felmeddelanden -->
    <?php if (!empty($errors)) : ?>
        <div class="errors">
            <?php foreach ($errors as $error) : ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Inläggsformulär -->
    <form method="POST" enctype="multipart/form-data">
        <header>
            <img class="avatar" src="<?php echo isset($avatar_path) ? htmlspecialchars($avatar_path) : 'http://localhost/Labb4A/frontend/uploads/' . (isset($user['avatar']) ? $user['avatar'] : ''); ?>" alt="Avatar">
        </header>
        <h1>Skapa Inlägg</h1>
        <label for="title">Titel</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>">

        <label for="content">Innehåll</label>
        <textarea id="content" name="content"><?php echo htmlspecialchars($content); ?></textarea>

        <label for="category">Kategori</label>
        <select id="category" name="category">
            <?php foreach ($categories as $category) : ?>
                <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
            <?php endforeach; ?>
        </select>

        <label for="image">Bild</label>
        <input type="file" id="image" name="image">

        <button type="submit">Skapa Inlägg</button>
    </form>
</body>

</html>