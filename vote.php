<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require __DIR__ . '/connection.php';
require __DIR__ . '/election_guard.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$action  = $_POST['action'] ?? '';

// 🚨 BLOCK VOTING ONLY IF ELECTION IS PUBLISHED (CLOSED)
if ($GLOBALS['ELECTION_STATUS'] === 'published') {
    echo json_encode([
        'status'  => 'closed',
        'message' => 'Voting is closed'
    ]);
    exit;
}

/* ================= GENERATE OTP ================= */
if ($action === 'generate_otp') {

    $candidate_id = (int)$_POST['candidate_id'];
    $otp = rand(100000, 999999);

    $_SESSION['vote_otp'] = (string)$otp;
    $_SESSION['vote_candidate'] = $candidate_id;

    echo json_encode(['otp' => $otp]);
    exit;
}

/* ================= CONFIRM VOTE ================= */
if ($action === 'confirm_vote') {

    $candidate_id = (int)$_POST['candidate_id'];
    $otp_input = trim($_POST['otp'] ?? '');

    if (
        empty($_SESSION['vote_otp']) ||
        $otp_input !== $_SESSION['vote_otp'] ||
        $candidate_id !== $_SESSION['vote_candidate']
    ) {
        echo json_encode(['status' => 'error']);
        exit;
    }

    $chk = $conn->prepare("SELECT id FROM votes WHERE voter_id=? LIMIT 1");
    $chk->bind_param("i", $user_id);
    $chk->execute();
    $chk->store_result();
    if ($chk->num_rows > 0) {
        echo json_encode(['status' => 'error']);
        exit;
    }
    $chk->close();

    $conn->begin_transaction();
    try {
        $ins = $conn->prepare(
            "INSERT INTO votes (voter_id, candidate_id) VALUES (?, ?)"
        );
        $ins->bind_param("ii", $user_id, $candidate_id);
        $ins->execute();
        $ins->close();

        $upd = $conn->prepare(
            "UPDATE candidates SET votes_count = votes_count + 1 WHERE id=?"
        );
        $upd->bind_param("i", $candidate_id);
        $upd->execute();
        $upd->close();

        $conn->commit();

        unset($_SESSION['vote_otp'], $_SESSION['vote_candidate']);

        echo json_encode(['status' => 'success']);
        exit;

    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['status' => 'error']);
        exit;
    }
}