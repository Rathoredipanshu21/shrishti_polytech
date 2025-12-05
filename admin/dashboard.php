<?php
session_start();
include '../config/db.php';

// --- FETCH STATS ---
// 1. Total Enquiries
$enquiryCount = $conn->query("SELECT COUNT(*) as count FROM contact_enquiries")->fetch_assoc()['count'];

// 2. Total Products
$productCount = $conn->query("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'];

// 3. Total Services
$serviceCount = $conn->query("SELECT COUNT(*) as count FROM services")->fetch_assoc()['count'];

// 4. Total Gallery Images
$galleryCount = $conn->query("SELECT COUNT(*) as count FROM gallery")->fetch_assoc()['count'];

// --- FETCH RECENT ENQUIRIES ---
$recentEnquiries = $conn->query("SELECT * FROM contact_enquiries ORDER BY created_at DESC LIMIT 5");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body { font-family: 'Poppins', sans-serif; background: #f3f4f6; }
        
        /* Stats Card Hover Effect */
        .stat-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
        
        /* Table Row Hover */
        .table-row-hover:hover { background-color: #f9fafb; }
    </style>
</head>
<body class="overflow-x-hidden p-6">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4" data-aos="fade-down">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Dashboard <span class="text-[#D71920]">Overview</span></h1>
            <p class="text-gray-500 mt-1">Welcome back, Admin! Here's what's happening today.</p>
        </div>
        <div class="bg-white px-4 py-2 rounded-lg shadow-sm border border-gray-200 text-gray-600 text-sm flex items-center gap-2">
            <i class="fa-regular fa-calendar-days text-[#1e90b8]"></i>
            <?php echo date("l, d F Y"); ?>
        </div>
    </div>

    <!-- STATS CARDS GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- Enquiries Card -->
        <div class="stat-card bg-white p-6 rounded-2xl shadow-lg border-l-4 border-[#1e90b8] relative overflow-hidden" data-aos="fade-up" data-aos-delay="100">
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Total Enquiries</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1"><?php echo $enquiryCount; ?></h3>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-[#1e90b8] text-xl">
                    <i class="fa-solid fa-envelope-open-text"></i>
                </div>
            </div>
            <div class="mt-4 text-xs text-green-600 font-medium flex items-center gap-1">
                <i class="fa-solid fa-arrow-trend-up"></i> +5% this week
            </div>
            <!-- Decorative Icon -->
            <i class="fa-solid fa-envelope absolute -bottom-4 -right-4 text-8xl text-gray-50 opacity-50 z-0"></i>
        </div>

        <!-- Products Card -->
        <div class="stat-card bg-white p-6 rounded-2xl shadow-lg border-l-4 border-[#D71920] relative overflow-hidden" data-aos="fade-up" data-aos-delay="200">
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Total Products</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1"><?php echo $productCount; ?></h3>
                </div>
                <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center text-[#D71920] text-xl">
                    <i class="fa-solid fa-box-open"></i>
                </div>
            </div>
            <div class="mt-4 text-xs text-gray-400 font-medium">
                Active in catalogue
            </div>
            <i class="fa-solid fa-boxes-stacked absolute -bottom-4 -right-4 text-8xl text-gray-50 opacity-50 z-0"></i>
        </div>

        <!-- Services Card -->
        <div class="stat-card bg-white p-6 rounded-2xl shadow-lg border-l-4 border-green-500 relative overflow-hidden" data-aos="fade-up" data-aos-delay="300">
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Services Listed</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1"><?php echo $serviceCount; ?></h3>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center text-green-600 text-xl">
                    <i class="fa-solid fa-gears"></i>
                </div>
            </div>
            <div class="mt-4 text-xs text-gray-400 font-medium">
                Solutions offered
            </div>
            <i class="fa-solid fa-screwdriver-wrench absolute -bottom-4 -right-4 text-8xl text-gray-50 opacity-50 z-0"></i>
        </div>

        <!-- Gallery Card -->
        <div class="stat-card bg-white p-6 rounded-2xl shadow-lg border-l-4 border-purple-500 relative overflow-hidden" data-aos="fade-up" data-aos-delay="400">
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Gallery Photos</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1"><?php echo $galleryCount; ?></h3>
                </div>
                <div class="w-12 h-12 bg-purple-50 rounded-full flex items-center justify-center text-purple-600 text-xl">
                    <i class="fa-solid fa-images"></i>
                </div>
            </div>
            <div class="mt-4 text-xs text-gray-400 font-medium">
                Portfolio items
            </div>
            <i class="fa-solid fa-photo-film absolute -bottom-4 -right-4 text-8xl text-gray-50 opacity-50 z-0"></i>
        </div>

    </div>

    <!-- CHARTS & TABLES ROW -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        
        <!-- CHART SECTION -->
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-lg border border-gray-100" data-aos="fade-right">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-gray-800">Enquiry Analytics</h3>
                <select class="text-xs border-gray-300 rounded-md text-gray-500 bg-gray-50 px-2 py-1 outline-none">
                    <option>Last 7 Days</option>
                    <option>Last 30 Days</option>
                </select>
            </div>
            <div class="relative h-64 w-full">
                <canvas id="enquiryChart"></canvas>
            </div>
        </div>

        <!-- DONUT CHART / DISTRIBUTION -->
        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100" data-aos="fade-left">
            <h3 class="text-lg font-bold text-gray-800 mb-6">Content Distribution</h3>
            <div class="relative h-48 flex justify-center">
                <canvas id="contentChart"></canvas>
            </div>
            <div class="mt-6 space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-[#1e90b8]"></span> Products</span>
                    <span class="font-bold text-gray-700"><?php echo $productCount; ?></span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-[#D71920]"></span> Services</span>
                    <span class="font-bold text-gray-700"><?php echo $serviceCount; ?></span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-purple-500"></span> Gallery</span>
                    <span class="font-bold text-gray-700"><?php echo $galleryCount; ?></span>
                </div>
            </div>
        </div>

    </div>

    <!-- RECENT ENQUIRIES TABLE -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden" data-aos="fade-up">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">Recent Enquiries</h3>
            <a href="admin_enquiries.php" class="text-sm text-[#1e90b8] font-medium hover:underline">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Client Name</th>
                        <th class="px-6 py-4 font-semibold">Service Interest</th>
                        <th class="px-6 py-4 font-semibold">Contact</th>
                        <th class="px-6 py-4 font-semibold">Date</th>
                        <th class="px-6 py-4 font-semibold text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                    <?php if ($recentEnquiries->num_rows > 0): 
                        while($row = $recentEnquiries->fetch_assoc()): ?>
                    <tr class="table-row-hover transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-800">
                            <?php echo htmlspecialchars($row['name']); ?>
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-blue-50 text-[#1e90b8] px-2 py-1 rounded text-xs font-semibold">
                                <?php echo htmlspecialchars($row['service_interested']) ?: 'General'; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <?php echo htmlspecialchars($row['phone']); ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php echo date("M d, Y", strtotime($row['created_at'])); ?>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span> New
                            </span>
                        </td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-400 italic">No recent enquiries found.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- AOS & Chart Initialization -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true });

        // --- CHART CONFIGURATION ---
        
        // 1. Line Chart (Enquiries) - Dummy Data for visual
        const ctxEnquiry = document.getElementById('enquiryChart').getContext('2d');
        new Chart(ctxEnquiry, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'New Enquiries',
                    data: [12, 19, 3, 5, 2, 3, 10], // Replace with dynamic data if needed
                    borderColor: '#1e90b8',
                    backgroundColor: 'rgba(30, 144, 184, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#1e90b8',
                    pointHoverBackgroundColor: '#1e90b8',
                    pointHoverBorderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [2, 4] } },
                    x: { grid: { display: false } }
                }
            }
        });

        // 2. Doughnut Chart (Content)
        const ctxContent = document.getElementById('contentChart').getContext('2d');
        new Chart(ctxContent, {
            type: 'doughnut',
            data: {
                labels: ['Products', 'Services', 'Gallery'],
                datasets: [{
                    data: [<?php echo $productCount; ?>, <?php echo $serviceCount; ?>, <?php echo $galleryCount; ?>],
                    backgroundColor: ['#1e90b8', '#D71920', '#a855f7'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: { display: false }
                }
            }
        });
    </script>
</body>
</html>