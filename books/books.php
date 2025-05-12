<?php
require_once '../config/config.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

// Fetch all books from the database
$books = $pdo->query("
    SELECT books.*, users.username 
    FROM books 
    JOIN users ON books.user_id = users.id 
    ORDER BY books.id DESC
")->fetchAll();

include '../includes/header.php';
?>

<h2 class="head2">All Books</h2>

<?php if ($books): ?>
    <ul>
        <?php foreach ($books as $book): ?>
            <li style="margin-bottom: 20px;">
                <strong><?= htmlspecialchars($book['title']) ?></strong> by <?= htmlspecialchars($book['author']) ?>
                (uploaded by <?= htmlspecialchars($book['username']) ?>)

                <?php if (!empty($book['cover_image']) && file_exists("../uploads/" . $book['cover_image'])): ?>
                    <div>
                        <img src="../uploads/<?= htmlspecialchars($book['cover_image']) ?>" alt="Cover Image" style="max-width: 150px; display: block; margin-top: 10px;">
                    </div>
                <?php endif; ?>

                <?php if (isLoggedIn() && $_SESSION['user_id'] == $book['user_id']): ?>
                    <!-- Delete option for uploader only -->
                    <a href="books/delete.php?id=<?= $book['id'] ?>"
                        onclick="return confirm('Are you sure you want to delete this book with ID <?= htmlspecialchars($book['id'], ENT_QUOTES) ?>?');">
                        Delete
                    </a>

                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No books available.</p>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>