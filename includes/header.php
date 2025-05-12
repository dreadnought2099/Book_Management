<?php
// includes/header.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= APP_URL ?>style.css">
</head>
<body>
<header>
    <h1><?= APP_NAME ?></h1>
    <nav>
        <a href="<?= APP_URL ?>">Home</a>
        <a href="<?= APP_URL ?>books/books.php">Books</a>
        <?php if (isLoggedIn()): ?>
            <a href="<?= APP_URL ?>dashboard.php">Dashboard</a>
            <?php if (isAdmin()): ?>
                <a href="<?= APP_URL ?>admin/dashboard.php">Admin Panel</a>
            <?php endif; ?>
            <a href="<?= APP_URL ?>auth/logout.php">Logout</a>
        <?php else: ?>
            <a href="<?= APP_URL ?>auth/login.php">Login</a>
            <a href="<?= APP_URL ?>auth/register.php">Register</a>
        <?php endif; ?>
    </nav>
</header>
<main>
