<?php
// Start the session
session_start();

// Include database connection file
include('db_connection.php');

include('auth_check.php'); // Ensure only logged-in users can access

// Initialize the search term
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Query to select all rows from the tb_inventory table and group by category
$sql = "SELECT category, GROUP_CONCAT(product_id, '::', name, '::', quantity SEPARATOR ';;') AS products, 
               SUM(quantity) AS total_quantity
        FROM tb_inventory";
if (!empty($search)) {
    $sql .= " WHERE name LIKE '%$search%' OR category LIKE '%$search%' OR product_id LIKE '%$search%'";
}
$sql .= " GROUP BY category";
$result = $conn->query($sql);
?>

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
 body {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    color: #2c3e50;
    background-color: #ecf0f1;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.mainContent {
    margin-left: auto;
    margin-right: 100px; /* Increased from 20px to 40px */
    padding: 50px;
    width: calc(100% - 280px);
    min-height: calc(100vh - 160px);
    height: auto;
}


.mainHeader h1 {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2980b9;
    text-align: center;
    letter-spacing: 1px;
    padding-bottom: 10px;
    border-bottom: 2px solid #2980b9;
}

.backButton {
    background-color: #2980b9;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    display: inline-block;
    transition: background-color 0.3s;
}

.backButton:hover {
    background-color: #3498db;
}

.categoryContainer {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
}

.categoryCard {
    background-color: #ffffff;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
}

.categoryCard:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

.categoryButton {
    width: 100%;
    background-color: #2980b9;
    color: white;
    padding: 15px;
    border-radius: 8px;
    cursor: pointer;
    text-align: center;
    font-size: 1.2rem;
    border: none;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.categoryButton:hover {
    background-color: #3498db;
    transform: scale(1.05);
}

.categoryDropdown {
    display: none;
    position: absolute;
    left: 0;
    top: 100%;
    width: 100%;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    z-index: 10;
    flex-direction: column;
    padding: 10px;
}

.categoryDropdown a {
    display: block;
    padding: 8px 12px;
    color: #34495e;
    text-decoration: none;
    border-radius: 6px;
    transition: background-color 0.3s;
}

.categoryDropdown a:hover {
    background-color: #f1f1f1;
}


    @media (max-width: 768px) {
    .categoryContainer {
        grid-template-columns: 1fr;
    }
    .mainContent {
        margin-left: 0;
        padding: 15px;
        width: 100%;
    }

    .mainHeader h1 {
        font-size: 2rem;
        text-align: center;
        padding-bottom: 8px;
    }

    .backButton {
        width: 100%;
        text-align: center;
        padding: 8px;
        font-size: 0.9rem;
    }

    .categoryContainer {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .categoryCard {
        width: 100%;
        padding: 12px;
    }

    .categoryButton {
        font-size: 1rem;
        padding: 12px;
    }

    .categoryDropdown a {
        font-size: 0.9rem;
        padding: 10px;
    }

    .logout-button {
        font-size: 1rem;
        padding: 8px;
    }

    .fa-arrow-left {
        font-size: 14px;
    }
}

    </style>
</head>
<body>
    
    <div class="mainContent">
        <a href="employee_landing.php" class="backButton"><i class="fa fa-arrow-left"></i> Back to Employee Dashboard</a>
        
        <div class="mainHeader">
            <h1>Inventory</h1>
        </div>

        <div class="categoryContainer">
    <?php
    // Get all distinct main categories
    $mainCategoriesQuery = "SELECT DISTINCT main_category FROM tb_inventory";
    $mainCategoriesResult = $conn->query($mainCategoriesQuery);

    if ($mainCategoriesResult->num_rows > 0) {
        while ($mainCategoryRow = $mainCategoriesResult->fetch_assoc()) {
            $mainCategory = $mainCategoryRow['main_category'];
            $dropdownId = strtolower($mainCategory) . "Dropdown";
            $cardId = strtolower($mainCategory) . "Card";

            echo "<div class='categoryCard' id='$cardId'>
                    <button class='categoryButton' onclick=\"toggleDropdown('$dropdownId')\">" . htmlspecialchars($mainCategory) . "</button>
                    <div id='$dropdownId' class='categoryDropdown'>";
            
            // Get categories under this main category
            $subQuery = "SELECT DISTINCT category FROM tb_inventory WHERE main_category = ?";
            $stmt = $conn->prepare($subQuery);
            $stmt->bind_param("s", $mainCategory);
            $stmt->execute();
            $subResult = $stmt->get_result();

            if ($subResult->num_rows > 0) {
                while ($subRow = $subResult->fetch_assoc()) {
                    echo "<a href='employee-category.php?category=" . urlencode($subRow['category']) . "'>" . htmlspecialchars($subRow['category']) . "</a>";
                }
            } else {
                echo "<p>No products found in " . htmlspecialchars($mainCategory) . ".</p>";
            }

            echo "</div></div>";
        }
    } else {
        echo "<p>No main categories found.</p>";
    }
    ?>
</div>
    <script>
        function toggleDropdown(dropdownId) {
    const dropdown = document.getElementById(dropdownId);
    const isVisible = dropdown.style.display === "block";

    // Close all dropdowns
    document.querySelectorAll(".categoryDropdown").forEach(el => el.style.display = "none");

    // Show the selected dropdown if it was closed
    if (!isVisible) {
        dropdown.style.display = "block";
    }
}
</script>
</body>
</html>
