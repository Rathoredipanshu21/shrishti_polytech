<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Profile - Srishti Polytech</title>
    <link rel="icon" type="image/x-icon" href="Assets/logo.png">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet">

    <style>
        #srishti-profile-root {
            font-family: 'Poppins', sans-serif;
            --brand-red: #D71920;
            --brand-teal: #1e90b8;
            --brand-dark: #111111;
        }

        .font-serif-display {
            font-family: 'Playfair Display', serif;
        }

        /* Smooth Scroll */
        html { scroll-behavior: smooth; }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 10px; }
        ::-webkit-scrollbar-track { background: #f8f9fa; }
        ::-webkit-scrollbar-thumb { background: linear-gradient(to bottom, var(--brand-teal), var(--brand-red)); border-radius: 5px; }

        /* Animated Backgrounds */
        .bg-grid-pattern {
            background-image: linear-gradient(to right, rgba(0,0,0,0.05) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(0,0,0,0.05) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        /* Quote Icon Animation */
        .quote-icon { animation: float 3s ease-in-out infinite; }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        /* Map Pulse */
        .map-dot {
            width: 15px; height: 15px; background: var(--brand-red); border-radius: 50%;
            position: relative;
        }
        .map-dot::after {
            content: ''; position: absolute; inset: -10px; border-radius: 50%;
            border: 2px solid var(--brand-red); opacity: 0; animation: ripple 2s infinite;
        }
        @keyframes ripple {
            0% { transform: scale(0.5); opacity: 1; }
            100% { transform: scale(2); opacity: 0; }
        }
    </style>
</head>
<body id="srishti-profile-root" class="bg-white overflow-x-hidden">

    <header class="relative w-full h-[80vh] flex items-center bg-[#111] overflow-hidden">
        
        <div class="absolute inset-0 z-0">
             <div class="absolute inset-0 bg-gradient-to-r from-black via-black/80 to-transparent z-10"></div>
             <img src="https://images.unsplash.com/photo-1613929906260-c9377d135547?w=1600&auto=format&fit=crop&q=80" 
                  alt="Industrial RO Plant" 
                  class="w-full h-full object-cover opacity-60 transform scale-105"
                  data-aos="scale-down" data-aos-duration="20000">
        </div>

        <div class="container mx-auto px-6 relative z-20">
            <div class="max-w-3xl" data-aos="fade-up" data-aos-duration="1000">
                <div class="flex items-center gap-3 mb-4">
                    <span class="h-[2px] w-12 bg-[#D71920]"></span>
                    <span class="text-[#1e90b8] font-bold uppercase tracking-[0.2em] text-sm">Since 2015</span>
                </div>
                
                <h1 class="text-5xl md:text-7xl font-bold text-white leading-tight mb-6">
                    Hands That Cure, <br>
                    <span class="text-[#00e1ff]">Hearts That Care.</span>
                </h1>

                <p class="text-gray-300 text-lg md:text-xl leading-relaxed mb-8 border-l-4 border-[#1e90b8] pl-6">
                    More than just a manufacturer, Srishti Polytech is a legacy of trust. 
                    Serving <span class="text-white font-bold">10,000+ clients</span> across India with 
                    unwavering commitment to quality and service.
                </p>

                <div class="flex flex-wrap gap-4">
                    <div class="flex items-center gap-2 bg-white/10 backdrop-blur-sm px-6 py-3 rounded-lg border border-white/20">
                        <i class="fa-solid fa-trophy text-[#D71920] text-xl"></i>
                        <div class="text-white">
                            <div class="font-bold text-lg leading-none">ISO</div>
                            <div class="text-[10px] uppercase opacity-70">Certified</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 bg-white/10 backdrop-blur-sm px-6 py-3 rounded-lg border border-white/20">
                        <i class="fa-solid fa-users text-[#1e90b8] text-xl"></i>
                        <div class="text-white">
                            <div class="font-bold text-lg leading-none">10k+</div>
                            <div class="text-[10px] uppercase opacity-70">Happy Clients</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="py-24 bg-white relative">
        <div class="container mx-auto px-6 lg:px-16">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                
                <div class="w-full lg:w-5/12 relative" data-aos="fade-right">
                    <div class="absolute -top-6 -left-6 w-full h-full border-[8px] border-[#f3f4f6] rounded-xl z-0"></div>
                    <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=1974&auto=format&fit=crop" 
                         alt="Director Srishti Polytech" 
                         class="relative z-10 rounded-lg shadow-2xl w-full h-[500px] object-cover grayscale hover:grayscale-0 transition-all duration-700">
                    
                    <div class="absolute bottom-8 right-[-20px] bg-[#D71920] text-white p-6 rounded-lg shadow-xl z-20 max-w-[200px]">
                        <p class="font-serif-display text-xl italic">"Your trust is our biggest strength."</p>
                    </div>
                </div>

                <div class="w-full lg:w-7/12" data-aos="fade-left">
                    <i class="fa-solid fa-quote-left text-6xl text-[#1e90b8]/20 quote-icon mb-4"></i>
                    
                    <h2 class="text-4xl font-bold text-gray-900 mb-6">Director's Note</h2>
                    
                    <div class="space-y-6 text-gray-600 text-lg leading-relaxed">
                        <p>
                            "At Srishti Polytech, we are committed to delivering the highest quality water treatment solutions as one of Jharkhand's largest manufacturing companies."
                        </p>
                        <p>
                            "With <strong class="text-[#1e90b8]">ISO 9001, ISO 14001, ISO 45001 and CE certified</strong> certifications and our strength as an OEM manufacturer, we ensure reliable products, advanced technology, and trusted service."
                        </p>
                        <p>
                            "I thank our customers for their continued support and assure them that Srishti Polytech will always stand for quality, integrity, and customer satisfaction."
                        </p>
                    </div>

                    <div class="mt-10 pt-8 border-t border-gray-200 flex items-center justify-between">
                        <div>
                            <h4 class="text-2xl font-bold text-gray-900 font-serif-display">Raj Kumar Pandey</h4>
                            <span class="text-[#D71920] font-semibold tracking-wide text-sm uppercase">Director</span>
                        </div>
                        </div>
                </div>

            </div>
        </div>
    </section>

    <section class="py-24 bg-gray-50 bg-grid-pattern relative">
        <div class="container mx-auto px-6 lg:px-12">
            
            <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
                <span class="bg-blue-100 text-[#1e90b8] px-4 py-1 rounded-full text-xs font-bold uppercase tracking-wider">Our Advantages</span>
                <h2 class="text-4xl font-bold text-gray-900 mt-4">Why Choose <span class="text-[#D71920]">Srishti Polytech?</span></h2>
                <p class="text-gray-500 mt-4">We combine technical expertise with customer-centric service to deliver unmatched value.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 group" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-14 h-14 bg-red-50 rounded-xl flex items-center justify-center text-[#D71920] text-2xl mb-6 group-hover:bg-[#D71920] group-hover:text-white transition-colors">
                        <i class="fa-solid fa-user-gear"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Skilled Technicians</h4>
                    <p class="text-gray-500 text-sm leading-relaxed">Our polite, professional, and well-trained staff ensures precision in every installation and repair.</p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 group" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center text-[#1e90b8] text-2xl mb-6 group-hover:bg-[#1e90b8] group-hover:text-white transition-colors">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">24/7 Support</h4>
                    <p class="text-gray-500 text-sm leading-relaxed">Round-the-clock service support because we understand that clean water can't wait.</p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 group" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-14 h-14 bg-green-50 rounded-xl flex items-center justify-center text-green-600 text-2xl mb-6 group-hover:bg-green-600 group-hover:text-white transition-colors">
                        <i class="fa-solid fa-indian-rupee-sign"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Affordable Pricing</h4>
                    <p class="text-gray-500 text-sm leading-relaxed">High-quality and durable products at the best value pricing in the market.</p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 group" data-aos="fade-up" data-aos-delay="400">
                    <div class="w-14 h-14 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600 text-2xl mb-6 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                        <i class="fa-solid fa-truck-fast"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Fast Doorstep Service</h4>
                    <p class="text-gray-500 text-sm leading-relaxed">Quick delivery and on-time project completion right at your doorstep.</p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 group" data-aos="fade-up" data-aos-delay="500">
                    <div class="w-14 h-14 bg-orange-50 rounded-xl flex items-center justify-center text-orange-600 text-2xl mb-6 group-hover:bg-orange-600 group-hover:text-white transition-colors">
                        <i class="fa-solid fa-screwdriver-wrench"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Free Installation</h4>
                    <p class="text-gray-500 text-sm leading-relaxed">We offer complimentary installation on all new products to get you started worry-free.</p>
                </div>

                <div class="bg-[#111] p-8 rounded-2xl shadow-lg border-2 border-[#D71920] relative overflow-hidden group" data-aos="fade-up" data-aos-delay="600">
                    <div class="absolute top-0 right-0 p-4 opacity-10 text-9xl text-white"><i class="fa-solid fa-users"></i></div>
                    <div class="relative z-10">
                        <div class="text-[#D71920] text-4xl font-extrabold mb-2">10K+</div>
                        <h4 class="text-xl font-bold text-white mb-3">Satisfied Clients</h4>
                        <p class="text-gray-400 text-sm">Join our growing family of happy customers across India.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="py-24 bg-[#111] text-white relative overflow-hidden">
        <div class="absolute inset-0 flex items-center justify-center opacity-10 pointer-events-none">
             <i class="fa-solid fa-map-location-dot text-[400px]"></i>
        </div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="flex flex-col lg:flex-row gap-16">
                
                <div class="lg:w-1/2" data-aos="fade-right">
                    <span class="text-[#D71920] font-bold tracking-widest uppercase mb-2 block">Our Footprint</span>
                    <h2 class="text-4xl font-bold mb-6">Serving Eastern India <br> & Beyond</h2>
                    <p class="text-gray-400 mb-8 text-lg">
                        Headquartered in the coal capital of India, Dhanbad, we have expanded our reach to major neighboring states, delivering excellence in every drop.
                    </p>
                    
                    <ul class="space-y-6">
                        <li class="flex items-center gap-4">
                            <div class="map-dot"></div>
                            <span class="text-xl font-semibold">Jharkhand (Headquarters)</span>
                        </li>
                        <li class="flex items-center gap-4">
                            <div class="w-3 h-3 bg-gray-600 rounded-full"></div>
                            <span class="text-lg text-gray-300">Bihar</span>
                        </li>
                        <li class="flex items-center gap-4">
                            <div class="w-3 h-3 bg-gray-600 rounded-full"></div>
                            <span class="text-lg text-gray-300">West Bengal</span>
                        </li>
                        <li class="flex items-center gap-4">
                            <div class="w-3 h-3 bg-gray-600 rounded-full"></div>
                            <span class="text-lg text-gray-300">Odisha</span>
                        </li>
                    </ul>
                </div>

                <div class="lg:w-1/2" data-aos="fade-left">
                    <div class="bg-white/5 backdrop-blur-md border border-white/10 p-8 rounded-2xl">
                        <h3 class="text-2xl font-bold text-white mb-6 border-b border-white/10 pb-4">Contact Hubs</h3>
                        
                        <div class="space-y-6">
                            <div class="flex gap-4">
                                <i class="fa-solid fa-building-columns text-[#1e90b8] mt-1"></i>
                                <div>
                                    <h5 class="font-bold text-white">Head Office</h5>
                                    <p class="text-sm text-gray-400">Pandey Niwas, Gopal Nagar, Manaitand, Dhanbad, Jharkhand - 826001</p>
                                </div>
                            </div>
                            
                            <div class="flex gap-4">
                                <i class="fa-solid fa-headset text-[#1e90b8] mt-1"></i>
                                <div>
                                    <h5 class="font-bold text-white">Support Center</h5>
                                    <p class="text-sm text-gray-400">Office No. 07, 4th Floor, Center Point Mall, Bank More, Dhanbad</p>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <i class="fa-solid fa-warehouse text-[#1e90b8] mt-1"></i>
                                <div>
                                    <h5 class="font-bold text-white">Warehouse</h5>
                                    <p class="text-sm text-gray-400">Hirak By-Pass Road, Near Holly Angel Public School, Sugiyadih, Dhanbad</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>
<?php include 'clients.php'; ?>
    <section class="py-12 bg-[#1e90b8]">
        <div class="container mx-auto px-6 text-center">
            <p class="text-white/80 uppercase tracking-widest font-semibold mb-6 text-sm">Our Certifications</p>
            <div class="flex flex-wrap justify-center gap-8 md:gap-16 items-center" data-aos="zoom-in">
                
                <div class="flex flex-col items-center text-white group">
                    <i class="fa-solid fa-certificate text-4xl mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="font-bold text-2xl">ISO 9001:2025</span>
                    <span class="text-xs opacity-75">Quality Management</span>
                </div>
                
                <div class="w-px h-12 bg-white/30 hidden md:block"></div>

                <div class="flex flex-col items-center text-white group">
                    <i class="fa-solid fa-leaf text-4xl mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="font-bold text-2xl">ISO 14001:2025</span>
                    <span class="text-xs opacity-75">Environmental Mgmt</span>
                </div>

                <div class="w-px h-12 bg-white/30 hidden md:block"></div>

                <div class="flex flex-col items-center text-white group">
                    <i class="fa-solid fa-circle-check text-4xl mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="font-bold text-2xl">CE Certified</span>
                    <span class="text-xs opacity-75">European Standards</span>
                </div>

            </div>
        </div>
    </section>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            offset: 120,
            once: true
        });
    </script>
</body>
</html>