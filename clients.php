<?php
include 'config/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Esteemed Clients</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; }
        
        /* Card Styling */
        .client-card {
            background: rgba(255, 255, 255, 0.9);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        /* Hover: Lift Card */
        .client-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 225, 255, 0.15); /* Aqua Glow */
            border-color: #00e1ff;
        }

        /* Logo Styling */
        .client-logo-img {
            transition: all 0.5s ease;
            /* REMOVED: filter: grayscale(100%) opacity(0.7); */
            transform: scale(0.9);
        }

        /* Hover: Scale Logo */
        .client-card:hover .client-logo-img {
            /* REMOVED: filter: grayscale(0%) opacity(1); */
            transform: scale(1.1);
        }

        /* Hover Overlay Animation */
        .client-info {
            opacity: 0;
            transform: translateY(100%);
            transition: all 0.4s ease-out;
        }
        
        .client-card:hover .client-info {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body class="bg-gray-50">

    <section class="relative py-24 bg-[#111] text-white overflow-hidden">
        <div class="absolute inset-0 opacity-10" 
             style="background-image: radial-gradient(#00e1ff 1px, transparent 1px); background-size: 30px 30px;">
        </div>
        
        <div class="container mx-auto px-6 text-center relative z-10" data-aos="zoom-in">
            <span class="inline-block py-1 px-3 rounded-full bg-[#00e1ff]/10 text-[#00e1ff] font-bold tracking-widest uppercase text-xs mb-4 border border-[#00e1ff]/20">
                Trusted Partnerships
            </span>
            <h1 class="text-4xl md:text-6xl font-bold mb-6">
                Our Valued <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#00e1ff] to-cyan-400">Clients</span>
            </h1>
            <p class="text-gray-400 max-w-2xl mx-auto text-lg font-light">
                We are honored to serve industry leaders across the nation. Their trust is the foundation of our excellence.
            </p>
        </div>
        
        <div class="absolute bottom-0 left-0 w-full h-24 bg-gradient-to-t from-gray-50 to-transparent"></div>
    </section>

    <section class="py-20 container mx-auto px-6 -mt-10 relative z-20">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-8">

            <?php
            $sql = "SELECT * FROM clients ORDER BY id DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
            ?>
                <div class="client-card relative group bg-white border border-gray-200 rounded-2xl shadow-lg h-64 flex items-center justify-center overflow-hidden cursor-pointer" data-aos="fade-up">
                    
                    <div class="p-8 w-full h-full flex items-center justify-center z-10 bg-white group-hover:bg-white/90 transition-colors">
                        <img src="<?php echo $row['client_logo']; ?>" alt="<?php echo $row['client_name']; ?>" 
                             class="client-logo-img w-auto h-auto max-h-32 max-w-[80%] object-contain">
                    </div>

                    <div class="client-info absolute inset-0 z-20 bg-gradient-to-t from-[#00e1ff]/95 to-[#1e90b8]/90 backdrop-blur-sm flex flex-col justify-center items-center text-center p-6">
                        
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-[#00e1ff] text-xl shadow-lg mb-3 scale-0 group-hover:scale-100 transition-transform duration-500 delay-100">
                            <i class="fa-solid fa-building"></i>
                        </div>

                        <h3 class="text-white font-bold text-xl mb-2 drop-shadow-sm tracking-wide">
                            <?php echo $row['client_name']; ?>
                        </h3>
                        
                        <?php if(!empty($row['description'])): ?>
                            <div class="w-8 h-1 bg-white/50 rounded mb-3"></div>
                            <p class="text-white text-sm font-medium leading-relaxed opacity-90 line-clamp-3">
                                <?php echo $row['description']; ?>
                            </p>
                        <?php endif; ?>

                    </div>
                </div>
            <?php 
                }
            } else {
                echo "
                <div class='col-span-full text-center py-12'>
                    <div class='text-gray-300 text-6xl mb-4'><i class='fa-regular fa-folder-open'></i></div>
                    <p class='text-gray-500 text-lg'>Our client list is being updated. Check back soon!</p>
                </div>";
            }
            ?>

        </div>
    </section>

    <section class="py-20 bg-white">
        <div class="container mx-auto text-center" data-aos="fade-up">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Ready to Join Our Network?</h2>
            <p class="text-gray-500 mb-8 max-w-xl mx-auto">Partner with Srishti Polytech for world-class water treatment solutions and sustainable engineering.</p>
            
            <a href="contact" class="group relative inline-flex items-center justify-center px-8 py-4 font-bold text-white transition-all duration-200 bg-[#00e1ff] font-lg rounded-full hover:bg-[#00c4e0] hover:shadow-lg hover:-translate-y-1 focus:outline-none ring-offset-2 focus:ring-2 ring-[#00e1ff]">
                <span class="mr-2">Connect With Us</span>
                <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </section>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            offset: 50,
            once: true
        });
    </script>
</body>
</html>