<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Furniture;

class AiController {
    public function index(): void {
        $title = "CarpinterAI • Asistente Inteligente de Ebanistería & CNC • CarpinterSoft";
        $models = Furniture::getAll();
        $woods = Furniture::getWoodOptions();
        
        require __DIR__ . '/../Views/layouts/header.php';
        require __DIR__ . '/../Views/ai.php';
        require __DIR__ . '/../Views/layouts/footer.php';
    }
}
