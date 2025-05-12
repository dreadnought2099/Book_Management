<?php
require_once 'config/config.php';
require_once 'config/db.php';
require_once 'includes/functions.php';

redirectIfNotLoggedIn();

$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM books WHERE user_id = ?");
$stmt->execute([$userId]);
$books = $stmt->fetchAll();

include 'includes/header.php';
?>

<h2>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>

<h3>Your Books</h3>
<p><a href="books/add.php">Add New Book</a></p>

<?php if ($books): ?>
    <ul>
        <?php foreach ($books as $book): ?>
            <li>
                <strong><?= htmlspecialchars($book['title']) ?></strong> by <?= htmlspecialchars($book['author']) ?>
                <a href="books/view.php?id=<?= $book['id'] ?>">View</a>
                <a href="books/edit.php?id=<?= $book['id'] ?>">Edit</a>
                <a href="books/delete.php?id=<?= $book['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No books found.</p>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
