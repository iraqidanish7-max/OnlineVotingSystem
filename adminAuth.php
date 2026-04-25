<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require __DIR__ . '/connection.php';

$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    header('Location: adminLogin.php?error=Missing credentials');
    exit;
}

$stmt = $conn->prepare("
    SELECT id, email, password_hash
    FROM admin
    WHERE email = ?
    LIMIT 1
");
$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();

if ($res && $res->num_rows === 1) {

    $admin = $res->fetch_assoc();

    if (password_verify($password, $admin['password_hash'])) {

        $_SESSION['admin_id']    = $admin['id'];
        $_SESSION['admin_email'] = $admin['email'];
        $_SESSION['role']        = 'admin';

        header('Location: admin_dashboard.php');
        exit;
    }
}

header('Location: adminLogin.php?error=Invalid email or password');
exit;