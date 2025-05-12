<?php
require_once '../config/config.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

redirectIfNotAdmin();

// Get all users
$users = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();

// Get all books with uploader info
$books = $pdo->query("SELECT books.*, users.username FROM books JOIN users ON books.user_id = users.id ORDER BY books.id DESC")->fetchAll();

include '../includes/header.php';
?>

<h2>Admin Dashboard</h2>

<section>
    <h3>All Users</h3>
    <?php if ($users): ?>
        <ul>
            <?php foreach ($users as $user): ?>
                <li><?= htmlspecialchars($user['username']) ?> (<?= htmlspecialchars($user['email']) ?>)</li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No users found.</p>
    <?php endif; ?>
</section>

<section>
    <h3>All Books</h3>
    <?php if ($books): ?>
        <ul>
            <?php foreach ($books as $book): ?>
                <li>
                    <?= htmlspecialchars($book['title']) ?> by <?= htmlspecialchars($book['author']) ?> 
                    (uploaded by <?= htmlspecialchars($book['username']) ?>)
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No books found.</p>
    <?php endif; ?>
</section>

<?php include '../includes/footer.php'; ?>
