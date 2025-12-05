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
        /* Scoped styles */
        #hero-root {
            font-family: 'Poppins', sans-serif;
            --brand-red: #D71920;
            --brand-teal: #1e90b8;
            --brand-dark: #111111;
        }

        /* Ken Burns Animation */
        @keyframes kenburns {
            0% { transform: scale(1); }
            100% { transform: scale(1.1); }
        }
        .animate-kenburns {
            animation: kenburns 20s infinite alternate;
        }

        /* Slide Transitions */
        .hero-slide {
            opacity: 0;
            transition: opacity 1000ms ease-in-out;
            z-index: 0;
        }
        .hero-slide.active {
            opacity: 1;
            z-index: 10;
        }

        /* Custom Text Shadow for better readability */
        .text-glow {
            text-shadow: 2px 2px 4px rgba(0,0,0,0.7);
        }
        
        /* Navigation Buttons Hover */
        .nav-btn:hover {
            background-color: var(--brand-red);
            border-color: var(--brand-red);
            transform: scale(1.1);
        }

        /* Progress Bar Animation */
        .progress-bar {
            width: 0%;
            height: 4px;
            background-color: var(--brand-red);
            position: absolute;
            bottom: 0;
            left: 0;
            z-index: 20;
        }
        .hero-slide.active ~ .progress-bar {
            animation: progress 7s linear infinite; /* Match JS interval */
        }
        @keyframes progress {
            0% { width: 0%; }
            100% { width: 100%; }
        }
    </style>
</head>
<body id="hero-root" class="bg-gray-100 overflow-x-hidden">

    <!-- HERO CONTAINER: Fixed 70vh Height -->
    <section class="relative w-full h-[60vh] md:h-[70vh] overflow-hidden bg-[#111111] group">
        
        <?php foreach ($slides as $index => $slide): ?>
            <!-- SLIDE ITEM -->
            <div class="hero-slide absolute inset-0 w-full h-full <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>">
                
                <!-- Background Image with Ken Burns -->
                <div class="absolute inset-0 overflow-hidden">
                    <img src="<?php echo htmlspecialchars($slide['image_path']); ?>" 
                         alt="Hero Banner" 
                         class="w-full h-full object-cover animate-kenburns">
                </div>

                <!-- Dark Gradient Overlay -->
                <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/50 to-transparent"></div>

                <!-- Content Container -->
                <div class="absolute inset-0 flex items-center">
                    <div class="container mx-auto px-6 lg:px-12 relative z-20 pt-10">
                        <div class="max-w-3xl">
                            
                            <!-- Decorator Line -->
                            <div class="w-16 h-1 bg-[#D71920] mb-6 hidden md:block" 
                                 data-aos="fade-right" 
                                 data-aos-delay="300"></div>

                            <!-- Headline -->
                            <h1 class="text-3xl md:text-5xl lg:text-6xl font-extrabold text-white leading-tight mb-4 text-glow"
                                data-aos="fade-up" 
                                data-aos-delay="400">
                                <?php 
                                    // Highlight first word or split for styling if needed
                                    echo htmlspecialchars($slide['headline']); 
                                ?>
                            </h1>

                            <!-- Description -->
                            <p class="text-gray-300 text-sm md:text-lg mb-8 max-w-xl leading-relaxed font-light border-l-4 border-[#1e90b8] pl-4"
                               data-aos="fade-up" 
                               data-aos-delay="600">
                                <?php echo htmlspecialchars($slide['description'] ?? ''); ?>
                            </p>

                            <!-- Buttons -->
                            <div class="flex flex-wrap gap-4" data-aos="fade-up" data-aos-delay="800">
                                <a href="products" class="bg-[#D71920] hover:bg-white hover:text-[#D71920] text-white px-8 py-3 rounded text-sm md:text-base font-semibold transition-all duration-300 shadow-lg flex items-center gap-2 group-btn">
                                    Explore Products
                                    <i class="fa-solid fa-arrow-right group-btn-hover:translate-x-1 transition-transform"></i>
                                </a>
                                <a href="contact" class="border-2 border-white text-white hover:bg-[#1e90b8] hover:border-[#1e90b8] px-8 py-3 rounded text-sm md:text-base font-semibold transition-all duration-300 backdrop-blur-sm flex items-center gap-2">
                                    <i class="fa-solid fa-phone"></i> Contact Us
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Progress Bar (Visual Timer) -->
        <div class="progress-bar"></div>

        <!-- Navigation Arrows (Hidden on mobile, visible on hover) -->
        <button id="prevSlide" class="nav-btn absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 border border-white/30 rounded-full flex items-center justify-center text-white backdrop-blur-md opacity-0 group-hover:opacity-100 transition-all duration-300 z-30 hidden md:flex">
            <i class="fa-solid fa-chevron-left"></i>
        </button>
        <button id="nextSlide" class="nav-btn absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 border border-white/30 rounded-full flex items-center justify-center text-white backdrop-blur-md opacity-0 group-hover:opacity-100 transition-all duration-300 z-30 hidden md:flex">
            <i class="fa-solid fa-chevron-right"></i>
        </button>

        <!-- Social Icons Floating -->
        <div class="absolute bottom-8 right-8 z-30 hidden lg:flex flex-col gap-4">
            <a href="#" class="text-white/60 hover:text-[#D71920] text-xl transition-colors"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="#" class="text-white/60 hover:text-[#1e90b8] text-xl transition-colors"><i class="fa-brands fa-linkedin-in"></i></a>
            <a href="#" class="text-white/60 hover:text-[#25D366] text-xl transition-colors"><i class="fa-brands fa-whatsapp"></i></a>
        </div>

    </section>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Initialize AOS
            AOS.init({ duration: 1000, once: false, mirror: false });

            const slides = document.querySelectorAll('.hero-slide');
            const totalSlides = slides.length;
            let currentSlide = 0;
            const intervalTime = 7000; // 7 Seconds
            let slideInterval;

            const resetAOS = () => {
                // Trick to re-trigger AOS animations on the new active slide
                const activeSlide = document.querySelector('.hero-slide.active');
                if(activeSlide) {
                    const animatedElements = activeSlide.querySelectorAll('[data-aos]');
                    animatedElements.forEach(el => {
                        el.classList.remove('aos-animate');
                        setTimeout(() => el.classList.add('aos-animate'), 100);
                    });
                }
            };

            const changeSlide = (direction) => {
                // Remove active class from current
                slides[currentSlide].classList.remove('active');
                
                // Calculate next
                if (direction === 'next') {
                    currentSlide = (currentSlide + 1) % totalSlides;
                } else {
                    currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
                }

                // Add active class to new
                slides[currentSlide].classList.add('active');
                resetAOS();
            };

            // Auto Play
            const startSlideShow = () => {
                slideInterval = setInterval(() => changeSlide('next'), intervalTime);
            };

            const stopSlideShow = () => {
                clearInterval(slideInterval);
            };

            // Event Listeners
            document.getElementById('nextSlide')?.addEventListener('click', () => {
                stopSlideShow();
                changeSlide('next');
                startSlideShow();
            });

            document.getElementById('prevSlide')?.addEventListener('click', () => {
                stopSlideShow();
                changeSlide('prev');
                startSlideShow();
            });

            // Start
            if(totalSlides > 1) {
                startSlideShow();
            }
            
            // Initial AOS trigger
            setTimeout(resetAOS, 100);
        });
    </script>
</body>
</html>