<?php
namespace App\Controllers;

use App\Models\Furniture;

class CheckoutController {
    public function index(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            // Default sample custom item in cart
            $_SESSION['cart'] = [
                [
                    'id' => 'cart-init-01',
                    'modelName' => 'Aparador Alistonado Horizon (V4.2)',
                    'image' => Furniture::getAll()[0]['image'],
                    'dimensions' => ['width' => 180, 'height' => 85, 'depth' => 45],
                    'woodName' => 'Roble Nórdico Miel',
                    'finishName' => 'Aceite Rubio Monocoat® Puro',
                    'baseName' => 'Patas Cónicas Macizas 150mm',
                    'addons' => ['Blum® Soft-close', 'Pasacables CNC'],
                    'price' => 1280,
                    'date' => date('d/m/Y')
                ]
            ];
        }

        $cart = $_SESSION['cart'];
        $cartTotal = array_sum(array_column($cart, 'price'));

        $title = "Presupuesto & Expedición a Fábrica • CarpinterSoft";
        require __DIR__ . '/../Views/layouts/header.php';
        require __DIR__ . '/../Views/checkout.php';
        require __DIR__ . '/../Views/layouts/footer.php';
    }

    public function add(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        if (!empty($input)) {
            $item = [
                'id' => 'ITEM-' . time(),
                'modelName' => $input['modelName'] ?? 'Mueble Personalizado',
                'image' => $input['image'] ?? Furniture::getAll()[0]['image'],
                'dimensions' => [
                    'width' => (float)($input['width'] ?? 180),
                    'height' => (float)($input['height'] ?? 85),
                    'depth' => (float)($input['depth'] ?? 45)
                ],
                'woodName' => $input['woodName'] ?? 'Roble Nórdico Miel',
                'finishName' => $input['finishName'] ?? 'Aceite Rubio Monocoat®',
                'baseName' => $input['baseName'] ?? 'Patas Cónicas 15cm',
                'addons' => $input['addons'] ?? ['Herrajes Blum® Soft-close'],
                'price' => (int)($input['price'] ?? 1280),
                'date' => date('d/m/Y')
            ];

            $_SESSION['cart'][] = $item;
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'cart' => $_SESSION['cart']]);
            exit;
        }

        header('Location: /checkout');
        exit;
    }

    public function clear(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['cart'] = [];
        header('Location: /checkout');
        exit;
    }
}
