<?php
// login_auth.php — Unified Authentication

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include __DIR__ . '/connection.php';

$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    header('Location: login.php?error=' . urlencode('Please enter email and password'));
    exit;
}

if (!isset($conn)) {
    die('Database connection unavailable.');
}

/* ---------- FETCH USER ---------- */
$stmt = $conn->prepare("
    SELECT id, name, email, password_hash, role
    FROM users
    WHERE email = ?
    LIMIT 1
");
$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();

if ($res && $res->num_rows === 1) {

    $user = $res->fetch_assoc();

    if (!password_verify($password, $user['password_hash'])) {
        header('Location: login.php?error=' . urlencode('Invalid credentials'));
        exit;
    }

    /* ---------- LOGIN SUCCESS ---------- */
    $_SESSION['user_id']    = $user['id'];
    $_SESSION['user_name']  = $user['name'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['role']       = $user['role'];

    header('Location: dashboard.php');
    exit;

} else {
    header('Location: login.php?error=' . urlencode('Account not found'));
    exit;
}