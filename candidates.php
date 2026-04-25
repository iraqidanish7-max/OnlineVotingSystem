<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require __DIR__ . '/connection.php';
require __DIR__ . '/election_guard.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = (int)$_SESSION['user_id'];

/* CHECK VOTED */
$hasVoted = false;
$myCandidateId = null;

$stmt = $conn->prepare("SELECT candidate_id FROM votes WHERE voter_id=? LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 1) {
    $hasVoted = true;
    $myCandidateId = (int)$res->fetch_assoc()['candidate_id'];
}
$stmt->close();

/* CANDIDATES */
$candidates = [];
$res = $conn->query("SELECT id,name,party,manifesto,photo FROM candidates WHERE is_active=1 ORDER BY name");
while ($row = $res->fetch_assoc()) $candidates[] = $row;

$totalCandidates = count($candidates);
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Candidates</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
<?php include 'header.php'; ?>

<div class="page-bg">
<main class="container py-5">

<section class="card dashboard-hero-card mb-4">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
      <h2 class="hero-heading mb-1">Candidates in Election</h2>
      <p class="hero-subtitle mb-0">Review candidates and cast your vote carefully.</p>
    </div>
    <div class="hero-stats">
      <div class="stat-card">
        <div class="stat-label">Voting Status</div>
        <div class="stat-value"><?= $hasVoted ? 'Voted' : 'Not Voted' ?></div>
      </div>
      <div class="stat-card">
        <div class="stat-label">Candidates</div>
        <div class="stat-value"><?= $totalCandidates ?></div>
      </div>
    </div>
  </div>
</section>

<section class="candidate-grid">

<?php foreach ($candidates as $c): ?>
<div class="card animated-border p-3 candidate-card">
  <img src="images/<?= htmlspecialchars($c['photo']) ?>">

  <h5 class="mt-2 mb-0"><?= htmlspecialchars($c['name']) ?></h5>
  <small class="muted"><?= htmlspecialchars($c['party']) ?></small>

  <div class="candidate-manifesto">
    <?= nl2br(htmlspecialchars($c['manifesto'])) ?>
  </div>

 <div class="candidate-footer mt-3">

<?php if ($hasVoted): ?>

    <?php if ($myCandidateId === (int)$c['id']): ?>
        <button class="btn btn-success w-100" disabled>
            You Voted Here
        </button>
    <?php else: ?>
        <button class="btn btn-secondary w-100" disabled>
            Voting Locked
        </button>
    <?php endif; ?>

<?php else: ?>

    <?php if ($GLOBALS['ELECTION_STATUS'] === 'draft'): ?>
        <button class="btn btn-primary w-100 vote-btn"
                data-id="<?= $c['id'] ?>">
            Vote
        </button>
    <?php else: ?>
        <button class="btn btn-secondary w-100" disabled>
            Voting Closed
        </button>
    <?php endif; ?>

<?php endif; ?>

</div>
</div>
<?php endforeach; ?>

</section>
</main>
</div>

<!-- OTP MODAL -->
<div class="modal fade" id="otpModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">

<div class="modal-header">
<h5 class="modal-title">OTP Verification</h5>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body text-center">
<p>Enter the OTP to confirm your vote</p>

<div class="alert alert-info">
<strong>OTP:</strong> <span id="otpText"></span>
</div>

<input type="text" id="otpInput" class="form-control text-center" placeholder="Enter OTP">

<div id="otpError" class="text-danger mt-2 d-none">
Invalid OTP. Please try again.
</div>
</div>

<div class="modal-footer">
<button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
<button class="btn btn-primary" id="confirmVote">Confirm Vote</button>
</div>

</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script>
let selectedCandidate = null;
let modal = new bootstrap.Modal(document.getElementById('otpModal'));

document.querySelectorAll('.vote-btn').forEach(btn => {
  btn.onclick = () => {
    selectedCandidate = btn.dataset.id;

    fetch('vote.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: 'action=generate_otp&candidate_id=' + selectedCandidate
    })
    .then(res => res.json())
    .then(data => {
      document.getElementById('otpText').innerText = data.otp;
      document.getElementById('otpInput').value = '';
      document.getElementById('otpError').classList.add('d-none');
      modal.show();
    });
  };
});

document.getElementById('confirmVote').onclick = () => {
  let otp = document.getElementById('otpInput').value;

  fetch('vote.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'action=confirm_vote&candidate_id=' + selectedCandidate + '&otp=' + otp
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === 'success') {
      Swal.fire({
        icon: 'success',
        title: 'Vote Submitted',
        text: 'Your vote has been recorded successfully.',
        confirmButtonText: 'Go to Dashboard'
      }).then(() => window.location.href = 'dashboard.php');
    } else {
      document.getElementById('otpError').classList.remove('d-none');
    }
  });
};
</script>

</body>
</html>