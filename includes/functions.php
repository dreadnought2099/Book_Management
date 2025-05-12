<?php
// includes/functions.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function isAdmin()
{
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function redirectIfNotLoggedIn()
{
    if (!isLoggedIn()) {
        header('Location: ' . APP_URL . 'auth/login.php');
        exit();
    }
}

function redirectIfNotAdmin()
{
    if (!isAdmin()) {
        header('Location: ' . APP_URL . 'auth/login.php');
        exit();
    }
}
?>
