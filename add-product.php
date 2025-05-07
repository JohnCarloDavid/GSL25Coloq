<?php
if (isset($_POST['submit'])) {
    include('db_connection.php');
    include('auth_check.php'); // Ensure only logged-in users can access

    $main_category = $_POST['main_category']; // New field for main category
    $name = $_POST['name'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];
    $size = $_POST['size'];
    $price = $_POST['price'];
    
    // Handle the uploaded image file
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);

    // Move the uploaded file to the target directory
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Prepare the SQL query with main_category
        $sql = "INSERT INTO tb_inventory (main_category, name, category, quantity, size, price, image_url) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        // Bind the parameters
        $stmt->bind_param('sssisss', $main_category, $name, $category, $quantity, $size, $price, $target_file);

        if ($stmt->execute()) {
            $message = "<p class='message success'>New product added successfully.</p>";
        } else {
            $message = "<p class='message error'>Error: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        $message = "<p class='message error'>Error uploading image.</p>";
    }
    
    $conn->close();
}

// Fetch the existing category options from the database
include('db_connection.php'); // Database connection

$sql = "SELECT DISTINCT main_category FROM tb_inventory"; // Get all distinct main categories
$result = $conn->query($sql);

$categories = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $categories[] = $row['main_category']; // Store the distinct categories in an array
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - GSL25 Inventory Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <link rel="icon" href="img/GSL25_transparent_2.png">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 0;
            transition: background-color 0.3s, color 0.3s;
        }

        body.dark-mode {
            background: #2c3e50;
            color: #ecf0f1;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: background 0.3s;
        }

        .container.dark-mode {
            background: #34495e;
        }

        h1 {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        .message {
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
        }

        form label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        form input,
        form select {
            width: 100%;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #ced4da;
            margin-bottom: 15px;
            font-size: 16px;
            background-color: #fff;
            transition: all 0.3s ease-in-out;
        }

        form input:focus,
        form select:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .button-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .button-container input[type="submit"],
        .button-container a {
            background-color: #007bff;
            color: #ffffff;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            text-align: center;
            transition: background-color 0.3s ease-in-out;
        }

        .button-container input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .button-container a:hover {
            background-color: #dc3545;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                width: 90%;
                padding: 15px;
            }
            form input, form select {
                font-size: 14px;
                padding: 10px;
            }
            .button-container input[type="submit"],
            .button-container a {
                width: 100%;
                text-align: center;
            }
        }
    </style>
    <script>
        // JavaScript to convert input text to uppercase for category field
        document.addEventListener('DOMContentLoaded', function() {
            const categoryInput = document.getElementById('category');
            categoryInput.addEventListener('input', function() {
                this.value = this.value.toUpperCase();
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <h1 class="text-2xl font-bold mb-4">Add New Product</h1>
        <?php
        if (isset($message)) {
            echo $message;
        }
        ?>
        <form action="add-product.php" method="post" enctype="multipart/form-data">
            <label for="main_category">Main Category:</label>
            <select id="main_category" name="main_category" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category; ?>" <?php echo (isset($main_category) && $main_category == $category) ? 'selected' : ''; ?>>
                        <?php echo $category; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="name">Name Item:</label>
            <input type="text" id="name" name="name" required>
            
            <label for="category">Category:</label>
            <input type="text" id="category" name="category" required>
            
            <label for="size">Size:</label>
            <input type="text" id="size" name="size" required>

            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" required>
            
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" required>

            <label for="image">Image:</label>
            <input type="file" id="image" name="image" accept="image/*" required>

            <div class="button-container">
                <input type="submit" name="submit" value="Add Product">
                <a href="javascript:history.back()">Back</a>
            </div>
        </form>
    </div>
</body>
</html>
