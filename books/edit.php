<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: ../dashboard.php');
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM books WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$book = $stmt->fetch();

if (!$book) {
    die('Book not found.');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $description = trim($_POST['description']);

    // Update cover image if a new one is uploaded
    $cover_image = $book['cover_image'];
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $file_ext = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);

        if (in_array(strtolower($file_ext), $allowed)) {
            $new_cover = uniqid() . '.' . $file_ext;
            move_uploaded_file($_FILES['cover_image']['tmp_name'], "../uploads/" . $new_cover);
            $cover_image = $new_cover;
        } else {
            $errors[] = "Only jpg, jpeg, png, gif files are allowed.";
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE books SET title = ?, author = ?, description = ?, cover_image = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$title, $author, $description, $cover_image, $id, $_SESSION['user_id']]);
        header('Location: ../dashboard.php');
        exit();
    }
}
?>

<?php include '../includes/header.php'; ?>

<h2 class="head2">Edit Book</h2>

<form method="POST" enctype="multipart/form-data">
    <input type="text" name="title" value="<?= htmlspecialchars($book['title']) ?>" required><br><br>
    <input type="text" name="author" value="<?= htmlspecialchars($book['author']) ?>" required><br><br>
    <textarea name="description"><?= htmlspecialchars($book['description']) ?></textarea><br><br>
    <?php if ($book['cover_image']): ?>
        <img src="../uploads/<?= htmlspecialchars($book['cover_image']) ?>" width="100"><br>
    <?php endif; ?>
    <input type="file" name="cover_image" accept="image/*"><br><br>
    <button type="submit">Update Book</button>
</form>

<?php include '../includes/footer.php'; ?>
