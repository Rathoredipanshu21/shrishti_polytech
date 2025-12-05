<?php
session_start();
include '../config/db.php';

// Setup Upload Directory
$targetDir = "../uploads/gallery/";
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

// --- HANDLE FORM SUBMISSION ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 1. ADD IMAGE
    if (isset($_POST['add_image'])) {
        $title = $conn->real_escape_string($_POST['title']);
        
        // Handle Image Upload
        if (isset($_FILES['gallery_image']) && $_FILES['gallery_image']['error'] == 0) {
            $fileName = basename($_FILES['gallery_image']['name']);
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];

            if (in_array($fileExt, $allowed)) {
                $newFileName = uniqid('img_') . '.' . $fileExt;
                $targetFilePath = $targetDir . $newFileName;
                $dbPath = "uploads/gallery/" . $newFileName; // Path to save in DB

                if (move_uploaded_file($_FILES['gallery_image']['tmp_name'], $targetFilePath)) {
                    $sql = "INSERT INTO gallery (image_path, title) VALUES ('$dbPath', '$title')";
                    if ($conn->query($sql)) {
                        $_SESSION['msg'] = "Image uploaded successfully!";
                        $_SESSION['type'] = "success";
                    } else {
                        $_SESSION['msg'] = "Database Error: " . $conn->error;
                        $_SESSION['type'] = "error";
                    }
                } else {
                    $_SESSION['msg'] = "Failed to move uploaded file.";
                    $_SESSION['type'] = "error";
                }
            } else {
                $_SESSION['msg'] = "Invalid file type. Only JPG, PNG, WEBP allowed.";
                $_SESSION['type'] = "error";
            }
        }
        header("Location: add_gallery.php");
        exit();
    }

    // 2. DELETE IMAGE
    if (isset($_POST['delete_id'])) {
        $id = $_POST['delete_id'];
        
        // Remove file from folder
        $res = $conn->query("SELECT image_path FROM gallery WHERE id=$id");
        if ($row = $res->fetch_assoc()) {
            if (file_exists("../" . $row['image_path'])) {
                unlink("../" . $row['image_path']);
            }
        }

        // Delete from DB
        $conn->query("DELETE FROM gallery WHERE id=$id");
        $_SESSION['msg'] = "Image deleted successfully!";
        $_SESSION['type'] = "error";
        header("Location: add_gallery.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gallery Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f3f4f6; }
        .modal { display: none; background: rgba(0,0,0,0.6); backdrop-filter: blur(5px); }
        .modal-active { display: flex; }
    </style>
</head>
<body class="overflow-x-hidden">

    <!-- Navbar -->
    <nav class="bg-[#111] text-white shadow-lg border-b-4 border-[#1e90b8]">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-[#1e90b8] rounded-lg flex items-center justify-center font-bold text-xl shadow-lg">
                    <i class="fa-solid fa-images"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold tracking-wide">Gallery Manager</h1>
                    <p class="text-xs text-gray-400">Add & Manage Photos</p>
                </div>
            </div>
            <a href="../index.php" class="text-gray-400 hover:text-[#D71920] transition flex items-center gap-2">
                <i class="fa-solid fa-home"></i> Dashboard
            </a>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-6 mt-10 pb-20">
        
        <!-- Header & Add Button -->
        <div class="flex flex-col sm:flex-row justify-between items-end mb-8 gap-4" data-aos="fade-down">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Photo <span class="text-[#D71920]">Gallery</span></h2>
                <p class="text-gray-500 mt-1">Curate your visual portfolio.</p>
            </div>
            <button onclick="openModal()" class="bg-[#D71920] hover:bg-[#b01319] text-white px-6 py-3 rounded-lg shadow-lg hover:shadow-xl transition-all flex items-center gap-2 transform hover:-translate-y-1">
                <i class="fa-solid fa-cloud-arrow-up"></i> Upload Image
            </button>
        </div>

        <!-- Notification -->
        <?php if (isset($_SESSION['msg'])): ?>
            <div class="mb-6 p-4 rounded-lg text-white font-medium shadow-md flex items-center gap-3 <?php echo $_SESSION['type'] == 'success' ? 'bg-green-600' : 'bg-red-600'; ?>" data-aos="fade-in">
                <i class="fa-solid <?php echo $_SESSION['type'] == 'success' ? 'fa-check-circle' : 'fa-circle-exclamation'; ?>"></i>
                <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
            </div>
        <?php endif; ?>

        <!-- Gallery Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php 
            $res = $conn->query("SELECT * FROM gallery ORDER BY id DESC");
            if ($res->num_rows > 0):
                while ($row = $res->fetch_assoc()): 
            ?>
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-2xl transition-all duration-300 group relative border border-gray-100" data-aos="fade-up">
                    <!-- Image -->
                    <div class="relative h-48 sm:h-64 overflow-hidden bg-gray-100">
                        <img src="../<?php echo $row['image_path']; ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>
                    
                    <!-- Overlay Actions -->
                    <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-all duration-300 z-10">
                        <form method="POST" onsubmit="return confirm('Permanently delete this photo?');">
                            <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                            <button class="w-8 h-8 bg-white text-red-500 rounded-full flex items-center justify-center hover:bg-red-500 hover:text-white shadow-lg transition-colors" title="Delete">
                                <i class="fa-solid fa-trash-can text-sm"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Title -->
                    <div class="absolute bottom-0 left-0 w-full p-3 bg-gradient-to-t from-black/80 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300">
                        <p class="text-white text-sm font-medium truncate text-center">
                            <?php echo $row['title'] ? htmlspecialchars($row['title']) : 'No Title'; ?>
                        </p>
                    </div>
                </div>
            <?php endwhile; else: ?>
                <div class="col-span-full py-16 text-center border-2 border-dashed border-gray-300 rounded-xl">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                        <i class="fa-regular fa-images text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-600">Gallery is Empty</h3>
                    <p class="text-gray-400 text-sm">Start by uploading your first image.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- ADD MODAL -->
    <div id="galleryModal" class="modal fixed inset-0 z-50 items-center justify-center p-4">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden transform transition-all scale-100" data-aos="zoom-in">
            <div class="bg-gradient-to-r from-[#111] to-[#333] px-6 py-4 flex justify-between items-center border-b border-[#D71920]">
                <h3 class="text-white font-bold text-lg flex items-center gap-2"><i class="fa-solid fa-upload text-[#1e90b8]"></i> Add Photo</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-white transition-colors"><i class="fa-solid fa-xmark text-xl"></i></button>
            </div>
            
            <form method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                
                <!-- Image Input -->
                <div>
                    <label class="block text-gray-700 font-bold mb-2 text-sm uppercase tracking-wide">Select Image</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:bg-blue-50 hover:border-[#1e90b8] transition cursor-pointer relative group">
                        <input type="file" name="gallery_image" required accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div class="flex flex-col items-center">
                            <i class="fa-solid fa-image text-3xl text-gray-300 group-hover:text-[#1e90b8] transition-colors mb-2"></i>
                            <span class="text-sm text-gray-500 font-medium">Click to Browse</span>
                        </div>
                    </div>
                </div>

                <!-- Title Input -->
                <div>
                    <label class="block text-gray-700 font-bold mb-2 text-sm uppercase tracking-wide">Caption (Optional)</label>
                    <input type="text" name="title" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-[#D71920] outline-none transition" placeholder="e.g. Factory Inauguration">
                </div>

                <button type="submit" name="add_image" class="w-full bg-[#1e90b8] hover:bg-[#156f8f] text-white py-3.5 rounded-lg font-bold shadow-md hover:shadow-lg transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-check"></i> Upload Now
                </button>
            </form>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
        const modal = document.getElementById('galleryModal');
        
        function openModal() { modal.classList.add('modal-active'); }
        function closeModal() { modal.classList.remove('modal-active'); }
        
        window.onclick = (e) => { if(e.target == modal) closeModal(); }
    </script>
</body>
</html>