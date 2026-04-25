<?php
// footer.php
// Global footer for all pages
?>

<footer class="site-footer bg-dark text-light py-4">
  <!-- decorative wave -->
  <svg class="footer-wave" viewBox="0 0 1440 120" preserveAspectRatio="none" aria-hidden="true">
    <path d="M0,32L48,42.7C96,53,192,75,288,96C384,117,480,139,576,128C672,117,768,71,864,42.7C960,13,1056,3,1152,13.3C1248,24,1344,56,1392,72L1440,88L1440,120L1392,120C1344,120,1248,120,1152,120C1056,120,960,120,864,120C768,120,672,120,576,120C480,120,384,120,288,120C192,120,96,120,48,120L0,120Z"
          fill="url(#g)" fill-opacity="0.12"></path>
    <defs>
      <linearGradient id="g" x1="0" x2="1">
        <stop offset="0%" stop-color="#6a11cb"/>
        <stop offset="100%" stop-color="#2575fc"/>
      </linearGradient>
    </defs>
  </svg>

  <div class="container d-flex justify-content-between align-items-center">
    <div>
      © <strong>Online Voting System</strong> — <?php echo date('Y'); ?>
    </div>
    <div class="muted small">
      Secure • Transparent • Reliable Elections
    </div>
  </div>
</footer>

<!-- ================= JS STARTS HERE ================= -->

<!-- Bootstrap JS (required for carousel etc.) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<!-- Active page underline -->
<script>
(function () {
  const path = location.pathname.split('/').pop() || 'index.php';

  document.querySelectorAll('nav .nav-link').forEach(link => {
    const href = link.getAttribute('href');
    if (href === path) {
      link.classList.add('active-link');
    } else {
      link.classList.remove('active-link');
    }
  });
})();
</script>
<script>
(function () {
  // Find all password toggle buttons
  document.querySelectorAll('.pwd-toggle-btn').forEach(function (btn) {

    btn.addEventListener('click', function () {
      const targetSelector = btn.getAttribute('data-target');
      if (!targetSelector) return;

      const input = document.querySelector(targetSelector);
      if (!input) return;

      const isPassword = input.type === 'password';

      // Toggle input type
      input.type = isPassword ? 'text' : 'password';

      // Accessibility updates
      btn.setAttribute('aria-pressed', String(isPassword));
      btn.setAttribute(
        'aria-label',
        isPassword ? 'Hide password' : 'Show password'
      );

      // Swap eye icons
      const eyeOpen = btn.querySelector('.eye-open');
      const eyeClosed = btn.querySelector('.eye-closed');

      if (eyeOpen && eyeClosed) {
        eyeOpen.style.display = isPassword ? 'none' : '';
        eyeClosed.style.display = isPassword ? '' : 'none';
      }
    });

  });
})();
</script>