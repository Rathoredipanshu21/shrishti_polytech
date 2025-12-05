<?php
include 'config/db.php';
?>
<!DOCTYPE html>
<html lang="en" class="overflow-x-hidden">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Our Products - Srishti Polytech</title>
    <link rel="icon" type="image/x-icon" href="Assets/logo.png">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .product-card:hover .product-img { transform: scale(1.1); }
        /* Ensure no horizontal scrollbar ever appears */
        body, html { overflow-x: hidden; width: 100%; }
    </style>
</head>
<body class="bg-gray-50 overflow-x-hidden w-full">

    <!-- Header -->
    <div class="bg-[#111] py-12 md:py-20 text-center relative overflow-hidden w-full">
        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1565514020176-db981be2d329?q=80&w=2070')] bg-cover bg-center opacity-40"></div>
        <div class="relative z-10 container mx-auto px-4 sm:px-6" data-aos="zoom-in">
            <h1 class="text-3xl sm:text-4xl md:text-6xl font-extrabold text-white mb-4 leading-tight">Our <span class="text-[#D71920]">Products</span></h1>
            <p class="text-gray-300 text-base sm:text-lg max-w-2xl mx-auto px-2">Discover our range of advanced water treatment solutions engineered for excellence.</p>
        </div>
    </div>

    <!-- Product Grid -->
    <section class="container mx-auto px-4 sm:px-6 py-12 md:py-16 w-full">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 md:gap-8">
            
            <?php 
            $sql = "SELECT * FROM products ORDER BY id DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $images = json_decode($row['images'], true);
                    $thumb = !empty($images) ? $images[0] : "https://via.placeholder.com/400x300?text=No+Image";
            ?>
            
            <!-- Card -->
            <a href="product_details?id=<?php echo $row['id']; ?>" class="group block w-full" data-aos="fade-up">
                <div class="product-card bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100 hover:shadow-2xl transition-all duration-300 h-full flex flex-col w-full">
                    
                    <!-- Image Wrapper -->
                    <div class="relative h-56 sm:h-64 overflow-hidden bg-gray-200">
                        <img src="<?php echo htmlspecialchars($thumb); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="product-img w-full h-full object-cover transition-transform duration-700">
                        
                        <!-- Overlay Icon -->
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <span class="bg-[#D71920] text-white w-10 h-10 sm:w-12 sm:h-12 rounded-full flex items-center justify-center text-lg sm:text-xl shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                                <i class="fa-solid fa-arrow-right"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-4 sm:p-6 flex-grow flex flex-col justify-between">
                        <div>
                            <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-2 group-hover:text-[#1e90b8] transition-colors line-clamp-2">
                                <?php echo htmlspecialchars($row['name']); ?>
                            </h3>
                            <div class="w-10 h-1 bg-[#1e90b8] rounded mb-3"></div>
                        </div>
                        <span class="text-xs sm:text-sm font-semibold text-gray-400 uppercase tracking-wider group-hover:text-[#D71920] transition-colors mt-2">
                            View Details
                        </span>
                    </div>
                </div>
            </a>

            <?php 
                }
            } else {
                echo '<div class="col-span-full text-center py-20 text-gray-500">No products found.</div>';
            }
            ?>

        </div>
    </section>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script> 
        AOS.init({ 
            duration: 800, 
            once: true,
            disable: 'mobile' // Optional: disables animations on mobile if they cause scroll issues
        }); 
    </script>
</body>
</html>