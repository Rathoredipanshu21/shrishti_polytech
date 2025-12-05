<?php
session_start();
include '../config/db.php';

// --- HANDLE ACTIONS ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // DELETE ENQUIRY
    if (isset($_POST['delete_id'])) {
        $id = $_POST['delete_id'];
        $conn->query("DELETE FROM contact_enquiries WHERE id=$id");
        $_SESSION['msg'] = "Enquiry Deleted!";
        $_SESSION['type'] = "error";
        header("Location: admin_enquiries.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Client Enquiries</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f3f4f6; }
        
        /* Custom Scrollbar for Table */
        .table-container::-webkit-scrollbar { height: 8px; }
        .table-container::-webkit-scrollbar-thumb { background: #1e90b8; border-radius: 4px; }
        
        /* Status Badge Animation */
        .new-badge { animation: pulse 2s infinite; }
        @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.6; } 100% { opacity: 1; } }
    </style>
</head>
<body class="overflow-x-hidden">

    <!-- Navbar -->
    <nav class="bg-[#111] text-white shadow-lg border-b-4 border-[#1e90b8]">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-[#1e90b8] rounded-lg flex items-center justify-center font-bold text-xl shadow-lg">
                    <i class="fa-solid fa-envelope-open-text"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold tracking-wide">Client Enquiries</h1>
                    <p class="text-xs text-gray-400">Manage Incoming Messages</p>
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
                <h2 class="text-3xl font-bold text-gray-800">Inbox <span class="text-[#D71920]">Messages</span></h2>
                <p class="text-gray-500 mt-1">View and manage contact form submissions.</p>
            </div>
            
            <!-- Simple Search (Visual Only for now, can be functional with JS) -->
            <div class="relative w-full sm:w-64">
                <input type="text" id="searchInput" placeholder="Search enquiries..." class="w-full bg-white border border-gray-300 text-gray-700 py-2 px-4 pl-10 rounded-lg focus:outline-none focus:border-[#1e90b8] transition shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-search text-gray-400"></i>
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

        <!-- Enquiries List -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden" data-aos="fade-up">
            
            <div class="overflow-x-auto table-container">
                <table class="w-full whitespace-nowrap">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">Client Name</th>
                            <th class="px-6 py-4">Contact Info</th>
                            <th class="px-6 py-4">Service Interest</th>
                            <th class="px-6 py-4">Message Date</th>
                            <th class="px-6 py-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100" id="enquiryTable">
                        <?php 
                        $sql = "SELECT * FROM contact_enquiries ORDER BY id DESC";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0):
                            while ($row = $result->fetch_assoc()): 
                        ?>
                        <tr class="hover:bg-blue-50/50 transition-colors group">
                            
                            <!-- ID -->
                            <td class="px-6 py-4 text-sm text-gray-400 font-mono">
                                #<?php echo $row['id']; ?>
                            </td>

                            <!-- Name -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-[#1e90b8]/10 text-[#1e90b8] flex items-center justify-center font-bold text-sm">
                                        <?php echo strtoupper(substr($row['name'], 0, 1)); ?>
                                    </div>
                                    <span class="font-semibold text-gray-800"><?php echo htmlspecialchars($row['name']); ?></span>
                                </div>
                            </td>

                            <!-- Contact -->
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <a href="mailto:<?php echo $row['email']; ?>" class="text-sm text-gray-600 hover:text-[#1e90b8] flex items-center gap-2 transition">
                                        <i class="fa-regular fa-envelope text-gray-400"></i> <?php echo htmlspecialchars($row['email']); ?>
                                    </a>
                                    <a href="tel:<?php echo $row['phone']; ?>" class="text-xs text-gray-500 hover:text-[#D71920] flex items-center gap-2 mt-1 transition">
                                        <i class="fa-solid fa-phone text-gray-400"></i> <?php echo htmlspecialchars($row['phone']); ?>
                                    </a>
                                </div>
                            </td>

                            <!-- Service -->
                            <td class="px-6 py-4">
                                <?php if(!empty($row['service_interested'])): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                        <?php echo htmlspecialchars($row['service_interested']); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-gray-400 text-xs italic">General Inquiry</span>
                                <?php endif; ?>
                            </td>

                            <!-- Date -->
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <i class="fa-regular fa-calendar mr-1"></i>
                                <?php echo date("d M Y, h:i A", strtotime($row['created_at'])); ?>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-3">
                                    
                                    <!-- View Message Btn -->
                                    <button onclick="viewMessage('<?php echo htmlspecialchars($row['name']); ?>', '<?php echo htmlspecialchars(addslashes($row['message'])); ?>')" 
                                            class="text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 p-2 rounded-full transition-colors" title="Read Message">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>

                                    <!-- Delete Btn -->
                                    <form method="POST" onsubmit="return confirm('Delete this enquiry permanently?');">
                                        <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                        <button class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-full transition-colors" title="Delete">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>
                        <?php endwhile; else: ?>
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <i class="fa-regular fa-folder-open text-4xl mb-3"></i>
                                    <p class="text-lg font-medium">No Enquiries Found</p>
                                    <p class="text-sm">Your inbox is currently empty.</p>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination (Visual) -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex items-center justify-between">
                <span class="text-xs text-gray-500">Showing all recent messages</span>
                <div class="flex gap-2">
                    <button class="px-3 py-1 bg-white border border-gray-300 rounded text-xs text-gray-600 hover:bg-gray-100 disabled:opacity-50" disabled>Previous</button>
                    <button class="px-3 py-1 bg-white border border-gray-300 rounded text-xs text-gray-600 hover:bg-gray-100 disabled:opacity-50" disabled>Next</button>
                </div>
            </div>

        </div>
    </div>

    <!-- MESSAGE MODAL -->
    <div id="messageModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden transform transition-all scale-100" data-aos="zoom-in">
            <div class="bg-gradient-to-r from-[#111] to-[#333] px-6 py-4 flex justify-between items-center border-b border-[#1e90b8]">
                <h3 class="text-white font-bold text-lg flex items-center gap-2">
                    <i class="fa-solid fa-comment-dots text-[#1e90b8]"></i> Message from <span id="modalClientName" class="text-[#D71920]"></span>
                </h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-white transition-colors"><i class="fa-solid fa-xmark text-xl"></i></button>
            </div>
            
            <div class="p-8">
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-gray-700 leading-relaxed text-sm h-48 overflow-y-auto" id="modalMessageBody">
                    <!-- Message Content -->
                </div>
                
                <div class="mt-6 flex justify-end">
                    <button onclick="closeModal()" class="bg-[#1e90b8] hover:bg-[#156f8f] text-white px-6 py-2 rounded-lg font-bold shadow transition-transform transform hover:-translate-y-1">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();

        // Modal Logic
        const modal = document.getElementById('messageModal');
        const modalName = document.getElementById('modalClientName');
        const modalBody = document.getElementById('modalMessageBody');

        function viewMessage(name, message) {
            modalName.textContent = name;
            modalBody.textContent = message;
            modal.classList.remove('hidden');
        }

        function closeModal() {
            modal.classList.add('hidden');
        }

        // Close on outside click
        window.onclick = (e) => { if(e.target == modal) closeModal(); }

        // Simple Search Filter
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#enquiryTable tr');

            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    </script>
</body>
</html>