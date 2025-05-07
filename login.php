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
        // If less than 30 seconds have passed since the last attempt
        if ($time_since_last_attempt < 30) {
            $remaining_time = 30 - $time_since_last_attempt;
            $error = "Too many failed attempts. Please try again in " . $remaining_time . " seconds.";
        } else {
            // Reset the attempt counter after 30 seconds
            $_SESSION['attempts'] = 0;
        }
    }

    if ($_SESSION['attempts'] < 3) {
        // Get username and password from POST request
        $username = isset($_POST['username']) ? $_POST['username'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        // Prepare SQL query to prevent SQL injection
        $stmt = $conn->prepare("SELECT user_id, password, role FROM tb_admin WHERE user_name = ?");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->bind_result($user_id, $stored_password, $role);
        $stmt->fetch();

        // Check if the stored password matches the provided password
        if ($stored_password !== null && password_verify($password, $stored_password)) {
            // Password is correct, reset attempts and set session variables
            $_SESSION['attempts'] = 0;
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;

            // Set unique session ID for the user (admin or employee)
            if ($role == 'admin') {
                $_SESSION['admin_loggedin'] = true;
                session_regenerate_id(true);
                header("Location: dashboard.php");
            } elseif ($role == 'employee') {
                $_SESSION['employee_loggedin'] = true;
                session_regenerate_id(true);
                header("Location: employee_landing.php");
            }
            exit();
        } else {
            // Invalid credentials, increase attempts
            $_SESSION['attempts']++;
            $_SESSION['last_attempt_time'] = time();

            if ($_SESSION['attempts'] >= 3) {
                $error = "Too many failed attempts. Please try again in 30 seconds.";
            } else {
                $error = "Invalid username or password.";
            }
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | GSL25</title>
    <link rel="icon" href="img/GSL25_transparent_2.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
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

        .error-message {
            color: red;
            margin-bottom: 10px;
            text-align: center;
            font-weight: bold;
        }
        .container {
            width: 90%;
            max-width: 1000px;
            display: flex;
            flex-direction: row-reverse;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }

        .left-section, .right-section {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
            flex-direction: column;
            text-align: center;
        }

        .right-section {
            color: white;
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.7)),
                        url('img/LOGIN1-removebg.png') no-repeat center;
            background-size: contain;
        }

        .right-section h1 {
            font-size: 2.2rem;
            font-weight: bold;
            text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.6);
        }

        .loginBody {
            width: 100%;
            max-width: 400px;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
            background: rgba(255, 255, 255, 0.98);
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .loginBody h2 {
            font-weight: 700;
            margin-bottom: 25px;
            color: #007BFF;
            font-size: 1.8rem;
        }

        .input-group {
            position: relative;
            width: 100%;
            margin-bottom: 18px;
        }

        .input-group input {
            width: 100%;
            padding: 14px;
            padding-right: 45px;
            border: 1px solid #bbb;
            border-radius: 6px;
            font-size: 16px;
            text-align: center;
        }

        .input-group i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #777;
        }

        button {
            width: 100%;
            padding: 14px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .signup-link {
            margin-top: 15px;
            font-size: 15px;
        }

        .signup-link a {
            color: #007BFF;
            text-decoration: none;
            font-weight: bold;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }

        .tutorial-link {
            margin-top: 15px;
            font-size: 15px;
        }

        .tutorial-link a {
            color: #28a745;
            text-decoration: none;
            font-weight: bold;
        }

        .tutorial-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                width: 95%;
                height: auto;
                margin: 20px 0;
            }

            .right-section {
                padding: 30px;
                background-size: contain;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-section">
            <div class="loginBody">
                <h2>Login</h2>
                <form action="login.php" method="post">
                    <?php if (isset($error)) { ?>
                        <div class="error-message"><?php echo $error; ?></div>
                    <?php } ?>
                    <div class="input-group">
                        <input type="text" id="username" name="username" placeholder="Username" required>
                        <i class="fas fa-user"></i>
                    </div>

                    <div class="input-group">
                        <input type="password" id="password" name="password" placeholder="Password" required>
                        <i class="fas fa-eye" id="togglePassword"></i>
                    </div>

                    <button type="submit">Login</button>
                </form>

                <div class="signup-link">
                    <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
                </div>

                <div class="tutorial-link">
                    <p>Need help? <a href="https://youtu.be/v_c0bz7v138">View system tutorial</a></p>
                </div>
            </div>
        </div>
        <div class="right-section">
            <h1>Welcome to GSL25 Steel Trading</h1>
            <p>Your trusted source for <strong>quality steel products</strong> and <strong>construction supplies</strong>.</p>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const togglePassword = document.getElementById("togglePassword");
            const passwordField = document.getElementById("password");

            togglePassword.addEventListener("click", function() {
                if (passwordField.type === "password") {
                    passwordField.type = "text";
                    togglePassword.classList.remove("fa-eye");
                    togglePassword.classList.add("fa-eye-slash");
                } else {
                    passwordField.type = "password";
                    togglePassword.classList.remove("fa-eye-slash");
                    togglePassword.classList.add("fa-eye");
                }
            });
        });
    </script>

    <script>
        window.onload = function() {
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        };
    </script>

</body>
</html>
