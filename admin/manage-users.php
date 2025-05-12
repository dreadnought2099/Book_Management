<?php
require_once '../config/config.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

redirectIfNotAdmin();

// Get all users
$users = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();

include '../includes/header.php';
?>

<h2>Manage Users</h2>

<?php if ($users): ?>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['id']) ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['role']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No users found.</p>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>
