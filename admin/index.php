<?php
session_start();
// Redirect if not logged in
if (!isset($_SESSION['admin'])) {
    header("Location: Login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Srishti Polytech</title>
    <link rel="icon" type="image/x-icon" href="../Assets/logo.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            /* Srishti Polytech Palette */
            --brand-red: #D71920;
            --brand-teal: #1e90b8;
            --brand-dark: #111111;
            
            --sidebar-bg: #111111;
            --sidebar-hover: #1e90b8;
            --sidebar-active: #D71920;
            --text-light: #f3f4f6;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9fafb;
            color: #333;
            overflow: hidden; /* Prevent body scroll, handled by containers */
        }

        /* --- Sidebar Styling --- */
        .sidebar {
            background-color: var(--sidebar-bg);
            background-image: linear-gradient(to bottom, #111, #0a0a0a);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100vh;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #333;
        }
        
        .sidebar-nav {
            flex-grow: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding-top: 1rem;
        }

        /* Scrollbar for sidebar */
        .sidebar-nav::-webkit-scrollbar { width: 5px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background-color: #333; border-radius: 10px; }

        .logo-area {
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #9ca3af;
            font-weight: 500;
            font-size: 0.9rem;
            border-left: 4px solid transparent;
            transition: all 0.2s ease;
            margin-bottom: 4px;
        }

        .sidebar-link:hover {
            background-color: rgba(30, 144, 184, 0.1); /* Teal tint */
            color: white;
            padding-left: 24px; /* Slide effect */
            border-left-color: var(--brand-teal);
        }

        .sidebar-link.active {
            background: linear-gradient(90deg, rgba(215,25,32,0.1), transparent);
            color: white;
            border-left-color: var(--brand-red);
            font-weight: 600;
        }
        
        .sidebar-link i {
            width: 24px;
            font-size: 1.1rem;
            margin-right: 12px;
            text-align: center;
            transition: transform 0.2s;
        }
        
        .sidebar-link:hover i {
            transform: scale(1.1);
        }

        /* Sidebar Group Label */
        .nav-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            color: #555;
            padding: 16px 20px 8px;
            font-weight: 700;
            letter-spacing: 0.05em;
        }

        /* --- Content Area --- */
        #main-content-wrapper {
            height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: #f3f4f6;
        }
        
        iframe {
            width: 100%;
            height: 100%;
            border: none;
            display: block;
        }

        /* --- Header --- */
        .top-header {
            background: white;
            border-bottom: 3px solid var(--brand-red);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            z-index: 20;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -100%;
                z-index: 50;
                width: 280px;
                box-shadow: 10px 0 20px rgba(0,0,0,0.5);
            }
            .sidebar.active {
                left: 0;
            }
            .overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.5);
                z-index: 40;
            }
            .overlay.active { display: block; }
        }
    </style>
</head>
<body>

    <div id="overlay" class="overlay"></div>

    <div class="flex h-screen">

        <aside id="sidebar" class="sidebar w-72 flex-shrink-0">
            
            <div class="logo-area p-6 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center shadow overflow-hidden p-0.5">
                        <img src="../Assets/logo.png" alt="Srishti Logo" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h1 class="text-white font-bold text-lg leading-none tracking-wide">SRISHTI</h1>
                        <span class="text-[#1e90b8] text-xs font-semibold uppercase tracking-widest">Polytech Admin</span>
                    </div>
                </div>
                <button id="close-sidebar" class="md:hidden text-gray-400 hover:text-white">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <nav class="sidebar-nav">
                
                <div class="nav-label">Main</div>
                <a href="dashboard.php" class="sidebar-link active" target="content-frame">
                    <i class="fa-solid fa-gauge-high text-indigo-400"></i> Dashboard
                </a>

                <div class="nav-label">Content Management</div>
                
                <a href="hero_banner.php" class="sidebar-link" target="content-frame">
                    <i class="fa-solid fa-panorama text-pink-400"></i> Home Banner
                </a>
                
                <a href="add_products.php" class="sidebar-link" target="content-frame">
                    <i class="fa-solid fa-box-open text-[#D71920]"></i> Products Manager
                </a>
                
                <a href="admin_services.php" class="sidebar-link" target="content-frame">
                    <i class="fa-solid fa-screwdriver-wrench text-[#1e90b8]"></i> Services
                </a>
                
                <a href="add_gallery.php" class="sidebar-link" target="content-frame">
                    <i class="fa-solid fa-images text-purple-400"></i> Add Gallery
                </a>
                
                <a href="add_blog.php" class="sidebar-link" target="content-frame">
                    <i class="fa-solid fa-newspaper text-yellow-400"></i> Add Blog
                </a>
                
                <a href="add_clients.php" class="sidebar-link" target="content-frame">
                    <i class="fa-solid fa-handshake text-cyan-400"></i> Add Clients
                </a>


                <div class="nav-label">Business</div>
                
                <a href="admin_enquiries.php" class="sidebar-link" target="content-frame">
                    <i class="fa-solid fa-comments text-green-400"></i> Client Enquiries
                </a>
                
                <a href="orders.php" class="sidebar-link" target="content-frame">
                    <i class="fa-solid fa-cart-flatbed text-emerald-500"></i> Client Orders
                </a>
                

                <div class="nav-label">Settings</div>
                
                <a href="change-password.php" class="sidebar-link" target="content-frame">
                    <i class="fa-solid fa-key text-gray-400"></i> Change Password
                </a>

            </nav>

            <div class="p-4 border-t border-white/10 bg-[#0a0a0a]">
                <div class="flex items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name=Admin&background=1e90b8&color=fff" class="w-10 h-10 rounded-full border-2 border-[#1e90b8]">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">Administrator</p>
                        <a href="logout.php" class="text-xs text-red-400 hover:text-red-300 flex items-center gap-1">
                            <i class="fa-solid fa-power-off"></i> Logout
                        </a>
                    </div>
                </div>
            </div>

        </aside>

        <div id="main-content-wrapper" class="flex-1">
            
            <header class="top-header h-16 flex items-center justify-between px-6 bg-white">
                
                <div class="flex items-center gap-4">
                    <button id="menu-toggle" class="md:hidden text-gray-600 hover:text-[#D71920] text-xl">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <h2 id="page-title" class="text-lg font-bold text-gray-800 hidden sm:block">
                        Dashboard Overview
                    </h2>
                </div>

                <div class="flex items-center gap-4">
                    <a href="../index.php" target="_blank" class="hidden sm:flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-[#1e90b8] transition-colors border border-gray-200 px-3 py-1.5 rounded-full hover:border-[#1e90b8]">
                        <i class="fa-solid fa-globe"></i> Visit Website
                    </a>
                    
                    <button onclick="toggleFullScreen()" class="w-10 h-10 rounded-full bg-gray-50 hover:bg-gray-100 text-gray-600 flex items-center justify-center transition-colors" title="Fullscreen">
                        <i id="fullscreen-icon" class="fa-solid fa-expand"></i>
                    </button>
                </div>

            </header>

            <main class="flex-1 relative bg-[#f3f4f6]">
                <iframe id="content-frame" name="content-frame" src="dashboard.php" allowfullscreen></iframe>
            </main>

        </div>

    </div>

    <script>
        // DOM Elements
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const menuToggle = document.getElementById('menu-toggle');
        const closeSidebar = document.getElementById('close-sidebar');
        const links = document.querySelectorAll('.sidebar-link');
        const pageTitle = document.getElementById('page-title');
        const iframe = document.getElementById('content-frame');

        // Toggle Sidebar (Mobile)
        function toggleSidebar() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }

        menuToggle.addEventListener('click', toggleSidebar);
        closeSidebar.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);

        // Link Handling
        links.forEach(link => {
            link.addEventListener('click', function() {
                // Remove active class from all
                links.forEach(l => l.classList.remove('active'));
                // Add active to clicked
                this.classList.add('active');
                
                // Update Header Title
                if(pageTitle) {
                    pageTitle.textContent = this.textContent.trim();
                }

                // Close sidebar on mobile after click
                if(window.innerWidth < 768) {
                    toggleSidebar();
                }
            });
        });

        // Fullscreen Logic
        const fullscreenIcon = document.getElementById('fullscreen-icon');
        function toggleFullScreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
                fullscreenIcon.classList.remove('fa-expand');
                fullscreenIcon.classList.add('fa-compress');
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                    fullscreenIcon.classList.remove('fa-compress');
                    fullscreenIcon.classList.add('fa-expand');
                }
            }
        }
    </script>
</body>
</html>