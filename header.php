<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Online Voting System</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/online_voting/style.css">
</head>

<body>

<header class="site-header">
  <div class="container d-flex align-items-center justify-content-between py-3">

    <!-- BRAND -->
    <a class="brand" href="index.php">
      Online<span class="accent">Voting</span>System
    </a>

    <!-- NAV LINKS -->
    <?php if (empty($_SESSION['admin_id'])): ?>
    <nav class="d-none d-md-flex gap-3 align-items-center">

      <a class="nav-link <?= $currentPage === 'index.php' ? 'active' : '' ?>"
         href="index.php">Home</a>

      <a class="nav-link <?= $currentPage === 'results.php' ? 'active' : '' ?>"
         href="results.php">Results</a>

      <a class="nav-link <?= $currentPage === 'about.php' ? 'active' : '' ?>"
         href="about.php">About</a>

      <?php if (empty($_SESSION['user_id'])): ?>
        <a class="nav-link <?= $currentPage === 'adminLogin.php' ? 'active' : '' ?>"
           href="adminLogin.php">Admin</a>
      <?php endif; ?>

    </nav>
    <?php endif; ?>

    <!-- RIGHT ACTION BUTTONS -->
    <div class="d-flex align-items-center gap-2">

      <?php if (!empty($_SESSION['admin_id'])): ?>
        <a class="btn btn-secondary" href="admin_dashboard.php">Dashboard</a>
        <a class="btn btn-danger" href="logout.php">Logout</a>

      <?php elseif (!empty($_SESSION['user_id'])): ?>
        <a class="btn btn-secondary" href="dashboard.php">Dashboard</a>
        <a class="btn btn-danger" href="logout.php">Logout</a>

      <?php else: ?>
        <a class="btn btn-primary" href="login.php">Login</a>
        <a class="btn btn-primary" href="register.php">Register</a>
      <?php endif; ?>

      <button id="mobileMenuBtn" class="nav-toggle d-md-none">Menu</button>
    </div>

  </div>
</header>