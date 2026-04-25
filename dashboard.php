<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include __DIR__ . '/connection.php';

/* =======================
   SECURITY
======================= */
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id   = (int)$_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? 'User';

/* =======================
   FETCH USER
======================= */
$stmt = $conn->prepare("
    SELECT id, name, email, role
    FROM users
    WHERE id = ?
    LIMIT 1
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

/* =======================
   VOTING STATUS
======================= */
$voteStmt = $conn->prepare("
    SELECT candidate_id
    FROM votes
    WHERE voter_id = ?
    LIMIT 1
");
$voteStmt->bind_param("i", $user_id);
$voteStmt->execute();
$voteRes = $voteStmt->get_result();
$hasVoted = ($voteRes->num_rows === 1);
$myVotedCandidate = $hasVoted ? $voteRes->fetch_assoc()['candidate_id'] : null;
$voteStmt->close();

/* =======================
   VOTED CANDIDATE DETAILS
======================= */
$votedCandidateDetails = null;

if ($hasVoted && $myVotedCandidate) {
    $vcStmt = $conn->prepare("
        SELECT name, party
        FROM candidates
        WHERE id = ?
        LIMIT 1
    ");
    $vcStmt->bind_param("i", $myVotedCandidate);
    $vcStmt->execute();
    $votedCandidateDetails = $vcStmt->get_result()->fetch_assoc();
    $vcStmt->close();
}
/* =======================
   CANDIDATE STATUS
======================= */
$candidate = null;
$candidateApproved = false;

$cStmt = $conn->prepare("
    SELECT id, name, party, manifesto, photo, votes_count, is_active
    FROM candidates
    WHERE email = ?
    LIMIT 1
");
$cStmt->bind_param("s", $user['email']);
$cStmt->execute();
$cRes = $cStmt->get_result();

if ($cRes && $cRes->num_rows === 1) {
    $candidate = $cRes->fetch_assoc();
    $candidateApproved = ((int)$candidate['is_active'] === 1);
}
$cStmt->close();

/* =======================
   CANDIDATES (CAROUSEL)
======================= */
$candidates = [];
$res = $conn->query("
    SELECT id, name, party, photo
    FROM candidates
    WHERE is_active = 1
    ORDER BY created_at DESC
");
while ($row = $res->fetch_assoc()) {
    $candidates[] = $row;
}

/* =======================
   STATS (ANALYTICS)
======================= */
$totalVoters = $conn->query("SELECT COUNT(*) c FROM users")->fetch_assoc()['c'];
$votedCount  = $conn->query("SELECT COUNT(*) c FROM votes")->fetch_assoc()['c'];
$notVoted    = $totalVoters - $votedCount;
$myVotes     = $candidate ? (int)$candidate['votes_count'] : 0;
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>User Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>

<body>

<?php include 'header.php'; ?>

<div class="page-bg">
<main class="container py-5">
<?php if (isset($_GET['msg']) && $_GET['msg'] === 'candidate_applied'): ?>
  <div class="alert alert-success">
    Your candidate application was submitted successfully and is pending admin approval.
  </div>
<?php endif; ?>

  <!-- ================= HERO ================= -->
  <section class="card dashboard-hero-card mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
      <div>
        <p class="welcome-chip mb-1">Dashboard</p>
        <h2 class="hero-heading mb-1">Welcome, <?= htmlspecialchars($user_name) ?></h2>
        <p class="hero-subtitle mb-0">
          Your central control panel for voting & participation.
        </p>
      </div>

      <div class="hero-stats">
        <div class="stat-card">
          <div class="stat-label">Voting</div>
          <div class="stat-value"><?= $hasVoted ? 'Voted' : 'Not Voted' ?></div>
        </div>

        <div class="stat-card">
          <div class="stat-label">Candidate</div>
          <div class="stat-value">
            <?php
              if (!$candidate) echo 'No';
              elseif ($candidateApproved) echo 'Approved';
              else echo 'Pending';
            ?>
          </div>
        </div>

        <?php if ($candidateApproved): ?>
        <div class="stat-card">
          <div class="stat-label">My Votes</div>
          <div class="stat-value"><?= $myVotes ?></div>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- ================= MAIN ================= -->
  <section class="row g-4 mb-5">

    <!-- LEFT -->
    <div class="col-lg-8">
      <div class="card animated-border p-4">
        <h4 class="mb-3">Candidates in Election</h4>

        <div class="candidate-scroll-wrapper">
          <div class="candidate-scroll-track">
            <?php foreach ($candidates as $c): ?>
              <div class="candidate-mini-card">
                <img src="images/<?= htmlspecialchars($c['photo']) ?>">
                <h6><?= htmlspecialchars($c['name']) ?></h6>
                <small><?= htmlspecialchars($c['party']) ?></small>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="text-center mt-4">
          <a href="candidates.php" class="btn btn-primary px-5">Vote Now</a>
        </div>
      </div>
    </div>

    <!-- RIGHT -->
    <div class="col-lg-4">

      <div class="card p-4 mb-3">
        <h5>How Voting Works</h5>
        <ul class="small muted mb-0">
  <li>One user = one vote</li>
  <li>Votes cannot be changed</li>
  <li>Admin supervised</li>
  <li>Secure session-based voting</li>
  <li>Real-time vote counting</li>
  <li>Candidate approval by admin</li>
  <li>Fraud prevention checks</li>
  <li>System audit logs maintained</li>
  <li>User can be candidate too</li>
</ul>
      </div>

      <div class="card p-4">
        <h5>Apply as Candidate</h5>

        <?php if (!$candidate): ?>
          <a href="apply_candidate.php" class="btn btn-outline-primary w-100">Apply Now</a>
        <?php elseif (!$candidateApproved): ?>
          <button class="btn btn-secondary w-100" disabled>Approval Pending</button>
        <?php else: ?>
          <button class="btn btn-success w-100" disabled>You are a Candidate</button>
        <?php endif; ?>
      </div>

    </div>
  </section>


<?php if ($votedCandidateDetails): ?>
<section class="card animated-border p-4 mb-4">
  <div class="d-flex align-items-center gap-3 flex-wrap">

    <div style="
      font-size: 2rem;
      width: 52px;
      height: 52px;
      background: linear-gradient(135deg, #22c55e, #16a34a);
      color: #fff;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 0 15px rgba(34,197,94,0.4);
    ">
      ✓
    </div>

    <div>
      <h5 class="mb-1">Your Vote Has Been Recorded</h5>
      <p class="mb-0 text-muted">
        You voted for
        <strong><?= htmlspecialchars($votedCandidateDetails['name']) ?></strong>
        (<?= htmlspecialchars($votedCandidateDetails['party']) ?>)
      </p>
    </div>

  </div>
</section>
<?php endif; ?>
  <!-- ================= CANDIDATE SECTION ================= -->
  <?php if ($candidate): ?>
  <section class="mt-5">

    <div class="card animated-border p-4 mb-4">
      <div class="row g-4">
        <div class="col-md-4">
          <img src="images/<?= htmlspecialchars($candidate['photo']) ?>"
               class="img-fluid rounded">
          <h4 class="mt-3"><?= htmlspecialchars($candidate['name']) ?></h4>
          <p class="muted"><strong>Party:</strong> <?= htmlspecialchars($candidate['party']) ?></p>
        </div>

        <div class="col-md-8">
          <h5>Your Manifesto</h5>
          <p><?= nl2br(htmlspecialchars($candidate['manifesto'])) ?></p>
        </div>
      </div>
    </div>

    <?php if ($candidateApproved): ?>
    <div class="card animated-border p-4">
      <h4 class="mb-4">Election Analytics</h4>
      <div class="row">
        <div class="col-md-6"><canvas id="votePie"></canvas></div>
        <div class="col-md-6"><canvas id="voteBar"></canvas></div>
      </div>
    </div>
    <?php endif; ?>

  </section>
  <?php endif; ?>

</main>
</div>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?php if ($candidateApproved): ?>
<script>
new Chart(document.getElementById('votePie'), {
  type: 'doughnut',
  data: {
    labels: ['Votes Received', 'Remaining'],
    datasets: [{
      data: [<?= $myVotes ?>, <?= $totalVoters - $myVotes ?>],
      backgroundColor: ['#22c55e', '#e5e7eb']
    }]
  }
});

new Chart(document.getElementById('voteBar'), {
  type: 'bar',
  data: {
    labels: ['Total Users', 'Voted', 'Not Voted'],
    datasets: [{
      data: [<?= $totalVoters ?>, <?= $votedCount ?>, <?= $notVoted ?>],
      backgroundColor: ['#3b82f6', '#22c55e', '#ef4444']
    }]
  },
  options: { scales: { y: { beginAtZero: true } } }
});
</script>
<?php endif; ?>

</body>
</html>