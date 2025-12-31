<?php
// Database Connection (if needed for dynamic content later)
include 'config/db.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/x-icon" href="Assets/logo.png">

    <meta name="description" content="Srishti Polytech is the leading Water Purification Company in Dhanbad and RO Plant Manufacturer in Jharkhand. We specialize in Industrial RO Plant Installation, ETP, STP, Mineral Water Bottling Plants, and Water Supply Plant projects across India.">
    <meta name="keywords" content="Water Purification Company in Dhanbad, RO Plant Manufacturer in Jharkhand, Industrial RO Plant Installation, Water Supply Plant, Commercial RO Plant Supplier India, Effluent Treatment Plant (ETP) manufacturer, Sewage Treatment Plant (STP) installation, Mineral Water Bottling Plant, Hemodialysis RO System, Water Cooler Supplier Dhanbad, Wastewater Treatment Company India, OEM Water Treatment Manufacturer, Industrial Water Solutions, 1000 LPH RO Plant, 5000 LPH RO Plant, Best RO Plant Service Dhanbad, Water Engineering Research, SS RO Plants, Turnkey Water Projects, Pan India Water Solutions, Industrial Water Filter, Reverse Osmosis System">
    
    <title>About Us - Srishti Polytech | Best RO Plant Manufacturer in Dhanbad & Jharkhand</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,700;1,600&display=swap" rel="stylesheet">

    <style>
        /* Scoped Styles */
        #srishti-about-page {
            font-family: 'Poppins', sans-serif;
            --brand-red: #D71920;
            --brand-teal: #1e90b8;
            --brand-navy: #002244; 
            --brand-dark: #111111;
            overflow-x: hidden;
        }

        .font-serif-display {
            font-family: 'Playfair Display', serif;
        }

        /* Hero Animation */
        .hero-collage-img {
            transition: transform 0.5s ease-out;
        }
        .hero-collage-container:hover .hero-collage-img {
            transform: scale(1.02);
        }

        /* Abstract Shapes */
        .shape-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            z-index: -1;
        }

        /* Card Hover Effects */
        .vision-card {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .vision-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: var(--brand-navy); border-radius: 4px; }
    </style>
</head>
<body id="srishti-about-page" class="bg-white text-gray-800">
<?php include 'navbar.php'; ?>
  
    <header class="relative pt-24 pb-16 lg:pt-32 lg:pb-24 overflow-hidden bg-gray-50">
        
        <div class="shape-blob bg-blue-100 w-96 h-96 top-0 left-0 opacity-50"></div>
        <div class="shape-blob bg-teal-100 w-96 h-96 bottom-0 right-0 opacity-50"></div>
        <div class="absolute right-10 top-20 text-[200px] text-gray-200 opacity-20 font-bold leading-none select-none z-0">WHO</div>

        <div class="container mx-auto px-4 lg:px-12 relative z-10">
            <div class="flex flex-col lg:flex-row items-center gap-16 lg:gap-24">
                
                <div class="w-full lg:w-1/2" data-aos="fade-right" data-aos-duration="1000">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="h-[2px] w-10 bg-[#002244]"></span>
                        <span class="text-[#1e90b8] font-bold uppercase tracking-widest text-sm">About Srishti Polytech</span>
                    </div>
                    
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-gray-900 leading-tight mb-6">
                        Leading <span class="text-[#1E90B8]">Water Purification Company</span> in Dhanbad.
                    </h1>
                    
                    <p class="text-gray-600 text-lg leading-relaxed mb-8 border-l-4 border-gray-200 pl-6">
                        Established in 2015, Srishti Polytech is the premier <strong>RO Plant Manufacturer in Jharkhand</strong>. We are an ISO 9001:14001:45001 & CE certified OEM committed to engineering cleaner futures through advanced <strong>Water Supply Plant Installation</strong> and maintenance.
                    </p>

                    <div class="flex flex-wrap gap-4">
                        <a href="#services-scope" class="bg-[#1e90b8] text-white px-8 py-3 rounded-full font-bold shadow-lg hover:bg-[#156f8f] transition-all flex items-center gap-2">
                            Our Solutions <i class="fa-solid fa-arrow-down"></i>
                        </a>
                        <div class="flex items-center gap-3 px-4 py-2 border border-gray-200 rounded-full bg-white shadow-sm">
                            <i class="fa-solid fa-certificate text-[#002244] text-xl"></i>
                            <span class="text-sm font-bold text-gray-700">OEM Certified</span>
                        </div>
                    </div>
                </div>

                <div class="w-full lg:w-1/2 relative hero-collage-container" data-aos="fade-left" data-aos-duration="1200">
                    <div class="relative z-10 w-[85%] ml-auto rounded-2xl overflow-hidden shadow-2xl border-4 border-white">
                        <img src="https://images.unsplash.com/photo-1581093450021-4a7360e9a6b5?q=80&w=2070&auto=format&fit=crop" 
                             alt="Industrial RO Plant Installation in Dhanbad" 
                             class="w-full h-[350px] lg:h-[450px] object-cover hero-collage-img">
                        <div class="absolute inset-0 bg-[#1e90b8] mix-blend-multiply opacity-20"></div>
                    </div>

                    <div class="absolute -bottom-10 -left-4 lg:-left-10 z-20 w-[60%] rounded-xl overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.3)] border-4 border-white" data-aos="fade-up" data-aos-delay="400">
                        <img src="https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?q=80&w=2070&auto=format&fit=crop" 
                             alt="Water Treatment Plant Engineer" 
                             class="w-full h-[200px] lg:h-[280px] object-cover hover:scale-110 transition-transform duration-700">
                    </div>

                    <div class="absolute top-10 -right-10 w-24 h-24 bg-[#D71920] rounded-full blur-xl opacity-40 z-0"></div>
                    <div class="absolute -bottom-10 right-10 text-6xl text-gray-200 z-0">
                        <i class="fa-solid fa-quote-right"></i>
                    </div>
                </div>

            </div>
        </div>
    </header>

    <section id="services-scope" class="py-20 bg-white">
        <div class="container mx-auto px-4 lg:px-12">
            <div class="max-w-4xl mx-auto text-center mb-16" data-aos="fade-up">
                <h2 class="text-3xl font-bold text-gray-800 mb-6">Original Equipment Manufacturer <span class="text-[#D71920]">(OEM)</span></h2>
                <p class="text-gray-600 text-lg leading-relaxed">
                    As a trusted <strong>Water Treatment Plant OEM</strong>, we design, manufacture, and supply a wide range of products including <strong>Industrial RO Plants</strong>, <strong>Effluent Treatment Plants (ETP)</strong>, and <strong>Sewage Treatment Plants (STP)</strong>. From customized <strong>250 LPH RO Plants</strong> to large-scale <strong>5000 LPH RO Plants</strong>, we provide complete <strong>Water Supply Plant Installation</strong> and after-sales service across <strong>Bihar, Jharkhand, West Bengal, and Odisha</strong>.
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 border-t border-b border-gray-100 py-10" data-aos="zoom-in">
                <div class="text-center">
                    <div class="text-4xl font-extrabold text-[#D71920] mb-2">2015</div>
                    <div class="text-sm text-gray-500 uppercase tracking-wide">Established</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-extrabold text-[#111] mb-2">10k+</div>
                    <div class="text-sm text-gray-500 uppercase tracking-wide">Satisfied Clients</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-extrabold text-[#1e90b8] mb-2">4+</div>
                    <div class="text-sm text-gray-500 uppercase tracking-wide">States Covered</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-extrabold text-[#111] mb-2">24/7</div>
                    <div class="text-sm text-gray-500 uppercase tracking-wide">Support</div>
                </div>
            </div>
        </div>
    </section>

    <section id="vision-mission" class="py-24 bg-[#111] text-white relative overflow-hidden">
        
        <div class="absolute inset-0 opacity-5" style="background-image: url('https://www.transparenttextures.com/patterns/cubes.png');"></div>

        <div class="container mx-auto px-4 lg:px-12 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="vision-card bg-[#1a1a1a] p-8 rounded-2xl border-t-4 border-[#D71920]" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-16 h-16 bg-[#D71920]/20 rounded-full flex items-center justify-center text-[#D71920] text-3xl mb-6">
                        <i class="fa-solid fa-bullseye"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Our Mission</h3>
                    <p class="text-gray-400 leading-relaxed">
                        To create a <span class="text-white font-semibold">cleaner and greener future</span> by offering advanced and affordable <strong>Industrial Water Treatment Solutions</strong>. We aim to be the <strong>Best RO Plant Service provider in Dhanbad</strong> and beyond.
                    </p>
                </div>

                <div class="vision-card bg-[#1a1a1a] p-8 rounded-2xl border-t-4 border-[#1e90b8]" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-16 h-16 bg-[#1e90b8]/20 rounded-full flex items-center justify-center text-[#1e90b8] text-3xl mb-6">
                        <i class="fa-solid fa-eye"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Our Vision</h3>
                    <p class="text-gray-400 leading-relaxed">
                        To serve as a leading <strong>Commercial RO Plant Supplier in India</strong>, setting global benchmarks in quality. We strive to provide accessible solutions like <strong>Mineral Water Bottling Plants</strong> to communities everywhere.
                    </p>
                </div>

                <div class="vision-card bg-[#1a1a1a] p-8 rounded-2xl border-t-4 border-green-500" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-16 h-16 bg-green-500/20 rounded-full flex items-center justify-center text-green-500 text-3xl mb-6">
                        <i class="fa-solid fa-hand-holding-heart"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Core Offerings</h3>
                    <ul class="space-y-3 text-gray-400 text-sm">
                        <li class="flex items-center gap-3"><i class="fa-solid fa-check text-green-500"></i> <strong>Hemodialysis RO Systems</strong></li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-check text-green-500"></i> <strong>Turnkey Water Projects</strong></li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-check text-green-500"></i> <strong>Water Cooler Supplier</strong></li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-check text-green-500"></i> <strong>Wastewater Treatment Company</strong></li>
                    </ul>
                </div>

            </div>
        </div>
    </section>

    <section class="py-24 bg-gray-50 relative">
        <div class="container mx-auto px-4 lg:px-12">
            <div class="flex flex-col md:flex-row items-center gap-12 bg-white rounded-3xl shadow-xl p-8 lg:p-12 border border-gray-100">
                
                <div class="w-full md:w-1/3 text-center" data-aos="zoom-in">
                    <div class="relative inline-block">
                        <div class="absolute inset-0 bg-[#D71920] rounded-full blur-md opacity-20 transform translate-x-2 translate-y-2"></div>
                        <img src="Assets/director.jpeg" 
                             alt="Raj Kumar Pandey - Srishti Polytech" 
                             class="relative w-48 h-48 lg:w-64 lg:h-64 rounded-full object-cover border-4 border-white shadow-lg mx-auto grayscale hover:grayscale-0 transition-all duration-500">
                    </div>
                    <div class="mt-6">
                        <h4 class="text-2xl font-bold text-gray-900 font-serif-display">Raj Kumar Pandey</h4>
                        <span class="text-[#1e90b8] font-bold uppercase text-xs tracking-widest">Director</span>
                    </div>
                </div>

                <div class="w-full md:w-2/3" data-aos="fade-left">
                    <i class="fa-solid fa-quote-left text-5xl text-gray-200 mb-6"></i>
                    <blockquote class="text-xl lg:text-2xl text-gray-700 leading-relaxed font-light italic mb-6">
                        "At Srishti Polytech, we are committed to delivering the highest quality water treatment solutions as one of Jharkhand's largest manufacturing companies. With ISO 9001, ISO 14001,  ISO 45001 and CE certified and our strength as an OEM manufacturer, we ensure reliable products, advanced technology, and trusted service. I thank our customers for their continued support and assure them that Srishti Polytech will always stand for quality, integrity, and customer satisfaction."
                    </blockquote>
                    <div class="flex items-center gap-2">
                        <div class="h-1 w-12 bg-[#D71920]"></div>
                        <span class="text-sm font-bold text-gray-400 uppercase">Director's Desk</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 lg:px-12 text-center">
            
            <div class="mb-16" data-aos="fade-up">
                <span class="text-[#1e90b8] font-bold uppercase tracking-widest text-sm">Quality Assurance</span>
                <h2 class="text-3xl font-bold text-gray-900 mt-2">Accredited Excellence & Pan India Reach</h2>
                <p class="mt-4 text-gray-500 max-w-2xl mx-auto">Providing <strong>Pan India Water Solutions</strong> to prestigious clients like <strong>Coal India, SAIL, Tata, Volvo, Ashok Leyland, and Maruti Suzuki</strong>.</p>
            </div>

            <div class="flex flex-wrap justify-center gap-10 md:gap-16 items-center opacity-80">
                <div class="flex flex-col items-center group cursor-pointer" data-aos="zoom-in" data-aos-delay="100">
                    <div class="w-24 h-24 rounded-full border-2 border-gray-200 flex items-center justify-center group-hover:border-[#D71920] transition-colors mb-4">
                        <i class="fa-solid fa-award text-4xl text-gray-400 group-hover:text-[#D71920] transition-colors"></i>
                    </div>
                    <span class="font-bold text-gray-800">ISO 9001</span>
                    <span class="text-xs text-gray-500">Quality Management</span>
                </div>

                <div class="flex flex-col items-center group cursor-pointer" data-aos="zoom-in" data-aos-delay="200">
                    <div class="w-24 h-24 rounded-full border-2 border-gray-200 flex items-center justify-center group-hover:border-green-600 transition-colors mb-4">
                        <i class="fa-solid fa-leaf text-4xl text-gray-400 group-hover:text-green-600 transition-colors"></i>
                    </div>
                    <span class="font-bold text-gray-800">ISO 14001</span>
                    <span class="text-xs text-gray-500">Environmental Mgmt</span>
                </div>

                <div class="flex flex-col items-center group cursor-pointer" data-aos="zoom-in" data-aos-delay="300">
                    <div class="w-24 h-24 rounded-full border-2 border-gray-200 flex items-center justify-center group-hover:border-[#1e90b8] transition-colors mb-4">
                        <i class="fa-solid fa-shield-halved text-4xl text-gray-400 group-hover:text-[#1e90b8] transition-colors"></i>
                    </div>
                    <span class="font-bold text-gray-800">ISO 45001</span>
                    <span class="text-xs text-gray-500">Occupational Health</span>
                </div>

                <div class="flex flex-col items-center group cursor-pointer" data-aos="zoom-in" data-aos-delay="400">
                    <div class="w-24 h-24 rounded-full border-2 border-gray-200 flex items-center justify-center group-hover:border-purple-600 transition-colors mb-4">
                        <i class="fa-solid fa-certificate text-4xl text-gray-400 group-hover:text-purple-600 transition-colors"></i>
                    </div>
                    <span class="font-bold text-gray-800">CE Certified</span>
                    <span class="text-xs text-gray-500">European Conformity</span>
                </div>
            </div>

        </div>
    </section>

    <section class="py-16 bg-gradient-to-r from-[#1e90b8] to-[#0f4c61] text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-20"></div>
        
        <div class="container mx-auto px-4 text-center relative z-10" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">Partner with the Leaders in Water Technology</h2>
            <p class="text-white/80 max-w-2xl mx-auto mb-10 text-lg">
                Get the best <strong>Industrial RO Plant Installation in Dhanbad</strong> and <strong>Water Supply Plant</strong> solutions. Join over 10,000 satisfied clients who trust Srishti Polytech.
            </p>
            <div class="flex justify-center gap-4">
                <a href="contact" class="bg-white text-[#1e90b8] px-8 py-4 rounded-lg font-bold shadow-lg hover:bg-gray-100 transition-transform transform hover:-translate-y-1">
                    Contact Us
                </a>
                <a href="services" class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg font-bold hover:bg-white hover:text-[#1e90b8] transition-colors">
                    View Services
                </a>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });
    </script>
</body>
</html>