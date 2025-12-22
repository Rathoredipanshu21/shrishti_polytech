<?php

include 'config/db.php'; 

$settings = [];
// Use try-catch for safety
try {
    $sql = "SELECT setting_key, setting_value FROM site_settings";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
    }
} catch (Exception $e) {
    // Silent fail
}

if(isset($conn)) {
    $conn->close();
}

// =========================================================
// 2. Set Fallbacks (Matching Srishti Polytech Info)
// =========================================================
$phone_list = $settings['phone_list'] ?? '+91-7004471859, +91-9431313684';
$main_phone = '+91-7004471859';
$email = $settings['email'] ?? 'srishtipolytech@gmail.com';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* Scoped Footer Styles */
        .srishti-footer-root {
            font-family: 'Poppins', sans-serif;
            --brand-red: #D71920;
            --brand-teal: #1e90b8; 
            --brand-dark: #111111;
            background-color: var(--brand-dark);
            color: #d1d5db; /* Gray-300 */
        }
        
        .srishti-footer-link {
            transition: all 0.3s ease;
        }
        .srishti-footer-link:hover {
            color: var(--brand-red);
            padding-left: 6px; /* Slide effect */
        }

        .srishti-social-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        .srishti-social-btn:hover {
            background: var(--brand-teal);
            transform: translateY(-3px);
            color: white;
        }
        
        /* Custom Underline for Headings */
        .srishti-heading-line::after {
            content: '';
            display: block;
            width: 40px;
            height: 3px;
            background: var(--brand-red);
            margin-top: 10px;
            border-radius: 2px;
        }
    </style>
</head>
<body class="overflow-x-hidden">

    <div class="w-full h-1 bg-gradient-to-r from-[#D71920] via-[#1e90b8] to-[#D71920]"></div>

    <footer class="srishti-footer-root pt-16 pb-8 w-full">
        <div class="max-w-[1400px] w-full mx-auto px-6 lg:px-10">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12 items-start">

                <div>
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-white uppercase tracking-wider">Srishti <span class="text-[#D71920]">Polytech</span></h2>
                    </div>
                    
                    <p class="text-sm leading-relaxed mb-6 opacity-90">
                        We are a leading provider of high-quality industrial solutions. Committed to excellence, innovation, and customer satisfaction, we deliver products that build the future.
                    </p>
                    
                    <div class="flex space-x-3 mb-8">
                        <a href="https://www.facebook.com/srishtipolytech" class="srishti-social-btn"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://www.instagram.com/srishtipolytech" class="srishti-social-btn"><i class="fab fa-instagram"></i></a>
                        <a href="" class="srishti-social-btn"><i class="fab fa-linkedin-in"></i></a>
                        <a href="https://wa.me/917004471859?text=Hi%20Srishti%20Polytech%2C%20I%20am%20interested%20in%20your%20services." class="srishti-social-btn"><i class="fab fa-whatsapp"></i></a>
                    </div>

                    <div class="space-y-3 border-t border-gray-800 pt-6">
                         <div class="flex items-center">
                            <i class="fas fa-phone-alt mr-3 text-[#D71920]"></i>
                            <div class="flex flex-col text-sm">
                                <?php 
                                    $phones = explode(',', $phone_list);
                                    foreach($phones as $ph) {
                                        echo '<a href="tel:'.trim($ph).'" class="hover:text-white transition-colors">'.trim($ph).'</a>';
                                    }
                                ?>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-envelope mr-3 text-[#D71920]"></i>
                            <a href="mailto:<?php echo htmlspecialchars($email); ?>" class="text-sm hover:text-white transition-colors break-all"><?php echo htmlspecialchars($email); ?></a>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-white mb-6 srishti-heading-line">Quick Links</h3>
                    <ul class="space-y-3 text-sm">
                        <li><a href="index" class="srishti-footer-link flex items-center"><i class="fas fa-chevron-right text-[10px] mr-2 text-[#1e90b8]"></i>Home</a></li>
                        <li><a href="about" class="srishti-footer-link flex items-center"><i class="fas fa-chevron-right text-[10px] mr-2 text-[#1e90b8]"></i>About Us</a></li>
                        <li><a href="products" class="srishti-footer-link flex items-center"><i class="fas fa-chevron-right text-[10px] mr-2 text-[#1e90b8]"></i>Our Products</a></li>
                        <li><a href="services" class="srishti-footer-link flex items-center"><i class="fas fa-chevron-right text-[10px] mr-2 text-[#1e90b8]"></i>Services</a></li>
                        <li><a href="gallery" class="srishti-footer-link flex items-center"><i class="fas fa-chevron-right text-[10px] mr-2 text-[#1e90b8]"></i>Gallery</a></li>
                        <li><a href="contact" class="srishti-footer-link flex items-center"><i class="fas fa-chevron-right text-[10px] mr-2 text-[#1e90b8]"></i>Contact Us</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-white mb-6 srishti-heading-line">Our Locations</h3>
                    <ul class="space-y-5 text-sm">
                        
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-3 text-[#D71920] flex-shrink-0"></i>
                            <div class="flex flex-col">
                                <span class="font-bold text-white mb-1">Head Office:</span>
                                <span class="opacity-80 leading-relaxed">Pandey Niwas, Gopal Nagar, Manaitand, Dhanbad, Jharkhand – 826001</span>
                            </div>
                        </li>

                        <li class="flex items-start">
                            <i class="fas fa-building mt-1 mr-3 text-[#D71920] flex-shrink-0"></i>
                            <div class="flex flex-col">
                                <span class="font-bold text-white mb-1">Comm. & Support Center:</span>
                                <span class="opacity-80 leading-relaxed">Office No. 07, 4th Floor, Center Point Mall, Katras Road, Bank More, Dhanbad, Jharkhand</span>
                            </div>
                        </li>

                        <li class="flex items-start">
                            <i class="fas fa-warehouse mt-1 mr-3 text-[#D71920] flex-shrink-0"></i>
                            <div class="flex flex-col">
                                <span class="font-bold text-white mb-1">Store / Warehouse:</span>
                                <span class="opacity-80 leading-relaxed">Hirak By-Pass Road, Near Holy Angel Public School, Sugiyadih, Dhanbad, Jharkhand</span>
                            </div>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-white mb-6 srishti-heading-line">Newsletter</h3>
                    <p class="text-sm mb-5 opacity-90">
                        Subscribe to our newsletter to get the latest updates and offers directly in your inbox.
                    </p>
                    <form action="#" method="POST" class="relative">
                        <input type="email" name="email" placeholder="Your Email Address" 
                            class="w-full bg-[#1a1a1a] border border-gray-700 text-white text-sm px-4 py-3 rounded focus:outline-none focus:border-[#D71920] transition-colors" required>
                        <button type="submit" 
                            class="absolute right-1 top-1 bottom-1 bg-[#D71920] hover:bg-[#b01319] text-white px-4 rounded transition-colors duration-300">
                            <i class="fas fa-paper-plane text-sm"></i>
                        </button>
                    </form>
                </div>

            </div>

            <div class="border-t border-gray-800 pt-8 mt-8 flex flex-col md:flex-row justify-between items-center text-xs md:text-sm text-gray-500">
                <p class="text-center md:text-left mb-2 md:mb-0">
                    &copy; <?php echo date("Y"); ?> <span class="text-gray-300 font-medium">Srishti Polytech</span>. All Rights Reserved.
                </p>
                <p class="text-center md:text-right">
                     Designed & Developed by <a href="https://www.systaio.com" target="_blank" rel="noopener noreferrer" class="font-medium text-[#1e90b8] hover:text-[#D71920] transition-colors">Systaio Technologies</a>
                </p>
            </div>

        </div>
    </footer>

</body>
</html>