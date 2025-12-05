<?php
// Get current page for active state
$current_page = basename($_SERVER['PHP_SELF'], '.php');

// Database Connection
include 'config/db.php'; 

// 1. Fetch Services for Dropdown
$services_list = [];
try {
    $sql_s = "SELECT id, name FROM services ORDER BY name ASC";
    $result_s = $conn->query($sql_s);
    if ($result_s && $result_s->num_rows > 0) {
        while($row = $result_s->fetch_assoc()) {
            $services_list[] = $row;
        }
    }
} catch (Exception $e) {}

// 2. Fetch Products for Dropdown
$products_list = [];
try {
    $sql_p = "SELECT id, name FROM products ORDER BY name ASC";
    $result_p = $conn->query($sql_p);
    if ($result_p && $result_p->num_rows > 0) {
        while($row = $result_p->fetch_assoc()) {
            $products_list[] = $row;
        }
    }
} catch (Exception $e) {}

// 3. Site Settings
$settings = [];
try {
    $sql = "SELECT setting_key, setting_value FROM site_settings";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
    }
} catch (Exception $e) {}

// Fallbacks
$email = $settings['email'] ?? 'srishtipolytech@gmail.com';
$phone_list = $settings['phone_list'] ?? '+91-7004471859, +91-9431313684'; 
$main_phone = '+91-7004471859';

// Define Main Navigation Items
$nav_items = [
    'Home' => 'index',
    'About Us' => 'about',
    'Product' => 'products', 
    'Service' => 'services',
    'Gallery' => 'gallery',
    'Contact Us' => 'contact'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="Assets/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* --- CORE VARIABLES & FONTS --- */
        :root {
            --sp-red: #D71920;
            --sp-teal: #1e90b8; 
            --sp-dark: #111111;
            --sp-font: 'Poppins', sans-serif;
        }

        body {
            font-family: var(--sp-font);
        }

        /* --- TOP BAR STYLES --- */
        .sp-topbar-wrapper {
            background-color: var(--sp-dark);
            color: #ffffff;
            font-size: 0.85rem;
            transition: height 0.3s ease;
        }

        .sp-social-icon {
            background-color: white;
            color: var(--sp-red);
            width: 28px; 
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            transition: all 0.3s ease;
            font-size: 0.8rem;
        }
        
        .sp-social-icon:hover {
            transform: translateY(-2px);
            background-color: var(--sp-teal);
            color: white;
        }

        /* --- NAVIGATION STYLES --- */
        .sp-navbar-wrapper {
            background-color: white;
            width: 100%;
            border-bottom: 1px solid #f3f4f6;
            z-index: 9999; /* High Z-Index to stay on top */
            transition: all 0.3s ease;
        }

        /* THE STICKY MAGIC CLASS */
        .sp-fixed-active {
            position: fixed !important;
            top: 0;
            left: 0;
            right: 0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            animation: spSlideDown 0.35s ease-in-out;
        }

        @keyframes spSlideDown {
            0% { transform: translateY(-100%); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        /* Nav Links */
        .sp-nav-link {
            color: #333;
            font-weight: 600;
            font-size: 15px; 
            padding: 28px 12px;
            transition: color 0.3s;
            white-space: nowrap;
            display: flex;
            align-items: center;
            height: 100%;
        }

        .sp-nav-link:hover {
            color: var(--sp-teal);
        }

        .sp-nav-active {
            color: var(--sp-red) !important;
        }

        /* --- DROPDOWN MENUS --- */
        .sp-dropdown-container {
            position: relative;
            height: 100%;
            display: flex;
            align-items: center;
        }

        .sp-dropdown-menu {
            opacity: 0;
            visibility: hidden;
            transform: translateY(15px);
            transition: all 0.3s ease;
            position: absolute;
            top: 100%;
            left: 0;
            min-width: 260px;
            background: white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-top: 3px solid var(--sp-red);
            padding: 8px 0;
            z-index: 1000;
            border-radius: 0 0 8px 8px;
        }

        .sp-dropdown-container:hover .sp-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .sp-dropdown-item {
            display: block;
            padding: 10px 20px;
            color: #4b5563;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
            border-bottom: 1px solid #f9fafb;
        }

        .sp-dropdown-item:hover {
            background-color: #f9fafb;
            color: var(--sp-teal);
            padding-left: 25px;
        }

        /* --- BUTTONS --- */
        .sp-btn-primary {
            background-color: var(--sp-teal);
            color: white;
            padding: 10px 24px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s;
            box-shadow: 0 4px 6px -1px rgba(30, 144, 184, 0.3);
            text-align: center;
            display: inline-block;
        }

        .sp-btn-primary:hover {
            background-color: var(--sp-red);
            transform: translateY(-1px);
        }

        /* --- MOBILE MENU --- */
        #sp-mobile-menu-drawer {
            transition: max-height 0.4s ease-in-out, opacity 0.4s ease-in-out;
            overflow: hidden;
            max-height: 0;
            opacity: 0;
        }
        
        #sp-mobile-menu-drawer.open {
            max-height: 100vh;
            opacity: 1;
        }
    </style>
</head>

<body>

    <!-- SECTION 1: Top Bar (This will scroll away) -->
    <div id="sp-top-bar" class="sp-topbar-wrapper py-2 border-b border-gray-800">
        <div class="max-w-[1400px] mx-auto px-4 lg:px-10 flex flex-col md:flex-row justify-between items-center gap-2 md:gap-0">
            
            <!-- Contact Details -->
            <div class="flex flex-wrap justify-center md:justify-start items-center gap-x-6 gap-y-1 text-xs md:text-[13px]">
                <div class="flex items-center gap-2">
                    <i class="fa-regular fa-envelope text-[#D71920]"></i>
                    <a href="mailto:<?php echo $email; ?>" class="hover:text-gray-300 transition-colors"><?php echo $email; ?></a>
                </div>
                <!-- Desktop Phones -->
                <div class="hidden sm:flex items-center gap-2">
                    <i class="fa-solid fa-phone-volume text-[#D71920]"></i>
                    <span><?php echo $phone_list; ?></span>
                </div>
                <!-- Mobile Phone -->
                <div class="sm:hidden flex items-center gap-2">
                     <i class="fa-solid fa-phone-volume text-[#D71920]"></i>
                     <a href="tel:<?php echo $main_phone; ?>"><?php echo $main_phone; ?></a>
                </div>
            </div>

            <!-- Social Media -->
            <div class="flex items-center gap-2">
                <a href="#" class="sp-social-icon"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#" class="sp-social-icon"><i class="fa-brands fa-instagram"></i></a>
                <a href="#" class="sp-social-icon"><i class="fa-brands fa-linkedin-in"></i></a>
                <a href="#" class="sp-social-icon"><i class="fa-brands fa-whatsapp"></i></a>
            </div>

        </div>
    </div>

    <!-- SECTION 2: Main Navigation (This will become Sticky) -->
    <nav id="sp-main-navbar" class="sp-navbar-wrapper">
        <div class="max-w-[1400px] mx-auto px-4 lg:px-10 h-[80px] lg:h-[90px] flex justify-between items-center">
            
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="index">
                    <!-- Adjusted Logo size for better proportion -->
                    <img src="Assets/2.png" alt="Srishti Polytech" class="h-[45px] md:h-[55px] lg:h-[65px] w-auto object-contain">
                </a>
            </div>

            <!-- Desktop Menu Links -->
            <div class="hidden xl:flex items-center gap-1 2xl:gap-6 h-full">
                <?php foreach ($nav_items as $title => $url): 
                    $isActive = ($url === $current_page) || ($current_page === '' && $url === 'index'); 
                    $activeClass = $isActive ? 'sp-nav-active' : '';
                    
                    // Logic for Product/Service Dropdowns
                    if ($title === 'Product' || $title === 'Service') {
                        $items = ($title === 'Product') ? $products_list : $services_list;
                        $detailPage = ($title === 'Product') ? 'product_details' : 'service_details';
                        $arrowIcon = '<i class="fa-solid fa-chevron-down text-[10px] ml-1.5 opacity-60 group-hover:rotate-180 transition-transform duration-300"></i>';
                ?>
                    <div class="sp-dropdown-container group">
                        <a href="<?php echo $url; ?>" class="sp-nav-link <?php echo $activeClass; ?>">
                            <?php echo $title . $arrowIcon; ?>
                        </a>
                        
                        <div class="sp-dropdown-menu">
                            <?php if (!empty($items)): ?>
                                <?php foreach ($items as $item): ?>
                                    <a href="<?php echo $detailPage; ?>?id=<?php echo $item['id']; ?>" class="sp-dropdown-item">
                                        <?php echo htmlspecialchars($item['name']); ?>
                                    </a>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span class="block px-5 py-3 text-xs text-gray-400 italic">Coming Soon</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php 
                    } else { 
                        // Standard Single Link
                ?>
                    <a href="<?php echo $url; ?>" class="sp-nav-link <?php echo $activeClass; ?>">
                        <?php echo $title; ?>
                    </a>
                <?php } endforeach; ?>
            </div>

            <!-- Desktop Action Button -->
            <div class="hidden lg:flex items-center">
                <a href="contact" class="sp-btn-primary">
                    Get Enquiry
                </a>
            </div>

            <!-- Mobile Toggle Button -->
            <div class="xl:hidden">
                 <button id="sp-mobile-toggle-btn" class="p-2 text-2xl text-gray-800 focus:outline-none hover:text-[#D71920] transition-colors">
                    <i class="fa-solid fa-bars"></i>
                 </button>
            </div>

        </div>

        <!-- Mobile Menu Drawer -->
        <div id="sp-mobile-menu-drawer" class="xl:hidden bg-white border-t border-gray-100 absolute w-full left-0 shadow-2xl overflow-y-auto">
            <div class="flex flex-col p-5 space-y-3">
                
                <?php foreach ($nav_items as $title => $url): 
                    if ($title === 'Product' || $title === 'Service') {
                        $items = ($title === 'Product') ? $products_list : $services_list;
                        $detailPage = ($title === 'Product') ? 'product_details' : 'service_details';
                ?>
                    <div class="border-b border-gray-50 pb-2">
                        <a href="<?php echo $url; ?>" class="flex justify-between items-center font-medium text-gray-800 px-2 py-2 rounded hover:bg-gray-50">
                            <?php echo $title; ?>
                            <i class="fa-solid fa-chevron-down text-xs text-gray-400"></i>
                        </a>
                        <div class="pl-6 mt-1 space-y-1 border-l-2 border-gray-100 ml-4">
                            <?php foreach ($items as $item): ?>
                                <a href="<?php echo $detailPage; ?>?id=<?php echo $item['id']; ?>" class="block text-sm text-gray-500 py-1.5 hover:text-[#D71920]">
                                    <?php echo htmlspecialchars($item['name']); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php } else { ?>
                    <a href="<?php echo $url; ?>" class="block font-medium text-gray-800 hover:text-[#D71920] hover:bg-gray-50 px-2 py-2 rounded transition-colors border-b border-gray-50">
                        <?php echo $title; ?>
                    </a>
                <?php } endforeach; ?>

                <div class="pt-4">
                    <a href="contact" class="block text-center bg-[#1e90b8] text-white py-3 rounded-lg font-medium shadow-md hover:bg-[#D71920] transition-colors">
                        Get Enquiry
                    </a>
                </div>

            </div>
        </div>
    </nav>
    
    <!-- Spacer DIV: Prevents content jump when navbar becomes fixed -->
    <div id="sp-navbar-spacer" class="hidden"></div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // --- 1. STICKY NAVBAR LOGIC (Guaranteed Fix) ---
            const topBar = document.getElementById('sp-top-bar');
            const navBar = document.getElementById('sp-main-navbar');
            const spacer = document.getElementById('sp-navbar-spacer');
            
            // Get the height of the top bar dynamically
            let topBarHeight = topBar ? topBar.offsetHeight : 0;
            let navHeight = navBar ? navBar.offsetHeight : 90;

            window.addEventListener('scroll', () => {
                // If we have scrolled past the top bar
                if (window.scrollY > topBarHeight) {
                    navBar.classList.add('sp-fixed-active');
                    
                    // Show spacer to push content down so it doesn't jump up
                    if(spacer) {
                        spacer.style.display = 'block';
                        spacer.style.height = navHeight + 'px';
                    }
                } else {
                    navBar.classList.remove('sp-fixed-active');
                    
                    // Hide spacer
                    if(spacer) {
                        spacer.style.display = 'none';
                        spacer.style.height = '0px';
                    }
                }
            });

            // Recalculate on resize in case top bar height changes
            window.addEventListener('resize', () => {
                if(topBar) topBarHeight = topBar.offsetHeight;
                if(navBar) navHeight = navBar.offsetHeight;
            });

            // --- 2. MOBILE MENU LOGIC ---
            const toggleBtn = document.getElementById('sp-mobile-toggle-btn');
            const mobileMenu = document.getElementById('sp-mobile-menu-drawer');
            const icon = toggleBtn ? toggleBtn.querySelector('i') : null;

            if(toggleBtn && mobileMenu) {
                toggleBtn.addEventListener('click', (e) => {
                    e.stopPropagation(); // Prevent immediate closing
                    mobileMenu.classList.toggle('open');
                    
                    // Icon Toggle
                    if (mobileMenu.classList.contains('open')) {
                        icon.classList.remove('fa-bars');
                        icon.classList.add('fa-xmark');
                    } else {
                        icon.classList.remove('fa-xmark');
                        icon.classList.add('fa-bars');
                    }
                });

                // Close menu when clicking outside
                document.addEventListener('click', (e) => {
                    if (mobileMenu.classList.contains('open') && !mobileMenu.contains(e.target) && !toggleBtn.contains(e.target)) {
                        mobileMenu.classList.remove('open');
                        icon.classList.remove('fa-xmark');
                        icon.classList.add('fa-bars');
                    }
                });
            }
        });
    </script>
</body>
</html>