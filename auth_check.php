<?php
// Start session only if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo '<script>
        localStorage.clear();
        sessionStorage.clear();
        history.pushState(null, null, "login.php"); // Prevents back button
        window.location.replace("login.php"); // Redirects to login page
    </script>';
    exit();
}

// Prevent page caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

// Optional: Restrict access by role (only admins can access admin pages)
if ($_SESSION['role'] !== 'admin' && basename($_SERVER['PHP_SELF']) === 'admin_page.php') {
    echo '<script>
        alert("Access denied! Admins only.");
        window.location.href = "dashboard.php"; // Redirect employees to their dashboard
    </script>';
    exit();
}
?>
