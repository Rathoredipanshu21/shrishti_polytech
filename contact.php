<?php
session_start();
include 'config/db.php';

// Handle Form Submission
$msg = "";
$msg_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $service = $conn->real_escape_string($_POST['service']);
    $message = $conn->real_escape_string($_POST['message']);

    // 1. Insert into Local Database first
    $sql = "INSERT INTO contact_enquiries (name, email, phone, service_interested, message) VALUES ('$name', '$email', '$phone', '$service', '$message')";

    if ($conn->query($sql) === TRUE) {
        $msg = "Thank you! Your enquiry has been submitted successfully.";
        $msg_type = "success";

        // ========================================================
        // START: BIZIVERSE LEAD ENTRY API INTEGRATION
        // ========================================================

        // A. Prepare Name (Split Single Name into First and Last)
        // API Requirement: lastName is compulsory. We split the string by spaces.
        $nameParts = explode(" ", trim($_POST['name']));
        $lastName = array_pop($nameParts); // Take the last word as surname
        $firstName = implode(" ", $nameParts); // Join the rest as first name

        // Fallback: If the user only entered one word, ensure lastName is not empty
        if (empty($lastName)) { $lastName = "User"; }

        // B. Prepare Phone (Ensure exactly 10 digits)
        // API Requirement: Mobile number must be exactly 10 digits
        $cleanPhone = preg_replace('/[^0-9]/', '', $_POST['phone']); // Remove non-numeric chars
        $finalPhone = substr($cleanPhone, -10); // Take the last 10 digits

        // C. Prepare Needs (Combine Service and Message)
        // API Requirement: 'needs' is max 200 chars. We combine Service + Message here.
        $needsText = "Service: " . $_POST['service'] . " - Msg: " . $_POST['message'];
        $needsText = substr($needsText, 0, 200); // Truncate to 200 chars max

        // D. Prepare Inner Data Array (The 'data' field)
        $leadDataArray = array(
            array(
                "companyName" => "", 
                "title"       => "",
                "firstName"   => substr($firstName, 0, 15), // Max 15 chars
                "lastName"    => substr($lastName, 0, 15),  // Max 15 chars (Compulsory)
                "email"       => substr($_POST['email'], 0, 50), // Max 50 chars
                "mobile"      => $finalPhone,               // Exactly 10 digits (Compulsory)
                "designation" => "",
                "city"        => "",
                "state"       => "", 
                "needs"       => $needsText,                // Contains Service + Message
                "source"      => "Website"
            )
        );

        // E. Prepare apiParams Structure
        // API Requirement: moduleID: 25, actionType: "setLead", data: stringified JSON
        $apiParamsArray = array(
            array(
                "moduleID"   => 25,
                "actionType" => "setLead",
                "data"       => json_encode($leadDataArray) // Inner JSON must be stringified
            )
        );

        // F. Prepare Final POST Fields
        $postFields = array(
            "apiKey"    => "0029-FE0696DE-7501-4E8C-ACF1-963A9342230F-5968", // <--- PASTE YOUR KEY HERE
            "apiParams" => json_encode($apiParamsArray)   // Outer JSON must be stringified
        );

        // G. Send Request using cURL
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://biziverse.com/PremiumAPI.asmx/setAPI',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => http_build_query($postFields),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        // ========================================================
        // END: BIZIVERSE INTEGRATION
        // ========================================================

    } else {
        $msg = "Error: " . $sql . "<br>" . $conn->error;
        $msg_type = "error";
    }
}

// Fetch Services for Dropdown
$services_result = $conn->query("SELECT name FROM services ORDER BY name ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Srishti Polytech</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        #srishti-contact-root {
            font-family: 'Poppins', sans-serif;
            --brand-red: #D71920;
            --brand-teal: #1e90b8;
            --brand-dark: #111111;
        }

        /* Hero Animation */
        .hero-bg-animate {
            animation: kenburns 20s infinite alternate;
        }
        @keyframes kenburns {
            0% { transform: scale(1); }
            100% { transform: scale(1.1); }
        }

        /* Form Input Focus */
        .form-input:focus {
            border-color: var(--brand-teal);
            box-shadow: 0 0 0 4px rgba(30, 144, 184, 0.1);
        }

        /* Map Container */
        .map-container iframe {
            width: 100%;
            height: 100%;
            border: 0;
            filter: grayscale(100%);
            transition: filter 0.3s;
        }
        .map-container:hover iframe {
            filter: grayscale(0%);
        }
    </style>
</head>
<body id="srishti-contact-root" class="bg-gray-50 overflow-x-hidden flex flex-col min-h-screen">

    <?php include 'navbar.php'; ?>

    <header class="relative w-full h-[50vh] flex items-center justify-center overflow-hidden bg-[#111]">
        
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-black/70 z-10"></div>
            <img src="https://images.unsplash.com/photo-1423666639041-f142fcb9449f?q=80&w=2070&auto=format&fit=crop" 
                 alt="Contact Us" 
                 class="w-full h-full object-cover hero-bg-animate opacity-60">
        </div>

        <div class="relative z-20 text-center px-4" data-aos="zoom-in">
            <span class="text-[#D71920] font-bold tracking-widest uppercase text-sm bg-white/10 backdrop-blur-md px-4 py-1 rounded-full border border-white/20 mb-4 inline-block">Get In Touch</span>
            <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-4 drop-shadow-xl">
                Let's Start a <span class="text-[#00e1ff]">Conversation</span>
            </h1>
            <p class="text-gray-300 text-lg md:text-xl max-w-2xl mx-auto font-light">
                Whether you have a question about our services, need a quote, or anything else, our team is ready to answer all your questions.
            </p>
        </div>
    </header>

    <section class="container mx-auto px-4 lg:px-12 py-16 lg:py-24 -mt-16 relative z-30">
        <div class="flex flex-col lg:flex-row gap-10">
            
            <div class="w-full lg:w-1/3" data-aos="fade-right">
                <div class="bg-[#111] text-white p-8 lg:p-10 rounded-2xl shadow-2xl h-full flex flex-col justify-between border-t-4 border-[#D71920]">
                    
                    <div>
                        <h3 class="text-2xl font-bold mb-6">Our Offices</h3>
                        
                        <div class="flex items-start gap-4 mb-6 group border-b border-gray-800 pb-4 last:border-0 last:pb-0">
                            <div class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center text-[#D71920] text-lg shrink-0 mt-1 group-hover:bg-[#D71920] group-hover:text-white transition-colors">
                                <i class="fa-solid fa-building"></i>
                            </div>
                            <div>
                                <h5 class="font-bold text-base text-[#00e1ff] mb-1">Head Office</h5>
                                <p class="text-gray-400 text-sm leading-relaxed">Pandey Niwas, Gopal Nagar, Manaitand, Dhanbad, Jharkhand – 826001</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4 mb-6 group border-b border-gray-800 pb-4 last:border-0 last:pb-0">
                            <div class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center text-[#1e90b8] text-lg shrink-0 mt-1 group-hover:bg-[#1e90b8] group-hover:text-white transition-colors">
                                <i class="fa-solid fa-headset"></i>
                            </div>
                            <div>
                                <h5 class="font-bold text-base text-[#00e1ff] mb-1">Support Center</h5>
                                <p class="text-gray-400 text-sm leading-relaxed">Office No. 07, 4th Floor, Center Point Mall, Katras Road, Bank More, Dhanbad, Jharkhand</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4 mb-8 group border-b border-gray-800 pb-4 last:border-0 last:pb-0">
                            <div class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center text-yellow-500 text-lg shrink-0 mt-1 group-hover:bg-yellow-500 group-hover:text-white transition-colors">
                                <i class="fa-solid fa-warehouse"></i>
                            </div>
                            <div>
                                <h5 class="font-bold text-base text-[#00e1ff] mb-1">Store / Warehouse</h5>
                                <p class="text-gray-400 text-sm leading-relaxed">Hirak By-Pass Road, Near Holly Angel Public School, Sugiyadih, Dhanbad, Jharkhand</p>
                            </div>
                        </div>

                        <h3 class="text-xl font-bold mb-4 mt-8">Contact Details</h3>

                        <div class="flex items-start gap-4 mb-4 group">
                            <div class="w-8 h-8 bg-white/10 rounded-full flex items-center justify-center text-[#1e90b8] text-sm group-hover:bg-[#1e90b8] group-hover:text-white transition-colors">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <div>
                                <p class="text-gray-400 text-sm hover:text-white transition"><a href="tel:+917004471859">+91-7004471859</a></p>
                                <p class="text-gray-400 text-sm hover:text-white transition"><a href="tel:+919431313684">+91-9431313684</a></p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4 group">
                            <div class="w-8 h-8 bg-white/10 rounded-full flex items-center justify-center text-green-500 text-sm group-hover:bg-green-500 group-hover:text-white transition-colors">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <div>
                                <p class="text-gray-400 text-sm hover:text-white transition"><a href="mailto:srishtipolytech@gmail.com">srishtipolytech@gmail.com</a></p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-white/10">
                        <h5 class="font-bold text-sm mb-4 text-gray-400">Follow Us</h5>
                        <div class="flex gap-4">
                            <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-[#1877F2] hover:text-white transition-all"><i class="fa-brands fa-facebook-f"></i></a>
                            <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-[#E1306C] hover:text-white transition-all"><i class="fa-brands fa-instagram"></i></a>
                            <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-[#0077B5] hover:text-white transition-all"><i class="fa-brands fa-linkedin-in"></i></a>
                            <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-[#FF0000] hover:text-white transition-all"><i class="fa-brands fa-youtube"></i></a>
                        </div>
                    </div>

                </div>
            </div>

            <div class="w-full lg:w-2/3" data-aos="fade-left">
                <div class="bg-white p-8 lg:p-12 rounded-2xl shadow-xl border border-gray-100 h-full">
                    
                    <div class="mb-8">
                        <h2 class="text-3xl font-bold text-gray-900">Send us a Message</h2>
                        <p class="text-gray-500 mt-2">Fill out the form below and we will get back to you shortly.</p>
                    </div>

                    <?php if ($msg): ?>
                        <div class="mb-6 p-4 rounded-lg text-white font-medium <?php echo $msg_type == 'success' ? 'bg-green-500' : 'bg-red-500'; ?>">
                            <?php echo $msg; ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                                <input type="text" name="name" required class="form-input w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 outline-none transition" placeholder="John Doe">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                                <input type="email" name="email" required class="form-input w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 outline-none transition" placeholder="john@example.com">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
                                <input type="tel" name="phone" required class="form-input w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 outline-none transition" placeholder="+91 98765 43210">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Interested Service (Optional)</label>
                                <div class="relative">
                                    <select name="service" class="form-input w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 outline-none transition appearance-none cursor-pointer text-gray-700">
                                        <option value="">Select a Service</option>
                                        <?php 
                                        if ($services_result->num_rows > 0) {
                                            while($row = $services_result->fetch_assoc()) {
                                                // Check if service was passed in URL (from product/service page)
                                                $selected = (isset($_GET['service']) && $_GET['service'] == $row['name']) ? 'selected' : '';
                                                echo '<option value="' . htmlspecialchars($row['name']) . '" ' . $selected . '>' . htmlspecialchars($row['name']) . '</option>';
                                            }
                                        }
                                        ?>
                                        <option value="Other">Other Inquiry</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                                        <i class="fa-solid fa-chevron-down text-xs"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Your Message</label>
                            <textarea name="message" rows="5" required class="form-input w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 outline-none transition resize-none" placeholder="Tell us about your requirements..."></textarea>
                        </div>

                        <button type="submit" class="w-full bg-[#1e90b8] hover:bg-[#156f8f] text-white font-bold py-4 rounded-lg shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2">
                            <span>Send Message</span>
                            <i class="fa-solid fa-paper-plane"></i>
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </section>

    <section class="w-full h-[400px] map-container" data-aos="fade-up">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3652.123456789!2d86.430000!3d23.790000!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjPCsDQ3JzI0LjAiTiA4NsKwMjUnNDguMCJF!5e0!3m2!1sen!2sin!4v1600000000000!5m2!1sen!2sin" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </section>

    <div class="mt-auto">
        <?php include 'footer.php'; ?>
    </div>

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