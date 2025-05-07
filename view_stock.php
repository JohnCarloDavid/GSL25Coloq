<?php
// Start the session
session_start();

// Include database connection file
include('db_connection.php');
include('auth_check.php'); // Ensure only logged-in users can access

// Fetch all products and their stock quantities
$stock_query = "SELECT name, quantity FROM tb_inventory";
$stock_result = $conn->query($stock_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View All Product Stocks</title>
    <!-- Link to Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="img/GSL25_transparent_2.png">
    <script>
        // JavaScript function to go back to the previous page
        function goBack() {
            window.history.back();
        }
    </script>
</head>
<body class="bg-gray-50">

    <div class="max-w-7xl mx-auto p-6">
        <!-- Back Button -->
        <div class="text-center mb-6">
            <button onclick="goBack()" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-full text-sm font-medium">
                Back to Previous Page
            </button>
        </div>

        <!-- Page Title -->
        <h2 class="text-3xl font-semibold text-gray-800 mb-8 text-center">All Product Stocks</h2>

        <!-- Check if there are stock results -->
        <?php if ($stock_result->num_rows > 0): ?>
            <div class="overflow-x-auto bg-white shadow-lg rounded-lg">
                <table class="min-w-full table-auto">
                    <thead class="bg-blue-600 text-white">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium">Product Name</th>
                            <th class="px-6 py-3 text-left text-sm font-medium">Stock Quantity</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        <?php while ($row = $stock_result->fetch_assoc()): ?>
                            <tr class="border-t hover:bg-blue-50">
                                <td class="px-6 py-4 text-sm"><?php echo htmlspecialchars($row['name']); ?></td>
                                <td class="px-6 py-4 text-sm font-semibold"><?php echo $row['quantity']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-6">
                <p class="text-gray-600">No products found in the inventory.</p>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>
