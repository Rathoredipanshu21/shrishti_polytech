<?php
// Connect to the database
include 'config/db.php'; 

// Fetch images
$slides = [];
try {
    $result = $conn->query("SELECT * FROM hero_images ORDER BY id DESC");
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $slides[] = $row;
        }
    }
} catch (Exception $e) {
    // Fallback if table doesn't exist
}

// Fallback Slide if empty
if (empty($slides)) {
    $slides[] = [
        'image_path' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=1920&q=80',
        'headline' => 'Innovating for a Better Future',
        'description' => 'Leading the industry with premium polytech solutions and sustainable manufacturing.'
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hero Section</title>
    <link rel="icon" type="image/x-icon" href="../Assets/logo.png">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <style>
        #hero-root {
            font-family: 'Poppins', sans-serif;
            --brand-red: #D71920;
            --brand-teal: #1e90b8;
        }
        .hero-slide {
            opacity: 0;
            transition: opacity 1000ms ease-in-out;
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            display: none;
        }
        .hero-slide.active {
            opacity: 1;
            display: flex;
            z-index: 10;
        }
        .text-glow {
            text-shadow: 2px 2px 4px rgba(0,0,0,0.7);
        }
        .nav-btn:hover {
            background-color: var(--brand-red);
            border-color: var(--brand-red);
            transform: scale(1.1);
        }
        @keyframes progress {
            0% { width: 0%; }
            100% { width: 100%; }
        }
        .progress-bar {
            width: 0%; height: 4px; background-color: var(--brand-red);
            position: absolute; bottom: 0; left: 0; z-index: 20;
        }
        .active-progress { animation: progress 7s linear infinite; }
    </style>
</head>
<body id="hero-root" class="bg-gray-100 overflow-x-hidden">

    <section class="relative w-full h-[35vh] md:h-[60vh] lg:h-[80vh] overflow-hidden bg-[#111111] group">
        
        <?php foreach ($slides as $index => $slide): ?>
            <div class="hero-slide <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>">
                
                <div class="absolute inset-0 overflow-hidden bg-white">
                    <img src="<?php echo htmlspecialchars($slide['image_path']); ?>" 
                         alt="Hero Banner" 
                         class="w-full h-full object-contain md:object-cover md:object-center">
                    
                    <div class="hidden md:block absolute inset-0 bg-gradient-to-r from-black/90 via-black/40 to-transparent"></div>
                </div>

                <div class="hidden md:flex absolute inset-0 items-center">
                    <div class="container mx-auto px-6 lg:px-12 relative z-20">
                        <div class="max-w-3xl">
                            <div class="w-12 md:w-16 h-1 bg-[#D71920] mb-4 md:mb-6" data-aos="fade-right"></div>
                            <h1 class="text-2xl md:text-5xl lg:text-6xl font-extrabold text-white leading-tight mb-4 text-glow" data-aos="fade-up">
                                <?php echo htmlspecialchars($slide['headline']); ?>
                            </h1>
                            <?php if(!empty($slide['description'])): ?>
                            <p class="text-gray-300 text-sm md:text-lg mb-6 md:mb-8 max-w-xl leading-relaxed border-l-4 border-[#1e90b8] pl-4" data-aos="fade-up">
                                <?php echo htmlspecialchars($slide['description']); ?>
                            </p>
                            <?php endif; ?>
                            <div class="flex gap-4" data-aos="fade-up">
                                <a href="products" class="bg-[#D71920] text-white px-8 py-3 rounded text-base font-semibold shadow-lg flex items-center gap-2">
                                    Explore Products <i class="fa-solid fa-arrow-right"></i>
                                </a>
                                <a href="contact" class="border-2 border-white text-white px-8 py-3 rounded text-base font-semibold backdrop-blur-sm flex items-center gap-2">
                                    <i class="fa-solid fa-phone"></i> Contact Us
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <div id="p-bar" class="progress-bar active-progress hidden md:block"></div>

        <button id="prevSlide" class="nav-btn absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 border border-white/30 rounded-full flex items-center justify-center text-white backdrop-blur-md z-30 hidden md:flex">
            <i class="fa-solid fa-chevron-left"></i>
        </button>
        <button id="nextSlide" class="nav-btn absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 border border-white/30 rounded-full flex items-center justify-center text-white backdrop-blur-md z-30 hidden md:flex">
            <i class="fa-solid fa-chevron-right"></i>
        </button>

    </section>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            AOS.init({ duration: 800, once: false });

            const slides = document.querySelectorAll('.hero-slide');
            const pBar = document.getElementById('p-bar');
            let currentSlide = 0;
            const intervalTime = 7000;
            let slideInterval;

            const resetAOS = () => {
                const activeSlide = document.querySelector('.hero-slide.active');
                if(activeSlide && window.innerWidth >= 768) {
                    activeSlide.querySelectorAll('[data-aos]').forEach(el => {
                        el.classList.remove('aos-animate');
                        setTimeout(() => el.classList.add('aos-animate'), 100);
                    });
                }
                if(pBar) {
                    pBar.classList.remove('active-progress');
                    void pBar.offsetWidth; 
                    pBar.classList.add('active-progress');
                }
            };

            const changeSlide = (direction) => {
                slides[currentSlide].classList.remove('active');
                if (direction === 'next') {
                    currentSlide = (currentSlide + 1) % slides.length;
                } else {
                    currentSlide = (currentSlide - 1 + slides.length) % slides.length;
                }
                slides[currentSlide].classList.add('active');
                resetAOS();
            };

            const startSlideShow = () => {
                slideInterval = setInterval(() => changeSlide('next'), intervalTime);
            };

            document.getElementById('nextSlide')?.addEventListener('click', () => {
                clearInterval(slideInterval); changeSlide('next'); startSlideShow();
            });

            document.getElementById('prevSlide')?.addEventListener('click', () => {
                clearInterval(slideInterval); changeSlide('prev'); startSlideShow();
            });

            if(slides.length > 1) startSlideShow();
            setTimeout(resetAOS, 100);
        });
    </script>
</body>
</html>