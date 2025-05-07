<?php
// Start the session
session_start();

// Include database connection file
include('db_connection.php');
include('auth_check.php'); // Ensure only logged-in users can access

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $main_category = trim($_POST['main_category']);

    // Check if the category is not empty
    if (!empty($main_category)) {
        // Check if the category already exists
        $checkSql = "SELECT * FROM tb_inventory WHERE main_category = ?";
        $stmt = $conn->prepare($checkSql);
        $stmt->bind_param("s", $main_category);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            // Insert the new category into the database
            $sql = "INSERT INTO tb_inventory (main_category) VALUES (?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $main_category);

            if ($stmt->execute()) {
                // Success message
                $_SESSION['message'] = "New main category added successfully!";
                header('Location: inventory.php'); // Redirect to the inventory page
                exit();
            } else {
                // Error message
                $_SESSION['error'] = "Failed to add new main category. Please try again.";
            }
        } else {
            $_SESSION['error'] = "Main category already exists.";
        }
    } else {
        $_SESSION['error'] = "Please enter a category name.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Main Category - GSL25 Inventory Management System</title>
    <link rel="icon" href="img/GSL25_transparent_2.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
</head>
<body>

<!-- Sidebar and Main Content layout -->
<div class="mainContent">
    <div class="container mx-auto my-8 p-6 bg-white shadow-lg rounded-lg">
        <h2 class="text-2xl font-semibold mb-4">Add New Main Category</h2>

        <!-- Display success or error messages -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="bg-green-500 text-white p-4 mb-4 rounded"><?= $_SESSION['message'] ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php elseif (isset($_SESSION['error'])): ?>
            <div class="bg-red-500 text-white p-4 mb-4 rounded"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form action="add-main-category.php" method="POST">
            <div class="mb-4">
                <label for="main_category" class="block text-sm font-medium text-gray-700">Main Category Name</label>
                <input type="text" id="main_category" name="main_category" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" required oninput="convertToUpperCase()">
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700">Add Category</button>
                <a href="inventory.php" class="text-blue-500 hover:text-blue-700">Back to Inventory</a>
            </div>
        </form>
    </div>
</div>
<script>
    function convertToUpperCase() {
        var mainCategoryInput = document.getElementById('main_category');
        mainCategoryInput.value = mainCategoryInput.value.toUpperCase();
    }
</script>


</body>
</html>
