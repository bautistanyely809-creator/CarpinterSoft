<?php
namespace App\Controllers;

use App\Models\Furniture;

class CatalogController {
    public function index(): void {
        $category = $_GET['cat'] ?? 'all';
        $wood = $_GET['wood'] ?? 'all';
        $search = strtolower(trim($_GET['q'] ?? ''));

        $allModels = Furniture::getAll();
        $woods = Furniture::getWoodOptions();

        $models = array_filter($allModels, function($m) use ($category, $wood, $search) {
            if ($category !== 'all' && stripos($m['category'], $category) === false) {
                return false;
            }
            if ($wood !== 'all' && !in_array($wood, $m['woodOptions'])) {
                return false;
            }
            if ($search !== '') {
                $text = strtolower($m['name'] . ' ' . $m['description'] . ' ' . $m['category']);
                if (strpos($text, $search) === false) {
                    return false;
                }
            }
            return true;
        });

        $title = "Catálogo de Diseños Paramétricos • CarpinterSoft";
        require __DIR__ . '/../Views/layouts/header.php';
        require __DIR__ . '/../Views/catalog.php';
        require __DIR__ . '/../Views/layouts/footer.php';
    }
}
