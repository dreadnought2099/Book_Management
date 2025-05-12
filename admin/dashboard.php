<?php
require_once '../config/config.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

redirectIfNotAdmin();

// Handle delete request
if (isset($_GET['delete_id'])) {
    $deleteId = (int)$_GET['delete_id'];

    if ($_SESSION['user_id'] == $deleteId) {
        echo "You cannot delete your own account.";
        exit();
    }

    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$deleteId]);

    // Avoid resubmission
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
                            <a href="dashboard.php?delete_id=<?= $user['id'] ?>"
                               class="button-link"
                               onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
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
