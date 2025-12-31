<?php
session_start();
include '../config/db.php';

// Setup Upload Directory
$targetDir = "../uploads/services/";
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

// --- HANDLE FORM SUBMISSION ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 1. ADD SERVICE
    if (isset($_POST['add_service'])) {
        $name = $conn->real_escape_string($_POST['name']);
        $description = $conn->real_escape_string($_POST['description']);
        
        $imagePaths = [];
        
        // Handle Multiple File Upload
        if (isset($_FILES['service_images'])) {
            $totalFiles = count($_FILES['service_images']['name']);
            
            for ($i = 0; $i < $totalFiles; $i++) {
                $fileName = basename($_FILES['service_images']['name'][$i]);
                $fileTmp = $_FILES['service_images']['tmp_name'][$i];
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                
                if(!empty($fileName)){
                    $newFileName = uniqid('srv_') . '.' . $fileExt;
                    $targetFilePath = $targetDir . $newFileName;
                    $dbPath = "uploads/services/" . $newFileName; 
                    
                    if (move_uploaded_file($fileTmp, $targetFilePath)) {
                        $imagePaths[] = $dbPath;
                    }
                }
            }
        }

        $imagesJson = json_encode($imagePaths);

        $sql = "INSERT INTO services (name, description, images) VALUES ('$name', '$description', '$imagesJson')";
        
        if ($conn->query($sql)) {
            $_SESSION['msg'] = "Service Added Successfully!";
            $_SESSION['type'] = "success";
        } else {
            $_SESSION['msg'] = "Error: " . $conn->error;
            $_SESSION['type'] = "error";
        }
        header("Location: admin_services.php");
        exit();
    }

    // 2. UPDATE SERVICE
    if (isset($_POST['update_service'])) {
        $id = $_POST['edit_id'];
        $name = $conn->real_escape_string($_POST['name']);
        $description = $conn->real_escape_string($_POST['description']);
        
        // Check if new images are provided
        $imageUpdateSQL = "";
        if (isset($_FILES['edit_service_images']) && !empty($_FILES['edit_service_images']['name'][0])) {
            $imagePaths = [];
            $totalFiles = count($_FILES['edit_service_images']['name']);
            
            for ($i = 0; $i < $totalFiles; $i++) {
                $fileName = basename($_FILES['edit_service_images']['name'][$i]);
                $fileTmp = $_FILES['edit_service_images']['tmp_name'][$i];
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                
                if(!empty($fileName)){
                    $newFileName = uniqid('srv_') . '.' . $fileExt;
                    $targetFilePath = $targetDir . $newFileName;
                    $dbPath = "uploads/services/" . $newFileName; 
                    
                    if (move_uploaded_file($fileTmp, $targetFilePath)) {
                        $imagePaths[] = $dbPath;
                    }
                }
            }
            $imagesJson = json_encode($imagePaths);
            $imageUpdateSQL = ", images='$imagesJson'";
        }

        $sql = "UPDATE services SET name='$name', description='$description' $imageUpdateSQL WHERE id=$id";
        
        if ($conn->query($sql)) {
            $_SESSION['msg'] = "Service Updated Successfully!";
            $_SESSION['type'] = "success";
        } else {
            $_SESSION['msg'] = "Error updating: " . $conn->error;
            $_SESSION['type'] = "error";
        }
        header("Location: admin_services.php");
        exit();
    }

    // 3. DELETE SERVICE
    if (isset($_POST['delete_id'])) {
        $id = $_POST['delete_id'];
        
        // Fetch images to delete files
        $res = $conn->query("SELECT images FROM services WHERE id=$id");
        if ($row = $res->fetch_assoc()) {
            $images = json_decode($row['images'], true);
            if(is_array($images)){
                foreach ($images as $img) {
                    if (file_exists("../" . $img)) {
                        unlink("../" . $img);
                    }
                }
            }
        }

        $conn->query("DELETE FROM services WHERE id=$id");
        $_SESSION['msg'] = "Service Deleted!";
        $_SESSION['type'] = "error";
        header("Location: admin_services.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Services</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f3f4f6; }
        .modal { display: none; background: rgba(0,0,0,0.6); backdrop-filter: blur(5px); }
        .modal-active { display: flex; }
        
        /* Custom Scrollbar for Modal */
        .modal-body::-webkit-scrollbar { width: 6px; }
        .modal-body::-webkit-scrollbar-track { background: #f1f1f1; }
        .modal-body::-webkit-scrollbar-thumb { background: #ccc; border-radius: 3px; }
        .modal-body::-webkit-scrollbar-thumb:hover { background: #1e90b8; }
    </style>
</head>
<body class="overflow-x-hidden">

    <nav class="bg-[#111] text-white shadow-lg border-b-4 border-[#1e90b8]">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-[#1e90b8] rounded-lg flex items-center justify-center font-bold text-xl"><i class="fa-solid fa-gears"></i></div>
                <h1 class="text-xl font-bold">Services Manager</h1>
            </div>
            <a href="../index.php" class="text-gray-400 hover:text-[#D71920] transition"><i class="fa-solid fa-home"></i> Home</a>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-6 mt-10 pb-20">
        
        <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4" data-aos="fade-down">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Our <span class="text-[#D71920]">Services</span></h2>
                <p class="text-gray-500 mt-1">Manage the services offered by Srishti Polytech.</p>
            </div>
            <button onclick="openModal()" class="bg-[#1e90b8] hover:bg-[#156f8f] text-white px-6 py-3 rounded-lg shadow-lg hover:shadow-xl transition-all flex items-center gap-2 transform hover:-translate-y-1">
                <i class="fa-solid fa-plus"></i> Add New Service
            </button>
        </div>

        <?php if (isset($_SESSION['msg'])): ?>
            <div class="mb-6 p-4 rounded text-white <?php echo $_SESSION['type'] == 'success' ? 'bg-green-600' : 'bg-red-600'; ?>" data-aos="fade-in">
                <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-8">
            <?php 
            $res = $conn->query("SELECT * FROM services ORDER BY id DESC");
            if ($res->num_rows > 0):
                while ($row = $res->fetch_assoc()): 
                    $images = json_decode($row['images'], true);
                    $thumb = (!empty($images) && is_array($images)) ? "../" . $images[0] : "https://via.placeholder.com/300?text=No+Image";
                    
                    // Prepare data safely for HTML attributes
                    $editName = htmlspecialchars($row['name'], ENT_QUOTES);
                    $editDesc = htmlspecialchars($row['description'], ENT_QUOTES);
            ?>
                <div class="bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-2xl transition-all duration-300 group flex flex-col h-full" data-aos="fade-up">
                    <div class="relative h-56 overflow-hidden bg-gray-100">
                        <img src="<?php echo $thumb; ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute bottom-0 left-0 w-full bg-gradient-to-t from-black/80 to-transparent p-4">
                             <div class="text-white text-xs font-bold"><i class="fa-solid fa-images"></i> <?php echo is_array($images) ? count($images) : 0; ?> Images</div>
                        </div>
                    </div>
                    <div class="p-6 flex-grow flex flex-col justify-between">
                        <div>
                            <h3 class="font-bold text-gray-800 text-xl mb-2"><?php echo $row['name']; ?></h3>
                            <p class="text-gray-500 text-sm line-clamp-3 mb-4 leading-relaxed"><?php echo strip_tags($row['description']); ?></p>
                        </div>
                        
                        <div class="border-t border-gray-100 pt-4 flex justify-between items-center mt-auto">
                            <span class="text-xs text-gray-400 font-mono">ID: <?php echo $row['id']; ?></span>
                            
                            <div class="flex gap-3">
                                <button 
                                    onclick="openEditModal(this)"
                                    data-id="<?php echo $row['id']; ?>"
                                    data-name="<?php echo $editName; ?>"
                                    data-desc="<?php echo $editDesc; ?>"
                                    class="text-[#1e90b8] hover:text-blue-700 font-semibold text-sm flex items-center gap-1 transition-colors">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </button>
                                
                                <form method="POST" onsubmit="return confirm('Delete this service?');">
                                    <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                    <button class="text-red-500 hover:text-red-700 font-semibold text-sm flex items-center gap-1 transition-colors">
                                        <i class="fa-solid fa-trash-can"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; else: ?>
                <div class="col-span-full text-center py-20 text-gray-400 bg-white rounded-xl border border-dashed border-gray-300">
                    <i class="fa-solid fa-gears text-6xl mb-4 text-gray-200"></i>
                    <p>No services added yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div id="serviceModal" class="modal fixed inset-0 z-50 items-center justify-center p-4">
        <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]" data-aos="zoom-in">
            
            <div class="bg-gray-900 px-6 py-4 flex justify-between items-center border-b border-[#1e90b8] shrink-0">
                <h3 class="text-white font-bold text-lg flex items-center gap-2"><i class="fa-solid fa-layer-group text-[#1e90b8]"></i> Add New Service</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-white transition-colors"><i class="fa-solid fa-xmark text-xl"></i></button>
            </div>
            
            <div class="modal-body overflow-y-auto p-8 space-y-6">
                <form method="POST" enctype="multipart/form-data">
                    <div>
                        <label class="block text-gray-700 font-bold mb-2 text-sm uppercase tracking-wide">Service Name</label>
                        <input type="text" name="name" required class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-[#D71920] outline-none transition placeholder-gray-400" placeholder="e.g. Industrial RO Plant">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2 text-sm uppercase tracking-wide">Service Images</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:bg-blue-50 hover:border-[#1e90b8] transition cursor-pointer relative group">
                            <input type="file" name="service_images[]" multiple required accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 group-hover:text-[#1e90b8] mb-2 transition-colors"></i>
                            <p class="text-sm text-gray-500 font-medium">Drag & drop or click to upload multiple photos</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2 text-sm uppercase tracking-wide">Description</label>
                        <textarea name="description" rows="5" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-[#D71920] outline-none transition placeholder-gray-400" placeholder="Detailed description of the service..."></textarea>
                    </div>

                    <button type="submit" name="add_service" class="w-full bg-[#D71920] hover:bg-[#b01319] text-white py-4 rounded-lg font-bold text-lg shadow-lg transition transform hover:-translate-y-1 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-save"></i> Save Service
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div id="editServiceModal" class="modal fixed inset-0 z-50 items-center justify-center p-4">
        <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
            
            <div class="bg-gray-900 px-6 py-4 flex justify-between items-center border-b border-[#1e90b8] shrink-0">
                <h3 class="text-white font-bold text-lg flex items-center gap-2"><i class="fa-solid fa-pen-to-square text-[#1e90b8]"></i> Edit Service</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-white transition-colors"><i class="fa-solid fa-xmark text-xl"></i></button>
            </div>
            
            <div class="modal-body overflow-y-auto p-8 space-y-6">
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="edit_id" id="edit_id">

                    <div>
                        <label class="block text-gray-700 font-bold mb-2 text-sm uppercase tracking-wide">Service Name</label>
                        <input type="text" name="name" id="edit_name" required class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-[#D71920] outline-none transition placeholder-gray-400">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2 text-sm uppercase tracking-wide">Update Images (Optional)</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:bg-blue-50 hover:border-[#1e90b8] transition cursor-pointer relative group">
                            <input type="file" name="edit_service_images[]" multiple accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            <i class="fa-solid fa-images text-3xl text-gray-400 group-hover:text-[#1e90b8] mb-2 transition-colors"></i>
                            <p class="text-sm text-gray-500 font-medium">Click to replace current images</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2 text-sm uppercase tracking-wide">Description</label>
                        <textarea name="description" id="edit_description" rows="5" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-[#D71920] outline-none transition placeholder-gray-400"></textarea>
                    </div>

                    <button type="submit" name="update_service" class="w-full bg-[#1e90b8] hover:bg-[#156f8f] text-white py-4 rounded-lg font-bold text-lg shadow-lg transition transform hover:-translate-y-1 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-rotate"></i> Update Service
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
        
        // Add Modal Functions
        function openModal() { document.getElementById('serviceModal').classList.add('modal-active'); }
        function closeModal() { document.getElementById('serviceModal').classList.remove('modal-active'); }
        
        // Edit Modal Functions
        function openEditModal(btn) {
            const id = btn.getAttribute('data-id');
            const name = btn.getAttribute('data-name');
            const desc = btn.getAttribute('data-desc');
            
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_description').value = desc;
            
            document.getElementById('editServiceModal').classList.add('modal-active');
        }
        
        function closeEditModal() { document.getElementById('editServiceModal').classList.remove('modal-active'); }
        
        // Close on outside click
        window.onclick = function(event) {
            const addModal = document.getElementById('serviceModal');
            const editModal = document.getElementById('editServiceModal');
            if (event.target == addModal) {
                closeModal();
            }
            if (event.target == editModal) {
                closeEditModal();
            }
        }
    </script>
</body>
</html>