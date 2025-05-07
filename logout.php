<?php
// Start the session
session_start();

// Unset session variables for both admin and employee
if (isset($_SESSION['loggedin'])) {
    unset($_SESSION['loggedin']);
    unset($_SESSION['user_id']);
    unset($_SESSION['username']);
    unset($_SESSION['role']);
}

// Destroy the session completely
session_unset();
session_destroy();

// Prevent back button from accessing previous pages
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

// Redirect to login page immediately using JavaScript
echo '<script>
    localStorage.clear(); // Clear browser storage
    sessionStorage.clear();
    window.location.href = "login.php"; // Redirect to login page
</script>';
exit();
?>
