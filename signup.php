<?php
// Start the session
session_start();

// Include database connection file
include('db_connection.php');

// Initialize session variables if not set
if (!isset($_SESSION['attempts'])) {
    $_SESSION['attempts'] = 0;
    $_SESSION['last_attempt_time'] = time();
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Calculate the time difference since the last attempt
    $time_since_last_attempt = time() - $_SESSION['last_attempt_time'];

    // Check if the user has exceeded the maximum attempts
    if ($_SESSION['attempts'] >= 3) {
        if ($time_since_last_attempt < 30) {
            $remaining_time = 30 - $time_since_last_attempt;
            $error = "Too many failed attempts. Please try again in " . $remaining_time . " seconds.";
        } else {
            $_SESSION['attempts'] = 0;
        }
    }

    if ($_SESSION['attempts'] < 3) {
        // Get form inputs
        $username = isset($_POST['username']) ? trim($_POST['username']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
        $role = isset($_POST['role']) ? $_POST['role'] : 'employee'; // Default role is 'employee'

        // Check if password and confirm password match
        if ($password !== $confirm_password) {
            $error = "Passwords do not match.";
        } else {
            // Password validation
            if (!preg_match('/[A-Z]/', $password)) {
                $error = "Password must contain at least one uppercase letter.";
            } elseif (!preg_match('/[0-9]/', $password)) {
                $error = "Password must contain at least one number.";
            } elseif (!preg_match('/[\W_]/', $password)) {
                $error = "Password must contain at least one special character.";
            } elseif (preg_match('/\s/', $password)) {
                $error = "Password cannot contain spaces.";
            } else {
                // Check if username already exists
                $stmt = $conn->prepare("SELECT user_name FROM tb_admin WHERE user_name = ?");
                if (!$stmt) {
                    die("Prepare failed: " . $conn->error);
                }

                $stmt->bind_param('s', $username);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $error = "Username already taken. Please choose a different one.";
                } else {
                    // Hash the password
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    // Insert new user
                    $stmt = $conn->prepare("INSERT INTO tb_admin (user_name, password, role) VALUES (?, ?, ?)");
                    if (!$stmt) {
                        die("Prepare failed: " . $conn->error);
                    }

                    $stmt->bind_param('sss', $username, $hashed_password, $role);
                    if ($stmt->execute()) {
                        $_SESSION['attempts'] = 0;
                        $_SESSION['loggedin'] = true;
                        $_SESSION['username'] = $username;
                        $_SESSION['success_message'] = "Registration successful! You can now log in.";

                        echo "<script>alert('Registration successful! You can now log in.'); window.location.href = 'login.php';</script>";
                        exit();
                    } else {
                        $error = "An error occurred. Please try again.";
                    }

                    $stmt->close();
                }

                $stmt->close();
            }
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - GSL25 Steel Trading</title>
    <link rel="icon" href="img/GSL25_transparent_2.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
body {
    margin: 0;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Poppins', Arial, sans-serif;
    background: url('img/steelbg.jpg') no-repeat center center/cover;
}

.container {
    width: 85%;
    max-width: 1000px;
    display: flex;
    flex-direction: row-reverse;
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.15);
    border-radius: 12px;
    overflow: hidden;
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(8px);
}

.left-section, .right-section {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 30px;
    flex-direction: column;
}

.left-section {
    background: white;
    text-align: center;
}

.right-section {
    text-align: center;
    color: white;
    background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.7)), 
                url('img/LOGIN1-removebg.png') no-repeat center center;
    background-size: contain;
    background-position: center;
    padding: 40px;
}

.right-section h1 {
    font-size: 2rem;
    font-weight: bold;
    text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.6);
}

.signupBody {
    width: 90%;
    max-width: 350px;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    background: rgba(255, 255, 255, 0.95);
    color: #333;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.signupBody h2 {
    text-align: center;
    font-weight: 600;
    margin-bottom: 15px;
    color: #007BFF;
}

.signupBody input, .signupBody select {
    width: 100%;
    padding: 8px;
    margin-bottom: 12px;
    border: 1px solid #ccc;
    border-radius: 5px;
    transition: box-shadow 0.3s ease;
}

.signupBody input:focus, .signupBody select:focus {
    outline: none;
    box-shadow: 0 0 4px #007BFF;
    border-color: #007BFF;
}

.signupBody button {
    width: 100%;
    padding: 10px;
    background-color: #007BFF;
    color: white;
    border: none;
    border-radius: 5px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.signupBody button:hover {
    background-color: #0056b3;
    transform: scale(1.02);
}

.signup-link {
    text-align: center;
    margin-top: 8px;
}

.signup-link a {
    color: #007BFF;
    text-decoration: none;
}

.signup-link a:hover {
    text-decoration: underline;
}

@media (max-width: 768px) {
    .container {
        flex-direction: column;
        width: 90%;
        height: auto;
        margin: 15px 0;
        align-items: center;
        justify-content: center;
    }

    .right-section {
        padding: 25px;
        background-size: contain;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .right-section h1 {
        font-size: 1.8rem;
        margin-bottom: 8px;
    }

    .left-section, .right-section {
        width: 100%;
        padding: 20px;
    }

    .signupBody {
        max-width: 85%;
        padding: 18px;
        box-shadow: none;
        border-radius: 8px;
    }
}

    </style>
</head>
<body>
    <div class="container">
        <div class="left-section">
            <div class="signupBody">
                <h2>Sign Up</h2>
                <form action="signup.php" method="post">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                    
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                    
                    <small id="password-requirements" style="color: gray;">
                        Password must contain at least:
                        <ul>
                            <li id="req-uppercase" style="color: red;">✔ One uppercase letter</li>
                            <li id="req-number" style="color: red;">✔ One number</li>
                            <li id="req-special" style="color: red;">✔ One special character</li>
                            <li id="req-no-space" style="color: red;">✔ No spaces</li>
                        </ul>
                    </small>

                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>

                    <label for="role">Role</label>
                    <select id="role" name="role">
                        <option value="employee" selected>Employee</option>
                        <option value="admin">Admin</option>
                    </select>
                    
                    <button type="submit">Sign Up</button>
                </form>
                <div class="signup-link">
                    <p>Already have an account? <a href="login.php">Login here</a></p>
                </div>
            </div>
        </div>
        <div class="right-section">
            <h1>Welcome to GSL25 Steel Trading</h1>
            <p>Your trusted source for <strong>quality steel products</strong> and <strong>construction supplies</strong>.</p>
        </div>
    </div>

    <script>
        document.getElementById("password").addEventListener("input", function() {
            let password = this.value;
            document.getElementById("req-uppercase").style.color = /[A-Z]/.test(password) ? "green" : "red";
            document.getElementById("req-number").style.color = /[0-9]/.test(password) ? "green" : "red";
            document.getElementById("req-special").style.color = /[\W_]/.test(password) ? "green" : "red";
            document.getElementById("req-no-space").style.color = /\s/.test(password) ? "red" : "green";
        });
    </script>
</body>
</html>
