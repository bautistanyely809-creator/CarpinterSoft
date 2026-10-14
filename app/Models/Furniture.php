<?php
namespace App\Models;

class Furniture {
    public static function getWoodOptions(): array {
        return [
            [
                'id' => 'roble',
                'name' => 'Roble Nórdico Miel',
                'subtitle' => 'Veta rectilínea satinada suave',
                'colorHex' => '#D4A373',
                'extraPrice' => 0,
                'origin' => 'Bosques gestionados FSC® Francia',
                'hardness' => '680 kg/m³ (Alta)'
            ],
            [
                'id' => 'nogal',
                'name' => 'Nogal Americano Macizo',
                'subtitle' => 'Cálido y tonalidad cacao noble',
                'colorHex' => '#583E26',
                'extraPrice' => 120,
                'origin' => 'Norteamérica (Juglans nigra)',
                'hardness' => '640 kg/m³ (Media-alta)'
            ],
            [
                'id' => 'tzalam',
                'name' => 'Tzalam Veta Profunda',
                'subtitle' => 'Madera tropical de extrema dureza',
                'colorHex' => '#7C472A',
                'extraPrice' => 190,
                'origin' => 'Península de Yucatán (Lysiloma latisiliquum)',
                'hardness' => '780 kg/m³ (Muy alta)'
            ],
            [
                'id' => 'cedro',
                'name' => 'Cedro Rojo Aromático',
                'subtitle' => 'Tonalidad terracota cálida',
                'colorHex' => '#A0522D',
                'extraPrice' => 140,
                'origin' => 'Península Ibérica',
                'hardness' => '560 kg/m³ (Media)'
            ],
            [
                'id' => 'recuperada',
                'name' => 'Madera Recuperada',
                'subtitle' => 'Pátina histórica sostenible',
                'colorHex' => '#BDB76B',
                'extraPrice' => 80,
                'origin' => 'Vigas recuperadas de molinos',
                'hardness' => '620 kg/m³ (Media)'
            ],
            [
                'id' => 'grafito',
                'name' => 'Laca Mate Grafito + Roble',
                'subtitle' => 'Contraste industrial arquitectónico',
                'colorHex' => '#2B2D2F',
                'extraPrice' => 95,
                'origin' => 'MDF Ultra Hidrófugo + Chapa Natural',
                'hardness' => '720 kg/m³ (Alta)'
            ]
        ];
    }

    public static function getAll(): array {
        return [
            [
                'id' => 'horizon',
                'name' => 'Aparador Alistonado Horizon',
                'category' => 'Salón y Estar',
                'subCategory' => 'Aparadores & Credenzas',
                'badge' => '100% Paramétrico 3D',
                'badgeSecondary' => 'Top Ventas 2025',
                'description' => 'Frontal curvo con palillería continua de roble macizo, tiradores embutidos artesanalmente y 4 módulos interiores configurables.',
                'baseWidth' => 180,
                'baseHeight' => 85,
                'baseDepth' => 45,
                'minWidth' => 120,
                'maxWidth' => 260,
                'minHeight' => 65,
                'maxHeight' => 105,
                'minDepth' => 35,
                'maxDepth' => 60,
                'basePrice' => 1280,
                'currency' => 'USD',
                'deliveryDays' => 18,
                'image' => 'https://lh3.googleusercontent.com/aida/AEtjO1UzbsboOsiRfAYPl4GbZf70Euh-xYGI8_7H81iuMpOXc88JV2EIGKE-oObUS4N9t05yVJ5Ngc8J3yuwcKeOTCdgBa9CaNfQm0PXna3_utxK54I5RLUvwbi97qXxEHgHxM49zfieRuinImvZqO7GwhXOupurg9_RGCjrYbKixiNS2ktLLVLr385w0M_GO_aRuDsB5Bub6IUqWbKkxGsgijywURyAyql0P5k8s4wOidEnRIqBKl45GFAExF32',
                'woodOptions' => ['roble', 'nogal', 'tzalam', 'cedro', 'recuperada', 'grafito'],
                'features' => ['Palillería continua', 'Herrajes Blum® soft-close', 'Bisel inglete 45°', 'Patas cónicas o zócalo'],
                'dimensionsLabel' => '180 × 85 × 45 cm',
                'productionNote' => 'Incluye herrajes Blum® con amortiguación hidráulica y acabado aceite Rubio Monocoat®.'
            ],
            [
                'id' => 'fusion-table',
                'name' => 'Mesa de Comedor Fusión',
                'category' => 'Comedor',
                'subCategory' => 'Mesas de Autor',
                'badge' => 'Madera Maciza 40mm',
                'badgeSecondary' => 'Corte Biselado',
                'description' => 'Tapa de roble macizo seleccionada alistonada en anchos continuos. Borde suizo o recto según configuración en visor 3D.',
                'baseWidth' => 200,
                'baseHeight' => 75,
                'baseDepth' => 95,
                'minWidth' => 160,
                'maxWidth' => 300,
                'minHeight' => 72,
                'maxHeight' => 78,
                'minDepth' => 80,
                'maxDepth' => 110,
                'basePrice' => 1140,
                'currency' => 'USD',
                'deliveryDays' => 15,
                'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBJP6OTwtQP3g1HEcECFiglaE-LKpxqvYsp9e1QuiYJbri9ifmWhCYRYK8AOb0aJZ64IaOk6bOpsXCanKsqtraPQTlDfCWHFE-XPLW4zpObryoIxacOL-7mq53hm-pN65WiHRSRP8MXcWbEYBBF7uGW5xWWPwU51zHmEfET_-kbAPgGnTXM6QWBfDjZElGQ6zvFTuOpvA9Cc80kUKyI5dWRybpcd14RkloJLJzYwc-hX0YIcDRBe-mbCQ',
                'woodOptions' => ['roble', 'nogal', 'recuperada'],
                'features' => ['Tablero alistonado 40mm', 'Borde suizo 45°', 'Pies con nivelación micrométrica', 'Capacidad 8-12 comensales'],
                'dimensionsLabel' => '200 × 75 × 95 cm',
                'capacityOrUse' => 'Para 8 - 12 comensales',
                'productionNote' => 'Protección de poro abierto antiralladuras y pies con nivelación micrométrica.'
            ],
            [
                'id' => 'nexus-bookshelf',
                'name' => 'Librero Modular Nexus',
                'category' => 'Estudio & Home Office',
                'subCategory' => 'Librerías & Estanterías',
                'badge' => 'Asistido por IA',
                'badgeSecondary' => 'Balda regulable',
                'description' => 'Estructura reticular de nogal o roble con módulos opcionales cerrados y ranuras fresadas para retroiluminación LED continua.',
                'baseWidth' => 220,
                'baseHeight' => 210,
                'baseDepth' => 38,
                'minWidth' => 120,
                'maxWidth' => 280,
                'minHeight' => 160,
                'maxHeight' => 240,
                'minDepth' => 30,
                'maxDepth' => 45,
                'basePrice' => 1690,
                'currency' => 'USD',
                'deliveryDays' => 20,
                'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAjMTuZQxC8P6UoPPgzDKSLRXoZAJq8NIMwUCTaR5oZEtK8mm1V5XamAs0HBtKA5d7Ns_XtUwKAzOgJx3KH_lq6oWr9_fl8DP0mPb2ITXVF6R089fo9EOfqOqXhN-t-CwpiGtpTwPdCmw394iwWJkJCp0OpK8hams_XBqZxrQwXAItZFYujIjcdkSGlwH5EyDS5QP0adKoIsThNuXOpj4urHZLlp5sGaKgsUHakIK319jjog9-M7gh6HQ',
                'woodOptions' => ['nogal', 'roble', 'grafito'],
                'features' => ['Módulos asimétricos', 'Cremallera de baldas oculta', 'Anclaje invisible a muro', 'Ranuras LED integradas'],
                'dimensionsLabel' => '220 × 210 × 38 cm',
                'capacityOrUse' => 'Pared completa',
                'productionNote' => 'Mecanizado de cremallera oculta y anclaje invisible a muro portante certificado.'
            ],
            [
                'id' => 'veta-tv',
                'name' => 'Mueble TV Flotante Veta',
                'category' => 'Salón y Estar',
                'subCategory' => 'Muebles TV',
                'badge' => 'Flotante Reforzado',
                'badgeSecondary' => 'Carga hasta 90kg',
                'description' => 'Diseñado con doble fondo ventilado para aparatos multimedia, tapas abatibles amortiguadas y pasacables ocultos directos a pared.',
                'baseWidth' => 210,
                'baseHeight' => 42,
                'baseDepth' => 42,
                'minWidth' => 140,
                'maxWidth' => 260,
                'minHeight' => 35,
                'maxHeight' => 55,
                'minDepth' => 35,
                'maxDepth' => 50,
                'basePrice' => 890,
                'currency' => 'USD',
                'deliveryDays' => 14,
                'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDS2I_wgaHl_fV9sgxALXv029NTxCTRbAMdzHw7lG5fNKZnkbtLy-ef-4kG6WOrvcXlitxKjLDaG3T0J30t-jpP6nRI0nI9fevSF8bodHsSYKHtd-5mEUz2t9jP8yrfHUaeJRiLFC4-2fhshdI63AH8Zri42NR-2pOZhXxmOdrzRgVjrt_5NuwLckF6YDfMOMTVEXbt-uihs7gDACQ-Hfb0ws2c-1KZ6XrmffdH1AVjNC91sMAWgnypYQ',
                'woodOptions' => ['roble', 'nogal', 'grafito'],
                'features' => ['Doble fondo ventilado', 'Colgadores Camar® heavy-duty', 'Gola 45° oculta', 'Pasacables encastrado'],
                'dimensionsLabel' => '210 × 42 × 42 cm',
                'capacityOrUse' => 'Ajuste milimétrico',
                'productionNote' => 'Herrajes colgadores Camar® de seguridad pesada y gola de apertura biselada a 45°.'
            ],
            [
                'id' => 'studio-desk',
                'name' => 'Escritorio Studio Pro',
                'category' => 'Estudio & Home Office',
                'subCategory' => 'Escritorios',
                'badge' => 'Home Office Élite',
                'badgeSecondary' => 'Cajón oculto',
                'description' => 'Tapa con canaleta de electrificación fresada en madera, pasacables magnético y cajonera suspendida con cierre suave Blum®.',
                'baseWidth' => 160,
                'baseHeight' => 75,
                'baseDepth' => 70,
                'minWidth' => 120,
                'maxWidth' => 200,
                'minHeight' => 72,
                'maxHeight' => 78,
                'minDepth' => 60,
                'maxDepth' => 80,
                'basePrice' => 950,
                'currency' => 'USD',
                'deliveryDays' => 16,
                'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDHIpgx7kBvrkRKNPHJ3d7z_k6kPzZHVQRC8ULBslGIDZ8FUquPvAAmMnfXJV2s4M3m4es14iKdMIJw6cOO-5W-4pBUM2rTAQrW6EZgVKriE-HhuYPgJ6C_RuC6ICMkxyxYDOp2dUnLUlEajTuKCFRI6QZHMBuJvNNTjtaqnCGOVYVKHZx-fAQGaMiO4osDJ39TxPVtbgHDAgeDtfr8DrSD0SS29pOW2PsVTtxbXLTCVsRLVy13pUIIRA',
                'woodOptions' => ['tzalam', 'roble', 'grafito'],
                'features' => ['Canaleta cables fresada', 'Tapa pasacables enrasada', 'Cajón tip-on', 'Alma de acero de refuerzo'],
                'dimensionsLabel' => '160 × 75 × 70 cm',
                'capacityOrUse' => 'Personalizable',
                'productionNote' => 'Estructura ensamblada con espiga ciega tradicional reforzada con alma de acero.'
            ],
            [
                'id' => 'mora-highboard',
                'name' => 'Aparador Alto Nórdico Mora',
                'category' => 'Salón y Estar',
                'subCategory' => 'Vitrinas & Alacenas',
                'badge' => 'Nuevo 2025',
                'badgeSecondary' => 'Roble Selección',
                'description' => 'Vitrina y alacena con puertas alistonadas en la parte inferior y baldas de cristal templado bronce con iluminación LED vertical.',
                'baseWidth' => 110,
                'baseHeight' => 145,
                'baseDepth' => 44,
                'minWidth' => 90,
                'maxWidth' => 140,
                'minHeight' => 120,
                'maxHeight' => 165,
                'minDepth' => 38,
                'maxDepth' => 50,
                'basePrice' => 1190,
                'currency' => 'USD',
                'deliveryDays' => 18,
                'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBhRKj1fNvfTnkkz__DSCKRK8c0Z_eDmOVyh1afyq6R5SOW3x44yRlWtBP6PO8YuPv7MGNcmMP3rs5vVd-B0ENb6Bolf--CeMuT-Ql5NHViJFACk2kax5-HC091-psqymRFoGXMDhjfz1AcobTXbfP1nYCxHuqsLwdDQEibTpoeIpGN4L72dgdMJV4mwqRBDdOEL7GUy0BMLaQHCgn0DtiyhUgeJZ6PJmIspQeTCOKDVa6E3rJnkKWzhQ',
                'woodOptions' => ['roble', 'cedro', 'recuperada'],
                'features' => ['Cristal templado bronce', 'Bisagras Tiomos 3D', 'LED perimetral cálido', 'Palillería inferior'],
                'dimensionsLabel' => '110 × 145 × 44 cm',
                'capacityOrUse' => 'Vertical',
                'productionNote' => 'Bisagras invisibles Tiomos® con regulación tridimensional en 6 posiciones.'
            ],
            [
                'id' => 'aura-console',
                'name' => 'Consola Recibidor Minimal Aura',
                'category' => 'Recibidor & Pasillos',
                'subCategory' => 'Consolas',
                'badge' => 'Perfil Delgado (28cm)',
                'badgeSecondary' => 'Sin patas estorbo',
                'description' => 'Diseño esbelto ideal para pasillos estrechos. Esquinas mecanizadas en curva suave R-40 y gaveta oculta para llaves y accesorios.',
                'baseWidth' => 120,
                'baseHeight' => 82,
                'baseDepth' => 28,
                'minWidth' => 90,
                'maxWidth' => 160,
                'minHeight' => 75,
                'maxHeight' => 90,
                'minDepth' => 24,
                'maxDepth' => 35,
                'basePrice' => 540,
                'currency' => 'USD',
                'deliveryDays' => 12,
                'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAzarcbB2r2kIumWPdEYFp490oRWC4R7d-XJ6w-ll__O5VzuPSWjyDWj1mB5goRNwHyM_vMaEaAMneIrlh06j0lAcYh6EnSPeixleV21l75Zkd6mILC1b1_eDmn2R-g4yYolqYQRGcLZ91cMpdxDrY3SHsOnozjxInRYh3jAWqaFswtvk6gvmY6XZps_bi9605ERMCnOolu6EHnKK6SzYV320DzmmQ0tOYEh06XhOFVuZfW4i9ZgBm3Og',
                'woodOptions' => ['nogal', 'roble'],
                'features' => ['Radio curva R-40', 'Cajón ciego magnético', 'Guías Hettich', 'Anclaje antivuelco'],
                'dimensionsLabel' => '120 × 82 × 28 cm',
                'capacityOrUse' => 'Espacios reducidos',
                'productionNote' => 'Guías de extracción total con tip-on magnético integrado.'
            ]
        ];
    }

    public static function find(string $id): ?array {
        foreach (self::getAll() as $model) {
            if ($model['id'] === $id) {
                return $model;
            }
        }
        return self::getAll()[0];
    }

    public static function calculateQuotation(
        string $modelId,
        float $width,
        float $height,
        float $depth,
        int $modules,
        string $woodId,
        string $finishType,
        string $baseType,
        bool $hasLed,
        bool $hasGrommet
    ): array {
        $model = self::find($modelId);
        $woods = self::getWoodOptions();
        $selectedWood = null;
        foreach ($woods as $w) {
            if ($w['id'] === $woodId) {
                $selectedWood = $w;
                break;
            }
        }
        if (!$selectedWood) $selectedWood = $woods[0];

        $baseVol = ($model['baseWidth'] * $model['baseHeight'] * $model['baseDepth']) * 0.000001;
        $currentVol = ($width * $height * $depth) * 0.000001;
        $volRatio = $currentVol / max(0.08, $baseVol);

        $baseWoodCost = round($model['basePrice'] * 0.60 * pow($volRatio, 0.75)) + $selectedWood['extraPrice'];
        $baseCNCCost = round($model['basePrice'] * 0.22 + ($width * 0.35));
        $hardwareCost = round($model['basePrice'] * 0.10 + (($modules - 2) * 25));
        $finishCost = $finishType === 'rubio' ? 110 : 80;
        $baseCost = $baseType === 'zocalo' ? 45 : ($baseType === 'flotante' ? 80 : 0);
        $extras = ($hasLed ? 65 : 0) + ($hasGrommet ? 30 : 0) + $baseCost;

        $total = $baseWoodCost + $baseCNCCost + $hardwareCost + $finishCost + $extras;

        return [
            'volume' => round($currentVol * 0.26, 2),
            'woodCost' => $baseWoodCost,
            'cncCost' => $baseCNCCost,
            'hardwareCost' => $hardwareCost,
            'finishCost' => $finishCost,
            'extrasCost' => $extras,
            'total' => (int)$total,
            'model' => $model,
            'wood' => $selectedWood
        ];
    }
}
