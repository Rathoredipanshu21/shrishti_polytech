<?php
// Initial include to handle sessions or settings
include 'config/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Services - Srishti Polytech</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="Assets/logo.png">

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f9fafb; }
        .hero-zoom { animation: zoomEffect 20s infinite alternate; }
        @keyframes zoomEffect { 0% { transform: scale(1); } 100% { transform: scale(1.1); } }
        
        .service-card { transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        .service-card:hover { transform: translateY(-10px); }
        
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>
<body class="overflow-x-hidden flex flex-col min-h-screen">
    
    <?php include 'navbar.php'; ?>

    <!-- HERO SECTION -->
    <!-- Used min-h-[60vh] to accommodate text on small mobile screens without clipping -->
    <div class="relative w-full min-h-[60vh] flex items-center justify-center overflow-hidden bg-[#111] py-20 md:py-0">
        <div class="absolute inset-0 opacity-60">
            <img src="https://images.unsplash.com/photo-1581093588402-4857474d2f78?q=80&w=2070&auto=format&fit=crop" 
                 class="w-full h-full object-cover hero-zoom" alt="Services Background">
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-[#111] via-transparent to-[#111]/80"></div>
        
        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto" data-aos="zoom-in">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-6 drop-shadow-lg leading-tight">
                Premium <span class="text-[#1e90b8]">Services</span>
            </h1>
            <p class="text-gray-300 text-lg md:text-xl max-w-2xl mx-auto font-light leading-relaxed">
                Comprehensive water engineering solutions tailored for efficiency, sustainability, and performance.
            </p>
            <div class="mt-10 animate-bounce hidden md:block">
                <i class="fa-solid fa-chevron-down text-white text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <section class="container mx-auto px-4 lg:px-8 py-16 md:py-20 flex-grow">
        
        <div class="flex flex-col md:flex-row items-center justify-between mb-12 border-b border-gray-200 pb-4 gap-4 text-center md:text-left">
            <div>
                <span class="text-[#D71920] font-bold uppercase tracking-widest text-sm">What We Offer</span>
                <h2 class="text-3xl font-bold text-gray-800 mt-1">Service Catalogue</h2>
            </div>
            <div class="hidden md:block text-gray-400 font-medium">
                <i class="fa-solid fa-filter mr-2"></i> All Services
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-10">
            
            <?php 
            // Re-connect to DB to ensure connection is active
            include 'config/db.php'; 

            $sql = "SELECT * FROM services ORDER BY id DESC";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $images = json_decode($row['images'], true);
                    $thumb = (!empty($images) && is_array($images)) ? $images[0] : "https://via.placeholder.com/600x400?text=Service";
            ?>
            
            <a href="service_details?id=<?php echo $row['id']; ?>" class="group block h-full">
                <div class="service-card bg-white rounded-2xl shadow-lg hover:shadow-2xl overflow-hidden h-full flex flex-col border border-gray-100 relative">
                    
                    <!-- Card Image -->
                    <div class="relative h-56 md:h-64 overflow-hidden">
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-colors z-10"></div>
                        <img src="<?php echo htmlspecialchars($thumb); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" 
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        
                        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm p-3 rounded-full shadow-lg z-20 text-[#1e90b8]">
                            <i class="fa-solid fa-arrow-right -rotate-45 group-hover:rotate-0 transition-transform duration-300"></i>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-6 md:p-8 flex-grow flex flex-col">
                        <div class="mb-4">
                            <h3 class="text-xl md:text-2xl font-bold text-gray-800 group-hover:text-[#D71920] transition-colors mb-3">
                                <?php echo htmlspecialchars($row['name']); ?>
                            </h3>
                            <div class="w-12 h-1 bg-[#1e90b8] rounded-full group-hover:w-20 transition-all duration-300"></div>
                        </div>
                        
                        <p class="text-gray-600 text-sm leading-relaxed mb-6 line-clamp-3">
                            <?php echo strip_tags($row['description']); ?>
                        </p>
                        
                        <div class="mt-auto flex items-center gap-2 text-[#1e90b8] font-bold text-sm uppercase tracking-wider group-hover:text-[#D71920] transition-colors">
                            <span>Read More</span>
                            <i class="fa-solid fa-arrow-right-long group-hover:translate-x-2 transition-transform"></i>
                        </div>
                    </div>
                </div>
            </a>

            <?php 
                }
            } else {
                echo '
                <div class="col-span-full py-20 text-center">
                    <div class="inline-block p-6 rounded-full bg-gray-100 text-gray-400 mb-4 text-4xl"><i class="fa-solid fa-box-open"></i></div>
                    <h3 class="text-xl font-bold text-gray-600">No services found</h3>
                    <p class="text-gray-400 mt-2">Please check back later.</p>
                </div>';
            }
            ?>

        </div>
    </section>

    <!-- CALL TO ACTION -->
    <section class="bg-[#D71920] py-16 text-center text-white relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-10"></div>
        <div class="container mx-auto px-4 relative z-10" data-aos="fade-up">
            <h2 class="text-2xl md:text-3xl font-bold mb-4">Need a Custom Solution?</h2>
            <p class="text-white/80 mb-8 max-w-2xl mx-auto text-sm md:text-base">We provide tailored engineering services for unique industrial requirements.</p>
            <a href="contact" class="inline-block bg-white text-[#D71920] px-8 py-3 rounded-full font-bold shadow-lg hover:bg-gray-100 transition-colors transform hover:-translate-y-1">
                Contact Us Today
            </a>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script> AOS.init({ duration: 800, once: true }); </script>
</body>
</html>