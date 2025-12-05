<?php
session_start();
include '../config/db.php';

// Setup Upload Directory
$uploadDir = '../uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// --- ACTIONS ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 1. Handle Headline Update
    if (isset($_POST['update_headline'])) {
        $id_to_update = $_POST['edit_image_id'];
        $headline = !empty($_POST['headline']) ? trim($_POST['headline']) : null;

        $stmt = $conn->prepare("UPDATE hero_images SET headline = ? WHERE id = ?");
        $stmt->bind_param("si", $headline, $id_to_update);

        if ($stmt->execute()) {
            $_SESSION['msg'] = "Headline updated successfully!";
            $_SESSION['type'] = "success";
        } else {
            $_SESSION['msg'] = "Error updating headline.";
            $_SESSION['type'] = "error";
        }
        $stmt->close();
        header("Location: hero_banner.php");
        exit();
    }

    // 2. Handle Image Upload
    if (isset($_FILES['banner_image'])) {
        $image = $_FILES['banner_image'];
        $headline = $_POST['headline'] ?? '';
        $description = $_POST['description'] ?? '';

        if ($image['error'] == 0) {
            $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '_hero.' . $ext;
            $target = $uploadDir . $filename;
            $dbPath = 'uploads/' . $filename;

            if (move_uploaded_file($image['tmp_name'], $target)) {
                $stmt = $conn->prepare("INSERT INTO hero_images (image_path, headline, description) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $dbPath, $headline, $description);
                $stmt->execute();
                $_SESSION['msg'] = "Banner Added Successfully!";
                $_SESSION['type'] = "success";
            }
        }
    }

    // 3. Handle Delete
    if (isset($_POST['delete_image'])) {
        $id = $_POST['image_id'];
        $path = $_POST['image_path'];
        
        $conn->query("DELETE FROM hero_images WHERE id=$id");
        // Remove file if it exists
        if (file_exists("../" . $path)) {
            unlink("../" . $path);
        } else if (file_exists($path)) { 
            // Try relative to current file if ../ fails
            unlink($path);
        }
        
        $_SESSION['msg'] = "Banner Deleted!";
        $_SESSION['type'] = "error";
    }

    header("Location: hero_banner.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Hero Banners</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #f3f4f6; 
        }
        /* Srishti Polytech Colors */
        .text-brand-red { color: #D71920; }
        .bg-brand-red { background-color: #D71920; }
        .hover-bg-brand-red:hover { background-color: #b01319; }
        
        .text-brand-teal { color: #1e90b8; }
        .bg-brand-teal { background-color: #1e90b8; }
        .hover-bg-brand-teal:hover { background-color: #156f8f; }

        .bg-brand-dark { background-color: #111111; }
        
        /* Modal Overlay */
        .modal {
            display: none;
            position: fixed;
            z-index: 50;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.6);
            backdrop-filter: blur(4px);
            align-items: center;
            justify-content: center;
        }
        .modal-active { display: flex; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen pb-10">

    <!-- Top Navigation Bar (Consistent with Brand) -->
    <nav class="bg-brand-dark text-white shadow-lg border-b-4 border-[#D71920]">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-[#D71920] rounded-lg flex items-center justify-center text-white font-bold text-xl shadow-md">
                    <i class="fa-solid fa-images"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold tracking-wide">Hero Manager</h1>
                    <p class="text-xs text-gray-400">Admin Dashboard</p>
                </div>
            </div>
            <div>
                 <a href="../index.php" target="_blank" class="text-gray-300 hover:text-[#1e90b8] transition-colors text-sm flex items-center gap-2">
                    <i class="fa-solid fa-external-link-alt"></i> View Site
                 </a>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-6 mt-10">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4" data-aos="fade-down">
            <div>
                <h2 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
                    <span class="text-[#1e90b8]">Home Page</span> Slides
                </h2>
                <p class="text-gray-500 mt-2 flex items-center gap-2">
                    <i class="fa-solid fa-info-circle text-[#D71920]"></i>
                    Manage the 70vh high-impact visuals for your homepage.
                </p>
            </div>
            
            <button onclick="openModal('addBannerModal')" 
                    class="bg-[#1e90b8] hover:bg-[#156f8f] text-white px-6 py-3 rounded-lg shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1 flex items-center gap-2 font-medium">
                <i class="fa-solid fa-plus-circle text-lg"></i> Add New Slide
            </button>
        </div>

        <!-- Alerts -->
        <?php if (isset($_SESSION['msg'])): ?>
            <div class="mb-6 p-4 rounded-lg text-white font-medium shadow-md flex items-center gap-3 <?php echo $_SESSION['type'] == 'success' ? 'bg-green-600' : 'bg-[#D71920]'; ?>" data-aos="fade-in">
                <i class="fa-solid <?php echo $_SESSION['type'] == 'success' ? 'fa-check-circle' : 'fa-triangle-exclamation'; ?> text-xl"></i>
                <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
            </div>
        <?php endif; ?>

        <!-- Info Card -->
        <div class="bg-white border-l-4 border-[#D71920] p-5 mb-10 rounded-r-lg shadow-sm flex items-start gap-4" data-aos="fade-right">
            <div class="bg-red-50 p-3 rounded-full text-[#D71920]">
                <i class="fa-solid fa-ruler-combined text-xl"></i>
            </div>
            <div>
                <h3 class="font-bold text-gray-800">Recommended Dimensions</h3>
                <p class="text-sm text-gray-600 mt-1">
                    For the best cinematic look on the <strong>70vh</strong> hero section, please upload images with a resolution of 
                    <span class="bg-gray-100 px-2 py-0.5 rounded text-[#111] font-mono border border-gray-300">1920 x 900 px</span>.
                </p>
            </div>
        </div>

        <!-- Grid Layout -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php 
            $res = $conn->query("SELECT * FROM hero_images ORDER BY id DESC");
            if ($res->num_rows > 0):
                while ($row = $res->fetch_assoc()): 
            ?>
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-2xl transition-all duration-300 group border border-gray-100" data-aos="fade-up">
                    
                    <!-- Image Area -->
                    <div class="relative h-56 overflow-hidden bg-gray-100">
                        <img src="../<?php echo htmlspecialchars($row['image_path']); ?>" alt="Banner" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        
                        <!-- Overlay Actions -->
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-3">
                            <button onclick="openEditModal('<?php echo $row['id']; ?>', '<?php echo htmlspecialchars(addslashes($row['headline'])); ?>')" 
                                    class="w-10 h-10 rounded-full bg-white text-[#1e90b8] hover:bg-[#1e90b8] hover:text-white transition-all flex items-center justify-center shadow-lg" 
                                    title="Edit Headline">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <form method="POST" onsubmit="return confirm('Are you sure you want to delete this slide permanently?');">
                                <input type="hidden" name="image_id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="image_path" value="../<?php echo htmlspecialchars($row['image_path']); ?>">
                                <button type="submit" name="delete_image"
                                        class="w-10 h-10 rounded-full bg-white text-[#D71920] hover:bg-[#D71920] hover:text-white transition-all flex items-center justify-center shadow-lg"
                                        title="Delete Slide">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Content Area -->
                    <div class="p-5">
                        <div class="flex items-center gap-2 mb-2">
                            <i class="fa-solid fa-heading text-[#D71920] text-xs"></i>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Headline</span>
                        </div>
                        <h4 class="font-bold text-gray-800 text-lg mb-2 line-clamp-1" title="<?php echo htmlspecialchars($row['headline']); ?>">
                            <?php echo $row['headline'] ? htmlspecialchars($row['headline']) : '<span class="italic text-gray-400">No Headline Set</span>'; ?>
                        </h4>
                        
                        <div class="w-full h-px bg-gray-100 my-3"></div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-400 bg-gray-50 px-2 py-1 rounded">ID: <?php echo $row['id']; ?></span>
                            <span class="text-xs text-[#1e90b8] font-medium"><i class="fa-regular fa-clock"></i> Active</span>
                        </div>
                    </div>
                    
                    <!-- Bottom Color Strip -->
                    <div class="h-1 w-full bg-gradient-to-r from-[#D71920] to-[#1e90b8]"></div>
                </div>
            <?php 
                endwhile; 
            else:
            ?>
                <!-- Empty State -->
                <div class="col-span-full py-16 text-center bg-white rounded-xl border-2 border-dashed border-gray-300">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                        <i class="fa-regular fa-images text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-700">No Slides Found</h3>
                    <p class="text-gray-500 mt-2">Get started by adding your first banner image.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- ADD MODAL -->
    <div id="addBannerModal" class="modal">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden transform transition-all" data-aos="zoom-in">
            
            <div class="bg-gradient-to-r from-[#111] to-[#333] px-6 py-4 flex justify-between items-center border-b border-[#D71920]">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-cloud-upload-alt text-[#1e90b8]"></i> Upload New Slide
                </h3>
                <button onclick="closeModal('addBannerModal')" class="text-gray-400 hover:text-white transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <form method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                
                <!-- File Input -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Banner Image <span class="text-[#D71920]">*</span></label>
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:bg-blue-50 hover:border-[#1e90b8] transition-colors cursor-pointer relative group">
                        <input type="file" name="banner_image" required accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div class="flex flex-col items-center">
                            <i class="fa-solid fa-image text-4xl text-gray-300 group-hover:text-[#1e90b8] transition-colors mb-3"></i>
                            <p class="text-sm font-medium text-gray-600">Click to browse or drag image here</p>
                            <p class="text-xs text-gray-400 mt-1">Supports JPG, PNG, WEBP</p>
                        </div>
                    </div>
                </div>

                <!-- Text Input -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Headline Text</label>
                    <div class="relative">
                        <span class="absolute left-4 top-3 text-gray-400"><i class="fa-solid fa-heading"></i></span>
                        <input type="text" name="headline" placeholder="e.g. Leading the Industry" 
                               class="w-full bg-gray-50 border border-gray-300 rounded-lg pl-10 pr-4 py-3 focus:ring-2 focus:ring-[#1e90b8] focus:border-transparent outline-none transition-all">
                    </div>
                </div>

                <!-- Desc Input -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Description (Optional)</label>
                    <div class="relative">
                         <span class="absolute left-4 top-3 text-gray-400"><i class="fa-solid fa-align-left"></i></span>
                        <textarea name="description" rows="2" placeholder="Short subtitle..." 
                               class="w-full bg-gray-50 border border-gray-300 rounded-lg pl-10 pr-4 py-3 focus:ring-2 focus:ring-[#1e90b8] focus:border-transparent outline-none transition-all"></textarea>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-[#1e90b8] hover:bg-[#156f8f] text-white py-3.5 rounded-lg font-bold shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-check"></i> Upload Slide
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT MODAL -->
    <div id="editBannerModal" class="modal">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden transform transition-all" data-aos="zoom-in">
            
            <div class="bg-gradient-to-r from-[#111] to-[#333] px-6 py-4 flex justify-between items-center border-b border-[#D71920]">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-[#D71920]"></i> Edit Details
                </h3>
                <button onclick="closeModal('editBannerModal')" class="text-gray-400 hover:text-white transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <form method="POST" class="p-6 space-y-5">
                <input type="hidden" name="edit_image_id" id="edit_image_id">
                <input type="hidden" name="update_headline" value="1">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Update Headline</label>
                    <div class="relative">
                        <span class="absolute left-4 top-3 text-gray-400"><i class="fa-solid fa-heading"></i></span>
                        <input type="text" name="headline" id="edit_headline" 
                               class="w-full bg-gray-50 border border-gray-300 rounded-lg pl-10 pr-4 py-3 focus:ring-2 focus:ring-[#D71920] focus:border-transparent outline-none transition-all">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-[#D71920] hover:bg-[#b01319] text-white py-3.5 rounded-lg font-bold shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true
        });

        // Modal Functions
        function openModal(modalId) {
            document.getElementById(modalId).classList.add('modal-active');
            // Refresh AOS inside modal
            setTimeout(() => AOS.refresh(), 100);
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('modal-active');
        }

        function openEditModal(id, headline) {
            document.getElementById('edit_image_id').value = id;
            document.getElementById('edit_headline').value = headline;
            openModal('editBannerModal');
        }

        // Close on outside click
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.classList.remove('modal-active');
            }
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>