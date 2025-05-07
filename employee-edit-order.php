<?php
// Start the session
session_start();

// Include database connection file
include('db_connection.php');
include('auth_check.php'); // Ensure only logged-in users can access

// Check if order ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Invalid order ID.";
    exit;
}

$order_id = $_GET['id'];

// Fetch order details
$sql = "SELECT * FROM tb_orders WHERE order_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    echo "Order not found.";
    exit;
}

// Fetch product details for dropdown
$product_sql = "SELECT name FROM tb_inventory";
$product_result = $conn->query($product_sql);
$products = [];
while ($row = $product_result->fetch_assoc()) {
    $products[] = $row['name'];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_name = $_POST['customer_name'];
    $product_name = $_POST['product_name'];
    $quantity = $_POST['quantity'];
    $order_date = $_POST['order_date'];
    
    $update_sql = "UPDATE tb_orders SET customer_name = ?, product_name = ?, quantity = ?, order_date = ? WHERE order_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param('ssisi', $customer_name, $product_name, $quantity, $order_date, $order_id);
    
    if ($update_stmt->execute()) {
        echo "<script>alert('Order updated successfully!'); window.location.href='employee-orders.php';</script>";
    } else {
        echo "<script>alert('Error updating order.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Order - GSL25 Inventory Management System</title>
    <link rel="icon" href="img/GSL25_transparent_2.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="bg-white max-w-lg w-full p-8 rounded-2xl shadow-lg border border-gray-200">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Edit Order</h2>
    <form action="" method="POST" class="space-y-5">
        <div>
            <label class="block text-gray-700 font-medium mb-2">Customer Name</label>
            <input type="text" name="customer_name" value="<?php echo htmlspecialchars($order['customer_name']); ?>" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
        </div>
        
        <div>
            <label class="block text-gray-700 font-medium mb-2">Product Name</label>
            <select name="product_name" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                <?php foreach ($products as $product) { ?>
                    <option value="<?php echo htmlspecialchars($product); ?>" <?php echo ($product == $order['product_name']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($product); ?></option>
                <?php } ?>
            </select>
        </div>
        
        <div>
            <label class="block text-gray-700 font-medium mb-2">Quantity</label>
            <input type="number" name="quantity" value="<?php echo htmlspecialchars($order['quantity']); ?>" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
        </div>
        
        <div>
            <label class="block text-gray-700 font-medium mb-2">Order Date</label>
            <input type="date" name="order_date" value="<?php echo htmlspecialchars($order['order_date']); ?>" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
        </div>
        
        <div class="flex justify-between mt-6">
            <a href="employee-orders.php" class="bg-gray-500 text-white px-5 py-2 rounded-lg hover:bg-gray-600 transition">Cancel</a>
            <button type="submit" class="bg-blue-500 text-white px-5 py-2 rounded-lg hover:bg-blue-600 transition">Update Order</button>
        </div>
    </form>
</div>

</body>
</html>
