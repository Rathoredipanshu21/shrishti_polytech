<?php
session_start();
include '../config/db.php';

// --- HANDLE DELETE ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];
    $conn->query("DELETE FROM enquiries WHERE id=$id");
    $_SESSION['msg'] = "Order deleted successfully!";
    $_SESSION['type'] = "error";
    header("Location: orders.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Orders</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f3f4f6; }
        
        /* Custom Scrollbar */
        .table-container::-webkit-scrollbar { height: 8px; }
        .table-container::-webkit-scrollbar-thumb { background: #1e90b8; border-radius: 4px; }
        
        /* Modal Animation */
        .modal { transition: opacity 0.3s ease; }
        .modal-content { transition: transform 0.3s ease; }
    </style>
</head>
<body class="overflow-x-hidden">

    <!-- Navbar -->
    <nav class="bg-[#111] text-white shadow-lg border-b-4 border-[#1e90b8]">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-[#1e90b8] rounded-lg flex items-center justify-center font-bold text-xl shadow-lg">
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold tracking-wide">Order Management</h1>
                    <p class="text-xs text-gray-400">Track Product Enquiries</p>
                </div>
            </div>
            <a href="../index.php" class="text-gray-400 hover:text-[#D71920] transition flex items-center gap-2">
                <i class="fa-solid fa-home"></i> Dashboard
            </a>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-6 mt-10 pb-20">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-end mb-8 gap-4" data-aos="fade-down">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Recent <span class="text-[#D71920]">Orders</span></h2>
                <p class="text-gray-500 mt-1">Manage product interest and customer requests.</p>
            </div>
            
            <!-- Search -->
            <div class="relative w-full sm:w-72">
                <input type="text" id="searchInput" placeholder="Search orders..." class="w-full bg-white border border-gray-300 text-gray-700 py-2.5 px-4 pl-10 rounded-lg focus:outline-none focus:border-[#1e90b8] transition shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <i class="fa-solid fa-search"></i>
                </div>
            </div>
        </div>

        <!-- Notification -->
        <?php if (isset($_SESSION['msg'])): ?>
            <div class="mb-6 p-4 rounded-lg text-white font-medium shadow-md flex items-center gap-3 <?php echo $_SESSION['type'] == 'success' ? 'bg-green-600' : 'bg-red-600'; ?>" data-aos="fade-in">
                <i class="fa-solid <?php echo $_SESSION['type'] == 'success' ? 'fa-check-circle' : 'fa-circle-exclamation'; ?>"></i>
                <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
            </div>
        <?php endif; ?>

        <!-- Orders Table -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden" data-aos="fade-up">
            
            <div class="overflow-x-auto table-container">
                <table class="w-full whitespace-nowrap">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-4">Order ID</th>
                            <th class="px-6 py-4">Customer</th>
                            <th class="px-6 py-4">Product Interest</th>
                            <th class="px-6 py-4">Contact Info</th>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100" id="orderTable">
                        <?php 
                        // Fetch from 'enquiries' table which stores product enquiries
                        $sql = "SELECT * FROM enquiries ORDER BY id DESC";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0):
                            while ($row = $result->fetch_assoc()): 
                        ?>
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            
                            <!-- ID -->
                            <td class="px-6 py-4 text-sm font-mono text-gray-400">
                                #ORD-<?php echo str_pad($row['id'], 4, '0', STR_PAD_LEFT); ?>
                            </td>

                            <!-- Customer -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#1e90b8] to-[#156f8f] text-white flex items-center justify-center font-bold text-sm shadow">
                                        <?php echo strtoupper(substr($row['name'], 0, 1)); ?>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-800"><?php echo htmlspecialchars($row['name']); ?></div>
                                        <div class="text-xs text-gray-400">Customer</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Product -->
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200">
                                    <i class="fa-solid fa-box-open mr-2"></i>
                                    <?php echo htmlspecialchars($row['subject']) ?: 'General Enquiry'; ?>
                                </span>
                            </td>

                            <!-- Contact -->
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <a href="mailto:<?php echo $row['email']; ?>" class="text-sm text-gray-600 hover:text-[#1e90b8] flex items-center gap-2 transition">
                                        <i class="fa-regular fa-envelope text-xs text-gray-400"></i> <?php echo htmlspecialchars($row['email']); ?>
                                    </a>
                                    <a href="tel:<?php echo $row['phone']; ?>" class="text-xs text-gray-500 hover:text-[#D71920] flex items-center gap-2 transition">
                                        <i class="fa-solid fa-phone text-xs text-gray-400"></i> <?php echo htmlspecialchars($row['phone']); ?>
                                    </a>
                                </div>
                            </td>

                            <!-- Date -->
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <?php echo date("d M Y", strtotime($row['created_at'])); ?>
                                <span class="block text-xs text-gray-400"><?php echo date("h:i A", strtotime($row['created_at'])); ?></span>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    
                                    <!-- View Details -->
                                    <button onclick="viewOrder('<?php echo htmlspecialchars($row['name']); ?>', '<?php echo htmlspecialchars($row['subject']); ?>', '<?php echo htmlspecialchars(addslashes($row['message'])); ?>')" 
                                            class="text-[#1e90b8] hover:text-white hover:bg-[#1e90b8] w-8 h-8 rounded-full flex items-center justify-center transition-all shadow-sm border border-[#1e90b8]/30" title="View Message">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </button>

                                    <!-- Delete -->
                                    <form method="POST" onsubmit="return confirm('Permanently delete this order?');">
                                        <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                        <button class="text-red-500 hover:text-white hover:bg-red-500 w-8 h-8 rounded-full flex items-center justify-center transition-all shadow-sm border border-red-200" title="Delete">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>
                        <?php endwhile; else: ?>
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-300">
                                    <i class="fa-solid fa-clipboard-list text-5xl mb-4"></i>
                                    <p class="text-xl font-semibold text-gray-500">No Orders Yet</p>
                                    <p class="text-sm text-gray-400">New product enquiries will appear here.</p>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Footer/Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex items-center justify-between">
                <span class="text-xs text-gray-500 font-medium">Displaying recent records</span>
                <div class="flex gap-2">
                    <button class="w-8 h-8 flex items-center justify-center rounded bg-white border border-gray-300 text-gray-400 hover:text-[#1e90b8] hover:border-[#1e90b8] transition text-xs"><i class="fa-solid fa-chevron-left"></i></button>
                    <button class="w-8 h-8 flex items-center justify-center rounded bg-white border border-gray-300 text-gray-400 hover:text-[#1e90b8] hover:border-[#1e90b8] transition text-xs"><i class="fa-solid fa-chevron-right"></i></button>
                </div>
            </div>

        </div>
    </div>

    <!-- ORDER DETAILS MODAL -->
    <div id="orderModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 modal">
        <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden transform scale-95 modal-content" id="modalContent">
            <div class="bg-gradient-to-r from-[#111] to-[#222] px-6 py-4 flex justify-between items-center border-b border-[#D71920]">
                <h3 class="text-white font-bold text-lg flex items-center gap-2">
                    <i class="fa-solid fa-file-invoice text-[#1e90b8]"></i> Order Details
                </h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-white transition-colors"><i class="fa-solid fa-xmark text-xl"></i></button>
            </div>
            
            <div class="p-8 space-y-4">
                
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-full bg-blue-50 text-[#1e90b8] flex items-center justify-center text-xl">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider">Customer</p>
                        <h4 class="font-bold text-gray-800 text-lg" id="modalName"></h4>
                    </div>
                </div>

                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Product Interest</p>
                    <h5 class="font-bold text-[#D71920]" id="modalProduct"></h5>
                </div>

                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-2">Message / Requirement</p>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-gray-600 text-sm leading-relaxed max-h-32 overflow-y-auto" id="modalMessage">
                        <!-- Message Content -->
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end">
                    <button onclick="closeModal()" class="bg-[#1e90b8] hover:bg-[#156f8f] text-white px-6 py-2.5 rounded-lg font-bold shadow-md transition-transform transform hover:-translate-y-1">
                        Close Details
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();

        // Modal Logic
        const modal = document.getElementById('orderModal');
        const modalContent = document.getElementById('modalContent');
        const modalName = document.getElementById('modalName');
        const modalProduct = document.getElementById('modalProduct');
        const modalMessage = document.getElementById('modalMessage');

        function viewOrder(name, product, message) {
            modalName.textContent = name;
            modalProduct.textContent = product || 'N/A';
            modalMessage.textContent = message || 'No message provided.';
            
            modal.classList.remove('hidden');
            // Small timeout for animation
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }, 10);
        }

        function closeModal() {
            modal.classList.add('opacity-0');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Close on outside click
        window.onclick = (e) => { if(e.target == modal) closeModal(); }

        // Filter Logic
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#orderTable tr');

            rows.forEach(row => {
                let text = row.innerText.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    </script>
</body>
</html>