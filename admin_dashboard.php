<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require __DIR__ . '/connection.php';

/* =======================
   ADMIN SECURITY
======================= */
if (!isset($_SESSION['admin_id'])) {
    header('Location: adminLogin.php');
    exit;
}
$election = $conn->query("SELECT status, published_at FROM election_settings WHERE id = 1")
                 ->fetch_assoc();
$isPublished = ($election['status'] === 'published');
/* =======================
   USER STATS
======================= */
$totalUsers = $conn->query("SELECT COUNT(*) c FROM users")->fetch_assoc()['c'];
$votedUsers = $conn->query("SELECT COUNT(*) c FROM votes")->fetch_assoc()['c'];
$notVoted   = $totalUsers - $votedUsers;

/* =======================
   CANDIDATE STATS
======================= */
$totalCandidates = $conn->query("SELECT COUNT(*) c FROM candidates")->fetch_assoc()['c'];
$approvedCount   = $conn->query("SELECT COUNT(*) c FROM candidates WHERE is_active = 1")->fetch_assoc()['c'];
$pendingCount    = $conn->query("SELECT COUNT(*) c FROM candidates WHERE is_active = 0")->fetch_assoc()['c'];
$rejectedCount   = $conn->query("SELECT COUNT(*) c FROM candidates WHERE is_active = -1")->fetch_assoc()['c'];

/* =======================
   USERS LIST (READ ONLY)
======================= */
$users = $conn->query("
    SELECT u.name, u.email, u.phone,
           IF(v.id IS NULL, 'Not Voted', 'Voted') AS vote_status
    FROM users u
    LEFT JOIN votes v ON v.voter_id = u.id
    ORDER BY u.created_at DESC
");

/* =======================
   CANDIDATE REQUESTS
======================= */
$candidates = $conn->query("
    SELECT id, name, email, party, is_active
    FROM candidates
    ORDER BY created_at DESC
");

/* =======================
   PARTY ANALYTICS
======================= */
$partyData = [];
$res = $conn->query("
    SELECT party, SUM(votes_count) AS votes
    FROM candidates
    WHERE is_active = 1
    GROUP BY party
");
while ($row = $res->fetch_assoc()) {
    $partyData[] = $row;
}
/* =======================
   PARTY CANDIDATE COUNT
======================= */
$partyCandidates = [];
$res2 = $conn->query("
    SELECT party, COUNT(*) AS total
    FROM candidates
    WHERE is_active = 1
    GROUP BY party
");
while ($row = $res2->fetch_assoc()) {
    $partyCandidates[] = $row;
}
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Admin Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>

<body>
<?php include 'header.php'; ?>

<div class="page-bg">
<main class="container py-5">

<!-- ================= ADMIN HERO ================= -->
<section class="card dashboard-hero-card mb-4 p-4">
  <div class="row align-items-center g-4">

    <!-- LEFT : WELCOME -->
    <div class="col-md-5">
      <p class="welcome-chip mb-1">Admin Panel</p>
      <h2 class="hero-heading mb-1">Welcome, Admin</h2>
      <p class="hero-subtitle mb-0">
        This is your central control panel to manage users, candidates,
        approvals, and election analytics.
      </p>
    </div>

    <!-- RIGHT : STATS -->
    <div class="col-md-7">
      <div class="row g-3">

        <!-- USERS CARD -->
        <div class="col-md-6">
          <div class="stat-card h-100">
            <div class="stat-label">Users</div>
            <div class="stat-value"><?= $totalUsers ?></div>
            <div class="stat-sub">
              Voted: <?= $votedUsers ?> |
              Pending: <?= $notVoted ?>
            </div>
          </div>
        </div>

        <!-- CANDIDATES CARD -->
        <div class="col-md-6">
          <div class="stat-card h-100">
            <div class="stat-label">Candidates</div>
            <div class="stat-value"><?= $totalCandidates ?></div>
            <div class="stat-sub">
              Approved: <?= $approvedCount ?> |
              Pending: <?= $pendingCount ?> |
              Rejected: <?= $rejectedCount ?>
            </div>
          </div>
        </div>

      </div>
    </div>

  </div>
</section>
<!-- ================= ANALYTICS ================= -->
<section class="card animated-border p-4 mb-5">
  <h4 class="mb-4">Election Analytics</h4>

  <div class="row g-4">

    <!-- LEFT : PARTY DISTRIBUTION -->
    <div class="col-md-6">
      <h6 class="text-center mb-3">Party Participation</h6>
      <canvas id="partyPie"></canvas>
    </div>

    <!-- RIGHT : PARTY PERFORMANCE -->
    <div class="col-md-6">
      <h6 class="text-center mb-3">Party-wise Votes</h6>
      <canvas id="partyBar"></canvas>
    </div>

  </div>
</section>

<!-- ================= USERS TABLE ================= -->
<section class="card p-4 mb-5">
  <h4 class="mb-3">Registered Users</h4>

  <div class="table-responsive">
    <table class="table table-hover admin-users-table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Voting Status</th>
        </tr>
      </thead>

      <tbody>
        <?php while ($u = $users->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($u['name']) ?></td>
          <td><?= htmlspecialchars($u['email']) ?></td>
          <td><?= htmlspecialchars($u['phone']) ?></td>

          <td>
            <?php if ($u['vote_status'] === 'Voted'): ?>
              <span class="status-pill voted">Voted</span>
            <?php else: ?>
              <span class="status-pill not-voted">Not Voted</span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</section>
<!-- ================= CANDIDATES ================= -->
<section class="card p-4 mb-5">
  <h4 class="mb-3">Candidates</h4>

  <div class="table-responsive">
    <table class="table table-hover admin-candidate-table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Party</th>
          <th>Votes</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>

      <tbody>
      <?php while ($c = $candidates->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($c['name']) ?></td>
          <td><?= htmlspecialchars($c['email']) ?></td>
          <td><?= htmlspecialchars($c['party']) ?></td>

          <!-- VOTES -->
          <td>
            <?php if ($c['is_active'] == 1): ?>
              <?= (int)($c['votes_count'] ?? 0) ?>
            <?php else: ?>
              —
            <?php endif; ?>
          </td>

          <!-- STATUS -->
          <td>
            <?php
              if ($c['is_active'] == 1) {
                echo '<span class="status-pill approved">Approved</span>';
              } elseif ($c['is_active'] == -1) {
                echo '<span class="status-pill rejected">Rejected</span>';
              } else {
                echo '<span class="status-pill pending">Pending</span>';
              }
            ?>
          </td>

          <!-- ACTION -->
          <td>
  <form method="post" action="admin_action.php" class="d-inline">
    <input type="hidden" name="candidate_id" value="<?= $c['id'] ?>">
    <input type="hidden" name="action" value="approve">

    <button
      class="btn btn-success btn-sm <?= $c['is_active'] != 0 ? 'disabled-btn' : '' ?>"
      <?= $c['is_active'] != 0 ? 'disabled' : '' ?>>
      Approve
    </button>
  </form>

  <form method="post" action="admin_action.php" class="d-inline">
    <input type="hidden" name="candidate_id" value="<?= $c['id'] ?>">
    <input type="hidden" name="action" value="reject">

    <button
      class="btn btn-danger btn-sm <?= $c['is_active'] != 0 ? 'disabled-btn' : '' ?>"
      <?= $c['is_active'] != 0 ? 'disabled' : '' ?>>
      Reject
    </button>
  </form>
</td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</section>
<!-- ================= ELECTION CONTROL ================= -->
<section class="card animated-border p-4 mb-5">
  <h4 class="mb-3">Election Control</h4>

  <div class="row g-3 align-items-center">

    <div class="col-md-6">
      <p class="mb-1"><strong>Current Status:</strong>
        <?php if ($isPublished): ?>
          <span class="badge bg-danger">Published (Voting Closed)</span>
        <?php else: ?>
          <span class="badge bg-success">Draft (Voting Open)</span>
        <?php endif; ?>
      </p>

      <?php if ($election['published_at']): ?>
        <small class="text-muted">
          Published at: <?= htmlspecialchars($election['published_at']) ?>
        </small>
      <?php endif; ?>
    </div>

    <div class="col-md-6 text-md-end">

      <?php if (!$isPublished): ?>
        <form action="admin_publish.php" method="post" class="d-inline">
          <button class="btn btn-danger"
                  onclick="return confirm('Publishing will STOP voting. Continue?')">
            Publish Election
          </button>
        </form>
      <?php endif; ?>

      <form action="admin_reset.php" method="post" class="d-inline ms-2">
        <button class="btn btn-secondary"
                onclick="return confirm('This will RESET all votes. Continue?')">
          Reset Election
        </button>
      </form>

    </div>

  </div>
</section>

</main>
</div>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const partyLabels = <?= json_encode(array_column($partyData, 'party')) ?>;
const partyVotes  = <?= json_encode(array_column($partyData, 'votes')) ?>;

const partyCountLabels = <?= json_encode(array_column($partyCandidates, 'party')) ?>;
const partyCounts      = <?= json_encode(array_column($partyCandidates, 'total')) ?>;

/* ===== PIE CHART : PARTY PARTICIPATION ===== */
new Chart(document.getElementById('partyPie'), {
  type: 'pie',
  data: {
    labels: partyCountLabels,
    datasets: [{
      data: partyCounts,
      backgroundColor: [
        '#3b82f6', '#22c55e', '#f59e0b',
        '#ef4444', '#8b5cf6', '#14b8a6'
      ]
    }]
  }
});

/* ===== BAR CHART : PARTY PERFORMANCE ===== */
new Chart(document.getElementById('partyBar'), {
  type: 'bar',
  data: {
    labels: partyLabels,
    datasets: [{
      label: 'Votes',
      data: partyVotes,
      backgroundColor: '#2563eb'
    }]
  },
  options: {
    scales: {
      y: {
        beginAtZero: true,
        ticks: { stepSize: 1 }
      }
    }
  }
});
</script>
</body>
</html>