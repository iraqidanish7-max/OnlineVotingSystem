<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require __DIR__ . '/connection.php';

/* =======================
   CHECK ELECTION STATUS
======================= */
$electionStatus = 'draft';

$res = $conn->query("
    SELECT status
    FROM election_settings
    WHERE id = 1
    LIMIT 1
");

if ($res && $row = $res->fetch_assoc()) {
    $electionStatus = $row['status'];
}

if ($electionStatus !== 'published') {
    include 'header.php';
    ?>
    <div class="page-bg">
      <main class="container py-5">

        <div class="results-pending-wrapper">
          <section class="results-pending-card text-center">

            <div class="results-icon">🗳️</div>

            <h3 class="mt-3">Election in Progress</h3>

            <p class="text-muted mb-0">
              Voting is currently ongoing.<br>
              Results will be announced once the admin publishes the election.
            </p>

          </section>
        </div>

      </main>
    </div>
    <?php
    include 'footer.php';
    exit;
}

/* =======================
   FETCH TOP 2 CANDIDATES
======================= */
$top = [];
$res = $conn->query("
    SELECT name, party, photo, votes_count
    FROM candidates
    WHERE is_active = 1
    ORDER BY votes_count DESC
    LIMIT 2
");

while ($row = $res->fetch_assoc()) {
    $top[] = $row;
}

$winner = $top[0] ?? null;
$runnerUpVotes = $top[1]['votes_count'] ?? 0;
$margin = $winner ? ($winner['votes_count'] - $runnerUpVotes) : 0;
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Election Results</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>

<body>
<?php include 'header.php'; ?>

<div class="page-bg">
<main class="container py-5">

<?php if ($winner): ?>

<section class="card animated-border p-5">

  <div class="row align-items-center g-5">

    <!-- LEFT SIDE : PHOTO -->
    <div class="col-md-6 text-center">
      <img src="images/<?= htmlspecialchars($winner['photo']) ?>"
           class="rounded-circle"
           style="
             width:220px;
             height:220px;
             object-fit:cover;
             box-shadow:0 0 30px rgba(0,0,0,.3);
           ">
    </div>

    <!-- RIGHT SIDE : DETAILS -->
    <div class="col-md-6 text-center text-md-start">

      <!-- Crown -->
      <div class="mb-2" style="font-size:2.8rem;">👑</div>

      <!-- Name -->
      <h2 class="mb-1">
        <?= htmlspecialchars($winner['name']) ?>
      </h2>

      <!-- Party -->
      <h5 class="text-muted mb-4">
        <?= htmlspecialchars($winner['party']) ?>
      </h5>

      <!-- Stats -->
      <div class="row g-3 mb-3">

        <div class="col-6">
          <div class="stat-card">
            <div class="stat-label">Total Votes</div>
            <div class="stat-value"><?= $winner['votes_count'] ?></div>
          </div>
        </div>

        <div class="col-6">
          <div class="stat-card">
            <div class="stat-label">Winning Margin</div>
            <div class="stat-value">+<?= $margin ?></div>
          </div>
        </div>

      </div>

      <!-- Footer -->
      <p class="text-muted mb-0">
        Official Winner of the Election
      </p>

    </div>

  </div>

</section>
    <?php endif; ?>

</main>
</div>

<?php include 'footer.php'; ?>
</body>
</html>