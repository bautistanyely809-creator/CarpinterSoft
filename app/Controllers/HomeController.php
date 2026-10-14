<?php
namespace App\Controllers;

use App\Models\Furniture;

class HomeController {
    public function index(): void {
        $models = Furniture::getAll();
        $woods = Furniture::getWoodOptions();
        
        $title = "CarpinterSoft • Ebanistería Digital & Configuración 3D Paramétrica";
        require __DIR__ . '/../Views/layouts/header.php';
        require __DIR__ . '/../Views/home.php';
        require __DIR__ . '/../Views/layouts/footer.php';
    }
}
