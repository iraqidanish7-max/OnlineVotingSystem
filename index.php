<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Online Voting System — Home</title>

  <!-- Fonts & Bootstrap -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- App styles -->
  <link rel="stylesheet" href="style.css" />
</head>

<body>

<?php include 'header.php'; ?>

<!-- HERO SPLIT -->
<section class="container hero-split my-4">
  <div class="row align-items-center g-4">

    <!-- LEFT -->
    <div class="col-lg-6 order-2 order-lg-1">
      <div class="card p-4 mb-3">
        <h1 class="display-6 mb-2">Secure & Transparent Online Voting</h1>
        <p class="muted lead">
          A reliable digital voting platform ensuring fairness, transparency, and accessibility for every voter.
        </p>

        <div class="row g-2 mt-3">
          <div class="col-sm-6">
            <div class="feature-card p-3">
              <h6 class="mb-1">Voter Verification</h6>
              <p class="small muted mb-0">Verified voter identities ensure fair elections.</p>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="feature-card p-3">
              <h6 class="mb-1">Secure Voting</h6>
              <p class="small muted mb-0">Encrypted and tamper-proof voting process.</p>
            </div>
          </div>
        </div>

        <div class="mt-4 d-flex gap-2">
          <a class="btn btn-primary btn-lg" href="login.php">Vote Now</a>
          <a class="btn btn-outline-primary btn-lg" href="about.php">How it Works</a>
        </div>
      </div>

      <div class="card p-4">
        <h4 class="mb-2">Why Online Voting?</h4>
        <ul class="fa-list muted">
          <li><strong>Accessibility:</strong> Vote from anywhere securely.</li>
          <li><strong>Transparency:</strong> Clear and verifiable election process.</li>
          <li><strong>Efficiency:</strong> Faster results with reduced manual effort.</li>
        </ul>
      </div>
    </div>

    <!-- RIGHT (CAROUSEL) -->
    <div class="col-lg-6 order-1 order-lg-2">
      <div class="carousel-container card p-0">
        <div id="splitCarousel" class="carousel slide h-100" data-bs-ride="carousel" data-bs-interval="4000">
          <div class="carousel-inner h-100">
            <div class="carousel-item active h-100">
              <div class="carousel-bg" style="background-image:url('images/vote1.jpg')"></div>
              <div class="carousel-overlay"></div>
            </div>
            <div class="carousel-item h-100">
              <div class="carousel-bg" style="background-image:url('images/vote2.jpg')"></div>
              <div class="carousel-overlay"></div>
            </div>
            <div class="carousel-item h-100">
              <div class="carousel-bg" style="background-image:url('images/vote3.jpg')"></div>
              <div class="carousel-overlay"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- MAIN CONTENT -->
<main class="container my-5">
  <section>
    <h2 class="mb-3">How It Works</h2>
    <div class="row g-3">
      <div class="col-md-4">
        <div class="card min-h-140 p-3">
          <h5>Register as Voter</h5>
          <p class="small muted">Sign up and verify your identity to become an eligible voter.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card min-h-140 p-3">
          <h5>View Candidates</h5>
          <p class="small muted">Browse verified candidates contesting the election.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card min-h-140 p-3">
          <h5>Cast Your Vote</h5>
          <p class="small muted">Vote securely and track confirmation instantly.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="mt-5">
    <h2 class="mb-3">Key Features</h2>
    <div class="row g-3">
      <div class="col-md-6 col-lg-4">
        <div class="feature-card p-3">
          <h6>One Person, One Vote</h6>
          <p class="small muted">System ensures single valid vote per voter.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="feature-card p-3">
          <h6>Candidate Dashboard</h6>
          <p class="small muted">Candidates can view vote statistics securely.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="feature-card p-3">
          <h6>Admin Monitoring</h6>
          <p class="small muted">Admins manage voters, candidates, and results.</p>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include 'footer.php'; ?>
</body>
</html>