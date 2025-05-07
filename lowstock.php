<?php
// Start the session
session_start();

// Include database connection file
include('db_connection.php');
include('auth_check.php'); // Ensure only logged-in users can access

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
    <title>Low Stock Products</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="icon" href="img/GSL25_transparent_2.png">
</head>
<body class="bg-gray-100 font-sans">
    <div class="container mx-auto my-10">
        <?php if ($low_stock_items > 0): ?>
        <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-yellow-400">
            <div class="flex items-center justify-between bg-yellow-500 text-white p-4">
                <div class="flex items-center">
                    <i class="fas fa-bell text-xl mr-3"></i>
                    <h3 class="text-2xl font-semibold">Low Stock Alerts</h3>
                </div>
                <span class="bg-yellow-600 text-white py-1 px-3 text-sm rounded-full"><?php echo $low_stock_items; ?> Items</span>
            </div>
            <div class="p-6">
                <p class="text-lg text-gray-800 mb-5">The following products have less than 15 items left in stock. Consider restocking:</p>
                <ul class="list-disc pl-6 space-y-3">
                    <?php while ($row = $low_stock_details_result->fetch_assoc()): ?>
                        <li class="text-gray-800 text-lg">
                            <span class="font-semibold"><?php echo htmlspecialchars($row['name']); ?></span> – 
                            <span class="text-red-500"><?php echo $row['quantity']; ?> left</span>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
        <?php else: ?>
        <div class="bg-green-100 p-6 rounded-lg border border-green-300 text-green-700">
            <h3 class="text-xl font-semibold mb-4">No Low Stock Items</h3>
            <p>All products have sufficient stock available. Keep up the good work!</p>
        </div>
        <?php endif; ?>
        
        <!-- Back Button -->
        <div class="mt-6 text-center">
            <a href="javascript:history.back()" class="inline-block bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>
    </div>
</body>
</html>
