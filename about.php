<?php
// about.php — About page for Online Voting System
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>About Us — Online Voting System</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="page-bg">
  <main class="container py-5 about-page">

    <!-- HERO -->
    <section class="card p-4 mb-4 admin-hero-card">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div>
          <p class="welcome-chip mb-1">About</p>
          <h2 class="hero-heading mb-1">
            Online Voting System
          </h2>
          <p class="hero-subtitle mb-0">
            A secure, transparent and modern platform for conducting
            <strong>digital elections</strong>.
          </p>
        </div>
        <div class="text-md-end">
          <div class="small text-muted">Project Vision</div>
          <div class="fw-semibold">Democracy powered by technology.</div>
        </div>
      </div>
    </section>

    <!-- ZIGZAG CARDS -->
    <section class="about-cards-wrapper">

      <!-- Card 1 -->
      <div class="gradient-card-wrapper mb-4 about-animate slide-left zig-left">
        <div class="inner-card about-card p-0">
          <div class="row g-0 align-items-center">
            <div class="col-md-5">
              <img src="images/vote4.jpg" class="img-fluid rounded-start about-card-img" alt="Voting system">
            </div>
            <div class="col-md-7">
              <div class="card-body py-4 px-4">
                <h4 class="card-title mb-2">What is Online Voting System?</h4>
                <p class="card-text text-muted mb-2">
                  Online Voting System is a web-based platform designed to conduct elections
                  digitally while maintaining security, transparency and fairness.
                </p>
                <p class="card-text mb-0">
                  It allows voters to cast their vote online and enables candidates
                  to participate in elections in a structured manner.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 2 -->
      <div class="gradient-card-wrapper mb-4 about-animate slide-right zig-right">
        <div class="inner-card about-card p-0">
          <div class="row g-0 flex-md-row-reverse align-items-center">
            <div class="col-md-5">
              <img src="images/vote5.jpg" class="img-fluid rounded-start about-card-img" alt="Digital democracy">
            </div>
            <div class="col-md-7">
              <div class="card-body py-4 px-4">
                <h4 class="card-title mb-2">Why Online Voting?</h4>
                <p class="card-text text-muted mb-2">
                  Traditional voting systems require physical presence and large manpower.
                </p>
                <p class="card-text mb-0">
                  This project demonstrates how elections can be conducted digitally
                  with reduced cost, faster results and improved accessibility.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 3 -->
      <div class="gradient-card-wrapper mb-4 about-animate slide-left zig-left">
        <div class="inner-card about-card p-0">
          <div class="row g-0 align-items-center">
            <div class="col-md-5">
              <img src="images/vote6.jpg" class="img-fluid rounded-start about-card-img" alt="Voter process">
            </div>
            <div class="col-md-7">
              <div class="card-body py-4 px-4">
                <h4 class="card-title mb-2">How Voting Works</h4>
                <ul class="card-text text-muted small mb-0">
                  <li>Register as a <strong>Voter</strong></li>
                  <li>Login securely using credentials</li>
                  <li>View all active candidates</li>
                  <li>Cast your vote (only once)</li>
                  <li>Your vote is stored securely in database</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 4 -->
      <div class="gradient-card-wrapper mb-4 about-animate slide-right zig-right">
        <div class="inner-card about-card p-0">
          <div class="row g-0 flex-md-row-reverse align-items-center">
            <div class="col-md-5">
              <img src="images/vote7.jpg" class="img-fluid rounded-start about-card-img" alt="Candidate">
            </div>
            <div class="col-md-7">
              <div class="card-body py-4 px-4">
                <h4 class="card-title mb-2">Candidate Participation</h4>
                <ul class="card-text text-muted small mb-0">
                  <li>Register as a <strong>Candidate</strong></li>
                  <li>Provide party name and manifesto</li>
                  <li>Upload candidate photo</li>
                  <li>View votes received on dashboard</li>
                  <li>Participate fairly in election</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 5 -->
      <div class="gradient-card-wrapper mb-4 about-animate slide-left zig-left">
        <div class="inner-card about-card p-0">
          <div class="row g-0 align-items-center">
            <div class="col-md-5">
              <img src="images/vote8.jpg" class="img-fluid rounded-start about-card-img" alt="Admin panel">
            </div>
            <div class="col-md-7">
              <div class="card-body py-4 px-4">
                <h4 class="card-title mb-2">Admin Control</h4>
                <ul class="card-text text-muted small mb-0">
                  <li>Monitor registered voters and candidates</li>
                  <li>Activate or deactivate candidates</li>
                  <li>View voting statistics</li>
                  <li>Ensure election integrity</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 6 -->
      <div class="gradient-card-wrapper mb-4 about-animate slide-right zig-right">
        <div class="inner-card about-card p-0">
          <div class="row g-0 flex-md-row-reverse align-items-center">
            <div class="col-md-5">
              <img src="images/vote9.jpg" class="img-fluid rounded-start about-card-img" alt="Security">
            </div>
            <div class="col-md-7">
              <div class="card-body py-4 px-4">
                <h4 class="card-title mb-2">Security & Integrity</h4>
                <ul class="card-text text-muted small mb-0">
                  <li>One voter — one vote rule</li>
                  <li>Session-based authentication</li>
                  <li>Password hashing</li>
                  <li>Database-level protections</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 7 -->
      <div class="gradient-card-wrapper mb-4 about-animate slide-left zig-left">
        <div class="inner-card about-card p-0">
          <div class="row g-0 align-items-center">
            <div class="col-md-6">
              <div class="card-body py-4 px-4">
                <h4 class="card-title mb-2">Project Objective</h4>
                <p class="card-text text-muted small mb-0">
                  This project is developed as an academic demonstration of how
                  web technologies can support democratic systems
                  in a secure and user-friendly manner.
                </p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="ratio ratio-16x9 about-video-wrapper">
              <iframe 
  src="https://www.youtube.com/embed/wwdrGBjSzIw" 
  allowfullscreen>
</iframe>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Card 8 -->
      <div class="gradient-card-wrapper mb-4 about-animate slide-right zig-right">
        <div class="inner-card about-card p-0">
          <div class="row g-0 flex-md-row-reverse align-items-center">
            <div class="col-md-6">
              <div class="card-body py-4 px-4">
                <h4 class="card-title mb-2">Our Institution</h4>
                <p class="card-text text-muted small mb-0">
                  This project is developed as part of academic curriculum
                  and reflects practical implementation of PHP, MySQL,
                  sessions and secure authentication.
                </p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="ratio ratio-4x3 about-map-wrapper">
                <iframe
                  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5547.534843146895!2d72.80039803595852!3d19.416511992825598!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be7abe2f6aaaaab%3A0xfb2503d0748cb985!2sM.B%20HARRIS%20COLLEGE%20OF%20ARTS!5e0!3m2!1sen!2sin"
                  style="border:0;"
                  allowfullscreen
                  loading="lazy">
                </iframe>
              </div>
            </div>
          </div>
        </div>
      </div>

    </section>

  </main>
</div>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const cards = document.querySelectorAll('.about-animate');
  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('in-view');
      }
    });
  }, { threshold: 0.2 });

  cards.forEach(card => observer.observe(card));
});
</script>

</body>
</html>