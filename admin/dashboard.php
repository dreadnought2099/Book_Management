<?php
require_once '../config/config.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

redirectIfNotAdmin();

// Handle user deletion
if (isset($_GET['delete_id'])) {
    $deleteId = (int)$_GET['delete_id'];

    if ($_SESSION['user_id'] == $deleteId) {
        echo "You cannot delete your own account.";
        exit();
    }

    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$deleteId]);

    header('Location: dashboard.php');
    exit();
}

// Handle book deletion by admin
if (isset($_GET['delete_book_id'])) {
    $bookId = (int)$_GET['delete_book_id'];

    // Get book info to delete cover image if exists
    $stmt = $pdo->prepare("SELECT cover_image FROM books WHERE id = ?");
    $stmt->execute([$bookId]);
    $book = $stmt->fetch();

    if ($book) {
        if (!empty($book['cover_image'])) {
            $coverPath = '../uploads/' . $book['cover_image'];
            if (file_exists($coverPath)) {
                unlink($coverPath);
            }
        }

        $stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
        $stmt->execute([$bookId]);
    }

    header('Location: dashboard.php');
    exit();
}

// Fetch users and books
$users = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();
$books = $pdo->query("SELECT books.*, users.username FROM books JOIN users ON books.user_id = users.id ORDER BY books.id DESC")->fetchAll();

include '../includes/header.php';
?>

<h2 class="head2">Admin Dashboard</h2>

<section>
    <h3>All Users</h3>
    <?php if ($users): ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['role']) ?></td>
                        <td>
                            <?php if ($_SESSION['user_id'] != $user['id']): ?>
                                <a href="dashboard.php?delete_id=<?= $user['id'] ?>"
                                   class="button-link"
                                   onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                            <?php else: ?>
                                <em>Cannot delete self</em>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No users found.</p>
    <?php endif; ?>
</section>

<section>
    <h3>All Books</h3>
    <?php if ($books): ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Uploader</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td><?= htmlspecialchars($book['title']) ?></td>
                        <td><?= htmlspecialchars($book['author']) ?></td>
                        <td><?= htmlspecialchars($book['username']) ?></td>
                        <td>
                            <a href="dashboard.php?delete_book_id=<?= $book['id'] ?>"
                               class="button-link"
                               onclick="return confirm('Are you sure you want to delete the book: <?= htmlspecialchars(addslashes($book['title'])) ?>?');">
                               Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No books found.</p>
    <?php endif; ?>
</section>

<?php include '../includes/footer.php'; ?>
