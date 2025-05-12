<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $description = trim($_POST['description']);

    // File upload
    $cover_image = '';
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $file_ext = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);

        if (in_array(strtolower($file_ext), $allowed)) {
            $cover_image = uniqid() . '.' . $file_ext;
            move_uploaded_file($_FILES['cover_image']['tmp_name'], "../uploads/" . $cover_image);
        } else {
            $errors[] = "Only jpg, jpeg, png, gif files are allowed.";
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO books (title, author, description, cover_image, user_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$title, $author, $description, $cover_image, $_SESSION['user_id']]);
        header('Location: ../dashboard.php');
        exit();
    }
}
?>

<?php include '../includes/header.php'; ?>

<h2 class="head2">Add Book</h2>

<form method="POST" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="Title" required><br><br>
    <input type="text" name="author" placeholder="Author" required><br><br>
    <textarea name="description" placeholder="Description"></textarea><br><br>
    <input type="file" name="cover_image" accept="image/*"><br><br>
    <button type="submit">Add Book</button>
</form>

<?php include '../includes/footer.php'; ?>
