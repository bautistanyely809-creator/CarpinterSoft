<?php
namespace App\Controllers;

use App\Models\Order;
use App\Models\Furniture;

class KanbanController {
    public function index(): void {
        $orders = Order::all();
        $models = Furniture::getAll();
        $woods = Furniture::getWoodOptions();

        $columns = [
            ['id' => 'dxf', 'title' => '1. Validación & DXF', 'subtitle' => 'Validación paramétrica & tolerancias', 'dotColor' => 'bg-slate-400'],
            ['id' => 'cnc', 'title' => '2. Corte CNC', 'subtitle' => 'Nesting activo & seccionado', 'dotColor' => 'bg-amber-600'],
            ['id' => 'edges', 'title' => '3. Calibrado & Cantos', 'subtitle' => 'Encolado PUR 1-2mm & golas', 'dotColor' => 'bg-blue-600'],
            ['id' => 'assembly', 'title' => '4. Premontaje', 'subtitle' => 'Ajuste de escuadras & herrajes', 'dotColor' => 'bg-indigo-600'],
            ['id' => 'finish', 'title' => '5. Lijado & Acabado', 'subtitle' => 'Lijado grano 180-320 & Rubio', 'dotColor' => 'bg-orange-600'],
            ['id' => 'packaging', 'title' => '6. Control & Embalaje', 'subtitle' => 'Inspección ±0.2mm & QR Zebra', 'dotColor' => 'bg-emerald-600']
        ];

        $title = "Cola de Producción & Flujo Kanban de Taller • CarpinterSoft";
        require __DIR__ . '/../Views/layouts/header.php';
        require __DIR__ . '/../Views/kanban.php';
        require __DIR__ . '/../Views/layouts/footer.php';
    }
}
