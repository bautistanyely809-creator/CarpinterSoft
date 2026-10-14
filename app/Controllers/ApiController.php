<?php
namespace App\Controllers;

use App\Models\Order;
use App\Models\Furniture;

class ApiController {
    public function handle(string $endpoint): void {
        header('Content-Type: application/json; charset=utf-8');

        switch ($endpoint) {
            case 'orders':
                echo json_encode(Order::all());
                break;

            case 'orders/update-column':
                $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
                $orderId = $input['order_id'] ?? '';
                $columnId = $input['column_id'] ?? '';
                if ($orderId && $columnId) {
                    $ok = Order::updateColumn($orderId, $columnId);
                    echo json_encode(['success' => $ok]);
                } else {
                    http_response_code(400);
                    echo json_encode(['error' => 'Faltan parámetros']);
                }
                break;

            case 'orders/create':
                $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
                $id = Order::create($input);
                echo json_encode(['success' => true, 'id' => $id]);
                break;

            case 'quote/calculate':
                $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
                $modelId = $input['model_id'] ?? 'horizon';
                $width = (float)($input['width'] ?? 180);
                $height = (float)($input['height'] ?? 85);
                $depth = (float)($input['depth'] ?? 45);
                $modules = (int)($input['modules'] ?? 3);
                $woodId = $input['wood_id'] ?? 'roble';
                $finishType = $input['finish_type'] ?? 'rubio';
                $baseType = $input['base_type'] ?? 'conicas';
                $hasLed = !empty($input['has_led']);
                $hasGrommet = !empty($input['has_grommet']);

                $quote = Furniture::calculateQuotation(
                    $modelId,
                    $width,
                    $height,
                    $depth,
                    $modules,
                    $woodId,
                    $finishType,
                    $baseType,
                    $hasLed,
                    $hasGrommet
                );
                echo json_encode($quote);
                break;

            case 'cart':
                if (session_status() === PHP_SESSION_NONE) session_start();
                echo json_encode($_SESSION['cart'] ?? []);
                break;

            case 'ai/chat':
                $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
                $msg = $input['message'] ?? '';
                $context = $input['context'] ?? [];
                $result = \App\Services\CarpinterAIService::chat($msg, $context);
                echo json_encode($result);
                break;

            default:
                http_response_code(404);
                echo json_encode(['error' => 'Endpoint no encontrado']);
                break;
        }
        exit;
    }
}
