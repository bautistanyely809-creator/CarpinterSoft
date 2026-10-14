<?php
namespace App\Controllers;

use App\Models\Furniture;

class CustomizerController {
    public function index(): void {
        $modelId = $_GET['model'] ?? 'horizon';
        $model = Furniture::find($modelId);
        $woods = Furniture::getWoodOptions();
        $allModels = Furniture::getAll();

        $width = (float)($_GET['w'] ?? $model['baseWidth']);
        $height = (float)($_GET['h'] ?? $model['baseHeight']);
        $depth = (float)($_GET['d'] ?? $model['baseDepth']);
        $modules = (int)($_GET['m'] ?? 3);
        $woodId = $_GET['wood'] ?? 'roble';
        $finishType = $_GET['finish'] ?? 'rubio';
        $baseType = $_GET['base'] ?? 'conicas';
        $hasLed = isset($_GET['led']) && $_GET['led'] === '1';
        $hasGrommet = isset($_GET['grommet']) && $_GET['grommet'] === '1';

        $quote = Furniture::calculateQuotation(
            $model['id'],
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

        $title = "Personalizador 3D Paramétrico • " . htmlspecialchars($model['name']);
        require __DIR__ . '/../Views/layouts/header.php';
        require __DIR__ . '/../Views/customizer.php';
        require __DIR__ . '/../Views/layouts/footer.php';
    }
}
