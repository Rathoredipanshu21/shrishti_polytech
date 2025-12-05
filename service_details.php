<?php
include 'config/db.php';

if (!isset($_GET['id'])) {
    header("Location: services");
    exit();
}

$id = $conn->real_escape_string($_GET['id']);
$sql = "SELECT * FROM services WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "Service not found.";
    exit();
}

$service = $result->fetch_assoc();
$images = json_decode($service['images'], true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($service['name']); ?> - Service Details</title>
            <link rel="icon" type="image/x-icon" href="Assets/logo.png">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        
        /* Thumbnails */
        .thumb-active { border: 2px solid #D71920; opacity: 1; transform: scale(1.05); }
        .thumb-item { transition: all 0.3s ease; opacity: 0.6; }
        .thumb-item:hover { opacity: 1; }

        /* Description Styling */
        .desc-content h1, .desc-content h2, .desc-content h3 { font-weight: 700; color: #1f2937; margin-top: 1.5em; margin-bottom: 0.5em; }
        .desc-content p { margin-bottom: 1em; line-height: 1.7; color: #4b5563; }
        .desc-content ul { list-style-type: disc; padding-left: 1.5em; margin-bottom: 1em; color: #4b5563; }
        .desc-content li { margin-bottom: 0.5em; }
    </style>
</head>
<body class="bg-white overflow-x-hidden">

   <?php include 'navbar.php'; ?>

    <!-- MAIN CONTENT -->
    <div class="container mx-auto px-4 py-12 lg:py-16">
        <div class="flex flex-col lg:flex-row gap-12">
            
            <!-- LEFT: GALLERY -->
            <div class="w-full lg:w-5/12" data-aos="fade-right">
                
                <!-- Main Display Image -->
                <div class="relative w-full h-[350px] md:h-[450px] bg-gray-100 rounded-2xl overflow-hidden shadow-2xl mb-4 group">
                    <img id="mainDisplay" src="<?php echo (!empty($images) && is_array($images)) ? htmlspecialchars($images[0]) : 'https://via.placeholder.com/600'; ?>" 
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    
                    <div class="absolute top-4 left-4 bg-white/90 backdrop-blur px-3 py-1 rounded text-xs font-bold text-gray-800 shadow">
                        SERVICE GALLERY
                    </div>
                </div>

                <!-- Thumbnails -->
                <?php if (!empty($images) && is_array($images) && count($images) > 1): ?>
                <div class="grid grid-cols-4 gap-3">
                    <?php foreach($images as $index => $img): ?>
                        <div onclick="swapImage('<?php echo htmlspecialchars($img); ?>', this)" 
                             class="thumb-item cursor-pointer aspect-square rounded-lg overflow-hidden border border-gray-200 shadow-sm <?php echo $index === 0 ? 'thumb-active' : ''; ?>">
                            <img src="<?php echo htmlspecialchars($img); ?>" class="w-full h-full object-cover">
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Quick Contact Card -->
                <div class="mt-8 bg-[#f8f9fa] border border-gray-200 rounded-xl p-6 shadow-sm">
                    <h4 class="font-bold text-gray-800 mb-4">Interested in this service?</h4>
                    <div class="flex flex-col gap-3">
                        <a href="tel:+917004471859" class="flex items-center gap-3 text-gray-600 hover:text-[#1e90b8] transition">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-[#1e90b8]"><i class="fa-solid fa-phone"></i></div>
                            <span class="font-medium">+91-7004471859</span>
                        </a>
                        <a href="mailto:srishtipolytech@gmail.com" class="flex items-center gap-3 text-gray-600 hover:text-[#D71920] transition">
                            <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-[#D71920]"><i class="fa-solid fa-envelope"></i></div>
                            <span class="font-medium">srishtipolytech@gmail.com</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- RIGHT: INFO -->
            <div class="w-full lg:w-7/12" data-aos="fade-left">
                
                <div class="mb-2 flex items-center gap-2">
                    <span class="h-0.5 w-8 bg-[#D71920]"></span>
                    <span class="text-[#1e90b8] font-bold text-sm uppercase tracking-widest">Service Overview</span>
                </div>
                
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-6 leading-tight">
                    <?php echo htmlspecialchars($service['name']); ?>
                </h1>

                <!-- Description Area -->
                <div class="desc-content bg-white p-2 text-lg text-gray-600 leading-relaxed mb-8">
                    <?php echo nl2br($service['description']); ?>
                </div>

                <!-- Features / Benefits (Static Visuals for Style) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                    <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg border-l-4 border-[#1e90b8]">
                        <i class="fa-solid fa-screwdriver-wrench text-2xl text-[#1e90b8] mt-1"></i>
                        <div>
                            <h5 class="font-bold text-gray-800">Expert Installation</h5>
                            <p class="text-xs text-gray-500 mt-1">Handled by certified technicians.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg border-l-4 border-[#D71920]">
                        <i class="fa-solid fa-medal text-2xl text-[#D71920] mt-1"></i>
                        <div>
                            <h5 class="font-bold text-gray-800">Quality Assurance</h5>
                            <p class="text-xs text-gray-500 mt-1">ISO Certified standards.</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-wrap gap-4">
                    <a href="contact?service=<?php echo urlencode($service['name']); ?>" 
                       class="bg-[#D71920] hover:bg-[#b01319] text-white px-8 py-4 rounded-lg font-bold shadow-lg transition-transform transform hover:-translate-y-1 flex items-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i> Get A Quote
                    </a>
                    <a href="https://wa.me/917004471859?text=Hi, I need details about <?php echo urlencode($service['name']); ?>" target="_blank"
                       class="bg-[#25D366] hover:bg-[#1ebc59] text-white px-8 py-4 rounded-lg font-bold shadow-lg transition-transform transform hover:-translate-y-1 flex items-center gap-2">
                        <i class="fa-brands fa-whatsapp text-xl"></i> WhatsApp Us
                    </a>
                </div>

            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true });

        function swapImage(src, el) {
            document.getElementById('mainDisplay').src = src;
            document.querySelectorAll('.thumb-item').forEach(i => i.classList.remove('thumb-active'));
            el.classList.add('thumb-active');
        }
    </script>
</body>
</html>