<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class Order {
    public static function all(): array {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM orders ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public static function find(string $id): ?array {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM orders WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public static function updateColumn(string $id, string $columnId): bool {
        $db = Database::getConnection();
        $badges = [
            'dxf' => ['DXF Verificado', 'bg-surface-container text-text-muted'],
            'cnc' => ['EN MÁQUINA', 'bg-secondary-container text-white'],
            'edges' => ['Canteado OK', 'bg-primary-container text-white'],
            'assembly' => ['En Banco', 'bg-primary text-white'],
            'finish' => ['En Cabina', 'bg-amber-600 text-white'],
            'packaging' => ['LISTO EXPEDICIÓN', 'bg-state-success text-white']
        ];

        $badge = $badges[$columnId] ?? ['En Proceso', 'bg-surface-container text-text-primary'];

        $stmt = $db->prepare("
            UPDATE orders 
            SET column_id = :column_id, status_badge = :badge, status_badge_class = :badge_class 
            WHERE id = :id
        ");
        return $stmt->execute([
            ':column_id' => $columnId,
            ':badge' => $badge[0],
            ':badge_class' => $badge[1],
            ':id' => $id
        ]);
    }

    public static function create(array $data): string {
        $db = Database::getConnection();
        $id = 'ord-' . substr(time(), -4);
        $orderNum = 'ORD-2025-' . rand(1000, 9999);

        $badges = [
            'dxf' => ['DXF Verificado', 'bg-surface-container text-text-muted'],
            'cnc' => ['EN MÁQUINA', 'bg-secondary-container text-white'],
            'edges' => ['Canteado OK', 'bg-primary-container text-white'],
            'assembly' => ['En Banco', 'bg-primary text-white'],
            'finish' => ['En Cabina', 'bg-amber-600 text-white'],
            'packaging' => ['LISTO EXPEDICIÓN', 'bg-state-success text-white']
        ];
        $col = $data['column_id'] ?? 'dxf';
        $badge = $badges[$col] ?? $badges['dxf'];

        $stmt = $db->prepare("
            INSERT INTO orders (
                id, order_number, client, project, product_name, dimensions,
                wood_name, wood_color, piece_count, column_id, status_badge,
                status_badge_class, delivery_date, thumbnail, urgency
            ) VALUES (
                :id, :order_number, :client, :project, :product_name, :dimensions,
                :wood_name, :wood_color, :piece_count, :column_id, :status_badge,
                :status_badge_class, :delivery_date, :thumbnail, :urgency
            )
        ");

        $stmt->execute([
            ':id' => $id,
            ':order_number' => $orderNum,
            ':client' => $data['client'] ?? 'Cliente Particular',
            ':project' => $data['project'] ?? 'Residencial',
            ':product_name' => $data['product_name'] ?? $data['title'] ?? 'Mueble a Medida',
            ':dimensions' => $data['dimensions'] ?? '180 × 85 × 45 cm',
            ':wood_name' => $data['wood_name'] ?? 'Roble Nórdico Miel',
            ':wood_color' => $data['wood_color'] ?? '#D4A373',
            ':piece_count' => (int)($data['piece_count'] ?? 20),
            ':column_id' => $col,
            ':status_badge' => $badge[0],
            ':status_badge_class' => $badge[1],
            ':delivery_date' => $data['delivery_date'] ?? '30 Oct',
            ':thumbnail' => $data['thumbnail'] ?? 'https://lh3.googleusercontent.com/aida/AEtjO1UzbsboOsiRfAYPl4GbZf70Euh-xYGI8_7H81iuMpOXc88JV2EIGKE-oObUS4N9t05yVJ5Ngc8J3yuwcKeOTCdgBa9CaNfQm0PXna3_utxK54I5RLUvwbi97qXxEHgHxM49zfieRuinImvZqO7GwhXOupurg9_RGCjrYbKixiNS2ktLLVLr385w0M_GO_aRuDsB5Bub6IUqWbKkxGsgijywURyAyql0P5k8s4wOidEnRIqBKl45GFAExF32',
            ':urgency' => $data['urgency'] ?? 'normal'
        ]);

        return $id;
    }
}
