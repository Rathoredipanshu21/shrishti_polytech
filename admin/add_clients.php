<?php
// Include Database Configuration
include '../config/db.php';

// Handle Form Submission
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_client'])) {
    $client_name = $conn->real_escape_string($_POST['client_name']);
    $description = $conn->real_escape_string($_POST['description']);
    
    // File Upload Logic
    $target_dir = "../uploads/clients_logo/";
    
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_name = time() . "_" . basename($_FILES["client_logo"]["name"]);
    $target_file = $target_dir . $file_name;
    
    // Database path
    $db_file_path = "uploads/clients_logo/" . $file_name;

    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Check if image file is a actual image
    if(getimagesize($_FILES["client_logo"]["tmp_name"]) === false) {
        $message = "<div class='bg-red-100 text-red-700 p-3 rounded mb-4'>File is not an image.</div>";
        $uploadOk = 0;
    }

    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["client_logo"]["tmp_name"], $target_file)) {
            $sql = "INSERT INTO clients (client_name, description, client_logo) VALUES ('$client_name', '$description', '$db_file_path')";
            if ($conn->query($sql) === TRUE) {
                $message = "<div class='bg-green-100 text-green-700 p-3 rounded mb-4'>New client added successfully!</div>";
            } else {
                $message = "<div class='bg-red-100 text-red-700 p-3 rounded mb-4'>Error: " . $conn->error . "</div>";
            }
        } else {
            $message = "<div class='bg-red-100 text-red-700 p-3 rounded mb-4'>Sorry, there was an error uploading your file.</div>";
        }
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // Get file path to unlink
    $query = "SELECT client_logo FROM clients WHERE id = $id";
    $result = $conn->query($query);
    if($row = $result->fetch_assoc()){
        $file_to_delete = "../" . $row['client_logo'];
        if(file_exists($file_to_delete)){
            unlink($file_to_delete);
        }
    }
    $conn->query("DELETE FROM clients WHERE id=$id");
    echo "<script>window.location.href='add_clients.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Clients</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">

    <div class="container mx-auto px-4 py-12">
        
        <div class="flex justify-between items-center mb-10" data-aos="fade-down">
            <h1 class="text-3xl font-bold text-gray-800"><i class="fa-solid fa-user-tie text-blue-600 mr-3"></i>Manage Clients</h1>
            <a href="../client.php" target="_blank" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"><i class="fa-solid fa-eye mr-2"></i>View Public Page</a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-1" data-aos="fade-right">
                <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-blue-600">
                    <h2 class="text-xl font-bold mb-6 text-gray-700 border-b pb-2">Add New Client</h2>
                    <?php echo $message; ?>
                    
                    <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
                        <div>
                            <label class="block text-gray-600 text-sm font-semibold mb-1">Client Title/Name</label>
                            <input type="text" name="client_name" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g. Tata Steel">
                        </div>

                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <label class="block text-gray-600 text-sm font-semibold">Description <span class="text-gray-400 font-normal">(Optional)</span></label>
                                <span class="text-xs text-red-500 font-bold bg-red-50 px-2 py-0.5 rounded">Max 10-15 Words!</span>
                            </div>
                            <textarea name="description" rows="3" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Keep it short (e.g. 'Leading steel manufacturing company')"></textarea>
                            <p class="text-xs text-gray-500 mt-1">Please write only short descriptions to fit the card design.</p>
                        </div>

                        <div>
                            <label class="block text-gray-600 text-sm font-semibold mb-1">Client Logo</label>
                            <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:bg-gray-50 transition">
                                <input type="file" name="client_logo" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 mb-2"></i>
                                <p class="text-sm text-gray-500">Click to upload or drag & drop</p>
                                <p class="text-xs text-blue-500 mt-2 font-medium">Recommended Size: 200x150px (PNG/JPG)</p>
                            </div>
                        </div>

                        <button type="submit" name="add_client" class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-1">
                            <i class="fa-solid fa-plus-circle mr-2"></i> Add Client
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2" data-aos="fade-left">
                <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-gray-800">
                    <h2 class="text-xl font-bold mb-6 text-gray-700 border-b pb-2">Current Clients</h2>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-sm text-gray-500 border-b">
                                    <th class="py-3 px-2">Logo</th>
                                    <th class="py-3 px-2">Name</th>
                                    <th class="py-3 px-2">Description</th>
                                    <th class="py-3 px-2 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                <?php
                                $sql = "SELECT * FROM clients ORDER BY id DESC";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while($row = $result->fetch_assoc()) {
                                        echo "<tr class='border-b hover:bg-gray-50 transition'>";
                                        echo "<td class='py-3 px-2'><img src='../" . $row['client_logo'] . "' class='w-12 h-12 object-contain rounded border bg-white p-1'></td>";
                                        echo "<td class='py-3 px-2 font-bold text-gray-800'>" . $row['client_name'] . "</td>";
                                        echo "<td class='py-3 px-2 text-gray-500 truncate max-w-xs'>" . $row['description'] . "</td>";
                                        echo "<td class='py-3 px-2 text-right'>
                                                <a href='?delete=" . $row['id'] . "' onclick='return confirm(\"Are you sure?\")' class='text-red-500 hover:text-red-700 bg-red-100 hover:bg-red-200 p-2 rounded-full transition'>
                                                    <i class='fa-solid fa-trash-can'></i>
                                                </a>
                                              </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4' class='text-center py-6 text-gray-400'>No clients found.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>
</html>