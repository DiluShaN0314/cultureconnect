<?php
// Define the base path of the application
define('BASE_PATH', '/cultureconnect/');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Parse the request URL
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); // Get the path without query parameters
$scriptName = dirname($_SERVER['SCRIPT_NAME']);
$requestPath = str_replace($scriptName, '', $requestUri); // Remove the base directory from the path
$requestPath = trim($requestPath, '/'); // Remove leading and trailing slashes

// Log the parsed path for debugging
error_log("Parsed Request Path: $requestPath");

// Route mapping
$routes = [
    '' => "views/user/dashboard.php",
    'login' => 'views/auth/login.php',
    'register' => 'views/auth/register.php',
    'logout' => 'views/auth/logout.php',
    /*'admin-dashboard' => "views/admin/dashboard.php",
    'user-dashboard' => "views/user/dashboard.php",*/
    'products' => 'views/products/index.php',
    'products/add' => 'views/products/add_product.php',
];

// Match the route
if (array_key_exists($requestPath, $routes)) {
    require_once $_SERVER['DOCUMENT_ROOT']  . BASE_PATH . $routes[$requestPath];
} else {
    // 404 Page
    http_response_code(404);
    echo "Page not found.". $requestPath;
}
?>
