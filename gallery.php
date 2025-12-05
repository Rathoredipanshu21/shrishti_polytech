<?php
include 'config/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery - Srishti Polytech</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        #srishti-gallery-root {
            font-family: 'Poppins', sans-serif;
            --brand-red: #D71920;
            --brand-teal: #1e90b8;
            --brand-dark: #111111;
        }

        /* Hero Animation */
        .zoom-bg { animation: zoomInOut 20s infinite alternate; }
        @keyframes zoomInOut { 0% { transform: scale(1); } 100% { transform: scale(1.1); } }

        /* Masonry Grid Setup */
        .gallery-item {
            break-inside: avoid;
            margin-bottom: 1.5rem;
        }

        /* Lightbox Styling */
        #lightbox {
            transition: opacity 0.3s ease;
            pointer-events: none;
            opacity: 0;
        }
        #lightbox.active {
            pointer-events: auto;
            opacity: 1;
        }
        #lightbox img {
            max-height: 90vh;
            max-width: 90vw;
            box-shadow: 0 0 50px rgba(0,0,0,0.5);
        }
    </style>
</head>
<body id="srishti-gallery-root" class="bg-gray-50 overflow-x-hidden">
        <?php include 'navbar.php'; ?>
    <header class="relative w-full h-[60vh] flex items-center justify-center overflow-hidden bg-[#111]">
        <div class="absolute inset-0 opacity-50">
            <img src="https://images.unsplash.com/photo-1565514020176-db981be2d329?q=80&w=2070&auto=format&fit=crop" 
                 alt="Gallery Background" class="w-full h-full object-cover zoom-bg">
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-[#111] via-transparent to-[#111]/80"></div>
        
        <div class="relative z-10 text-center px-4" data-aos="zoom-in">
            <div class="inline-block border border-white/30 bg-white/10 backdrop-blur-md rounded-full px-5 py-1 mb-4">
                <span class="text-[#D71920] font-bold text-sm tracking-widest uppercase">Our Portfolio</span>
            </div>
            <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-6 drop-shadow-2xl">
                Moments of <span class="text-[#1e90b8]">Excellence</span>
            </h1>
            <p class="text-gray-300 text-lg md:text-xl max-w-2xl mx-auto font-light leading-relaxed">
                A visual journey through our projects, infrastructure, and achievements.
            </p>
        </div>
    </header>

    <section class="container mx-auto px-4 py-20 min-h-screen">
        
        <div class="flex flex-col md:flex-row justify-between items-end mb-12 border-b border-gray-200 pb-4" data-aos="fade-up">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Recent <span class="text-[#D71920]">Uploads</span></h2>
            </div>
            <div class="mt-4 md:mt-0 text-gray-500 text-sm">
                <i class="fa-solid fa-camera text-[#1e90b8] mr-2"></i> All Photos
            </div>
        </div>

        <div class="columns-1 sm:columns-2 lg:columns-3 xl:columns-4 gap-6 space-y-6">
            <?php 
            // FIX: Re-include DB here in case navbar.php closed the previous connection
            include 'config/db.php';

            $sql = "SELECT * FROM gallery ORDER BY id DESC";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $imgSrc = htmlspecialchars($row['image_path']);
                    $imgTitle = htmlspecialchars($row['title']);
            ?>
                <div class="gallery-item relative group overflow-hidden rounded-xl shadow-lg cursor-zoom-in" 
                     onclick="openLightbox('<?php echo $imgSrc; ?>', '<?php echo $imgTitle; ?>')"
                     data-aos="fade-up">
                    
                    <img src="<?php echo $imgSrc; ?>" alt="Gallery Image" class="w-full h-auto transform transition-transform duration-700 group-hover:scale-110">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                        <div class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                            <span class="inline-block bg-[#D71920] w-10 h-1 mb-2 rounded-full"></span>
                            <?php if($imgTitle): ?>
                                <h3 class="text-white font-bold text-lg leading-tight"><?php echo $imgTitle; ?></h3>
                            <?php else: ?>
                                <h3 class="text-white/70 italic text-sm">Srishti Polytech</h3>
                            <?php endif; ?>
                        </div>
                        <div class="absolute top-4 right-4 text-white">
                            <i class="fa-solid fa-expand text-xl drop-shadow-md"></i>
                        </div>
                    </div>
                </div>
            <?php 
                }
            } else {
                echo '
                <div class="col-span-full py-20 text-center w-full bg-white rounded-xl border border-dashed border-gray-300">
                    <i class="fa-regular fa-image text-5xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">No images found in the gallery.</p>
                </div>';
            }
            ?>
        </div>

    </section>

    <div id="lightbox" class="fixed inset-0 z-[100] bg-black/95 flex items-center justify-center p-4">
        
        <button onclick="closeLightbox()" class="absolute top-6 right-6 text-white/50 hover:text-[#D71920] transition-colors z-[110]">
            <i class="fa-solid fa-xmark text-4xl"></i>
        </button>

        <div class="relative max-w-full max-h-full flex flex-col items-center">
            <img id="lightbox-img" src="" alt="Full View" class="rounded-lg object-contain">
            <p id="lightbox-caption" class="text-white mt-4 text-lg font-medium tracking-wide bg-black/50 px-4 py-2 rounded-full backdrop-blur-sm"></p>
        </div>

    </div>

    

    <?php include 'footer.php'; ?>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true });

        // Lightbox Logic
        const lightbox = document.getElementById('lightbox');
        const lightboxImg = document.getElementById('lightbox-img');
        const lightboxCaption = document.getElementById('lightbox-caption');

        function openLightbox(src, title) {
            lightboxImg.src = src;
            lightboxCaption.textContent = title || '';
            lightboxCaption.style.display = title ? 'block' : 'none';
            lightbox.classList.add('active');
            document.body.style.overflow = 'hidden'; // Prevent scrolling
        }

        function closeLightbox() {
            lightbox.classList.remove('active');
            setTimeout(() => { lightboxImg.src = ''; }, 300);
            document.body.style.overflow = ''; // Restore scrolling
        }

        // Close on background click
        lightbox.addEventListener('click', (e) => {
            if (e.target === lightbox) closeLightbox();
        });

        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && lightbox.classList.contains('active')) closeLightbox();
        });
    </script>
</body>
</html>