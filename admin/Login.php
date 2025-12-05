<?php
session_start();

$error = ''; // Initialize error variable

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../config/db.php'; // Include your database connection

    $username = $_POST['username'];
    $password = $_POST['password'];

    // --- SECURITY IMPROVEMENT ---
    // Using prepared statements to prevent SQL injection
    $sql = "SELECT password FROM admin WHERE username = ?"; // Select only the password
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            // In a real application, you should use password_hash() and password_verify()
            // For now, we are matching the plain text password as in your original code.
            // Note: If you updated passwords using the change-password page, use password_verify($password, $row['password']) here instead.
            if ($password === $row['password']) {
                $_SESSION['admin'] = $username;
                header("Location: index");
                exit(); // Always exit after a header redirect
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Invalid username or password.";
        }
        $stmt->close();
    } else {
        $error = "An error occurred. Please try again later.";
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Srishti Polytech</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            /* Srishti Polytech Color Palette */
            --brand-teal: #1e90b8; 
            --brand-red: #D71920;
            --brand-dark: #111111;
            
            /* Dark Theme Variables */
            --background-color: #000000;
            --form-background: #111111; /* Dark card background */
            --text-color: #ffffff;
            --label-color: #a3a3a3;
            --border-color: #333333;
            --input-bg: #1a1a1a;
            --error-color: #D71920;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            /* Sleek Dark Gradient */
            background-image: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
        }

        .main-container {
            width: 100%;
            max-width: 420px;
            text-align: center;
        }

        .header {
            margin-bottom: 30px;
        }

        .header img {
            width: 90px;
            height: auto;
            margin-bottom: 15px;
            object-fit: contain;
            /* Optional: Add a white filter if your logo is dark, remove if it has white text already */
            /* filter: brightness(0) invert(1); */ 
        }

        .header h1 {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 5px;
            color: #ffffff;
        }
        
        .header h1 span {
            color: var(--brand-red); /* Red Accent */
        }

        .header p {
            font-size: 14px;
            color: var(--label-color);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 500;
        }

        .login-box {
            background: var(--form-background);
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5); /* Stronger shadow for dark mode */
            text-align: left;
            border: 1px solid #222; /* Subtle border */
            border-top: 5px solid var(--brand-red); /* Red Top Border */
        }

        .login-box h2 {
            font-size: 20px;
            font-weight: 600;
            text-align: center;
            margin-bottom: 30px;
            color: #ffffff;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--label-color);
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .input-field {
            position: relative;
        }

        .input-field i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #555;
            transition: color 0.3s ease;
        }

        .input-field input {
            width: 100%;
            padding: 14px 15px 14px 45px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 15px;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
            background-color: var(--input-bg);
            color: #fff;
        }

        .input-field input:focus {
            outline: none;
            border-color: var(--brand-red); /* Focus Red */
            background-color: #000;
            box-shadow: 0 0 0 4px rgba(215, 25, 32, 0.15); /* Red Glow */
        }
        
        .input-field input:focus + i {
            color: var(--brand-red);
        }

        /* Target icon on focus-within */
        .input-field:focus-within i {
            color: var(--brand-red);
        }

        .login-button {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 8px;
            background-color: var(--brand-red); /* Red Button */
            color: white;
            font-size: 16px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            box-shadow: 0 4px 15px rgba(215, 25, 32, 0.4);
        }

        .login-button:hover {
            background-color: #b01319; /* Darker Red Hover */
            box-shadow: 0 6px 20px rgba(215, 25, 32, 0.6);
            transform: translateY(-2px);
        }

        .error-message {
            color: #ff6b6b;
            background-color: rgba(215, 25, 32, 0.1);
            padding: 10px;
            border-radius: 6px;
            font-size: 14px;
            text-align: center;
            margin-top: 20px;
            font-weight: 500;
            border: 1px solid rgba(215, 25, 32, 0.3);
        }

        .back-link {
            margin-top: 30px;
        }

        .back-link a {
            color: var(--label-color);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .back-link a:hover {
            color: var(--brand-red);
        }

        /* Autocomplete background fix for dark mode */
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active{
            -webkit-box-shadow: 0 0 0 30px var(--input-bg) inset !important;
            -webkit-text-fill-color: white !important;
            transition: background-color 5000s ease-in-out 0s;
        }

    </style>
</head>
<body>

    <div class="main-container">
        <header class="header">
            <!-- Ensure this path is correct relative to admin/Login.php -->
            <img src="../Assets/logo.png" alt="Srishti Polytech Logo">
            <h1>Srishti <span>Polytech</span></h1>
            <p>Admin Panel Access</p>
        </header>

        <div class="login-box">
            <h2>Sign In</h2>
            <form method="post" action="">
                <div class="input-group">
                    <label for="username">Username</label>
                    <div class="input-field">
                        <input type="text" id="username" name="username" placeholder="Enter your username" required autocomplete="off">
                        <i class="fas fa-user"></i>
                    </div>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <div class="input-field">
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                        <i class="fas fa-lock"></i>
                    </div>
                </div>

                <button type="submit" class="login-button">Login to Dashboard</button>

                <?php if (!empty($error)): ?>
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle mr-1"></i> <?php echo $error; ?>
                    </div>
                <?php endif; ?>
            </form>
        </div>

        <div class="back-link">
            <a href="../index"><i class="fas fa-arrow-left"></i> Back to Website</a>
        </div>
    </div>

</body>
</html>