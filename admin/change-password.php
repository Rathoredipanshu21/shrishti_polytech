<?php
session_start();
include '../config/db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}

$msg = "";
$msg_type = "";

// --- HANDLE FORM SUBMISSION ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $current_pass = $_POST['current_password'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];
    $admin_user = $_SESSION['admin']; // Assuming session stores username

    // 1. Validate Inputs
    if (empty($current_pass) || empty($new_pass) || empty($confirm_pass)) {
        $msg = "All fields are required.";
        $msg_type = "error";
    } elseif ($new_pass !== $confirm_pass) {
        $msg = "New passwords do not match.";
        $msg_type = "error";
    } else {
        // 2. Verify Old Password
        // Use prepared statements for security
        $stmt = $conn->prepare("SELECT id, password FROM admin WHERE username = ?");
        $stmt->bind_param("s", $admin_user);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hashed_password = $row['password'];

            // Verify hash (Use `==` if you are storing plain text, but password_verify is recommended)
            if (password_verify($current_pass, $hashed_password)) {
                
                // 3. Update Password
                $new_hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
                
                $updateStmt = $conn->prepare("UPDATE admin SET password = ? WHERE id = ?");
                $updateStmt->bind_param("si", $new_hashed_pass, $row['id']);
                
                if ($updateStmt->execute()) {
                    $msg = "Password updated successfully!";
                    $msg_type = "success";
                } else {
                    $msg = "Error updating password.";
                    $msg_type = "error";
                }
            } else {
                $msg = "Incorrect current password.";
                $msg_type = "error";
            }
        } else {
            $msg = "Admin user not found.";
            $msg_type = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Change Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f3f4f6; }
        
        /* Input Focus Styles */
        .form-input:focus {
            border-color: #1e90b8;
            box-shadow: 0 0 0 4px rgba(30, 144, 184, 0.1);
        }
        
        /* Password Strength Line */
        .strength-bar {
            height: 4px;
            border-radius: 2px;
            transition: all 0.3s ease;
            width: 0%;
        }
    </style>
</head>
<body class="overflow-x-hidden">

    <!-- Navbar -->
    <nav class="bg-[#111] text-white shadow-lg border-b-4 border-[#D71920]">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-[#D71920] rounded-lg flex items-center justify-center font-bold text-xl shadow-lg">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold tracking-wide">Security Settings</h1>
                    <p class="text-xs text-gray-400">Update Access Credentials</p>
                </div>
            </div>
            <a href="../index.php" class="text-gray-400 hover:text-[#1e90b8] transition flex items-center gap-2">
                <i class="fa-solid fa-home"></i> Dashboard
            </a>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-6 mt-10 pb-20">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4" data-aos="fade-down">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Change <span class="text-[#1e90b8]">Password</span></h2>
                <p class="text-gray-500 mt-1">Keep your admin account secure.</p>
            </div>
        </div>

        <!-- Notification -->
        <?php if ($msg): ?>
            <div class="mb-6 p-4 rounded-lg text-white font-medium shadow-md flex items-center gap-3 <?php echo $msg_type == 'success' ? 'bg-green-600' : 'bg-[#D71920]'; ?>" data-aos="fade-in">
                <i class="fa-solid <?php echo $msg_type == 'success' ? 'fa-check-circle' : 'fa-circle-exclamation'; ?>"></i>
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            
            <!-- Left: Form -->
            <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100 h-full" data-aos="fade-right">
                <form method="POST" class="space-y-6">
                    
                    <!-- Current Password -->
                    <div>
                        <label class="block text-gray-700 font-bold mb-2 text-sm uppercase tracking-wide">Current Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                <i class="fa-solid fa-key"></i>
                            </span>
                            <input type="password" name="current_password" required class="form-input w-full bg-gray-50 border border-gray-300 rounded-lg pl-10 pr-4 py-3 outline-none transition" placeholder="Enter current password">
                        </div>
                    </div>

                    <div class="border-t border-gray-100 my-4"></div>

                    <!-- New Password -->
                    <div>
                        <label class="block text-gray-700 font-bold mb-2 text-sm uppercase tracking-wide">New Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                <i class="fa-solid fa-lock"></i>
                            </span>
                            <input type="password" id="new_pass" name="new_password" required class="form-input w-full bg-gray-50 border border-gray-300 rounded-lg pl-10 pr-4 py-3 outline-none transition" placeholder="Enter new password">
                        </div>
                        <!-- Strength Visual -->
                        <div class="w-full bg-gray-200 h-1 mt-2 rounded-full overflow-hidden">
                            <div class="strength-bar bg-[#D71920]" id="strength-bar"></div>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-gray-700 font-bold mb-2 text-sm uppercase tracking-wide">Confirm New Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                <i class="fa-solid fa-check-double"></i>
                            </span>
                            <input type="password" name="confirm_password" required class="form-input w-full bg-gray-50 border border-gray-300 rounded-lg pl-10 pr-4 py-3 outline-none transition" placeholder="Confirm new password">
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="pt-4">
                        <button type="submit" class="w-full bg-[#1e90b8] hover:bg-[#156f8f] text-white font-bold py-4 rounded-lg shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2">
                            <span>Update Password</span>
                            <i class="fa-solid fa-shield-check"></i>
                        </button>
                    </div>

                </form>
            </div>

            <!-- Right: Info / Illustration -->
            <div class="bg-[#111] p-8 rounded-2xl shadow-xl flex flex-col justify-center items-center text-center relative overflow-hidden" data-aos="fade-left">
                
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <i class="fa-solid fa-fingerprint text-[300px] absolute -right-10 -bottom-10 text-white"></i>
                </div>

                <div class="relative z-10">
                    <div class="w-20 h-20 bg-[#1e90b8] rounded-full flex items-center justify-center text-white text-3xl mx-auto mb-6 shadow-lg shadow-blue-900/50">
                        <i class="fa-solid fa-user-shield"></i>
                    </div>
                    
                    <h3 class="text-2xl font-bold text-white mb-4">Secure Your Account</h3>
                    <p class="text-gray-400 mb-8 max-w-sm mx-auto leading-relaxed">
                        Regularly updating your password helps prevent unauthorized access. Ensure your new password is strong and unique.
                    </p>

                    <div class="bg-white/5 border border-white/10 rounded-lg p-6 text-left max-w-sm mx-auto">
                        <h4 class="text-[#D71920] font-bold mb-3 flex items-center gap-2 text-sm uppercase"><i class="fa-solid fa-circle-info"></i> Password Tips</h4>
                        <ul class="text-gray-400 text-sm space-y-2">
                            <li class="flex items-center gap-2"><i class="fa-solid fa-check text-green-500"></i> Use at least 8 characters</li>
                            <li class="flex items-center gap-2"><i class="fa-solid fa-check text-green-500"></i> Mix letters & numbers</li>
                            <li class="flex items-center gap-2"><i class="fa-solid fa-check text-green-500"></i> Include special symbols</li>
                        </ul>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();

        // Simple Password Strength Visualizer
        const passInput = document.getElementById('new_pass');
        const strengthBar = document.getElementById('strength-bar');

        passInput.addEventListener('input', function() {
            const val = this.value;
            let strength = 0;
            if (val.length > 5) strength += 20;
            if (val.length > 8) strength += 20;
            if (/[A-Z]/.test(val)) strength += 20;
            if (/[0-9]/.test(val)) strength += 20;
            if (/[^A-Za-z0-9]/.test(val)) strength += 20;

            strengthBar.style.width = strength + '%';
            
            if(strength < 40) strengthBar.style.backgroundColor = '#D71920'; // Red
            else if(strength < 80) strengthBar.style.backgroundColor = '#eab308'; // Yellow
            else strengthBar.style.backgroundColor = '#22c55e'; // Green
        });
    </script>
</body>
</html>