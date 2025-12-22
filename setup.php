<?php
// setup.php
$folder = dirname($_SERVER['SCRIPT_NAME']);
if ($folder == '/' || $folder == '\\') { $folder = ''; }

echo "<h3>Copy the code below into your .htaccess file:</h3>";
echo "<textarea style='width:100%; height:300px; font-family:monospace; padding:10px;'>";
echo "RewriteEngine On\n\n";

echo "# 1. Point 404 errors to the correct location\n";
echo "ErrorDocument 404 " . $folder . "/404.php\n\n";

echo "# 2. Allow access to real files and directories\n";
echo "RewriteCond %{REQUEST_FILENAME} !-d\n";
echo "RewriteCond %{REQUEST_FILENAME} !-f\n\n";

echo "# 3. Hide .php extension (Only if the .php file exists)\n";
echo "RewriteCond %{REQUEST_FILENAME}.php -f\n";
echo "RewriteRule ^(.+)$ $1.php [L,QSA]\n";
echo "</textarea>";
?>