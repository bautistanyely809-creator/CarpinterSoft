-- ========================================================
-- CarpinterSoft • Base de Datos MySQL / MariaDB (XAMPP)
-- Base de Datos: carpintersoft_db
-- Puerto XAMPP: 3307
-- ========================================================

CREATE DATABASE IF NOT EXISTS `carpintersoft_db` 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `carpintersoft_db`;

-- --------------------------------------------------------
-- 1. Tabla de Órdenes de Producción / Taller Kanban
-- --------------------------------------------------------
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
    `id` VARCHAR(50) NOT NULL PRIMARY KEY,
    `order_number` VARCHAR(50) NOT NULL,
    `client` VARCHAR(150) NOT NULL,
    `project` VARCHAR(150) NOT NULL,
    `product_name` VARCHAR(200) NOT NULL,
    `dimensions` VARCHAR(100) NOT NULL,
    `wood_name` VARCHAR(100) NOT NULL,
    `wood_color` VARCHAR(20) NOT NULL,
    `piece_count` INT NOT NULL DEFAULT 1,
    `column_id` VARCHAR(50) NOT NULL DEFAULT 'dxf',
    `status_badge` VARCHAR(100) NOT NULL,
    `status_badge_class` VARCHAR(150) NOT NULL,
    `delivery_date` VARCHAR(50) NOT NULL,
    `thumbnail` TEXT NOT NULL,
    `machine` VARCHAR(100) NULL,
    `operator` VARCHAR(100) NULL,
    `remaining_time` VARCHAR(50) NULL,
    `progress_percent` INT NULL DEFAULT 0,
    `alert` VARCHAR(255) NULL,
    `urgency` ENUM('normal', 'soon', 'urgent') NOT NULL DEFAULT 'normal',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_column_id` (`column_id`),
    INDEX `idx_urgency` (`urgency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 2. Tabla de Cotizaciones Guardadas del Personalizador 3D
-- --------------------------------------------------------
DROP TABLE IF EXISTS `quotes`;
CREATE TABLE `quotes` (
    `id` VARCHAR(50) NOT NULL PRIMARY KEY,
    `model_id` VARCHAR(50) NOT NULL,
    `model_name` VARCHAR(150) NOT NULL,
    `width` DECIMAL(8,2) NOT NULL,
    `height` DECIMAL(8,2) NOT NULL,
    `depth` DECIMAL(8,2) NOT NULL,
    `modules` INT NOT NULL DEFAULT 3,
    `wood_id` VARCHAR(50) NOT NULL,
    `finish_type` VARCHAR(50) NOT NULL,
    `base_type` VARCHAR(50) NOT NULL,
    `has_led` TINYINT(1) NOT NULL DEFAULT 0,
    `has_cable_grommet` TINYINT(1) NOT NULL DEFAULT 0,
    `total_price` DECIMAL(10,2) NOT NULL,
    `client_name` VARCHAR(150) NULL,
    `client_email` VARCHAR(150) NULL,
    `notes` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 3. Datos Iniciales de Órdenes de Fabricación
-- --------------------------------------------------------
INSERT INTO `orders` (
    `id`, `order_number`, `client`, `project`, `product_name`, `dimensions`,
    `wood_name`, `wood_color`, `piece_count`, `column_id`, `status_badge`,
    `status_badge_class`, `delivery_date`, `thumbnail`, `machine`, `operator`,
    `remaining_time`, `progress_percent`, `alert`, `urgency`
) VALUES 
(
    'ord-899', 'ORD-2025-0899', 'Estudio Arq. Vidal', 'Hotel Boutique Dénia',
    'Aparador Alistonado Horizon 220cm', '220 × 85 × 45 cm', 'Roble Nórdico Miel', '#D4A373',
    28, 'dxf', 'DXF Pendiente', 'bg-surface-container text-text-muted', '28 Oct',
    'https://lh3.googleusercontent.com/aida/AEtjO1UzbsboOsiRfAYPl4GbZf70Euh-xYGI8_7H81iuMpOXc88JV2EIGKE-oObUS4N9t05yVJ5Ngc8J3yuwcKeOTCdgBa9CaNfQm0PXna3_utxK54I5RLUvwbi97qXxEHgHxM49zfieRuinImvZqO7GwhXOupurg9_RGCjrYbKixiNS2ktLLVLr385w0M_GO_aRuDsB5Bub6IUqWbKkxGsgijywURyAyql0P5k8s4wOidEnRIqBKl45GFAExF32',
    NULL, NULL, NULL, 0, NULL, 'normal'
),
(
    'ord-894', 'ORD-2025-0894', 'Elena Morales', 'Vivienda Unifamiliar Godella',
    'Mesa de Comedor Fusión 240cm', '240 × 75 × 100 cm', 'Roble Macizo 40mm', '#b89758',
    12, 'cnc', 'EN MÁQUINA 65%', 'bg-secondary-container text-white', '22 Oct',
    'https://lh3.googleusercontent.com/aida-public/AB6AXuBJP6OTwtQP3g1HEcECFiglaE-LKpxqvYsp9e1QuiYJbri9ifmWhCYRYK8AOb0aJZ64IaOk6bOpsXCanKsqtraPQTlDfCWHFE-XPLW4zpObryoIxacOL-7mq53hm-pN65WiHRSRP8MXcWbEYBBF7uGW5xWWPwU51zHmEfET_-kbAPgGnTXM6QWBfDjZElGQ6zvFTuOpvA9Cc80kUKyI5dWRybpcd14RkloJLJzYwc-hX0YIcDRBe-mbCQ',
    'Biesse Rover #2', 'Marc Ribera', '28 min', 65, NULL, 'soon'
),
(
    'ord-888', 'ORD-2025-0888', 'Manuel R.', 'Ático Turia',
    'Mueble TV Flotante Veta 210cm', '210 × 42 × 42 cm', 'Roble Natural FSC®', '#b89758',
    22, 'edges', 'Canteado OK', 'bg-primary-container text-white', '23 Oct',
    'https://lh3.googleusercontent.com/aida-public/AB6AXuDS2I_wgaHl_fV9sgxALXv029NTxCTRbAMdzHw7lG5fNKZnkbtLy-ef-4kG6WOrvcXlitxKjLDaG3T0J30t-jpP6nRI0nI9fevSF8bodHsSYKHtd-5mEUz2t9jP8yrfHUaeJRiLFC4-2fhshdI63AH8Zri42NR-2pOZhXxmOdrzRgVjrt_5NuwLckF6YDfMOMTVEXbt-uihs7gDACQ-Hfb0ws2c-1KZ6XrmffdH1AVjNC91sMAWgnypYQ',
    NULL, NULL, NULL, 0, NULL, 'soon'
),
(
    'ord-881', 'ORD-2025-0881', 'Mónica Gil', 'Estudio Creativo',
    'Escritorio Studio Pro 180cm', '180 × 75 × 75 cm', 'Tzalam & Grafito', '#7C472A',
    18, 'assembly', 'En Encolado', 'bg-primary text-white', '21 Oct',
    'https://lh3.googleusercontent.com/aida-public/AB6AXuDHIpgx7kBvrkRKNPHJ3d7z_k6kPzZHVQRC8ULBslGIDZ8FUquPvAAmMnfXJV2s4M3m4es14iKdMIJw6cOO-5W-4pBUM2rTAQrW6EZgVKriE-HhuYPgJ6C_RuC6ICMkxyxYDOp2dUnLUlEajTuKCFRI6QZHMBuJvNNTjtaqnCGOVYVKHZx-fAQGaMiO4osDJ39TxPVtbgHDAgeDtfr8DrSD0SS29pOW2PsVTtxbXLTCVsRLVy13pUIIRA',
    NULL, NULL, NULL, 0, NULL, 'soon'
),
(
    'ord-879', 'ORD-2025-0879', 'Carlos Benítez', 'Residencial Gran Vía',
    'Librero Modular Nexus 240cm', '240 × 210 × 38 cm', 'Nogal Americano', '#583E26',
    36, 'finish', 'Aceitado Rubio', 'bg-amber-600 text-white', '20 Oct',
    'https://lh3.googleusercontent.com/aida-public/AB6AXuAjMTuZQxC8P6UoPPgzDKSLRXoZAJq8NIMwUCTaR5oZEtK8mm1V5XamAs0HBtKA5d7Ns_XtUwKAzOgJx3KH_lq6oWr9_fl8DP0mPb2ITXVF6R089fo9EOfqOqXhN-t-CwpiGtpTwPdCmw394iwWJkJCp0OpK8hams_XBqZxrQwXAItZFYujIjcdkSGlwH5EyDS5QP0adKoIsThNuXOpj4urHZLlp5sGaKgsUHakIK319jjog9-M7gh6HQ',
    NULL, NULL, NULL, 0, NULL, 'urgent'
),
(
    'ord-872', 'ORD-2025-0872', 'Galería Alarte', 'Espacio Expositivo Ruzafa',
    'Aparador Alto Nórdico Mora', '110 × 145 × 44 cm', 'Roble Selección & Cristal', '#D4A373',
    24, 'packaging', 'Control QA OK', 'bg-state-success text-white', '19 Oct',
    'https://lh3.googleusercontent.com/aida-public/AB6AXuBhRKj1fNvfTnkkz__DSCKRK8c0Z_eDmOVyh1afyq6R5SOW3x44yRlWtBP6PO8YuPv7MGNcmMP3rs5vVd-B0ENb6Bolf--CeMuT-Ql5NHViJFACk2kax5-HC091-psqymRFoGXMDhjfz1AcobTXbfP1nYCxHuqsLwdDQEibTpoeIpGN4L72dgdMJV4mwqRBDdOEL7GUy0BMLaQHCgn0DtiyhUgeJZ6PJmIspQeTCOKDVa6E3rJnkKWzhQ',
    NULL, NULL, NULL, 0, NULL, 'normal'
);
