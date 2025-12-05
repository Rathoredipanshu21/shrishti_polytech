<?php
// Start a session for messages/potential auth
session_start();

// NOTE: In a real application, implement robust user authentication and authorization here
// For simplicity, this example skips full auth.

// Include the database configuration
include '../config/db.php';

$message = '';
$error = '';

// --- Handle Form Submission (UPDATE) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve form data
    $new_address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
    $new_email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $new_phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);

    $updates_successful = true;

    // List of settings to update
    $settings_to_update = [
        'address' => $new_address,
        'email' => $new_email,
        'phone' => $new_phone
    ];

    foreach ($settings_to_update as $key => $value) {
        // Prepare an SQL statement to UPDATE or INSERT (using ON DUPLICATE KEY UPDATE for robustness)
        $stmt = $conn->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?) 
                                ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
        
        if ($stmt) {
            $stmt->bind_param("ss", $key, $value);

            if (!$stmt->execute()) {
                $updates_successful = false;
                $error .= "Failed to update $key: " . $stmt->error . "<br>";
            }
            $stmt->close();
        } else {
            $updates_successful = false;
            $error .= "Failed to prepare statement for $key: " . $conn->error . "<br>";
        }
    }

    if ($updates_successful) {
        $_SESSION['admin_message'] = "Contact details updated successfully!";
        // Redirect to prevent form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $message = '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert"><p class="font-bold">Error!</p><p>' . $error . '</p></div>';
    }
}

// Check for success message in session
if (isset($_SESSION['admin_message'])) {
    $message = '<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert"><p class="font-bold">Success!</p><p>' . $_SESSION['admin_message'] . '</p></div>';
    unset($_SESSION['admin_message']);
}


// --- Fetch Current Settings (SELECT) ---
$current_settings = [
    'address' => '',
    'email' => '',
    'phone' => ''
];

$result = $conn->query("SELECT setting_key, setting_value FROM site_settings WHERE setting_key IN ('address', 'email', 'phone')");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $current_settings[$row['setting_key']] = htmlspecialchars($row['setting_value']);
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | Contact Settings</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .teal-theme { background-color: #115E59; }
    </style>
</head>
<body class="bg-gray-100 p-8">

    <div class="max-w-4xl mx-auto bg-white p-8 md:p-12 rounded-xl shadow-2xl">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-4">Admin Panel: Contact Settings</h1>
        
        <?= $message ?>

        <form action="contact.php" method="POST" class="space-y-6">
            
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Our Address</label>
                <textarea name="address" id="address" rows="3" required class="mt-1 block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500"><?= $current_settings['address'] ?></textarea>
            </div>
            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input type="email" name="email" id="email" value="<?= $current_settings['email'] ?>" required class="mt-1 block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500">
            </div>
            
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                <input type="tel" name="phone" id="phone" value="<?= $current_settings['phone'] ?>" required class="mt-1 block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500">
            </div>
            
            <div class="pt-4">
                <button type="submit" class="w-full teal-theme text-white font-bold py-3 px-6 rounded-lg hover:bg-teal-700 transition duration-300 transform hover:scale-[1.01] shadow-lg">
                    Update Contact Details
                </button>
            </div>
        </form>
    </div>

</body>
</html>