<?php
declare(strict_types=1);

// Static file support for PHP's built-in web server
if (php_sapi_name() === 'cli-server') {
    $requestedPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $fullPath = __DIR__ . $requestedPath;
    if ($requestedPath !== '/' && file_exists($fullPath) && !is_dir($fullPath)) {
        return false;
    }
}

// PSR-4 Autoloader for 'App\' namespace
spl_autoload_register(function (string $class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../app/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Initialize session if not active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure database and initial tables exist
try {
    \App\Config\Database::init();
} catch (\Throwable $e) {
    error_log("Database initialization error: " . $e->getMessage());
}

// Parse request route
$rawUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$uri = rtrim($rawUri, '/');
if ($uri === '') {
    $uri = '/';
}

// API Routes
if (str_starts_with($uri, '/api/')) {
    $endpoint = substr($uri, 5);
    $api = new \App\Controllers\ApiController();
    $api->handle($endpoint);
    exit;
}

// Web Page Routes
switch ($uri) {
    case '/':
        (new \App\Controllers\HomeController())->index();
        break;

    case '/customizer':
        (new \App\Controllers\CustomizerController())->index();
        break;

    case '/catalog':
        (new \App\Controllers\CatalogController())->index();
        break;

    case '/kanban':
        (new \App\Controllers\KanbanController())->index();
        break;

    case '/nesting':
        (new \App\Controllers\NestingController())->index();
        break;

    case '/checkout':
        (new \App\Controllers\CheckoutController())->index();
        break;

    case '/cart/add':
        (new \App\Controllers\CheckoutController())->add();
        break;

    case '/cart/clear':
        (new \App\Controllers\CheckoutController())->clear();
        break;

    default:
        http_response_code(404);
        $title = "404 - Página no encontrada • CarpinterSoft";
        require __DIR__ . '/../app/Views/layouts/header.php';
        ?>
        <div class="max-w-2xl mx-auto px-4 py-20 text-center">
            <div class="w-16 h-16 rounded-2xl bg-surface-container flex items-center justify-center mx-auto mb-4 text-secondary-container">
                <span class="material-symbols-outlined text-4xl">error_outline</span>
            </div>
            <h1 class="text-3xl font-extrabold text-primary font-mono mb-2">Página No Encontrada</h1>
            <p class="text-sm text-text-muted mb-6">La ruta solicitada <code><?= htmlspecialchars($uri) ?></code> no existe en el taller digital.</p>
            <a href="/" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-primary text-white text-xs font-bold font-mono">
                <span class="material-symbols-outlined text-[18px]">home</span>
                <span>Volver al Inicio</span>
            </a>
        </div>
        <?php
        require __DIR__ . '/../app/Views/layouts/footer.php';
        break;
}
