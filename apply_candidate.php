<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include __DIR__ . '/connection.php';

/* ================= SECURITY ================= */
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = (int)$_SESSION['user_id'];

/* ================= FETCH USER ================= */
$stmt = $conn->prepare("
    SELECT name, email
    FROM users
    WHERE id = ?
    LIMIT 1
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user) {
    die('User not found.');
}

/* ================= CHECK IF ALREADY APPLIED ================= */
$chk = $conn->prepare("
    SELECT id, is_active
    FROM candidates
    WHERE user_id = ?
    LIMIT 1
");
$chk->bind_param("i", $user_id);
$chk->execute();
$res = $chk->get_result();

if ($res->num_rows === 1) {
    $row = $res->fetch_assoc();
    $chk->close();

    if ((int)$row['is_active'] === 1) {
        header("Location: dashboard.php?msg=already_candidate");
        exit;
    } else {
        header("Location: dashboard.php?msg=approval_pending");
        exit;
    }
}
$chk->close();

/* ================= HANDLE SUBMIT ================= */
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $party     = trim($_POST['party'] ?? '');
    $manifesto = trim($_POST['manifesto'] ?? '');

    if ($party === '' || $manifesto === '') {
        $error = "All fields are required.";
    } elseif (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== 0) {
        $error = "Candidate photo is required.";
    } else {

        /* ---------- IMAGE UPLOAD ---------- */
        $imgName = 'candidate_' . time() . '_' . basename($_FILES['photo']['name']);
        $target  = __DIR__ . '/images/' . $imgName;

        $ext = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg','jpeg','png'])) {
            $error = "Only JPG, JPEG, PNG allowed.";
        } elseif (!move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
            $error = "Image upload failed.";
        } else {

            /* ---------- INSERT CANDIDATE ---------- */
            $dummyPassword = password_hash(uniqid(), PASSWORD_DEFAULT);

            $ins = $conn->prepare("
                INSERT INTO candidates
                (user_id, name, email, password, party, manifesto, photo, votes_count, is_active)
                VALUES (?, ?, ?, ?, ?, ?, ?, 0, 0)
            ");
            $ins->bind_param(
                "issssss",
                $user_id,
                $user['name'],
                $user['email'],
                $dummyPassword,
                $party,
                $manifesto,
                $imgName
            );

            if ($ins->execute()) {
    header("Location: dashboard.php?msg=candidate_applied");
    exit;
} else {
                $error = "Something went wrong. Try again.";
            }
            $ins->close();
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Apply as Candidate</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>

<body>
<?php include 'header.php'; ?>

<div class="page-bg">
<main class="container py-5">

  <!-- HERO -->
  <section class="card dashboard-hero-card mb-4">
    <h2 class="hero-heading mb-1">Apply as Candidate</h2>
    <p class="hero-subtitle mb-0">
      Submit your profile for election approval.
    </p>
  </section>

  <!-- FORM CARD -->
  <div class="card animated-border p-4 mx-auto" style="max-width: 700px;">

    <?php if ($success): ?>
      <div class="alert alert-success"><?= $success ?></div>
    <?php elseif ($error): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">

      <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" disabled>
      </div>

      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" disabled>
      </div>

      <div class="mb-3">
        <label class="form-label">Party Name</label>
        <input type="text" name="party" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Manifesto</label>
        <textarea name="manifesto" rows="6" class="form-control" required></textarea>
      </div>

      <div class="mb-4">
        <label class="form-label">Candidate Photo</label>
        <input type="file" name="photo" class="form-control" accept="image/*" required>
      </div>

      <button type="submit" class="btn btn-primary w-100">
        Submit Application
      </button>

    </form>

  </div>

</main>
</div>

<?php include 'footer.php'; ?>
</body>
</html>