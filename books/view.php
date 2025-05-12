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

$stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
$stmt->execute([$id]);
$book = $stmt->fetch();

if (!$book) {
    die('Book not found.');
}
?>

<?php include '../includes/header.php'; ?>

<h2><?= htmlspecialchars($book['title']) ?></h2>
<p><strong>Author:</strong> <?= htmlspecialchars($book['author']) ?></p>
<p><?= nl2br(htmlspecialchars($book['description'])) ?></p>

<?php if ($book['cover_image']): ?>
    <img src="../uploads/<?= htmlspecialchars($book['cover_image']) ?>" width="200">
<?php endif; ?>

<br><br>
<a href="../dashboard.php">Back to Dashboard</a>

<?php include '../includes/footer.php'; ?>
