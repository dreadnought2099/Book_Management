<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Fetch the book and confirm it belongs to the current user
    $stmt = $pdo->prepare("SELECT cover_image FROM books WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $_SESSION['user_id']]);
    $book = $stmt->fetch();

    if ($book) {
        // Delete cover image if it exists
        $coverPath = '../uploads/' . $book['cover_image'];
        if (!empty($book['cover_image']) && file_exists($coverPath)) {
            unlink($coverPath);
        }

        // Delete the book from the database
        $stmt = $pdo->prepare("DELETE FROM books WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $_SESSION['user_id']]);
    }
}

// Redirect to books list
header('Location: ../books.php');
exit();
