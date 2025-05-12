<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Optional: delete cover image file too
    $stmt = $pdo->prepare("SELECT cover_image FROM books WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $_SESSION['user_id']]);
    $book = $stmt->fetch();

    if ($book) {
        if ($book['cover_image']) {
            unlink('../uploads/' . $book['cover_image']);
        }
        $stmt = $pdo->prepare("DELETE FROM books WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $_SESSION['user_id']]);
    }
}

header('Location: ../dashboard.php');
exit();
?>
