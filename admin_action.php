<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require __DIR__ . '/connection.php';

/* =========================
   ADMIN SECURITY CHECK
========================= */
if (
    !isset($_SESSION['admin_id']) ||
    !isset($_SESSION['admin_email'])
) {
    header('Location: adminLogin.php');
    exit;
}

/* =========================
   VALIDATE INPUT
========================= */
$candidate_id = isset($_POST['candidate_id']) ? (int) $_POST['candidate_id'] : 0;
$action       = $_POST['action'] ?? '';

if ($candidate_id <= 0 || !in_array($action, ['approve', 'reject'], true)) {
    header('Location: admin_dashboard.php?error=invalid_request');
    exit;
}

/* =========================
   FETCH CANDIDATE
========================= */
$stmt = $conn->prepare("
    SELECT id, is_active
    FROM candidates
    WHERE id = ?
    LIMIT 1
");
$stmt->bind_param("i", $candidate_id);
$stmt->execute();
$res = $stmt->get_result();

if (!$res || $res->num_rows !== 1) {
    header('Location: admin_dashboard.php?error=candidate_not_found');
    exit;
}

$candidate = $res->fetch_assoc();
$stmt->close();

/* =========================
   FINAL STATUS RULES
========================= */
$currentStatus = (int) $candidate['is_active'];

/*
  STATUS MEANING
  0  = Pending
  1  = Approved
 -1  = Rejected (FINAL)
*/

// If already rejected → BLOCK forever
if ($currentStatus === -1) {
    header('Location: admin_dashboard.php?error=already_rejected');
    exit;
}

// If already approved → no action
if ($currentStatus === 1) {
    header('Location: admin_dashboard.php?error=already_approved');
    exit;
}

/* =========================
   PERFORM ACTION
========================= */
if ($action === 'approve') {
    $newStatus = 1;
}

if ($action === 'reject') {
    $newStatus = -1;
}

$update = $conn->prepare("
    UPDATE candidates
    SET is_active = ?
    WHERE id = ?
");
$update->bind_param("ii", $newStatus, $candidate_id);

if ($update->execute()) {
    $update->close();

    header(
        'Location: admin_dashboard.php?success=' .
        ($action === 'approve' ? 'candidate_approved' : 'candidate_rejected')
    );
    exit;
}

$update->close();
header('Location: admin_dashboard.php?error=action_failed');
exit;