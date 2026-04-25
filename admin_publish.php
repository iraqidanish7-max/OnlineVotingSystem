<?php
session_start();
require __DIR__ . '/connection.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: adminLogin.php');
    exit;
}

$conn->query("
  UPDATE election_settings
  SET status = 'published',
      published_at = NOW()
  WHERE id = 1
");

header('Location: admin_dashboard.php?success=election_published');
exit;