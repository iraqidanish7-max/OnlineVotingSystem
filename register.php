<?php
// register.php — User Registration (Voter Only, FINAL)

ini_set('display_errors', 1);
error_reporting(E_ALL);

include __DIR__ . '/connection.php';

/* ---------- helper: show form ---------- */
function show_form($errors = [], $old = []) {
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Register — Online Voting System</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'header.php'; ?>

<main class="container my-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card p-4">
        <h3 class="mb-3">Create Account</h3>

        <?php if ($errors): ?>
          <div class="alert alert-danger">
            <?php foreach ($errors as $e): ?>
              <div><?php echo htmlspecialchars($e); ?></div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <form method="post" action="register.php" novalidate>

          <!-- Name -->
          <div class="mb-2">
            <label class="form-label">Full Name</label>
            <input class="form-control" name="name"
                   value="<?php echo htmlspecialchars($old['name'] ?? ''); ?>" required>
          </div>

          <!-- Email -->
          <div class="mb-2">
            <label class="form-label">Email</label>
            <input class="form-control" type="email" name="email"
                   value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>" required>
          </div>

          <!-- Phone -->
          <div class="mb-2">
            <label class="form-label">Phone</label>
            <input class="form-control" name="phone"
                   value="<?php echo htmlspecialchars($old['phone'] ?? ''); ?>" required>
          </div>

          <!-- Password -->
          <div class="mb-3">
            <label class="form-label" for="pwd">Password</label>
            <div class="pwd-wrap">
              <input id="pwd" class="form-control" type="password" name="password" required>

              <button type="button"
                      class="pwd-toggle-btn"
                      data-target="#pwd"
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

          <!-- 18+ Eligibility Checkbox -->
          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="eligible" name="eligible" required>
            <label class="form-check-label" for="eligible">
              I confirm that I am <strong>18 years or older</strong> and eligible to vote.
            </label>
          </div>

          <div class="d-flex gap-2 mt-3">
            <button class="btn btn-primary">Register</button>
            <a class="btn btn-outline-secondary" href="index.php">Cancel</a>
          </div>

        </form>
      </div>
    </div>
  </div>
</main>

<?php include 'footer.php'; ?>
</body>
</html>
<?php
}

/* ---------- GET ---------- */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    show_form();
    exit;
}

/* ---------- POST VALIDATION ---------- */
$name     = trim($_POST['name'] ?? '');
$email    = trim($_POST['email'] ?? '');
$phone    = trim($_POST['phone'] ?? '');
$password = $_POST['password'] ?? '';
$eligible = isset($_POST['eligible']);

$errors = [];

if ($name === '') $errors[] = 'Name is required.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
if ($phone === '') $errors[] = 'Phone number is required.';
if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
if (!$eligible) $errors[] = 'You must confirm eligibility to vote (18+).';

if ($errors) {
    show_form($errors, $_POST);
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);

/* ---------- INSERT USER ---------- */
try {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $stmt = $conn->prepare("
        INSERT INTO users (name, email, phone, password_hash, role)
        VALUES (?, ?, ?, ?, 'voter')
    ");
    $stmt->bind_param("ssss", $name, $email, $phone, $hash);
    $stmt->execute();
    $stmt->close();

    header("Location: login.php?prefill=" . urlencode($email) . "&just_registered=1");
    exit;

} catch (mysqli_sql_exception $e) {
    $msg = (str_contains($e->getMessage(), 'Duplicate'))
         ? 'An account with this email already exists.'
         : 'Registration failed. Please try again.';
    show_form([$msg], $_POST);
    exit;
}