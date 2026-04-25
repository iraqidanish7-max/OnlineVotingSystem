<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/connection.php';

$email = 'admin@ov.com'; // admin@onlinevoting.com
$password = 'admin123';

$hash = password_hash($password, PASSWORD_DEFAULT);

// Check if admin already exists
$check = $conn->prepare("SELECT id FROM admin WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    die('Admin already exists.');
}
$check->close();

// Insert admin
$stmt = $conn->prepare("
    INSERT INTO admin (email, password_hash)
    VALUES (?, ?)
");
$stmt->bind_param("ss", $email, $hash);

if ($stmt->execute()) {
    echo "Admin created successfully.<br>";
    echo "Email: admin@ov.com<br>";
    echo "Password: admin123";
} else {
    echo "Error creating admin.";
}

$stmt->close();
$conn->close();