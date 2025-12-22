<?php
// Get current page for active state
$current_page = basename($_SERVER['PHP_SELF'], '.php');

// Database Connection
// Note: Ensure this path is correct relative to this file
if (file_exists('config/db.php')) {
    include 'config/db.php';
}

// Initialize arrays to prevent errors if DB fails
$services_list = [];
$products_list = [];
$settings = [];

// 1. Fetch Services for Dropdown
try {
    if (isset($conn)) {
        $sql_s = "SELECT id, name FROM services ORDER BY name ASC";
        $result_s = $conn->query($sql_s);
        if ($result_s && $result_s->num_rows > 0) {
            while($row = $result_s->fetch_assoc()) {
                $services_list[] = $row;
            }
        }
    }
} catch (Exception $e) {}

// 2. Fetch Products for Dropdown
try {
    if (isset($conn)) {
        $sql_p = "SELECT id, name FROM products ORDER BY name ASC";
        $result_p = $conn->query($sql_p);
        if ($result_p && $result_p->num_rows > 0) {
            while($row = $result_p->fetch_assoc()) {
                $products_list[] = $row;
            }
        }
    }
} catch (Exception $e) {}

// 3. Site Settings
try {
    if (isset($conn)) {
        $sql = "SELECT setting_key, setting_value FROM site_settings";
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $settings[$row['setting_key']] = $row['setting_value'];
            }
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
    'Blog' => 'blog',
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
            position: relative;
            z-index: 50;
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
            z-index: 9999; 
            transition: all 0.3s ease;
            position: relative; /* Essential for absolute children */
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
            font-weight: 500; /* Slightly lighter for modern look */
            font-size: 15px; 
            padding: 28px 12px;
            transition: color 0.3s;
            white-space: nowrap;
            display: flex;
            align-items: center;
            height: 100%;
            position: relative;
        }

        .sp-nav-link:hover {
            color: var(--sp-teal);
        }

        /* Active State Indicator */
        .sp-nav-active {
            color: var(--sp-red) !important;
        }
        
        /* Optional: Underline effect for active/hover */
        .sp-nav-link::after {
            content: '';
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background-color: var(--sp-red);
            transition: width 0.3s ease;
        }
        
        .sp-nav-link:hover::after,
        .sp-nav-active::after {
            width: 80%;
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
        
        /* Special style for View More link */
        .sp-dropdown-viewmore {
            color: var(--sp-teal);
            font-weight: 600;
            border-top: 1px solid #e5e7eb;
            background-color: #fcfcfc;
        }
        .sp-dropdown-viewmore:hover {
            color: var(--sp-red);
            background-color: #f3f4f6;
        }

        /* --- BUTTONS --- */
        .sp-btn-primary {
            background-color: var(--sp-teal);
            color: white;
            padding: 10px 28px;
            border-radius: 50px; /* Fully rounded for style */
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s;
            box-shadow: 0 4px 10px rgba(30, 144, 184, 0.2);
            text-align: center;
            display: inline-block;
            white-space: nowrap;
        }

        .sp-btn-primary:hover {
            background-color: var(--sp-red);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(215, 25, 32, 0.2);
        }

        /* --- MOBILE MENU --- */
        #sp-mobile-menu-drawer {
            transition: max-height 0.4s ease-in-out, opacity 0.4s ease-in-out, visibility 0.4s;
            overflow-y: auto; 
            max-height: 0;
            opacity: 0;
            visibility: hidden;
            top: 100%; 
        }
        
        #sp-mobile-menu-drawer.open {
            max-height: calc(100vh - 80px);
            opacity: 1;
            visibility: visible;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>

    <div id="sp-top-bar" class="sp-topbar-wrapper py-2 border-b border-gray-800">
        <div class="max-w-[1400px] mx-auto px-4 lg:px-10 flex flex-col md:flex-row justify-between items-center gap-2 md:gap-0">
            
            <div class="flex flex-wrap justify-center md:justify-start items-center gap-x-6 gap-y-1 text-xs md:text-[13px]">
                <div class="flex items-center gap-2">
                    <i class="fa-regular fa-envelope text-[#D71920]"></i>
                    <a href="mailto:<?php echo $email; ?>" class="hover:text-gray-300 transition-colors"><?php echo $email; ?></a>
                </div>
                <div class="hidden sm:flex items-center gap-2">
                    <i class="fa-solid fa-phone-volume text-[#D71920]"></i>
                    <span><?php echo $phone_list; ?></span>
                </div>
                <div class="sm:hidden flex items-center gap-2">
                     <i class="fa-solid fa-phone-volume text-[#D71920]"></i>
                     <a href="tel:<?php echo $main_phone; ?>"><?php echo $main_phone; ?></a>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="#" class="sp-social-icon"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#" class="sp-social-icon"><i class="fa-brands fa-instagram"></i></a>
                <a href="#" class="sp-social-icon"><i class="fa-brands fa-linkedin-in"></i></a>
                <a href="#" class="sp-social-icon"><i class="fa-brands fa-whatsapp"></i></a>
            </div>

        </div>
    </div>

    <nav id="sp-main-navbar" class="sp-navbar-wrapper">
        <div class="max-w-[1400px] mx-auto px-4 lg:px-10 h-[80px] lg:h-[90px] flex justify-between items-center relative">
            
            <div class="flex-shrink-0 flex items-center">
                <a href="index">
                    <img src="Assets/2.png" alt="Srishti Polytech" class="h-[45px] md:h-[55px] lg:h-[65px] w-auto object-contain">
                </a>
            </div>

            <div class="hidden xl:flex items-center gap-6 h-full">
                
                <div class="flex items-center gap-1">
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
                                    <?php 
                                    // LIMIT LOGIC: Show only 5 items
                                    $limit = 5;
                                    $count = 0;
                                    foreach ($items as $item): 
                                        if($count >= $limit) break;
                                    ?>
                                        <a href="<?php echo $detailPage; ?>?id=<?php echo $item['id']; ?>" class="sp-dropdown-item">
                                            <?php echo htmlspecialchars($item['name']); ?>
                                        </a>
                                    <?php 
                                        $count++; 
                                    endforeach; 
                                    ?>

                                    <?php 
                                    // VIEW MORE LINK if items exceed limit
                                    if (count($items) > $limit): 
                                    ?>
                                        <a href="<?php echo $url; ?>" class="sp-dropdown-item sp-dropdown-viewmore">
                                            View All <?php echo $title; ?> <i class="fa-solid fa-arrow-right-long ml-1 text-xs"></i>
                                        </a>
                                    <?php endif; ?>

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

                <div class="pl-2">
                    <a href="contact" class="sp-btn-primary">
                        Get Enquiry
                    </a>
                </div>

            </div>

            <div class="xl:hidden ml-auto">
                 <button id="sp-mobile-toggle-btn" class="p-2 text-2xl text-gray-800 focus:outline-none hover:text-[#D71920] transition-colors">
                    <i class="fa-solid fa-bars"></i>
                 </button>
            </div>

        </div>

        <div id="sp-mobile-menu-drawer" class="xl:hidden bg-white border-t border-gray-100 absolute w-full left-0 shadow-2xl z-40">
            <div class="flex flex-col p-5 space-y-3 pb-20"> 
                
                <?php foreach ($nav_items as $title => $url): 
                    $isActive = ($url === $current_page);
                    $mobileActiveClass = $isActive ? 'text-[#D71920] bg-gray-50' : 'text-gray-800';

                    if ($title === 'Product' || $title === 'Service') {
                        $items = ($title === 'Product') ? $products_list : $services_list;
                        $detailPage = ($title === 'Product') ? 'product_details' : 'service_details';
                ?>
                    <div class="border-b border-gray-50 pb-2">
                        <div class="flex flex-col">
                            <a href="<?php echo $url; ?>" class="flex justify-between items-center font-medium <?php echo $mobileActiveClass; ?> px-2 py-2 rounded hover:bg-gray-50 transition-colors">
                                <?php echo $title; ?>
                                <i class="fa-solid fa-chevron-down text-xs text-gray-400"></i>
                            </a>
                            <div class="pl-4 mt-1 space-y-1 border-l-2 border-gray-100 ml-2">
                                <?php if (!empty($items)): ?>
                                    <?php 
                                    // LIMIT LOGIC FOR MOBILE TOO (To keep menu clean)
                                    $limit = 5;
                                    $count = 0;
                                    foreach ($items as $item): 
                                        if($count >= $limit) break;
                                    ?>
                                        <a href="<?php echo $detailPage; ?>?id=<?php echo $item['id']; ?>" class="block text-sm text-gray-500 py-2 px-2 rounded hover:text-[#D71920] hover:bg-gray-50">
                                            - <?php echo htmlspecialchars($item['name']); ?>
                                        </a>
                                    <?php 
                                        $count++;
                                    endforeach; 
                                    ?>
                                    
                                    <?php if (count($items) > $limit): ?>
                                        <a href="<?php echo $url; ?>" class="block text-sm font-semibold text-[#1e90b8] py-2 px-2 rounded hover:text-[#D71920]">
                                            View All <?php echo $title; ?> <i class="fa-solid fa-arrow-right text-[10px] ml-1"></i>
                                        </a>
                                    <?php endif; ?>

                                <?php else: ?>
                                    <span class="text-xs text-gray-400 py-1 pl-2">No items yet</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                <?php } else { ?>
                    <a href="<?php echo $url; ?>" class="block font-medium <?php echo $mobileActiveClass; ?> hover:text-[#D71920] hover:bg-gray-50 px-2 py-2 rounded transition-colors border-b border-gray-50">
                        <?php echo $title; ?>
                    </a>
                <?php } endforeach; ?>

                <div class="pt-4">
                    <a href="contact" class="block w-full text-center bg-[#1e90b8] text-white py-3 rounded-lg font-medium shadow-md hover:bg-[#D71920] transition-colors">
                        Get Enquiry
                    </a>
                </div>

            </div>
        </div>
    </nav>
    
    <div id="sp-navbar-spacer" class="hidden"></div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // --- 1. STICKY NAVBAR LOGIC ---
            const topBar = document.getElementById('sp-top-bar');
            const navBar = document.getElementById('sp-main-navbar');
            const spacer = document.getElementById('sp-navbar-spacer');
            
            let topBarHeight = topBar ? topBar.offsetHeight : 0;
            let navHeight = navBar ? navBar.offsetHeight : 90;

            window.addEventListener('scroll', () => {
                if (window.scrollY > topBarHeight) {
                    navBar.classList.add('sp-fixed-active');
                    if(spacer) {
                        spacer.style.display = 'block';
                        spacer.style.height = navHeight + 'px';
                    }
                } else {
                    navBar.classList.remove('sp-fixed-active');
                    if(spacer) {
                        spacer.style.display = 'none';
                        spacer.style.height = '0px';
                    }
                }
            });

            // --- 2. MOBILE MENU LOGIC ---
            const toggleBtn = document.getElementById('sp-mobile-toggle-btn');
            const mobileMenu = document.getElementById('sp-mobile-menu-drawer');
            const icon = toggleBtn ? toggleBtn.querySelector('i') : null;

            if(toggleBtn && mobileMenu) {
                toggleBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
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

                // Ensure menu closes if window is resized to desktop view
                window.addEventListener('resize', () => {
                    if (window.innerWidth >= 1280) { // xl breakpoint
                         mobileMenu.classList.remove('open');
                         if(icon) {
                             icon.classList.remove('fa-xmark');
                             icon.classList.add('fa-bars');
                         }
                    }
                });
            }
        });
    </script>
</body>
</html>