<?php
// Start the session
session_start();

// Include database connection file
include('db_connection.php');
include('auth_check.php'); // Ensure only logged-in users can access

// Fetch Total Stock
$total_stock_query = "SELECT SUM(quantity) AS total_stock FROM tb_inventory";
$total_stock_result = $conn->query($total_stock_query);
$total_stock = ($total_stock_result->num_rows > 0) ? $total_stock_result->fetch_assoc()['total_stock'] : 0;

// Fetch Total Orders and Total Amount
$total_orders_query = "SELECT COUNT(*) AS total_orders, SUM(o.quantity * i.price) AS total_amount 
                       FROM tb_orders o 
                       JOIN tb_inventory i ON o.product_name = i.name";
$total_orders_result = $conn->query($total_orders_query);

if ($total_orders_result->num_rows > 0) {
    $orders_data = $total_orders_result->fetch_assoc();
    $total_orders = $orders_data['total_orders'];
    $total_amount = $orders_data['total_amount'];
} else {
    $total_orders = 0;
    $total_amount = 0.00;
}

// Fetch Total Categories
$total_categories_query = "SELECT COUNT(DISTINCT category) AS total_categories FROM tb_inventory";
$total_categories_result = $conn->query($total_categories_query);
$total_categories = ($total_categories_result->num_rows > 0) ? $total_categories_result->fetch_assoc()['total_categories'] : 0;

// Fetch Low Stock Items
$low_stock_query = "SELECT COUNT(*) AS low_stock_items FROM tb_inventory WHERE quantity < 15";
$low_stock_result = $conn->query($low_stock_query);
$low_stock_items = ($low_stock_result->num_rows > 0) ? $low_stock_result->fetch_assoc()['low_stock_items'] : 0;

// Fetch Low Stock Product Details
$low_stock_details_query = "SELECT name, quantity FROM tb_inventory WHERE quantity < 15 ORDER BY quantity ASC";
$low_stock_details_result = $conn->query($low_stock_details_query);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - GSL25 Inventory Management System</title>
    <link rel="icon" href="img/GSL25_transparent_2.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
/* Body and general styling */
body {
    font-family: 'Poppins', sans-serif;
    display: flex;
    margin: 0;
    color: #2c3e50;
    transition: background-color 0.3s ease, color 0.3s ease;
}

/* Collapsible Sidebar for Mobile */
@media (max-width: 768px) {
    .sidebar {
        width: 220px;
        transform: translateX(-100%);
        position: fixed;
        z-index: 1000;
    }

    .sidebar.open {
        transform: translateX(0);
    }
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

@media (max-width: 768px) {
    .sidebar-toggle {
        display: block;
    }
}

/* Burger Menu Button */
.sidebar-toggle {
    display: none;
    position: fixed;
    top: 15px;
    left: 15px;
    font-size: 24px;
    background: none;
    border: none;
    color:black;
    cursor: pointer;
    z-index: 1100;
}

/* Sidebar (default hidden on mobile) */
@media (max-width: 768px) {
    .sidebar-toggle {
        display: block;
    }

    .sidebar {
        width: 220px;
        transform: translateX(-100%);
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        background: linear-gradient(145deg, #34495e, #2c3e50);
        transition: transform 0.3s ease;
        z-index: 1000;
    }

    .sidebar.open {
        transform: translateX(0);
    }

    .mainContent {
        margin-left: 0;
        width: 100%;
        transition: margin-left 0.3s ease;
    }
}
/* Main content styling */
.mainContent {
    margin-left: 280px;
    padding: 30px;
    width: calc(100% - 280px);
    min-height: 100vh;
    transition: margin-left 0.3s ease;
}

/* Adjust for mobile when sidebar is hidden */
@media (max-width: 768px) {
    .mainContent {
        margin-left: 0;
        width: 100%;
    }
}

/* Dashboard Sections */
.dashboardSections {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 20px; /* Reduced from 30px for natural spacing */
    position: relative;
    top: -40px; /* Slight upward movement */
}


/* Ensure sections don't break on small screens */
@media (max-width: 600px) {
    .dashboardSections {
        grid-template-columns: 1fr;
    }
}

/* Quick Actions & Recent Activities */
.quickActions, .recentActivities {
    background: lightblue;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    transition: background 0.3s ease, color 0.3s ease;
    text-align: center;
}

/* Quick Actions Section */
.quickActions {
    text-align: center;
    margin: 20px 0; /* Reduced from 40px to 20px to move it up */
    position: relative;
    top: -30px; /* Slight upward shift */
}


.quickActions h2 {
    font-size: 1.8rem;
    margin-bottom: 20px;
    color: #2c3e50;
    font-weight: 600;
}

.quickActions .buttonGroup {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 16px;
}

.quickActions .buttonGroup a {
    background: #3498db;
    padding: 12px 32px;
    border-radius: 16px;
    color: #ffffff;
    font-size: 1rem;
    font-weight: 500;
    text-decoration: none;
    text-align: center;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.quickActions .buttonGroup a:hover {
    background: #2c80b4;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
}

.chart-container {
    width: 100%;
    max-width: 1100px;
    min-height: 380px;
    margin: 0 auto 30px auto;
    padding: 30px 40px;
    background: linear-gradient(135deg, #f5f7fa, #e2eafc); /* Soft gradient */
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    position: relative;
    top: -30px;
    transition: all 0.3s ease;
    border: 1px solid #dce3ec;
}

/* Optional heading inside chart container */
.chart-container h3 {
    font-size: 1.6rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 16px;
}

/* Optional paragraph below heading */
.chart-container p {
    font-size: 0.95rem;
    color: #6c7a89;
    margin-bottom: 20px;
}

/* Responsive canvas styling */
.chart-container canvas {
    width: 100% !important;
    height: auto !important;
    max-height: 280px;
}


/* Logout Button */
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

/* Dashboard Sections */
.dashboardSections {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-top: 30px;
}

/* Stat Card Styling */
.statCard {
    background: #ffffff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
    overflow: hidden;
}

.statCard:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
}

.statCard h3 {
    font-size: 1.2rem;
    color: #34495e;
    margin-bottom: 10px;
    font-weight: 600;
}

/* Icon Design */
.statCard i {
    font-size: 2.5rem;
    margin-bottom: 10px;
}

/* Color Coding for Cards */
.statCard.total-stock {
    border-left: 5px solid #2980b9;
}

.statCard.total-orders {
    border-left: 5px solid #27ae60;
}

.statCard.categories {
    border-left: 5px solid #f39c12;
}

.statCard.low-stock {
    border-left: 5px solid #e74c3c;
}

/* Responsive Grid */
@media (max-width: 768px) {
    .dashboardSections {
        grid-template-columns: 1fr;
    }
}

    </style>
</head>
<body>
<!-- Burger Menu -->
<div class="sidebar-toggle" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
</div>

<!-- Sidebar -->
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

</aside>
    <div class="mainContent">    
        <div class="quickActions">
            <h2>Quick Actions</h2>
        <div class="buttonGroup">
            <a href="add-product.php">Add Product</a>
            <a href="add-order.php">Add Order</a>
            <a href="reports.php">Reports</a>
        </div>

        </div>
        <div class="chart-container">
            <h3 style="text-align: center; margin-bottom: 15px;">
            Stacked Bar Graph</h3>
            <canvas id="myChart"></canvas>
        </div>
        <div class="dashboardSections">
            <!-- Total Orders -->
            <div class="statCard total-orders bg-white shadow-lg rounded-lg p-6 flex flex-col items-center text-center border border-green-200">
                <i class="fas fa-shopping-cart text-4xl text-green-500 mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Total Orders</h3>
                <p class="text-3xl font-bold text-green-600"><?php echo $total_orders; ?></p>
            </div>

            <!-- Categories -->
            <div class="statCard categories bg-white shadow-lg rounded-lg p-6 flex flex-col items-center text-center border border-yellow-200">
                <i class="fas fa-layer-group text-4xl text-yellow-500 mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Categories</h3>
                <p class="text-3xl font-bold text-yellow-600"><?php echo $total_categories; ?></p>
            </div>

            <!-- Total Stock -->
            <div class="statCard total-stock bg-white shadow-lg rounded-lg p-6 flex flex-col items-center text-center border border-blue-200">
                <i class="fas fa-boxes text-4xl text-blue-500 mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Total Stock</h3>
                <p class="text-3xl font-bold text-blue-600"><?php echo $total_stock; ?></p>
                <a href="view_stock.php" class="mt-3 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm">
                    View All Product Stocks
                </a>
            </div>

            <!-- Low Stock Items -->
            <div class="statCard low-stock bg-white shadow-lg rounded-lg p-6 flex flex-col items-center text-center border border-red-200">
                <i class="fas fa-exclamation-triangle text-4xl text-red-500 mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Low Stock Items</h3>
                <p class="text-3xl font-bold text-red-600"><?php echo $low_stock_items; ?></p>
                <a href="lowstock.php" class="mt-3 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded text-sm">
                    View Low Stock Details
                </a>
            </div>
        </div>
    </div>
</div>

        </div>
    <script>
        const ctx = document.getElementById('myChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Total Stock', 'Total Orders', 'Categories', 'Low Stock',], 
                datasets: [{
                    label: 'Inventory Statistics',
                    data: [
                        <?php echo $total_stock; ?>,
                        <?php echo $total_orders; ?>,
                        <?php echo $total_categories; ?>,
                        <?php echo $low_stock_items; ?>,
                    ],
                    backgroundColor: [
                        'rgba(52, 152, 219, 0.6)', // Blue
                        'rgba(46, 204, 113, 0.6)', // Green
                        'rgba(155, 89, 182, 0.6)', // Purple
                        'rgba(231, 76, 60, 0.6)',  // Red
                    ],
                    borderColor: [
                        'rgba(52, 152, 219, 1)',
                        'rgba(46, 204, 113, 1)',
                        'rgba(155, 89, 182, 1)',
                        'rgba(231, 76, 60, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

<script>
        function toggleSidebar() {
            document.getElementById("sidebar").classList.toggle("open");
        }

        function toggleDropdown(id) {
            document.getElementById(id).classList.toggle("hidden");
        }
    </script>

</body>
</html>
