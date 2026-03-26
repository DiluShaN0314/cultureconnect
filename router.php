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
    '' => ['controller' => 'UserController', 'method' => 'dashboard'],
    'login' => 'views/auth/login.php',
    'register' => 'views/auth/register.php',
    'logout' => 'views/auth/logout.php',
    'admin-dashboard' => ['controller' => 'AdminController', 'method' => 'index'],
    'admin/users/add' => ['controller' => 'AdminController', 'method' => 'addUser'],
    'admin/users/store' => ['controller' => 'AdminController', 'method' => 'storeUser'],
    'sme-dashboard' => ['controller' => 'SmeController', 'method' => 'dashboard'],
    'user-dashboard' => ['controller' => 'UserController', 'method' => 'dashboard'],
    'profile' => ['controller' => 'UserController', 'method' => 'profile'],
    'profile/update' => ['controller' => 'UserController', 'method' => 'updateProfile'],
    'events' => ['controller' => 'ProductController', 'method' => 'list'],

    // Product routes
    'products' => ['controller' => 'ProductController', 'method' => 'index'],
    'products/list' => ['controller' => 'ProductController', 'method' => 'list'],
    'products/add' => ['controller' => 'ProductController', 'method' => 'add'],
    'products/store' => ['controller' => 'ProductController', 'method' => 'store'],
    'products/edit' => ['controller' => 'ProductController', 'method' => 'edit'],
    'products/update' => ['controller' => 'ProductController', 'method' => 'update'],
    'products/delete' => ['controller' => 'ProductController', 'method' => 'delete'],

    // Resident routes
    'residents' => ['controller' => 'ResidentController', 'method' => 'index'],
    'residents/add' => ['controller' => 'ResidentController', 'method' => 'add'],
    'residents/store' => ['controller' => 'ResidentController', 'method' => 'store'],
    'residents/edit' => ['controller' => 'ResidentController', 'method' => 'edit'],
    'residents/update' => ['controller' => 'ResidentController', 'method' => 'update'],
    'residents/delete' => ['controller' => 'ResidentController', 'method' => 'delete'],

    // SME routes
    'smes' => ['controller' => 'SMEController', 'method' => 'index'],
    'smes/add' => ['controller' => 'SMEController', 'method' => 'add'],
    'smes/store' => ['controller' => 'SMEController', 'method' => 'store'],
    'smes/edit' => ['controller' => 'SMEController', 'method' => 'edit'],
    'smes/update' => ['controller' => 'SMEController', 'method' => 'update'],
    'smes/delete' => ['controller' => 'SMEController', 'method' => 'delete'],

    // Vote routes
    'votes' => ['controller' => 'VoteController', 'method' => 'index'],
    'votes/store' => ['controller' => 'VoteController', 'method' => 'store'],
    'votes/delete' => ['controller' => 'VoteController', 'method' => 'delete'],

    // Area routes
    'areas' => ['controller' => 'AreaController', 'method' => 'index'],
    'areas/add' => ['controller' => 'AreaController', 'method' => 'add'],
    'areas/store' => ['controller' => 'AreaController', 'method' => 'store'],
    'areas/edit' => ['controller' => 'AreaController', 'method' => 'edit'],
    'areas/update' => ['controller' => 'AreaController', 'method' => 'update'],
    'areas/delete' => ['controller' => 'AreaController', 'method' => 'delete'],
];

// Match the route
if (array_key_exists($requestPath, $routes)) {
    $route = $routes[$requestPath];

    if (is_array($route)) {
        require_once $_SERVER['DOCUMENT_ROOT'] . BASE_PATH . 'controllers/' . $route['controller'] . '.php';
        $controller = new $route['controller']();
        $method = $route['method'];
        $controller->$method();
    } else {
        require_once $_SERVER['DOCUMENT_ROOT']  . BASE_PATH . $route;
    }
} else {
    // 404 Page
    http_response_code(404);
    echo "Page not found.". $requestPath;
}
?>
