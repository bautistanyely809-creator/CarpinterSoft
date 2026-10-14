<?php
namespace App\Controllers;

use App\Models\CutPart;
use App\Models\Furniture;

class NestingController {
    public function index(): void {
        $parts = CutPart::getHorizonCutParts();
        $totalPieces = array_sum(array_column($parts, 'quantity'));
        $totalAreaM2 = 0;
        foreach ($parts as $p) {
            $totalAreaM2 += ($p['length'] * $p['width'] * $p['quantity']) / 1000000;
        }

        $title = "Despiece CNC & Nesting Maestro de Corte • CarpinterSoft";
        require __DIR__ . '/../Views/layouts/header.php';
        require __DIR__ . '/../Views/nesting.php';
        require __DIR__ . '/../Views/layouts/footer.php';
    }
}
