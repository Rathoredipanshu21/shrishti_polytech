<?php
// Dynamically find the base URL for the Home button
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
// This assumes your project is in a folder. If on a live domain, it goes to root.
$path = dirname($_SERVER['PHP_SELF']); 
$home_url = $protocol . "://" . $host . $path . "/";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom animation for floating effect */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        .floating-img {
            animation: float 6s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-gray-900 text-white h-screen flex flex-col items-center justify-center overflow-hidden relative">

    <div class="absolute top-10 left-10 w-20 h-20 bg-blue-500 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
    <div class="absolute top-10 right-10 w-20 h-20 bg-purple-500 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
    <div class="absolute -bottom-8 left-20 w-20 h-20 bg-pink-500 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>

    <div class="container mx-auto px-4 text-center z-10">
        
        <h1 class="text-9xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-600 mb-4 drop-shadow-2xl">
            404
        </h1>

        <h2 class="text-3xl md:text-4xl font-bold mb-4">Something's missing.</h2>
        <p class="text-gray-400 text-lg mb-8 max-w-md mx-auto">
            The page you are looking for doesn't exist or has been moved. 
            We apologize for the inconvenience.
        </p>

        <div class="flex justify-center gap-4">
            <a href="<?php echo $home_url; ?>" 
               class="px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full font-semibold shadow-lg hover:shadow-blue-500/50 transition transform hover:-translate-y-1">
                Back to Home
            </a>
            <button onclick="history.back()" 
                    class="px-8 py-3 border border-gray-600 rounded-full font-semibold hover:bg-gray-800 transition transform hover:-translate-y-1">
                Go Back
            </button>
        </div>
    </div>

    <div class="absolute bottom-5 text-gray-600 text-sm">
        Error Code: 404 Not Found
    </div>

</body>
</html>