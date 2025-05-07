<?php
session_start();
include('db_connection.php');

// Check if logged in and admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// Check if product_id is provided
if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Move product to "deleted" table or just delete (depends on your system)
    $sql = "DELETE FROM tb_inventory WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $product_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete product']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>

