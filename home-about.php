<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Section Component</title>
    <link rel="icon" type="image/x-icon" href="Assets/logo.png">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* Scoped Styles for Srishti Polytech Theme */
        #srishti-about-root {
            font-family: 'Poppins', sans-serif;
            --brand-red: #D71920;
            --brand-teal: #1e90b8;
            --brand-dark: #111111;
        }

        /* 3D Floating Animation for Badge */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        .animate-float {
            animation: float 4s ease-in-out infinite;
        }

        /* Decorative Background Pattern */
        .bg-pattern-dots {
            background-image: radial-gradient(#1e90b8 1px, transparent 1px);
            background-size: 20px 20px;
            opacity: 0.1;
        }

        /* Custom Button Hover Effect */
        .btn-hover-slide {
            background-image: linear-gradient(120deg, transparent 0%, transparent 50%, #111 50%);
            background-size: 230%;
            transition: all 0.4s;
        }
        .btn-hover-slide:hover {
            background-position: 100%;
            color: white;
            transform: translateY(-2px);
        }
    </style>
</head>
<body id="srishti-about-root" class="overflow-x-hidden">

    <section class="relative py-20 lg:py-28 bg-white overflow-hidden">
        
        <!-- Background Elements -->
        <div class="absolute top-0 left-0 w-64 h-64 bg-blue-50 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2 z-0"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-red-50 rounded-full blur-3xl translate-x-1/3 translate-y-1/3 z-0"></div>
        <div class="absolute inset-0 bg-pattern-dots z-0"></div>

        <div class="container mx-auto px-4 lg:px-10 relative z-10">
            <div class="flex flex-col lg:flex-row items-center gap-16 lg:gap-20">

                <!-- LEFT SIDE: 2-Image Collage Album -->
                <div class="w-full lg:w-1/2 relative mb-10 lg:mb-0" data-aos="fade-right" data-aos-duration="1000">
                    
                    <!-- Decorative Frame Behind -->
                    <div class="absolute top-4 left-4 w-[85%] h-full border-2 border-[#1e90b8]/30 rounded-2xl z-0 hidden md:block"></div>

                    <!-- Main Image (Larger) -->
                    <div class="relative w-[85%] rounded-2xl overflow-hidden shadow-2xl z-10 group">
                        <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition-all duration-500"></div>
                        <!-- Placeholder for Industrial Plant Image -->
                        <img src="https://images.unsplash.com/photo-1532601224476-15c79f2f7a51?q=80&w=2070&auto=format&fit=crop" 
                             alt="Industrial RO Plant" 
                             class="w-full h-auto object-cover transform group-hover:scale-105 transition-transform duration-700">
                    </div>

                    <!-- Secondary Image (Overlapping Album Style) -->
                    <div class="absolute -bottom-12 -right-2 md:-right-6 w-[55%] rounded-xl overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.2)] border-[6px] border-white z-20"
                         data-aos="fade-up" data-aos-delay="400">
                         <!-- Placeholder for Technician/Worker Image -->
                        <img src="https://plus.unsplash.com/premium_photo-1664477121402-e6a42b9094e3?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8ZXhwZXJ0JTIwdGVjaG5pY2lhbnxlbnwwfHwwfHx8MA%3D%3D" 
                             alt="Expert Technicians" 
                             class="w-full h-auto object-cover">
                    </div>

                    <!-- Floating Experience Badge -->
                    <div class="absolute top-10 -right-4 md:-right-10 bg-[#D71920] text-white p-6 rounded-lg shadow-lg z-30 animate-float text-center max-w-[140px]">
                        <span class="block text-4xl font-extrabold">10+</span>
                        <span class="text-xs uppercase font-medium tracking-wide">Years of Excellence</span>
                    </div>

                    <!-- Dotted Decor -->
                    <div class="absolute -bottom-16 left-10 text-[#1e90b8]/20 z-0">
                        <i class="fa-solid fa-braille text-8xl"></i>
                    </div>
                </div>

                <!-- RIGHT SIDE: Content Info -->
                <div class="w-full lg:w-1/2" data-aos="fade-left" data-aos-duration="1000">
                    
                    <!-- Section Title -->
                    <div class="flex items-center gap-2 mb-4">
                        <span class="h-0.5 w-10 bg-[#D71920]"></span>
                        <h6 class="text-[#1e90b8] font-bold uppercase tracking-widest text-sm">About Srishti Polytech</h6>
                    </div>

                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-gray-900 leading-tight mb-6">
                        Pioneering <span class="text-[#D71920]">Clean Water</span> Solutions Since 2015
                    </h2>

                    <p class="text-gray-600 text-lg leading-relaxed mb-6">
                        Srishti Polytech is a leading <span class="font-bold text-gray-800">ISO 9001:14001:45001 & CE certified</span> provider of advanced water treatment solutions. Headquartered in Dhanbad, Jharkhand, we are an OEM committed to engineering a cleaner, greener future.
                    </p>

                    <p class="text-gray-500 mb-8 font-light">
                        From industrial Effluent Treatment Plants (ETP) to domestic RO systems, we deliver innovation, reliability, and sustainability in every drop.
                    </p>

                    <!-- Icon Features Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-10">
                        
                        <!-- Feature 1 -->
                        <div class="flex items-center gap-4 p-4 rounded-lg bg-gray-50 border border-gray-100 hover:border-[#1e90b8] hover:shadow-md transition-all group">
                            <div class="w-12 h-12 rounded-full bg-white text-[#D71920] flex items-center justify-center text-xl shadow-sm group-hover:bg-[#D71920] group-hover:text-white transition-colors">
                                <i class="fa-solid fa-certificate"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">ISO Certified</h4>
                                <p class="text-xs text-gray-500">9001:14001:45001 & CE</p>
                            </div>
                        </div>

                        <!-- Feature 2 -->
                        <div class="flex items-center gap-4 p-4 rounded-lg bg-gray-50 border border-gray-100 hover:border-[#1e90b8] hover:shadow-md transition-all group">
                            <div class="w-12 h-12 rounded-full bg-white text-[#D71920] flex items-center justify-center text-xl shadow-sm group-hover:bg-[#D71920] group-hover:text-white transition-colors">
                                <i class="fa-solid fa-industry"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">OEM Manufacturer</h4>
                                <p class="text-xs text-gray-500">Original Equipment Mfg.</p>
                            </div>
                        </div>

                        <!-- Feature 3 -->
                        <div class="flex items-center gap-4 p-4 rounded-lg bg-gray-50 border border-gray-100 hover:border-[#1e90b8] hover:shadow-md transition-all group">
                            <div class="w-12 h-12 rounded-full bg-white text-[#D71920] flex items-center justify-center text-xl shadow-sm group-hover:bg-[#D71920] group-hover:text-white transition-colors">
                                <i class="fa-solid fa-users"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">10,000+ Clients</h4>
                                <p class="text-xs text-gray-500">Trusted Across India</p>
                            </div>
                        </div>

                        <!-- Feature 4 -->
                        <div class="flex items-center gap-4 p-4 rounded-lg bg-gray-50 border border-gray-100 hover:border-[#1e90b8] hover:shadow-md transition-all group">
                            <div class="w-12 h-12 rounded-full bg-white text-[#D71920] flex items-center justify-center text-xl shadow-sm group-hover:bg-[#D71920] group-hover:text-white transition-colors">
                                <i class="fa-solid fa-headset"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">24/7 Support</h4>
                                <p class="text-xs text-gray-500">Dedicated Service Team</p>
                            </div>
                        </div>

                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap items-center gap-6">
                        <a href="about" class="bg-[#D71920] text-white px-8 py-4 rounded font-bold shadow-lg btn-hover-slide flex items-center gap-2">
                            Read More <i class="fa-solid fa-arrow-right-long"></i>
                        </a>
                        
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full border-2 border-[#1e90b8] text-[#1e90b8] flex items-center justify-center text-lg animate-pulse">
                                <i class="fa-solid fa-phone-volume"></i>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs font-semibold text-gray-500 uppercase">Call for Enquiry</span>
                                <a href="tel:+917004471859" class="text-lg font-bold text-gray-800 hover:text-[#D71920] transition-colors">+91-7004471859</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>
</html>