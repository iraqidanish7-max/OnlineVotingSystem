<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include __DIR__ . '/connection.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("
        SELECT id, email, password_hash
        FROM admin
        WHERE email = ?
        LIMIT 1
    ");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res && $res->num_rows === 1) {
        $admin = $res->fetch_assoc();

        if (password_verify($password, $admin['password_hash'])) {
            $_SESSION['admin_id']    = $admin['id'];
            $_SESSION['admin_email'] = $admin['email'];
            $_SESSION['role']        = 'admin';

            header("Location: admin_dashboard.php");
            exit;
        }
    }

    $error = "Invalid admin email or password";
}
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Admin Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>

<body class="d-flex flex-column min-vh-100">

<?php include 'header.php'; ?>

<div class="page-bg flex-grow-1 d-flex align-items-center">
<main class="container py-5">

  <div class="row justify-content-center">
    <div class="col-md-5">

      <div class="card animated-border p-4">
        <h3 class="text-center mb-4">Admin Login</h3>

        <?php if ($error): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post">

          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email"
                   name="email"
                   class="form-control"
                   required>
          </div>

          <!-- Password -->
<div class="mb-3">
  <label class="form-label" for="adminPwd">Password</label>

  <div class="pwd-wrap">
    <input id="adminPwd"
           class="form-control"
           type="password"
           name="password"
           required>

    <button type="button"
            class="pwd-toggle-btn"
            data-target="#adminPwd"
            aria-label="Show password">

      <!-- Eye open -->
      <svg class="eye-open" viewBox="0 0 24 24" fill="none">
        <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"
              stroke="currentColor" stroke-width="1.4"/>
        <circle cx="12" cy="12" r="3"
                stroke="currentColor" stroke-width="1.4"/>
      </svg>

      <!-- Eye closed -->
      <svg class="eye-closed" viewBox="0 0 24 24" fill="none" style="display:none;">
        <path d="M17.94 17.94A10.94 10.94 0 0 1 12 19c-7 0-11-7-11-7"
              stroke="currentColor" stroke-width="1.4"/>
        <path d="M1 1l22 22"
              stroke="currentColor" stroke-width="1.4"/>
      </svg>

    </button>
  </div>
</div>
          <button class="btn btn-primary w-100">
            Login
          </button>

        </form>
      </div>

    </div>
  </div>

</main>
</div>

<?php include 'footer.php'; ?>

<script>
function toggleAdminPassword() {
  const input = document.getElementById('adminPassword');
  input.type = (input.type === 'password') ? 'text' : 'password';
}
</script>

</body>
</html>