<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: ../dashboard.php');
    exit();
}

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../config/db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $errors[] = "Email and password are required.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Login successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'] ?? 'user';

            header('Location: ../dashboard.php');
            exit();
        } else {
            $errors[] = "Invalid email or password.";
        }
    }
}
?>

<?php include '../includes/header.php'; ?>

<h2>Login</h2>
<?php if (!empty($errors)): ?>
    <div>
        <?php foreach ($errors as $error): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="POST">
    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit">Login</button>
</form>

<p>Don't have an account? <a href="register.php">Register</a></p>

<?php include '../includes/footer.php'; ?>
