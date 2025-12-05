<?php
include 'config/db.php';

// Handle Enquiry Form Submission
$msg = "";
$msg_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_enquiry'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $subject = $conn->real_escape_string($_POST['product_name']); // Storing product name
    $message = $conn->real_escape_string($_POST['message']);

    $insertSql = "INSERT INTO enquiries (name, email, phone, subject, message) VALUES ('$name', '$email', '$phone', '$subject', '$message')";

    if ($conn->query($insertSql) === TRUE) {
        $msg = "Enquiry submitted successfully! We will contact you soon.";
        $msg_type = "success";
    } else {
        $msg = "Error: " . $conn->error;
        $msg_type = "error";
    }
}

// Fetch Product Details
if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$id = $conn->real_escape_string($_GET['id']);
$sql = "SELECT * FROM products WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "Product not found.";
    exit();
}

$product = $result->fetch_assoc();
$images = json_decode($product['images'], true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Details</title>
    <link rel="icon" type="image/x-icon" href="Assets/logo.png">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .thumb-active { border-color: #D71920; opacity: 1; }
        .description-content ul { list-style-type: disc; padding-left: 20px; margin-bottom: 10px; }
        .description-content p { margin-bottom: 10px; }
        
        /* Modal Styling */
        .modal {
            transition: opacity 0.3s ease, visibility 0.3s ease;
            opacity: 0;
            visibility: hidden;
        }
        .modal.active {
            opacity: 1;
            visibility: visible;
        }
        .modal-content {
            transform: scale(0.9);
            transition: transform 0.3s ease;
        }
        .modal.active .modal-content {
            transform: scale(1);
        }
    </style>
</head>
<body class="bg-white overflow-x-hidden">

    <?php include 'navbar.php'; ?>

    <section class="container mx-auto px-4 py-12 lg:py-20">
        
        <!-- Notification Message -->
        <?php if ($msg): ?>
            <div class="mb-8 p-4 rounded-lg text-white text-center font-medium <?php echo $msg_type == 'success' ? 'bg-green-600' : 'bg-red-600'; ?>" data-aos="fade-down">
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>

        <div class="flex flex-col lg:flex-row gap-12 lg:gap-16">
            
            <!-- LEFT: Image Gallery -->
            <div class="w-full lg:w-1/2" data-aos="fade-right">
                <!-- Main Image -->
                <div class="w-full h-[400px] md:h-[500px] bg-gray-100 rounded-2xl overflow-hidden shadow-lg mb-4 border border-gray-200 relative group">
                    <img id="mainImage" src="<?php echo !empty($images) ? htmlspecialchars($images[0]) : 'https://via.placeholder.com/600'; ?>" 
                         class="w-full h-full object-contain mix-blend-multiply p-4 transition-transform duration-500 group-hover:scale-105">
                    
                    <div class="absolute top-4 left-4 bg-[#1e90b8] text-white text-xs font-bold px-3 py-1 rounded shadow">
                        PREMIUM QUALITY
                    </div>
                </div>

                <!-- Thumbnails -->
                <?php if (count($images) > 1): ?>
                <div class="flex gap-4 overflow-x-auto pb-2">
                    <?php foreach($images as $index => $img): ?>
                        <div onclick="changeImage('<?php echo htmlspecialchars($img); ?>', this)" 
                             class="w-24 h-24 flex-shrink-0 border-2 border-transparent rounded-lg overflow-hidden cursor-pointer opacity-70 hover:opacity-100 transition-all <?php echo $index === 0 ? 'thumb-active' : ''; ?>">
                            <img src="<?php echo htmlspecialchars($img); ?>" class="w-full h-full object-cover">
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- RIGHT: Details -->
            <div class="w-full lg:w-1/2" data-aos="fade-left">
                <div class="mb-6">
                    <span class="text-[#D71920] font-bold tracking-wider uppercase text-sm mb-2 block">Product Details</span>
                    <h1 class="text-3xl md:text-5xl font-extrabold text-gray-900 leading-tight mb-6">
                        <?php echo htmlspecialchars($product['name']); ?>
                    </h1>
                    <div class="w-20 h-1.5 bg-gradient-to-r from-[#1e90b8] to-[#D71920] rounded-full"></div>
                </div>

                <!-- Description Box -->
                <div class="bg-gray-50 rounded-xl p-8 border border-gray-100 shadow-sm mb-8">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-[#1e90b8]"></i> Specifications & Features
                    </h3>
                    <div class="text-gray-600 leading-relaxed description-content">
                        <?php echo nl2br($product['description']); ?> 
                    </div>
                </div>

                <!-- CTA Actions -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <!-- Button Triggers Modal -->
                    <button onclick="openEnquiryModal()" 
                       class="flex-1 bg-[#D71920] hover:bg-[#b01319] text-white text-center py-4 rounded-xl font-bold shadow-lg shadow-red-200 transition-transform transform hover:-translate-y-1 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-envelope"></i> Get Enquiry
                    </button>
                    
                    <a href="https://wa.me/917004471859?text=I'm interested in <?php echo urlencode($product['name']); ?>" 
                       target="_blank"
                       class="flex-1 bg-[#25D366] hover:bg-[#20bd5a] text-white text-center py-4 rounded-xl font-bold shadow-lg shadow-green-200 transition-transform transform hover:-translate-y-1 flex items-center justify-center gap-2">
                        <i class="fa-brands fa-whatsapp text-xl"></i> Chat on WhatsApp
                    </a>
                </div>

                <!-- Features Strip -->
                <div class="grid grid-cols-3 gap-4 mt-10 border-t border-gray-100 pt-8">
                    <div class="text-center">
                        <i class="fa-solid fa-shield-halved text-2xl text-gray-400 mb-2"></i>
                        <div class="text-xs font-bold text-gray-600 uppercase">Durable</div>
                    </div>
                    <div class="text-center border-l border-gray-100">
                        <i class="fa-solid fa-award text-2xl text-gray-400 mb-2"></i>
                        <div class="text-xs font-bold text-gray-600 uppercase">Certified</div>
                    </div>
                    <div class="text-center border-l border-gray-100">
                        <i class="fa-solid fa-headset text-2xl text-gray-400 mb-2"></i>
                        <div class="text-xs font-bold text-gray-600 uppercase">Support</div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ENQUIRY MODAL -->
    <div id="enquiryModal" class="modal fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4">
        <div class="modal-content bg-white w-full max-w-lg rounded-2xl shadow-2xl relative overflow-hidden">
            
            <!-- Modal Header -->
            <div class="bg-[#111] px-6 py-4 flex justify-between items-center border-b-4 border-[#D71920]">
                <h3 class="text-white font-bold text-lg flex items-center gap-2">
                    <i class="fa-solid fa-paper-plane text-[#1e90b8]"></i> Enquiry Form
                </h3>
                <button onclick="closeEnquiryModal()" class="text-gray-400 hover:text-white transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <form method="POST" action="" class="p-6 space-y-4">
                <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>">
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Product Name</label>
                    <input type="text" value="<?php echo htmlspecialchars($product['name']); ?>" disabled class="w-full bg-gray-100 border border-gray-300 rounded px-3 py-2 text-gray-500 cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Your Name</label>
                    <input type="text" name="name" required class="w-full border border-gray-300 rounded px-3 py-2 focus:border-[#1e90b8] focus:ring-1 focus:ring-[#1e90b8] outline-none transition">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Phone</label>
                        <input type="tel" name="phone" required class="w-full border border-gray-300 rounded px-3 py-2 focus:border-[#1e90b8] focus:ring-1 focus:ring-[#1e90b8] outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" required class="w-full border border-gray-300 rounded px-3 py-2 focus:border-[#1e90b8] focus:ring-1 focus:ring-[#1e90b8] outline-none transition">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Message</label>
                    <textarea name="message" rows="3" required class="w-full border border-gray-300 rounded px-3 py-2 focus:border-[#1e90b8] focus:ring-1 focus:ring-[#1e90b8] outline-none transition" placeholder="I am interested in this product..."></textarea>
                </div>

                <button type="submit" name="submit_enquiry" class="w-full bg-[#1e90b8] hover:bg-[#156f8f] text-white font-bold py-3 rounded-lg shadow-md transition-transform transform hover:-translate-y-1">
                    Submit Enquiry
                </button>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true });

        function changeImage(src, element) {
            document.getElementById('mainImage').src = src;
            const thumbs = document.querySelectorAll('.thumb-active');
            thumbs.forEach(t => t.classList.remove('thumb-active'));
            element.classList.add('thumb-active');
        }

        // Modal Logic
        const modal = document.getElementById('enquiryModal');

        function openEnquiryModal() {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden'; // Prevent scroll
        }

        function closeEnquiryModal() {
            modal.classList.remove('active');
            document.body.style.overflow = ''; // Restore scroll
        }

        // Close on outside click
        window.onclick = function(e) {
            if(e.target == modal) {
                closeEnquiryModal();
            }
        }
    </script>
</body>
</html>