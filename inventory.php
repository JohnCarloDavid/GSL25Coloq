<?php
// Start the session
session_start();

// Include database connection file
include('db_connection.php');
include('auth_check.php'); // Ensure only logged-in users can access

// Initialize the search term
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Query to fetch all main categories
$sql = "SELECT DISTINCT main_category FROM tb_inventory";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Loop through each main category and display it
    while ($row = $result->fetch_assoc()) {
        $main_category = $row['main_category'];
        echo "<div class='categoryCard' id='category_$main_category' oncontextmenu='showContextMenu(event, \"$main_category\")'>
                 <button class='categoryButton' onclick='toggleDropdown(\"$main_category\")'>$main_category</button>
                 <div id='$main_category" . "Dropdown' class='categoryDropdown hidden'>";

        // Nested query to show products in each category (for the selected main category)
        $productQuery = "SELECT DISTINCT category FROM tb_inventory WHERE main_category = '$main_category'";
        if (!empty($search)) {
            $productQuery .= " AND (name LIKE '%$search%' OR category LIKE '%$search%')";
        }
        $productResult = $conn->query($productQuery);

        if ($productResult->num_rows > 0) {
            while ($productRow = $productResult->fetch_assoc()) {
                $category = $productRow['category'];
                echo "<a href='category.php?category=" . urlencode($category) . "' class='text-gray-800'>" . htmlspecialchars($category) . "</a>";
            }
        } else {
            echo "<p>No products found in this category.</p>";
        }

        echo "</div></div>";
    }
} else {
    echo "<p>No main categories found.</p>";
}
?>

<!-- Context Menu -->
<div id="contextMenu" class="context-menu" style="display: none;">
    <ul>
        <li onclick="deleteCategory()">Delete This Category</li>
    </ul>
</div>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory - GSL25 Inventory Management System</title>
    <link rel="icon" href="img/GSL25_transparent_2.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <style>
        .context-menu {
            position: absolute;
            background-color: white;
            border: 1px solid #ccc;
            padding: 10px;
            display: none;
            z-index: 1000;
        }

        .context-menu ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .context-menu ul li {
            padding: 8px;
            cursor: pointer;
        }

        .context-menu ul li:hover {
            background-color: #f0f0f0;
        }

        body {
        font-family: 'Poppins', sans-serif;
        margin: 0;
        color: #2c3e50;
        background-color: #ecf0f1;
        transition: background-color 0.3s ease, color 0.3s ease;
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

        /* Mobile Sidebar */
        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            font-size: 24px;
            background: none;
            border: none;
            color: black;
            cursor: pointer;
            z-index: 1100;
        }

        /* Mobile view adjustments for sidebar */
        @media (max-width: 768px) {
            .sidebar {
                width: 220px;
                transform: translateX(-100%);
                position: fixed;
                transition: transform 0.3s ease;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .sidebar-toggle {
                display: block;
            }

            .mainContent {
                margin-left: 0;
                width: 100%;
                padding: 20px;
            }
        }

        .mainContent {
            margin-left: 280px;
            padding: 30px;
            width: calc(100% - 280px);
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }
        
        @media (max-width: 768px) {
        .mainContent {
                width: 100%;
                margin-left: 0;
                padding: 20px;
        
            }
        }

        /* Primary Button */
        .button1 {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: #27ae60; /* Green */
            color: #ffffff;
            padding: 12px 18px;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: bold;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        /* Ensuring icon spacing remains consistent */
        .button1 i {
            margin-right: 8px;
            font-size: 1.2rem;
        }

        .button1:hover {
            background-color: #2ecc71; /* Lighter Green */
            transform: translateY(-2px);
        }
        
        /* Centering and Adjusting Button Position on Mobile View */
        @media (max-width: 768px) {
        .button1 {
            display: flex;
            width: 100%;
            max-width: 280px; /* Limits button width */
            margin: 0 auto; /* Centers the button */
            transform: translateX(30px); /* Moves it slightly to the left */
            }
        }

        /* Category Button */
        .categoryButton {
            width: 100%;
            background-color: #2980b9;
            color: #ffffff;
            padding: 15px;
            border-radius: 8px;
            cursor: pointer;
            text-align: center;
            font-size: 1.2rem;
            border: none;
            transition: background-color 0.3s ease, transform 0.3s ease;
            display: block;
        }

        .categoryButton:hover {
            background-color: #3498db;
            transform: scale(1.05);
        }

        /* Mobile View: Adjusts alignment & shifts slightly to the right */
        @media (max-width: 414px) {
            .categoryContainer {
                grid-template-columns: 1fr;
                margin-left: auto;
                margin-right: auto;
                max-width: 95%; /* Makes sure content doesn’t stretch too much */
            }

            .categoryButton {
                max-width: 80%; /* Keeps button size proportional */
                margin: 0 auto; /* Centers the button */
                display: block;
                transform: translateX(5px); /* Slight right shift */
            }

            .categoryCard {
                display: block;
                transform: translateX(30px); /* Slight right shift */
            }
        }

        /* Category Card */
        .categoryCard {
            width: 30%; /* 50% width of the container */
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 15px 0;
            padding: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            margin-left: 280px;;
        }

        /* Hover Effect on Category Card */
        .categoryCard:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        /* Category Button Styling */
        .categoryButton {
            width: 100%;
            background-color: #2980b9;
            color: #ffffff;
            padding: 12px 16px;
            font-size: 1.1rem;
            text-align: center;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;

            /* Align the button to the right */
            margin-left: auto;  /* This makes the button move to the right */
            display: block; /* Ensure the button behaves as a block-level element */
        }

        /* Hover Effect for Category Button */
        .categoryButton:hover {
            background-color: #3498db;
            transform: scale(1.05);
        }

        /* Category Dropdown Styling */
        .categoryDropdown {
            display: none;
            margin-top: 10px;
            padding: 10px 0;
            background-color: #f5f5f5;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: opacity 0.3s ease-in-out;
        }

        /* Display Dropdown on Button Click */
        .categoryDropdown.show {
            display: block;
            opacity: 1;
        }

        /* Dropdown Links Styling */
        .categoryDropdown a {
            display: block;
            padding: 8px 15px;
            text-decoration: none;
            color: #2980b9;
            font-size: 1rem;
            transition: background-color 0.2s ease;
            border-radius: 6px;
        }

        /* Hover Effect on Dropdown Links */
        .categoryDropdown a:hover {
            background-color: #ecf0f1;
        }

        /* Adjustments for Mobile */
        @media (max-width: 768px) {
            .categoryCard {
                padding: 15px;
            }

            .categoryButton {
                font-size: 1rem;
                padding: 10px 14px;
            }

            .categoryDropdown a {
                font-size: 0.95rem;
                padding: 8px 12px;
            }
        }

        /* Floating Add Category Button (Existing) */
        .addCategoryButton {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: linear-gradient(135deg, #007bff, #0056b3); /* Blue Gradient */
            color: white;
            border: none;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .addCategoryButton:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        /* Floating Add New Main Category Button (New Look) */
        .addMainCategoryButton {
            position: fixed;
            bottom: 90px; /* Place it above the first button */
            right: 20px;
            background: linear-gradient(135deg, #f39c12, #e67e22); /* Orange Gradient */
            color: white;
            border: none;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .addMainCategoryButton:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        /* Logout button styling */
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

<!-- Main Content -->
<div class="mainContent">
    <div class="buttonContainer">
        <!-- Move the buttons to the right -->
        <a href="pos.php" class="button1"><i class="fa fa-plus"></i> Add Supply</a>
        <!-- Add New Main Category Button -->
        <button class="addMainCategoryButton" onclick="window.location.href='add-main-category.php'">
            <i class="fa fa-plus"></i>
        </button>
        <button class="addCategoryButton" onclick="window.location.href='add-product.php'">
            <i class="fa fa-plus"></i> 
        </button>
    </div>
</div>

<script>
    let clickedCategory = '';

    // Function to show context menu on right-click
    function showContextMenu(event, category) {
        event.preventDefault();
        clickedCategory = category;

        const contextMenu = document.getElementById('contextMenu');
        contextMenu.style.left = event.pageX + 'px';
        contextMenu.style.top = event.pageY + 'px';
        contextMenu.style.display = 'block';
    }

    // Function to delete category
    function deleteCategory() {
        if (confirm('Are you sure you want to delete this category? This action will delete all products in this category.')) {
            // Redirect to delete_category.php with the category name to delete
            window.location.href = 'delete_category.php?category=' + encodeURIComponent(clickedCategory);
        }
    }

    // Close context menu when clicking elsewhere
    window.addEventListener('click', function(event) {
        const contextMenu = document.getElementById('contextMenu');
        if (!contextMenu.contains(event.target)) {
            contextMenu.style.display = 'none';
        }
    });
</script>


<script>
    function toggleDropdown(category) {
    var dropdown = document.getElementById(category + "Dropdown");
    dropdown.classList.toggle('show');
}


function toggleSidebar() {
    var sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('open');
}

</script>

</body>
</html>
