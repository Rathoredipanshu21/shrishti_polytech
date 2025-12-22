<?php
include 'config/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Blog & News</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; overflow-x: hidden; }
        
        /* Modal Transitions */
        .modal-active { opacity: 1; pointer-events: auto; }
        .modal-inactive { opacity: 0; pointer-events: none; }
        
        /* Custom Scrollbar for Modal Content */
        .modal-scroll::-webkit-scrollbar { width: 8px; }
        .modal-scroll::-webkit-scrollbar-thumb { background: #1e90b8; border-radius: 4px; }
        .modal-scroll::-webkit-scrollbar-track { background: #f1f1f1; }
    </style>
</head>
<body class="bg-gray-50">
    <?php include 'navbar.php'; ?>

    <div class="relative bg-[#111] text-white py-20 overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-[#D71920] rounded-full filter blur-[100px] opacity-20 translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-[#1e90b8] rounded-full filter blur-[100px] opacity-20 -translate-x-1/2 translate-y-1/2"></div>
        
        <div class="max-w-7xl mx-auto px-6 relative z-10 text-center" data-aos="fade-up">
            <h1 class="text-4xl md:text-6xl font-bold mb-4">Our <span class="text-[#1e90b8]">Latest</span> Insights</h1>
            <p class="text-gray-400 text-lg max-w-2xl mx-auto">Discover trends, news, and stories carefully curated for you. Stay updated with our world.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-20">
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            <?php 
            $sql = "SELECT * FROM blogs ORDER BY created_at DESC";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0):
                while($row = $result->fetch_assoc()):
                    // --- 1. Limit content to 20 words for the preview card ---
                    $fullContent = $row['content']; // Keep full content for modal
                    $cleanText = strip_tags($fullContent); // Remove HTML tags for counting
                    $words = explode(" ", $cleanText);
                    $shortContent = implode(" ", array_slice($words, 0, 20));
                    if(count($words) > 20) { $shortContent .= "..."; }
            ?>
                <div id="blog-data-<?php echo $row['id']; ?>" class="hidden">
                    <div class="data-title"><?php echo htmlspecialchars($row['title']); ?></div>
                    <div class="data-date"><?php echo date('M d, Y', strtotime($row['created_at'])); ?></div>
                    <div class="data-image"><?php echo $row['image']; ?></div>
                    <div class="data-content"><?php echo nl2br(htmlspecialchars($fullContent)); ?></div>
                </div>

                <article class="bg-white rounded-2xl shadow-lg overflow-hidden group hover:shadow-2xl transition-all duration-300 border border-gray-100 flex flex-col h-full" data-aos="fade-up" data-aos-delay="100">
                    <div class="relative h-64 overflow-hidden shrink-0">
                        <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['title']; ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        
                        <div class="absolute top-4 left-4 bg-[#D71920] text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg">
                            <i class="fa-regular fa-clock mr-1"></i> <?php echo date('M d, Y', strtotime($row['created_at'])); ?>
                        </div>
                    </div>

                    <div class="p-8 flex flex-col flex-1">
                        <h2 class="text-2xl font-bold text-gray-800 mb-3 group-hover:text-[#1e90b8] transition-colors line-clamp-2">
                            <?php echo $row['title']; ?>
                        </h2>
                        
                        <div class="w-12 h-1 bg-[#D71920] rounded mb-4"></div>
                        
                        <p class="text-gray-500 mb-6 leading-relaxed flex-1">
                            <?php echo $shortContent; ?>
                        </p>

                        <div class="flex items-center justify-between mt-auto pt-6 border-t border-gray-100">
                            <button onclick="openReader(<?php echo $row['id']; ?>)" class="text-[#1e90b8] font-bold text-sm uppercase tracking-wider hover:text-[#111] transition flex items-center gap-2 group/btn">
                                Read Article <i class="fa-solid fa-arrow-right transform group-hover/btn:translate-x-1 transition"></i>
                            </button>
                            <div class="text-gray-300 flex gap-3 text-sm">
                                <i class="fa-solid fa-share-nodes hover:text-[#1e90b8] cursor-pointer transition"></i>
                            </div>
                        </div>
                    </div>
                </article>
            <?php 
                endwhile; 
            else: 
            ?>
                <div class="col-span-full text-center py-20">
                    <div class="inline-block p-6 rounded-full bg-gray-100 mb-6">
                        <i class="fa-solid fa-layer-group text-5xl text-gray-300"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">No Stories Found</h3>
                    <p class="text-gray-500 mt-2">We haven't published any blogs yet. Check back soon!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div id="readModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm transition-all duration-300 modal-inactive">
        <div class="bg-white w-full max-w-4xl max-h-[90vh] rounded-2xl shadow-2xl overflow-hidden flex flex-col relative transform scale-95 transition-transform duration-300" id="modalContainer">
            
            <button onclick="closeReader()" class="absolute top-4 right-4 z-20 bg-black/50 hover:bg-[#D71920] text-white w-10 h-10 rounded-full flex items-center justify-center transition backdrop-blur-md">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>

            <div class="overflow-y-auto modal-scroll flex-1">
                <div class="relative w-full h-64 md:h-80">
                    <img id="modalImg" src="" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent"></div>
                    
                    <div class="absolute bottom-6 left-6 md:bottom-10 md:left-10 right-6 text-white">
                        <span id="modalDate" class="bg-[#D71920] text-xs font-bold px-3 py-1 rounded-full mb-3 inline-block shadow-lg"></span>
                        <h2 id="modalTitle" class="text-2xl md:text-4xl font-bold leading-tight drop-shadow-lg"></h2>
                    </div>
                </div>

                <div class="p-8 md:p-12">
                    <div id="modalContent" class="text-gray-700 text-lg leading-loose space-y-6">
                        </div>
                    
                    <div class="mt-10 pt-6 border-t border-gray-200 text-center">
                        <p class="text-gray-400 text-sm italic">Thanks for reading!</p>
                        <button onclick="closeReader()" class="mt-2 text-[#1e90b8] font-semibold hover:underline">Close Article</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();

        const modal = document.getElementById('readModal');
        const modalContainer = document.getElementById('modalContainer');
        
        // Elements to populate
        const mTitle = document.getElementById('modalTitle');
        const mDate = document.getElementById('modalDate');
        const mImg = document.getElementById('modalImg');
        const mContent = document.getElementById('modalContent');

        function openReader(id) {
            // Fetch data from hidden div
            const dataDiv = document.getElementById('blog-data-' + id);
            
            if(dataDiv) {
                mTitle.innerHTML = dataDiv.querySelector('.data-title').innerHTML;
                mDate.innerHTML = dataDiv.querySelector('.data-date').innerHTML;
                mImg.src = dataDiv.querySelector('.data-image').innerHTML;
                mContent.innerHTML = dataDiv.querySelector('.data-content').innerHTML;

                // Show Modal
                modal.classList.remove('modal-inactive');
                modal.classList.add('modal-active');
                modalContainer.classList.remove('scale-95');
                modalContainer.classList.add('scale-100');
                
                // Disable background scrolling
                document.body.style.overflow = 'hidden';
            }
        }

        function closeReader() {
            // Hide Modal
            modal.classList.remove('modal-active');
            modal.classList.add('modal-inactive');
            modalContainer.classList.remove('scale-100');
            modalContainer.classList.add('scale-95');
            
            // Enable background scrolling
            document.body.style.overflow = 'auto';
        }

        // Close on clicking outside the modal box
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeReader();
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeReader();
        });
    </script>
</body>
</html>

