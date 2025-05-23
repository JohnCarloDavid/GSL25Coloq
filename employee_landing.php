<?php
session_start();

// Check if the user is logged in and if they are an employee
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'employee') {
    // Redirect to login page if not logged in or not an employee
    header("Location: login.php");
    exit();
}

// Logout functionality for employee
if (isset($_GET['logout']) && $_SESSION['role'] === 'employee') {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Check if admin is logged in and handle admin logout separately
if (isset($_GET['admin_logout']) && $_SESSION['role'] === 'admin') {
    session_destroy();
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <link rel="icon" href="img/GSL25_transparent_2.png">
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #edf2f7;
            margin: 0;
            padding: 0;
            color: #2d3748;
        }

        /* Header Styles */
        .header {
            background-color: #2d3748;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header .logo-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo {
            max-width: 60px;
            height: auto;
        }

        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: bold;
            text-align: left;
            white-space: nowrap;
        }

        .logout {
            padding: 10px 20px;
            background-color: #e53e3e;
            border-radius: 6px;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .logout a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
        }

        .logout:hover {
            background-color: #c53030;
            transform: scale(1.05);
        }

        /* Main Container */
        .container {
            max-width: 90%;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .container h2 {
            color: #2d3748;
            font-size: 24px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        /* Feature Cards */
        .features {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
        }

        .feature-card {
            flex: 1 1 calc(50% - 20px);
            background-color: #3182ce;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
            min-width: 150px;
        }

        .feature-card a {
            display: block;
            text-decoration: none;
            color: white;
            padding: 15px;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        .feature-card:hover a {
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header {
                flex-direction: row;
                flex-wrap: wrap;
                justify-content: space-between;
                padding: 10px;
            }

            .header .logo-container {
                flex-grow: 1;
                justify-content: center;
                text-align: center;
            }

            .logo {
                max-width: 50px;
            }

            .header h1 {
                font-size: 18px;
            }

            .logout {
                font-size: 12px;
                padding: 8px;
            }

            .features {
                flex-direction: column;
                align-items: center;
                width: 100%;
            }

            .feature-card {
                width: 90%;
                max-width: 300px;
            }
        }
    </style>
</head>
<body>

<div class="header">
    <div class="logo-container">
        <img src="img/GSL25_transparent_2.png" class="logo" alt="GSL25 Logo">
        <h1>GSL25 STEEL TRADING</h1>
    </div>
    <div class="logout">
        <a href="?logout=true">Logout</a>
    </div>
</div>

<div class="container">
    <h2>Welcome to the Employee Portal</h2>
    <div class="features">
        <div class="feature-card">
            <a href="add-order-employee.php">➕ Add Order</a>
        </div>

        <div class="feature-card">
            <a href="employee-orders.php">📦 Orders</a>
        </div>

        <div class="feature-card">
            <a href="employee_inventory.php">📋 Inventory</a>
        </div>

        <div class="feature-card">
            <a href="employee-settings.php">🔒 Change Password</a>
        </div>
    </div>
</div>

</body>
</html>