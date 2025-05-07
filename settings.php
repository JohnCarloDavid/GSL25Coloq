<?php
session_start();
include 'db_connection.php'; // DB connection file mo
include('auth_check.php'); // Para makapasok lang ang logged in

$message = ""; // Message para sa success or error
$default_password = 'admin'; // Default password para sa admin

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if nag-update siya ng sarili niyang password
    if (isset($_POST['current_password']) && isset($_POST['new_password']) && isset($_POST['username'])) {
        $username = trim($_POST['username']);
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];

        // Fetch current hashed password
        $stmt = $conn->prepare("SELECT password FROM tb_admin WHERE user_name = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($hashed_password);
        $stmt->fetch();
        $stmt->close();

        if ($hashed_password !== null) {
            if (password_verify($current_password, $hashed_password) || $current_password === $default_password) {
                if (strlen($new_password) < 8) {
                    $message = "<div class='bg-red-500 text-white p-3 mb-4'>New password must be at least 8 characters long.</div>";
                } else {
                    $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $update_stmt = $conn->prepare("UPDATE tb_admin SET password = ? WHERE user_name = ?");
                    $update_stmt->bind_param("ss", $new_hashed_password, $username);
                    if ($update_stmt->execute()) {
                        $message = "<div class='bg-green-500 text-white p-3 mb-4'>Password changed successfully!</div>";
                    } else {
                        $message = "<div class='bg-red-500 text-white p-3 mb-4'>Error updating password: " . htmlspecialchars($conn->error) . "</div>";
                    }
                    $update_stmt->close();
                }
            } else {
                $message = "<div class='bg-red-500 text-white p-3 mb-4'>Current password is incorrect!</div>";
            }
        } else {
            $message = "<div class='bg-red-500 text-white p-3 mb-4'>No user found with that username.</div>";
        }
    }

    // Check if nag-reset ng ibang employee password
    if (isset($_POST['reset_username']) && isset($_POST['reset_new_password']) && isset($_POST['reset_confirm_password'])) {
        $reset_username = trim($_POST['reset_username']);
        $reset_new_password = $_POST['reset_new_password'];
        $reset_confirm_password = $_POST['reset_confirm_password'];

        if ($reset_new_password !== $reset_confirm_password) {
            $message = "<div class='bg-red-500 text-white p-3 mb-4'>Passwords do not match!</div>";
        } elseif (strlen($reset_new_password) < 8) {
            $message = "<div class='bg-red-500 text-white p-3 mb-4'>New password must be at least 8 characters long.</div>";
        } else {
            $reset_hashed_password = password_hash($reset_new_password, PASSWORD_DEFAULT);
            $update_reset_stmt = $conn->prepare("UPDATE tb_admin SET password = ? WHERE user_name = ?");
            $update_reset_stmt->bind_param("ss", $reset_hashed_password, $reset_username);
            if ($update_reset_stmt->execute()) {
                $message = "<div class='bg-green-500 text-white p-3 mb-4'>Password reset successfully for employee: $reset_username</div>";
            } else {
                $message = "<div class='bg-red-500 text-white p-3 mb-4'>Error resetting password: " . htmlspecialchars($conn->error) . "</div>";
            }
            $update_reset_stmt->close();
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - GSL25 IMS</title>
    <link rel="icon" href="img/GSL25_transparent_2.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            display: flex;
            background-color: #f4f6f8;
            min-height: 100vh;
            color: #2c3e50;
        }
                /* Sidebar styling */
                .sidebar {
            width: 260px;
            background: linear-gradient(145deg, #34495e, #2c3e50);
            color: #ecf0f1;
            padding: 30px 20px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            z-index: 1000;
        }

        .sidebarHeader h2 {
            font-size: 1.8rem;
            font-weight: bold;
            text-align: center;
        }

        .sidebarNav ul {
            list-style: none;
            padding: 0;
        }

        .sidebarNav ul li {
            margin: 1.2rem 0;
        }

        .sidebarNav ul li a {
            text-decoration: none;
            color: #ecf0f1;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            padding: 0.8rem 1rem;
            border-radius: 8px;
            transition: background 0.3s ease;
        }

        .sidebarNav ul li a:hover {
            background-color: #2980b9;
        }

        .sidebarNav ul li a i {
            margin-right: 15px;
        }
        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            font-size: 26px;
            color: #34495e;
            cursor: pointer;
            z-index: 1100;
        }
        .mainContent {
            margin-left: 280px;
            padding: 40px 20px;
            flex: 1;
            transition: margin-left 0.3s ease;
        }
        .form-container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
            border: 1px solid #dce1e7;
        }
        .form-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: 600;
            text-align: center;
            color: #3498db;
        }
        .form-group {
            margin-bottom: 18px;
            position: relative;
        }
        .form-group label {
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
            color: #2980b9;
        }
        .form-group input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccd0d5;
            border-radius: 6px;
            outline: none;
            font-size: 1rem;
            color: #2c3e50;
        }
        .form-group input:focus {
            border-color: #3498db;
        }
        .submit-button {
            width: 100%;
            padding: 12px;
            background: #3498db;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 6px;
            transition: background-color 0.3s;
        }
        .submit-button:hover {
            background: #2980b9;
        }
        .logout-form {
    margin-top: auto;
        }

        .logout-button {
            background-color: #e74c3c;
            color: #ffffff;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s;
            margin-top: 10px;
        }

        .logout-button i {
            margin-right: 8px;
            font-size: 1.2rem;
        }

        .logout-button:hover {
            background-color: #c0392b;
        }
        .toggle-password {
            position: absolute;
            right: 12px;
            top: 36px;
            cursor: pointer;
            color: #7f8c8d;
        }
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .sidebar-toggle {
                display: block;
            }
            .mainContent {
                margin-left: 0;
                padding-top: 80px;
            }
        }
    </style>
</head>

<body>

<!-- Sidebar toggle (mobile) -->
<div class="sidebar-toggle" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
</div>

<div class="sidebar" id="sidebar">
    <div class="sidebarHeader">
        <h2>GSL25 IMS</h2>
    </div>
    <nav class="sidebarNav">
        <ul>
            <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="inventory.php"><i class="fa fa-box"></i> Inventory</a></li>
            <li><a href="orders.php"><i class="fas fa-cash-register"></i> Point of Sale (POS)</a></li>
            <li><a href="reports.php"><i class="fa fa-chart-line"></i> Reports</a></li>
            <li><a href="settings.php"><i class="fa fa-cog"></i> Settings</a></li>
        </ul>
    </nav>
    <form action="logout.php" method="POST" class="logout-form">
        <button type="submit" class="logout-button">
            <i class="fas fa-sign-out-alt"></i> Logout
        </button>
    </form>
</div>

<!-- Main Content -->
<div class="mainContent">
    <div class="form-container">
        <h2>Change Own Password</h2>
        <?php if (isset($message)) echo $message; ?>
        <form action="settings.php" method="POST" class="space-y-4">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="admin" readonly>
            </div>
            <div class="form-group">
                <label for="current_password">Current Password:</label>
                <input type="password" id="current_password" name="current_password" required>
                <i class="fas fa-eye toggle-password" onclick="togglePassword('current_password', this)"></i>
            </div>
            <div class="form-group">
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" required>
                <i class="fas fa-eye toggle-password" onclick="togglePassword('new_password', this)"></i>
            </div>
            <button type="submit" class="submit-button">Change Password</button>
        </form>

        <hr class="my-8">

        <h2>Reset Employee Password</h2>
        <form action="settings.php" method="POST" class="space-y-4">
            <div class="form-group">
                <label for="reset_username">Employee Username:</label>
                <input type="text" id="reset_username" name="reset_username" required>
            </div>
            <div class="form-group">
                <label for="reset_new_password">New Password:</label>
                <input type="password" id="reset_new_password" name="reset_new_password" required>
                <i class="fas fa-eye toggle-password" onclick="togglePassword('reset_new_password', this)"></i>
            </div>
            <div class="form-group">
                <label for="reset_confirm_password">Confirm New Password:</label>
                <input type="password" id="reset_confirm_password" name="reset_confirm_password" required>
                <i class="fas fa-eye toggle-password" onclick="togglePassword('reset_confirm_password', this)"></i>
            </div>
            <button type="submit" class="submit-button bg-green-500 hover:bg-green-600">Reset Employee Password</button>
        </form>
    </div>
</div>

<!-- Scripts -->
<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
    }

    function togglePassword(fieldId, toggleIcon) {
        const field = document.getElementById(fieldId);
        if (field.type === 'password') {
            field.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
</script>

</body>
</html>


<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
    }
</script>

</body>
</html>
