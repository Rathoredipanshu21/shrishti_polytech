<?php
session_start();
include '../config/db.php';

// Setup Upload Directory
$targetDir = "../uploads/products/";
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

// --- HANDLE FORM SUBMISSION ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 1. ADD PRODUCT
    if (isset($_POST['add_product'])) {
        $name = $conn->real_escape_string($_POST['name']);
        $description = $conn->real_escape_string($_POST['description']);
        
        $imagePaths = [];
        
        // Handle Multiple File Upload
        if (isset($_FILES['product_images'])) {
            $totalFiles = count($_FILES['product_images']['name']);
            
            for ($i = 0; $i < $totalFiles; $i++) {
                $fileName = basename($_FILES['product_images']['name'][$i]);
                $fileTmp = $_FILES['product_images']['tmp_name'][$i];
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                
                if(!empty($fileName)){
                    $newFileName = uniqid('prod_') . '.' . $fileExt;
                    $targetFilePath = $targetDir . $newFileName;
                    $dbPath = "uploads/products/" . $newFileName; 
                    
                    if (move_uploaded_file($fileTmp, $targetFilePath)) {
                        $imagePaths[] = $dbPath;
                    }
                }
            }
        }

        $imagesJson = json_encode($imagePaths);

        $sql = "INSERT INTO products (name, description, images) VALUES ('$name', '$description', '$imagesJson')";
        
        if ($conn->query($sql)) {
            $_SESSION['msg'] = "Product Added Successfully!";
            $_SESSION['type'] = "success";
        } else {
            $_SESSION['msg'] = "Error: " . $conn->error;
            $_SESSION['type'] = "error";
        }
        header("Location: add_products.php");
        exit();
    }

    // 2. UPDATE PRODUCT
    if (isset($_POST['update_product'])) {
        $id = $_POST['edit_id'];
        $name = $conn->real_escape_string($_POST['name']);
        $description = $conn->real_escape_string($_POST['description']);
        
        // Check if new images are provided
        $imageUpdateSQL = "";
        if (isset($_FILES['edit_product_images']) && !empty($_FILES['edit_product_images']['name'][0])) {
            $imagePaths = [];
            $totalFiles = count($_FILES['edit_product_images']['name']);
            
            for ($i = 0; $i < $totalFiles; $i++) {
                $fileName = basename($_FILES['edit_product_images']['name'][$i]);
                $fileTmp = $_FILES['edit_product_images']['tmp_name'][$i];
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                
                if(!empty($fileName)){
                    $newFileName = uniqid('prod_') . '.' . $fileExt;
                    $targetFilePath = $targetDir . $newFileName;
                    $dbPath = "uploads/products/" . $newFileName; 
                    
                    if (move_uploaded_file($fileTmp, $targetFilePath)) {
                        $imagePaths[] = $dbPath;
                    }
                }
            }
            $imagesJson = json_encode($imagePaths);
            $imageUpdateSQL = ", images='$imagesJson'";
        }

        $sql = "UPDATE products SET name='$name', description='$description' $imageUpdateSQL WHERE id=$id";
        
        if ($conn->query($sql)) {
            $_SESSION['msg'] = "Product Updated Successfully!";
            $_SESSION['type'] = "success";
        } else {
            $_SESSION['msg'] = "Error updating: " . $conn->error;
            $_SESSION['type'] = "error";
        }
        header("Location: add_products.php");
        exit();
    }

    // 3. DELETE PRODUCT
    if (isset($_POST['delete_id'])) {
        $id = $_POST['delete_id'];
        
        // Fetch images to delete files
        $res = $conn->query("SELECT images FROM products WHERE id=$id");
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

        $conn->query("DELETE FROM products WHERE id=$id");
        $_SESSION['msg'] = "Product Deleted!";
        $_SESSION['type'] = "error";
        header("Location: add_products.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Products</title>
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

    <nav class="bg-[#111] text-white shadow-lg border-b-4 border-[#D71920]">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-[#D71920] rounded-lg flex items-center justify-center font-bold text-xl"><i class="fa-solid fa-box-open"></i></div>
                <h1 class="text-xl font-bold">Product Manager</h1>
            </div>
            <a href="../index.php" class="text-gray-400 hover:text-[#1e90b8] transition"><i class="fa-solid fa-home"></i> Home</a>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-6 mt-10 pb-20">
        
        <div class="flex justify-between items-end mb-8" data-aos="fade-down">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Our <span class="text-[#1e90b8]">Products</span></h2>
                <p class="text-gray-500 mt-1">Manage catalogue items visible on the website.</p>
            </div>
            <button onclick="openModal()" class="bg-[#D71920] hover:bg-[#b01319] text-white px-6 py-3 rounded-lg shadow-lg hover:shadow-xl transition-all flex items-center gap-2 transform hover:-translate-y-1">
                <i class="fa-solid fa-plus"></i> Add New Product
            </button>
        </div>

        <?php if (isset($_SESSION['msg'])): ?>
            <div class="mb-6 p-4 rounded text-white <?php echo $_SESSION['type'] == 'success' ? 'bg-green-600' : 'bg-red-600'; ?>" data-aos="fade-in">
                <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php 
            $res = $conn->query("SELECT * FROM products ORDER BY id DESC");
            if ($res->num_rows > 0):
                while ($row = $res->fetch_assoc()): 
                    $images = json_decode($row['images'], true);
                    $thumb = !empty($images) && is_array($images) ? "../" . $images[0] : "https://via.placeholder.com/300?text=No+Image";
                    
                    // Prepare data safe for HTML attributes
                    $editName = htmlspecialchars($row['name'], ENT_QUOTES);
                    $editDesc = htmlspecialchars($row['description'], ENT_QUOTES);
            ?>
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-2xl transition-all duration-300 group" data-aos="fade-up">
                    <div class="relative h-48 overflow-hidden bg-gray-100">
                        <img src="<?php echo $thumb; ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute top-2 right-2 bg-black/60 text-white px-2 py-1 rounded text-xs">
                            <i class="fa-solid fa-images"></i> <?php echo is_array($images) ? count($images) : 0; ?>
                        </div>
                    </div>
                    <div class="p-5">
                        <h3 class="font-bold text-gray-800 text-lg mb-1 truncate"><?php echo $row['name']; ?></h3>
                        <p class="text-gray-500 text-sm line-clamp-2 h-10 mb-4"><?php echo strip_tags($row['description']); ?></p>
                        
                        <div class="border-t pt-4 flex justify-between items-center gap-2">
                            <span class="text-xs text-gray-400">ID: #<?php echo $row['id']; ?></span>
                            
                            <div class="flex gap-3">
                                <button 
                                    onclick="openEditModal(this)" 
                                    data-id="<?php echo $row['id']; ?>"
                                    data-name="<?php echo $editName; ?>"
                                    data-desc="<?php echo $editDesc; ?>"
                                    class="text-[#1e90b8] hover:text-blue-700 font-medium text-sm flex items-center gap-1">
                                    <i class="fa-solid fa-pen"></i> Edit
                                </button>
                                
                                <form method="POST" onsubmit="return confirm('Delete this product?');">
                                    <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                    <button class="text-[#D71920] hover:text-red-700 font-medium text-sm flex items-center gap-1">
                                        <i class="fa-solid fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; else: ?>
                <div class="col-span-full text-center py-20 text-gray-400">
                    <i class="fa-solid fa-box-open text-6xl mb-4"></i>
                    <p>No products added yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div id="productModal" class="modal fixed inset-0 z-50 items-center justify-center p-4">
        <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]" data-aos="zoom-in">
            
            <div class="bg-gray-900 px-6 py-4 flex justify-between items-center border-b border-[#D71920] shrink-0">
                <h3 class="text-white font-bold text-lg"><i class="fa-solid fa-cloud-upload-alt text-[#1e90b8]"></i> Add Product</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-white"><i class="fa-solid fa-xmark text-xl"></i></button>
            </div>
            
            <div class="modal-body overflow-y-auto p-8 space-y-6">
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Product Name</label>
                        <input type="text" name="name" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-[#1e90b8] outline-none transition">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Product Images (Select Multiple)</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:bg-gray-50 transition cursor-pointer relative">
                            <input type="file" name="product_images[]" multiple required accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            <i class="fa-solid fa-images text-3xl text-gray-400 mb-2"></i>
                            <p class="text-sm text-gray-500">Drag & drop or click to upload</p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 font-bold mb-2">Description / Specifications</label>
                        <textarea name="description" rows="5" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-[#1e90b8] outline-none transition" placeholder="Enter product details..."></textarea>
                    </div>

                    <button type="submit" name="add_product" class="w-full bg-[#1e90b8] hover:bg-[#156f8f] text-white py-4 rounded-lg font-bold text-lg shadow-lg transition transform hover:-translate-y-1">
                        Save Product
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div id="editProductModal" class="modal fixed inset-0 z-50 items-center justify-center p-4">
        <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
            
            <div class="bg-gray-900 px-6 py-4 flex justify-between items-center border-b border-[#D71920] shrink-0">
                <h3 class="text-white font-bold text-lg"><i class="fa-solid fa-pen-to-square text-[#1e90b8]"></i> Edit Product</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-white"><i class="fa-solid fa-xmark text-xl"></i></button>
            </div>
            
            <div class="modal-body overflow-y-auto p-8 space-y-6">
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="edit_id" id="edit_id">

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Product Name</label>
                        <input type="text" name="name" id="edit_name" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-[#1e90b8] outline-none transition">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Update Images (Optional - Overwrites Old)</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:bg-gray-50 transition cursor-pointer relative">
                            <input type="file" name="edit_product_images[]" multiple accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 mb-2"></i>
                            <p class="text-sm text-gray-500">Click to replace current images</p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 font-bold mb-2">Description / Specifications</label>
                        <textarea name="description" id="edit_description" rows="5" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-[#1e90b8] outline-none transition"></textarea>
                    </div>

                    <button type="submit" name="update_product" class="w-full bg-[#1e90b8] hover:bg-[#156f8f] text-white py-4 rounded-lg font-bold text-lg shadow-lg transition transform hover:-translate-y-1">
                        Update Product
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
        
        // Add Modal Functions
        function openModal() { document.getElementById('productModal').classList.add('modal-active'); }
        function closeModal() { document.getElementById('productModal').classList.remove('modal-active'); }

        // Edit Modal Functions
        function openEditModal(btn) { 
            const id = btn.getAttribute('data-id');
            const name = btn.getAttribute('data-name');
            const desc = btn.getAttribute('data-desc');

            document.getElementById('edit_id').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_description').value = desc;
            
            document.getElementById('editProductModal').classList.add('modal-active'); 
        }
        
        function closeEditModal() { document.getElementById('editProductModal').classList.remove('modal-active'); }
    </script>
</body>
</html>