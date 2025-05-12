<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../auth/login.php');
    exit();
}

require_once '../config/db.php';

// Get all users
$users = $pdo->query("SELECT * FROM users")->fetchAll();

// Get all books
$books = $pdo->query("SELECT books.*, users.username FROM books JOIN users ON books.user_id = users.id")->fetchAll();
?>

<?php include '../includes/header.php'; ?>

<h2>Admin Dashboard</h2>
<p><a href="../auth/logout.php">Logout</a></p>

<h3>All Users</h3>
<ul>
    <?php foreach ($users as $user): ?>
        <li><?= htmlspecialchars($user['username']) ?> (<?= htmlspecialchars($user['email']) ?>)</li>
    <?php endforeach; ?>
</ul>

<h3>All Books</h3>
<ul>
    <?php foreach ($books as $book): ?>
        <li><?= htmlspecialchars($book['title']) ?> by <?= htmlspecialchars($book['author']) ?> (uploaded by <?= htmlspecialchars($book['username']) ?>)</li>
    <?php endforeach; ?>
</ul>

<?php include '../includes/footer.php'; ?>
