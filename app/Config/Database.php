<?php
namespace App\Config;

use PDO;
use PDOException;

class Database {
    private static ?PDO $instance = null;
    private static string $driver = 'mysql';

    public static function init(): void {
        self::getConnection();
    }

    public static function getDriver(): string {
        return self::$driver;
    }

    public static function getConnection(): PDO {
        if (self::$instance === null) {
            // 1. Intentar conexión a MySQL / MariaDB de XAMPP (puerto 3307 habitual cuando convive con otro MySQL, o 3306 estándar)
            $mysqlHosts = [
                ['host' => '127.0.0.1', 'port' => 3307, 'db' => 'carpintersoft_db', 'user' => 'root', 'pass' => ''],
                ['host' => '127.0.0.1', 'port' => 3306, 'db' => 'carpintersoft_db', 'user' => 'root', 'pass' => '']
            ];

            foreach ($mysqlHosts as $cfg) {
                try {
                    $dsn = "mysql:host={$cfg['host']};port={$cfg['port']};dbname={$cfg['db']};charset=utf8mb4";
                    $pdo = new PDO($dsn, $cfg['user'], $cfg['pass'], [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_TIMEOUT => 2
                    ]);
                    self::$instance = $pdo;
                    self::$driver = 'mysql (XAMPP :' . $cfg['port'] . ')';
                    return self::$instance;
                } catch (PDOException $e) {
                    // Continuar al siguiente puerto o fallback
                }
            }

            // 2. Fallback resiliente a SQLite local
            try {
                $dbPath = __DIR__ . '/../../database/carpintersoft.sqlite';
                $dbDir = dirname($dbPath);
                if (!is_dir($dbDir)) {
                    mkdir($dbDir, 0777, true);
                }

                self::$instance = new PDO("sqlite:" . $dbPath);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                self::$driver = 'sqlite';

                self::initSqliteSchema(self::$instance);
            } catch (PDOException $e) {
                die("Error al conectar a la base de datos (MySQL y SQLite no disponibles): " . $e->getMessage());
            }
        }
        return self::$instance;
    }

    private static function initSqliteSchema(PDO $db): void {
        $db->exec("
            CREATE TABLE IF NOT EXISTS orders (
                id TEXT PRIMARY KEY,
                order_number TEXT NOT NULL,
                client TEXT NOT NULL,
                project TEXT NOT NULL,
                product_name TEXT NOT NULL,
                dimensions TEXT NOT NULL,
                wood_name TEXT NOT NULL,
                wood_color TEXT NOT NULL,
                piece_count INTEGER NOT NULL,
                column_id TEXT NOT NULL,
                status_badge TEXT NOT NULL,
                status_badge_class TEXT NOT NULL,
                delivery_date TEXT NOT NULL,
                thumbnail TEXT NOT NULL,
                machine TEXT,
                operator TEXT,
                remaining_time TEXT,
                progress_percent INTEGER,
                alert TEXT,
                urgency TEXT NOT NULL DEFAULT 'normal',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );
        ");

        $db->exec("
            CREATE TABLE IF NOT EXISTS quotes (
                id TEXT PRIMARY KEY,
                model_id TEXT NOT NULL,
                model_name TEXT NOT NULL,
                width REAL NOT NULL,
                height REAL NOT NULL,
                depth REAL NOT NULL,
                modules INTEGER NOT NULL,
                wood_id TEXT NOT NULL,
                finish_type TEXT NOT NULL,
                base_type TEXT NOT NULL,
                has_led INTEGER NOT NULL DEFAULT 0,
                has_cable_grommet INTEGER NOT NULL DEFAULT 0,
                total_price REAL NOT NULL,
                client_name TEXT,
                client_email TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );
        ");

        $stmt = $db->query("SELECT COUNT(*) as count FROM orders");
        $count = $stmt->fetch()['count'] ?? 0;
        if ($count == 0) {
            self::seedInitialOrders($db);
        }
    }

    private static function seedInitialOrders(PDO $db): void {
        $initialOrders = [
            [
                'id' => 'ord-899',
                'order_number' => 'ORD-2025-0899',
                'client' => 'Estudio Arq. Vidal',
                'project' => 'Hotel Boutique Dénia',
                'product_name' => 'Aparador Alistonado Horizon 220cm',
                'dimensions' => '220 × 85 × 45 cm',
                'wood_name' => 'Roble Nórdico Miel',
                'wood_color' => '#D4A373',
                'piece_count' => 28,
                'column_id' => 'dxf',
                'status_badge' => 'DXF Pendiente',
                'status_badge_class' => 'bg-surface-container text-text-muted',
                'delivery_date' => '28 Oct',
                'thumbnail' => 'https://lh3.googleusercontent.com/aida/AEtjO1UzbsboOsiRfAYPl4GbZf70Euh-xYGI8_7H81iuMpOXc88JV2EIGKE-oObUS4N9t05yVJ5Ngc8J3yuwcKeOTCdgBa9CaNfQm0PXna3_utxK54I5RLUvwbi97qXxEHgHxM49zfieRuinImvZqO7GwhXOupurg9_RGCjrYbKixiNS2ktLLVLr385w0M_GO_aRuDsB5Bub6IUqWbKkxGsgijywURyAyql0P5k8s4wOidEnRIqBKl45GFAExF32',
                'urgency' => 'normal'
            ],
            [
                'id' => 'ord-894',
                'order_number' => 'ORD-2025-0894',
                'client' => 'Elena Morales',
                'project' => 'Vivienda Unifamiliar Godella',
                'product_name' => 'Mesa de Comedor Fusión 240cm',
                'dimensions' => '240 × 75 × 100 cm',
                'wood_name' => 'Roble Macizo 40mm',
                'wood_color' => '#b89758',
                'piece_count' => 12,
                'column_id' => 'cnc',
                'status_badge' => 'EN MÁQUINA 65%',
                'status_badge_class' => 'bg-secondary-container text-white',
                'delivery_date' => '22 Oct',
                'machine' => 'Biesse Rover #2',
                'operator' => 'Marc Ribera',
                'remaining_time' => '28 min',
                'progress_percent' => 65,
                'thumbnail' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBJP6OTwtQP3g1HEcECFiglaE-LKpxqvYsp9e1QuiYJbri9ifmWhCYRYK8AOb0aJZ64IaOk6bOpsXCanKsqtraPQTlDfCWHFE-XPLW4zpObryoIxacOL-7mq53hm-pN65WiHRSRP8MXcWbEYBBF7uGW5xWWPwU51zHmEfET_-kbAPgGnTXM6QWBfDjZElGQ6zvFTuOpvA9Cc80kUKyI5dWRybpcd14RkloJLJzYwc-hX0YIcDRBe-mbCQ',
                'urgency' => 'soon'
            ],
            [
                'id' => 'ord-888',
                'order_number' => 'ORD-2025-0888',
                'client' => 'Manuel R.',
                'project' => 'Ático Turia',
                'product_name' => 'Mueble TV Flotante Veta 210cm',
                'dimensions' => '210 × 42 × 42 cm',
                'wood_name' => 'Roble Natural FSC®',
                'wood_color' => '#b89758',
                'piece_count' => 22,
                'column_id' => 'edges',
                'status_badge' => 'Canteado OK',
                'status_badge_class' => 'bg-primary-container text-white',
                'delivery_date' => '23 Oct',
                'thumbnail' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDS2I_wgaHl_fV9sgxALXv029NTxCTRbAMdzHw7lG5fNKZnkbtLy-ef-4kG6WOrvcXlitxKjLDaG3T0J30t-jpP6nRI0nI9fevSF8bodHsSYKHtd-5mEUz2t9jP8yrfHUaeJRiLFC4-2fhshdI63AH8Zri42NR-2pOZhXxmOdrzRgVjrt_5NuwLckF6YDfMOMTVEXbt-uihs7gDACQ-Hfb0ws2c-1KZ6XrmffdH1AVjNC91sMAWgnypYQ',
                'urgency' => 'soon'
            ],
            [
                'id' => 'ord-881',
                'order_number' => 'ORD-2025-0881',
                'client' => 'Mónica Gil',
                'project' => 'Estudio Creativo',
                'product_name' => 'Escritorio Studio Pro 180cm',
                'dimensions' => '180 × 75 × 75 cm',
                'wood_name' => 'Tzalam & Grafito',
                'wood_color' => '#7C472A',
                'piece_count' => 18,
                'column_id' => 'assembly',
                'status_badge' => 'En Encolado',
                'status_badge_class' => 'bg-primary text-white',
                'delivery_date' => '21 Oct',
                'thumbnail' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDHIpgx7kBvrkRKNPHJ3d7z_k6kPzZHVQRC8ULBslGIDZ8FUquPvAAmMnfXJV2s4M3m4es14iKdMIJw6cOO-5W-4pBUM2rTAQrW6EZgVKriE-HhuYPgJ6C_RuC6ICMkxyxYDOp2dUnLUlEajTuKCFRI6QZHMBuJvNNTjtaqnCGOVYVKHZx-fAQGaMiO4osDJ39TxPVtbgHDAgeDtfr8DrSD0SS29pOW2PsVTtxbXLTCVsRLVy13pUIIRA',
                'urgency' => 'soon'
            ],
            [
                'id' => 'ord-879',
                'order_number' => 'ORD-2025-0879',
                'client' => 'Carlos Benítez',
                'project' => 'Residencial Gran Vía',
                'product_name' => 'Librero Modular Nexus 240cm',
                'dimensions' => '240 × 210 × 38 cm',
                'wood_name' => 'Nogal Americano',
                'wood_color' => '#583E26',
                'piece_count' => 36,
                'column_id' => 'finish',
                'status_badge' => 'Aceitado Rubio',
                'status_badge_class' => 'bg-amber-600 text-white',
                'delivery_date' => '20 Oct',
                'thumbnail' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAjMTuZQxC8P6UoPPgzDKSLRXoZAJq8NIMwUCTaR5oZEtK8mm1V5XamAs0HBtKA5d7Ns_XtUwKAzOgJx3KH_lq6oWr9_fl8DP0mPb2ITXVF6R089fo9EOfqOqXhN-t-CwpiGtpTwPdCmw394iwWJkJCp0OpK8hams_XBqZxrQwXAItZFYujIjcdkSGlwH5EyDS5QP0adKoIsThNuXOpj4urHZLlp5sGaKgsUHakIK319jjog9-M7gh6HQ',
                'urgency' => 'urgent'
            ],
            [
                'id' => 'ord-872',
                'order_number' => 'ORD-2025-0872',
                'client' => 'Galería Alarte',
                'project' => 'Espacio Expositivo Ruzafa',
                'product_name' => 'Aparador Alto Nórdico Mora',
                'dimensions' => '110 × 145 × 44 cm',
                'wood_name' => 'Roble Selección & Cristal',
                'wood_color' => '#D4A373',
                'piece_count' => 24,
                'column_id' => 'packaging',
                'status_badge' => 'Control QA OK',
                'status_badge_class' => 'bg-state-success text-white',
                'delivery_date' => '19 Oct',
                'thumbnail' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBhRKj1fNvfTnkkz__DSCKRK8c0Z_eDmOVyh1afyq6R5SOW3x44yRlWtBP6PO8YuPv7MGNcmMP3rs5vVd-B0ENb6Bolf--CeMuT-Ql5NHViJFACk2kax5-HC091-psqymRFoGXMDhjfz1AcobTXbfP1nYCxHuqsLwdDQEibTpoeIpGN4L72dgdMJV4mwqRBDdOEL7GUy0BMLaQHCgn0DtiyhUgeJZ6PJmIspQeTCOKDVa6E3rJnkKWzhQ',
                'urgency' => 'normal'
            ]
        ];

        $stmt = $db->prepare("
            INSERT INTO orders (
                id, order_number, client, project, product_name, dimensions,
                wood_name, wood_color, piece_count, column_id, status_badge,
                status_badge_class, delivery_date, thumbnail, machine, operator,
                remaining_time, progress_percent, alert, urgency
            ) VALUES (
                :id, :order_number, :client, :project, :product_name, :dimensions,
                :wood_name, :wood_color, :piece_count, :column_id, :status_badge,
                :status_badge_class, :delivery_date, :thumbnail, :machine, :operator,
                :remaining_time, :progress_percent, :alert, :urgency
            )
        ");

        foreach ($initialOrders as $ord) {
            $stmt->execute([
                ':id' => $ord['id'],
                ':order_number' => $ord['order_number'],
                ':client' => $ord['client'],
                ':project' => $ord['project'],
                ':product_name' => $ord['product_name'],
                ':dimensions' => $ord['dimensions'],
                ':wood_name' => $ord['wood_name'],
                ':wood_color' => $ord['wood_color'],
                ':piece_count' => $ord['piece_count'],
                ':column_id' => $ord['column_id'],
                ':status_badge' => $ord['status_badge'],
                ':status_badge_class' => $ord['status_badge_class'],
                ':delivery_date' => $ord['delivery_date'],
                ':thumbnail' => $ord['thumbnail'],
                ':machine' => $ord['machine'] ?? null,
                ':operator' => $ord['operator'] ?? null,
                ':remaining_time' => $ord['remaining_time'] ?? null,
                ':progress_percent' => $ord['progress_percent'] ?? null,
                ':alert' => $ord['alert'] ?? null,
                ':urgency' => $ord['urgency'] ?? 'normal'
            ]);
        }
    }
}
