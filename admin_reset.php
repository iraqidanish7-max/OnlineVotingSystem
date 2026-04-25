<?php
session_start();
require __DIR__ . '/connection.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: adminLogin.php');
    exit;
}

$conn->begin_transaction();

try {

    // Reset election status
    $conn->query("
      UPDATE election_settings
      SET status = 'draft',
          published_at = NULL
      WHERE id = 1
    ");

    // Clear votes
    $conn->query("TRUNCATE TABLE votes");

    // Reset vote counts
    $conn->query("UPDATE candidates SET votes_count = 0");

    $conn->commit();

} catch (Exception $e) {
    $conn->rollback();
}

header('Location: admin_dashboard.php?success=election_reset');
exit;