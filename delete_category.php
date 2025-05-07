<?php
// Start the session
session_start();

// Include database connection and authentication
include('db_connection.php');
include('auth_check.php'); // Ensure only logged-in users can access

// Check if the 'category' parameter exists in the URL
if (isset($_GET['category'])) {
    $categoryToDelete = $_GET['category'];

    // Start a transaction to ensure data consistency
    $conn->begin_transaction();

    try {
        // First, delete all products in the selected main category
        $deleteProductsQuery = "DELETE FROM tb_inventory WHERE main_category = ?";
        $stmt = $conn->prepare($deleteProductsQuery);
        $stmt->bind_param('s', $categoryToDelete);
        $stmt->execute();

        // Commit the transaction
        $conn->commit();

        // Redirect back to the inventory page after successful deletion
        header('Location: inventory.php');
        exit();
    } catch (Exception $e) {
        // If an error occurs, rollback the transaction
        $conn->rollback();

        // Show error message
        echo "Error: " . $e->getMessage();
    }
} else {
    // If no category is specified, show an error
    echo "No category specified for deletion.";
}
?>
