<?php
session_start();
include '../config/db.php'; // Adjust path if your db.php is elsewhere

// Setup Upload Directory
$targetDir = "../uploads/blogs/";
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

// --- HANDLE FORM SUBMISSION ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 1. ADD BLOG POST
    if (isset($_POST['add_blog'])) {
        $title = $conn->real_escape_string($_POST['title']);
        $content = $conn->real_escape_string($_POST['content']);
        
        $dbPath = ""; // Default empty

        if (isset($_FILES['blog_image']) && $_FILES['blog_image']['error'] == 0) {
            $fileName = basename($_FILES['blog_image']['name']);
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $newFileName = uniqid('blog_') . '.' . $fileExt;
            
            $targetFilePath = $targetDir . $newFileName;
            
            if (move_uploaded_file($_FILES['blog_image']['tmp_name'], $targetFilePath)) {
                $dbPath = "uploads/blogs/" . $newFileName; // Path stored in DB (relative to root)
            }
        }

        if (!empty($dbPath)) {
            $sql = "INSERT INTO blogs (title, content, image) VALUES ('$title', '$content', '$dbPath')";
            if ($conn->query($sql)) {
                $_SESSION['msg'] = "Blog Published Successfully!";
                $_SESSION['type'] = "success";
            } else {
                $_SESSION['msg'] = "Database Error: " . $conn->error;
                $_SESSION['type'] = "error";
            }
        } else {
            $_SESSION['msg'] = "Image Upload Failed!";
            $_SESSION['type'] = "error";
        }
        header("Location: add_blog.php");
        exit();
    }

    // 2. DELETE BLOG
    if (isset($_POST['delete_id'])) {
        $id = $_POST['delete_id'];
        
        // Fetch image to delete file
        $res = $conn->query("SELECT image FROM blogs WHERE id=$id");
        if ($row = $res->fetch_assoc()) {
            if (file_exists("../" . $row['image'])) {
                unlink("../" . $row['image']);
            }
        }

        $conn->query("DELETE FROM blogs WHERE id=$id");
        $_SESSION['msg'] = "Blog Post Deleted!";
        $_SESSION['type'] = "error";
        header("Location: add_blog.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Blogs</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f3f4f6; }
        .modal { display: none; background: rgba(0,0,0,0.6); backdrop-filter: blur(5px); }
        .modal-active { display: flex; }
        /* Custom Scrollbar for textarea */
        textarea::-webkit-scrollbar { width: 8px; }
        textarea::-webkit-scrollbar-thumb { background: #1e90b8; border-radius: 4px; }
    </style>
</head>
<body class="overflow-x-hidden">

    <nav class="bg-[#111] text-white shadow-lg border-b-4 border-[#D71920]">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-[#D71920] rounded-lg flex items-center justify-center font-bold text-xl"><i class="fa-solid fa-pen-nib"></i></div>
                <h1 class="text-xl font-bold">Blog Manager</h1>
            </div>
            <a href="../index.php" class="text-gray-400 hover:text-[#1e90b8] transition"><i class="fa-solid fa-home"></i> Home</a>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-6 mt-10 pb-20">
        
        <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4" data-aos="fade-down">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Latest <span class="text-[#1e90b8]">News & Articles</span></h2>
                <p class="text-gray-500 mt-1">Share updates and stories with your audience.</p>
            </div>
            <button onclick="openModal()" class="bg-[#1e90b8] hover:bg-[#156f8f] text-white px-6 py-3 rounded-lg shadow-lg hover:shadow-xl transition-all flex items-center gap-2 transform hover:-translate-y-1">
                <i class="fa-solid fa-plus"></i> Write New Blog
            </button>
        </div>

        <?php if (isset($_SESSION['msg'])): ?>
            <div class="mb-6 p-4 rounded text-white <?php echo $_SESSION['type'] == 'success' ? 'bg-green-600' : 'bg-[#D71920]'; ?>" data-aos="fade-in">
                <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php 
            $res = $conn->query("SELECT * FROM blogs ORDER BY created_at DESC");
            if ($res->num_rows > 0):
                while ($row = $res->fetch_assoc()): 
            ?>
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-2xl transition-all duration-300 group flex flex-col h-full" data-aos="fade-up">
                    <div class="relative h-56 overflow-hidden">
                        <img src="../<?php echo $row['image']; ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute top-0 right-0 bg-[#D71920] text-white px-3 py-1 rounded-bl-lg text-sm font-bold shadow-md">
                            <i class="fa-regular fa-calendar"></i> <?php echo date('d M Y', strtotime($row['created_at'])); ?>
                        </div>
                    </div>
                    <div class="p-6 flex-1 flex flex-col">
                        <h3 class="font-bold text-gray-800 text-xl mb-3 leading-tight group-hover:text-[#1e90b8] transition"><?php echo $row['title']; ?></h3>
                        <p class="text-gray-500 text-sm line-clamp-3 mb-6 flex-1"><?php echo substr($row['content'], 0, 150); ?>...</p>
                        
                        <div class="border-t pt-4 flex justify-between items-center mt-auto">
                            <span class="text-xs text-gray-400 font-mono">ID: <?php echo $row['id']; ?></span>
                            <form method="POST" onsubmit="return confirm('Delete this blog permanently?');">
                                <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                <button class="text-[#D71920] hover:text-red-700 font-semibold text-sm flex items-center gap-1">
                                    <i class="fa-solid fa-trash-can"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; else: ?>
                <div class="col-span-full text-center py-24 bg-white rounded-xl shadow-sm">
                    <div class="inline-block p-4 rounded-full bg-gray-100 mb-4">
                        <i class="fa-solid fa-newspaper text-5xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-700">No blogs posted yet</h3>
                    <p class="text-gray-500 mt-2">Click the button above to start writing.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div id="blogModal" class="modal fixed inset-0 z-50 items-center justify-center p-4">
        <div class="bg-white w-full max-w-3xl rounded-2xl shadow-2xl overflow-hidden transform scale-95 transition-transform duration-300" data-aos="zoom-in">
            <div class="bg-[#111] px-6 py-4 flex justify-between items-center border-b-2 border-[#1e90b8]">
                <h3 class="text-white font-bold text-lg flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-[#1e90b8]"></i> Create Blog Post
                </h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-[#D71920] transition bg-gray-800 w-8 h-8 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            
            <form method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 font-bold mb-2 text-sm uppercase tracking-wide">Blog Title</label>
                        <input type="text" name="title" required placeholder="Enter a catchy title..." 
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-[#1e90b8] focus:border-transparent outline-none transition bg-gray-50">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-gray-700 font-bold mb-2 text-sm uppercase tracking-wide">Cover Image</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:bg-[#f0f9ff] hover:border-[#1e90b8] transition cursor-pointer relative group">
                            <input type="file" name="blog_image" required accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="transition-transform group-hover:scale-110 duration-300">
                                <i class="fa-solid fa-cloud-arrow-up text-4xl text-[#1e90b8] mb-3"></i>
                            </div>
                            <p class="text-sm text-gray-600 font-medium">Click to upload image</p>
                            <p class="text-xs text-gray-400 mt-1">Supports JPG, PNG, WEBP</p>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-gray-700 font-bold mb-2 text-sm uppercase tracking-wide">Blog Content</label>
                        <textarea name="content" rows="8" required placeholder="Write your story here..." 
                                  class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-[#1e90b8] focus:border-transparent outline-none transition bg-gray-50 resize-none"></textarea>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 flex justify-end gap-4">
                    <button type="button" onclick="closeModal()" class="px-6 py-3 rounded-lg text-gray-600 font-bold hover:bg-gray-100 transition">Cancel</button>
                    <button type="submit" name="add_blog" class="px-8 py-3 bg-[#D71920] hover:bg-[#b01319] text-white rounded-lg font-bold shadow-lg hover:shadow-red-500/30 transition transform hover:-translate-y-1">
                        Publish Post <i class="fa-solid fa-paper-plane ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true });
        function openModal() { document.getElementById('blogModal').classList.add('modal-active'); }
        function closeModal() { document.getElementById('blogModal').classList.remove('modal-active'); }
    </script>
</body>
</html>