<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Furniture;

class CarpinterAIService {
    /**
     * Procesa la consulta del usuario y genera una respuesta experta con contexto de taller.
     */
    public static function chat(string $message, array $context = []): array {
        $msgClean = trim($message);
        $msgLower = mb_strtolower($msgClean, 'UTF-8');

        // 1. Detección de intención: Solicitud de diseño / configuración de mueble
        $furnitureIntent = self::detectFurnitureIntent($msgLower);
        if ($furnitureIntent !== null) {
            return $furnitureIntent;
        }

        // 2. Consulta de maderas y durabilidad (Humedad, Exterior, Resistencia)
        $woodAdvice = self::detectWoodAdvice($msgLower);
        if ($woodAdvice !== null) {
            return $woodAdvice;
        }

        // 3. Consulta de mecanizado CNC, fresado y herramientas
        $cncAdvice = self::detectCncAdvice($msgLower);
        if ($cncAdvice !== null) {
            return $cncAdvice;
        }

        // 4. Consulta de pandeo de baldas y resistencia estructural (Sagulator)
        $structuralAdvice = self::detectStructuralAdvice($msgLower);
        if ($structuralAdvice !== null) {
            return $structuralAdvice;
        }

        // 5. Consulta sobre el tablero Kanban y estado de producción
        $kanbanAdvice = self::detectKanbanAdvice($msgLower);
        if ($kanbanAdvice !== null) {
            return $kanbanAdvice;
        }

        // 6. Respuesta conversacional general de Maestro Carpintero Inteligente
        return self::generalExpertResponse($msgClean);
    }

    /**
     * Analiza si el usuario quiere diseñar o dimensionar un mueble con lenguaje natural
     */
    private static function detectFurnitureIntent(string $text): ?array {
        $hasModelKeyword = (
            str_contains($text, 'aparador') ||
            str_contains($text, 'mesa') ||
            str_contains($text, 'librero') ||
            str_contains($text, 'estanter') ||
            str_contains($text, 'tv') ||
            str_contains($text, 'escritorio') ||
            str_contains($text, 'despacho') ||
            str_contains($text, 'consola') ||
            str_contains($text, 'mora') ||
            str_contains($text, 'mueble') ||
            str_contains($text, 'diseñ') ||
            str_contains($text, 'quiero') ||
            str_contains($text, 'configur')
        );

        if (!$hasModelKeyword) {
            return null;
        }

        // Identificar modelo
        $modelId = 'horizon';
        $modelName = 'Aparador Alistonado Horizon';

        if (str_contains($text, 'mesa') || str_contains($text, 'comedor')) {
            $modelId = 'fusion-table';
            $modelName = 'Mesa de Comedor Fusión';
        } elseif (str_contains($text, 'librero') || str_contains($text, 'estanter')) {
            $modelId = 'nexus-bookshelf';
            $modelName = 'Librero Modular Nexus';
        } elseif (str_contains($text, 'tv') || str_contains($text, 'televis')) {
            $modelId = 'veta-tv';
            $modelName = 'Mueble TV Flotante Veta';
        } elseif (str_contains($text, 'escritorio') || str_contains($text, 'despacho') || str_contains($text, 'estudio')) {
            $modelId = 'studio-desk';
            $modelName = 'Escritorio Studio Pro';
        } elseif (str_contains($text, 'mora') || str_contains($text, 'vitrina') || str_contains($text, 'alto')) {
            $modelId = 'mora-highboard';
            $modelName = 'Aparador Alto Nórdico Mora';
        } elseif (str_contains($text, 'consola') || str_contains($text, 'recibidor') || str_contains($text, 'aura')) {
            $modelId = 'aura-console';
            $modelName = 'Consola Recibidor Aura';
        }

        // Buscar el modelo en el catálogo para conocer límites
        $allModels = Furniture::getAll();
        $matchedModel = null;
        foreach ($allModels as $m) {
            if ($m['id'] === $modelId) {
                $matchedModel = $m;
                break;
            }
        }
        if (!$matchedModel) $matchedModel = $allModels[0];

        // Extraer dimensiones numéricas del mensaje
        // Buscar ancho (e.g. "200 cm", "2.10 m", "240 de ancho", "200x90")
        $width = (float)($matchedModel['baseWidth'] ?? 180);
        $height = (float)($matchedModel['baseHeight'] ?? 85);
        $depth = (float)($matchedModel['baseDepth'] ?? 45);

        // Buscar patrón tipo "220x80x45" o "220 x 80"
        if (preg_match('/(\d{2,3})\s*(?:x|×|por)\s*(\d{2,3})(?:\s*(?:x|×|por)\s*(\d{2,3}))?/i', $text, $matches)) {
            if (!empty($matches[1])) $width = (float)$matches[1];
            if (!empty($matches[2])) $height = (float)$matches[2];
            if (!empty($matches[3])) $depth = (float)$matches[3];
        } else {
            // Buscar metros sueltos e.g. "2 metros" o "2.20 metros"
            if (preg_match('/(\d+(?:[.,]\d+)?)\s*(?:m|metros|metro)\b/i', $text, $mMetros)) {
                $valCm = (float)str_replace(',', '.', $mMetros[1]) * 100;
                if ($valCm >= 50 && $valCm <= 350) {
                    $width = $valCm;
                }
            } elseif (preg_match('/(\d{2,3})\s*(?:cm|centimetros)?\s*(?:de ancho|ancho)?/i', $text, $mCm)) {
                $valCm = (float)$mCm[1];
                if ($valCm >= 50 && $valCm <= 350) {
                    $width = $valCm;
                }
            }
        }

        // Asegurar que las dimensiones respeten los límites del modelo
        $width = max($matchedModel['minWidth'], min($matchedModel['maxWidth'], $width));
        $height = max($matchedModel['minHeight'], min($matchedModel['maxHeight'], $height));
        $depth = max($matchedModel['minDepth'], min($matchedModel['maxDepth'], $depth));

        // Módulos
        $modules = 3;
        if (str_contains($text, '2 puertas') || str_contains($text, '2 modulos') || str_contains($text, 'dos')) $modules = 2;
        if (str_contains($text, '4 puertas') || str_contains($text, '4 modulos') || str_contains($text, 'cuatro')) $modules = 4;
        if (str_contains($text, '5 puertas') || str_contains($text, 'cinco')) $modules = 5;

        // Tipo de madera
        $woodId = 'roble';
        $woodName = 'Roble Nórdico Miel';
        if (str_contains($text, 'nogal')) {
            $woodId = 'nogal';
            $woodName = 'Nogal Americano';
        } elseif (str_contains($text, 'fresno')) {
            $woodId = 'fresno';
            $woodName = 'Fresno Natural';
        } elseif (str_contains($text, 'castaño') || str_contains($text, 'castano')) {
            $woodId = 'castano';
            $woodName = 'Castaño Ibérico';
        } elseif (str_contains($text, 'recuperad') || str_contains($text, 'rustico')) {
            $woodId = 'recuperada';
            $woodName = 'Madera Recuperada';
        } elseif (str_contains($text, 'olivo')) {
            $woodId = 'olivo';
            $woodName = 'Olivo Milenario';
        }

        // Acabado
        $finishType = 'rubio';
        $finishName = 'Aceite Rubio Monocoat® Puro';
        if (str_contains($text, 'mate') || str_contains($text, 'barniz')) {
            $finishType = 'mate';
            $finishName = 'Barniz Poliuretano Mate al Agua';
        } elseif (str_contains($text, 'blanco') || str_contains($text, 'velado')) {
            $finishType = 'blanco';
            $finishName = 'Aceite Blanco Nórdico Monocoat®';
        } elseif (str_contains($text, 'oscuro') || str_contains($text, 'negro') || str_contains($text, 'tinte')) {
            $finishType = 'oscuro';
            $finishName = 'Tinte Wengué Intenso';
        }

        // Patas / Soporte
        $baseType = 'conicas';
        $baseName = 'Patas Cónicas Macizas 15cm';
        if (str_contains($text, 'metal') || str_contains($text, 'acero') || str_contains($text, 'negro')) {
            $baseType = 'metal';
            $baseName = 'Estructura Metálica Negra';
        } elseif (str_contains($text, 'socalo') || str_contains($text, 'zócalo') || str_contains($text, 'plinto')) {
            $baseType = 'socalo';
            $baseName = 'Zócalo de Madera Maciza 6cm';
        } elseif (str_contains($text, 'flotante') || str_contains($text, 'pared') || str_contains($text, 'colgado')) {
            $baseType = 'flotante';
            $baseName = 'Soporte Oculto a Pared (Flotante)';
        }

        // Extras
        $hasLed = (str_contains($text, 'led') || str_contains($text, 'luz') || str_contains($text, 'iluminac'));
        $hasGrommet = (str_contains($text, 'cable') || str_contains($text, 'pasacable') || str_contains($text, 'enchufe'));

        // Calcular cotización algorítmica
        $quote = Furniture::calculateQuotation(
            $modelId,
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

        $customizerUrl = "/customizer?model={$modelId}&w={$width}&h={$height}&d={$depth}&m={$modules}&wood={$woodId}&finish={$finishType}&base={$baseType}&led=" . ($hasLed ? '1' : '0');

        $reply = "He analizado tu solicitud técnica y he configurado la pieza paramétrica para ti:\n\n" .
                 "• **Modelo:** {$modelName}\n" .
                 "• **Medidas:** {$width} cm (Ancho) × {$height} cm (Alto) × {$depth} cm (Fondo)\n" .
                 "• **Madera FSC®:** {$woodName} con tratamiento {$finishName}\n" .
                 "• **Estructura inferior:** {$baseName}\n" .
                 "• **Módulos/Puertas:** {$modules} divisiones con cazoletas Blum® Soft-close\n" .
                 ($hasLed ? "• **Iluminación:** Integración LED perimetral 3000K cálida incluida\n" : "") .
                 ($hasGrommet ? "• **Pasa-cables CNC:** Ranurado ovalado para gestión de cableado\n" : "") .
                 "\n**Presupuesto estimado de fabricación:** €" . number_format($quote['total'] ?? 1280, 0) . " (incluye despiece y nesting CNC).\n\n" .
                 "Puedes cargar este mueble con un clic en el Visor 3D para examinarlo a escala milimétrica o enviarlo directamente a producción.";

        return [
            'type' => 'furniture_config',
            'reply' => $reply,
            'config' => [
                'model_id' => $modelId,
                'model_name' => $modelName,
                'width' => $width,
                'height' => $height,
                'depth' => $depth,
                'modules' => $modules,
                'wood_id' => $woodId,
                'wood_name' => $woodName,
                'finish_type' => $finishType,
                'finish_name' => $finishName,
                'base_type' => $baseType,
                'base_name' => $baseName,
                'has_led' => $hasLed,
                'has_grommet' => $hasGrommet,
                'price' => $quote['total'] ?? 1280,
                'url' => $customizerUrl
            ],
            'suggestions' => [
                'Ver en Personalizador 3D',
                'Cambiar a madera de Nogal',
                'Calcular aprovechamiento en tablero CNC',
                '¿Qué peso soporta esta estructura?'
            ]
        ];
    }

    /**
     * Consultas especializadas en maderas, humedades y aplicaciones
     */
    private static function detectWoodAdvice(string $text): ?array {
        if (str_contains($text, 'baño') || str_contains($text, 'humed') || str_contains($text, 'exterior') || str_contains($text, 'terraza')) {
            return [
                'type' => 'technical_guide',
                'reply' => "### Recomendación Técnica para Zonas Húmedas y Exterior\n\n" .
                           "Para ambientes con humedad relativa superior al 65% o contacto con agua:\n\n" .
                           "1. **Madera Maciza Óptima:** **Castaño Ibérico** o **Roble Europeo Tratado** (Clase de durabilidad natural 2). Ambos contienen taninos naturales con alta resistencia biológica a hongos.\n" .
                           "2. **Tableros Técnicos:** Evita el aglomerado estándar o MDF convencional. Utiliza **Contrachapado de Abedul Fenólico WBP** (Water Boiled Proof) o **MDF Hidrófugo** (color verdoso en masa).\n" .
                           "3. **Cantos y Encolado:** Obligatorio canteado con **adhesivo reactivo PUR** (Poliuretano en caliente a 140°C), impermeable al vapor de agua.\n" .
                           "4. **Acabado de Superficie:** Dos manos de **Aceite Rubio Monocoat® Hybrid Wood Protector** o barniz marino acrílico con filtros UV.",
                'suggestions' => [
                    'Configurar mueble en Castaño',
                    '¿Qué mantenimiento requiere el aceite?',
                    'Ver tolerancias de dilatación'
                ]
            ];
        }

        if (str_contains($text, 'roble') && (str_contains($text, 'dureza') || str_contains($text, 'propied') || str_contains($text, 'diferencia'))) {
            return [
                'type' => 'wood_spec',
                'reply' => "### Ficha Técnica: Roble Europeo (*Quercus robur*)\n\n" .
                           "• **Densidad:** 710 - 750 kg/m³ al 12% de humedad.\n" .
                           "• **Dureza Janka:** 6.000 N (Semidura / Resistente al impacto).\n" .
                           "• **Coeficiente de contracción volumétrica:** 0.45% (Estabilidad dimensional media-alta).\n" .
                           "• **Comportamiento en CNC:** Excelente desahogo de viruta. Se aconseja fresa de carburo de tungsteno (HW) con avance de 12 m/min a 18.000 RPM para evitar quemado por fricción de taninos.",
                'suggestions' => [
                    'Ver modelos con Roble',
                    'Comparar Roble vs Nogal',
                    'Configurar Aparador Horizon'
                ]
            ];
        }

        if (str_contains($text, 'nogal') && (str_contains($text, 'propied') || str_contains($text, 'precio') || str_contains($text, 'acabado'))) {
            return [
                'type' => 'wood_spec',
                'reply' => "### Ficha Técnica: Nogal Americano (*Juglans nigra*)\n\n" .
                           "• **Densidad:** 640 kg/m³ (Madera noble semipesada).\n" .
                           "• **Dureza Janka:** 4.500 N.\n" .
                           "• **Estética:** Tono chocolate profundo con vetas violáceas y nudos cerrados. Madera rey en ebanistería contemporánea.\n" .
                           "• **Acabado Recomendado:** Aceite natural de linaza polimerizado o cera vegetal dura para realzar el contraste y tornasolado de la veta.",
                'suggestions' => [
                    'Diseñar Librero Nexus en Nogal',
                    'Ver precio de Nogal',
                    'Comparar durezas de madera'
                ]
            ];
        }

        return null;
    }

    /**
     * Consultas técnicas de mecanizado CNC
     */
    private static function detectCncAdvice(string $text): ?array {
        if (str_contains($text, 'cnc') || str_contains($text, 'fres') || str_contains($text, 'rpm') || str_contains($text, 'astill') || str_contains($text, 'herramienta')) {
            return [
                'type' => 'cnc_technical',
                'reply' => "### Parámetros de Mecanizado CNC Recomendados en CarpinterSoft\n\n" .
                           "1. **Corte y Seccionado de Tableros (2440×1220 mm):**\n" .
                           "   • **Fresa recomendada:** Fresa de compresión helicoidal Z2+2 de Ø12mm (corte positivo abajo, negativo arriba).\n" .
                           "   • **Velocidad de avance ($V_f$):** 14 - 18 m/min.\n" .
                           "   • **Velocidad de giro ($S$):** 18.000 - 20.000 RPM.\n" .
                           "   • **Paso en profundidad ($A_p$):** En tableros de 19mm, 2 pasadas de 9.5mm o corte directo en máquina con mesa de vacío.\n\n" .
                           "2. **Solución al Astillado de Veta Superior:**\n" .
                           "   • Si el canto superior se astilla, reduce la velocidad de avance un 15% o verifica que la hélice descendente (down-cut) esté actuando en los primeros 3mm de superficie.\n\n" .
                           "3. **Taladrado para Herrajes (Sistema 32 mm):**\n" .
                           "   • Broca de espiga ciega Ø5mm y Ø8mm a 4.500 RPM con ciclo de desahogo de viruta (peck drilling).",
                'suggestions' => [
                    'Ver Despiece CNC y Nesting',
                    'Descargar planos DXF',
                    'Simular corte de tablero'
                ]
            ];
        }

        return null;
    }

    /**
     * Consultas sobre deflexión y resistencia de baldas (Sagulator)
     */
    private static function detectStructuralAdvice(string $text): ?array {
        if (str_contains($text, 'balda') || str_contains($text, 'curv') || str_contains($text, 'peso') || str_contains($text, 'resisten') || str_contains($text, 'pandeo')) {
            return [
                'type' => 'structural_calc',
                'reply' => "### Cálculo de Carga y Deflexión de Baldas (Criterio Sagulator UNE-EN 14072)\n\n" .
                           "Para evitar el pandeo visible (deflexión máxima admisible $L/200$ o 2 mm):\n\n" .
                           "• **Espesor 19 mm (Rechapado Roble / MDF):**\n" .
                           "  - Vano hasta 80 cm: Soporta hasta **35 kg** uniformemente repartidos.\n" .
                           "  - Vano entre 80 cm y 120 cm: Requiere **listón rigidizador frontal de madera maciza (mínimo 30×19 mm)** o montante vertical intermedio para no combarse con enciclopedias o vajilla.\n\n" .
                           "• **Espesor 28 mm (Roble Macizo):**\n" .
                           "  - Soporta hasta **65 kg** por balda en vanos de 100 cm sin flecha apreciable.\n\n" .
                           "¿Deseas que adapte las divisiones interiores en el configurador 3D?",
                'suggestions' => [
                    'Añadir montante central en Visor 3D',
                    'Calcular Librero de 240cm',
                    'Verificar especificaciones BOM'
                ]
            ];
        }

        return null;
    }

    /**
     * Consultas sobre el estado del taller y Kanban
     */
    private static function detectKanbanAdvice(string $text): ?array {
        if (str_contains($text, 'taller') || str_contains($text, 'kanban') || str_contains($text, 'orden') || str_contains($text, 'produccion') || str_contains($text, 'pedido')) {
            return [
                'type' => 'kanban_status',
                'reply' => "### Estado de Producción en Planta de Taller\n\n" .
                           "El taller digital de CarpinterSoft gestiona los pedidos en **6 fases de fabricación industrial**:\n\n" .
                           "1. **Validación DXF / Planos:** Verificación dimensional y exportación G-Code.\n" .
                           "2. **Corte CNC & Nesting:** Fresado y escuadrado en centros de mecanizado.\n" .
                           "3. **Calibrado & Canteado:** Canto PVC/Madera con cola PUR resistente.\n" .
                           "4. **Premontaje & Encolado:** Prensado en banco con herrajes Blum®.\n" .
                           "5. **Lijado & Acabado:** Cabina de pulverización y aceitado Rubio Monocoat®.\n" .
                           "6. **Control de Calidad & Embalaje:** Embalaje reforzado para expedición.\n\n" .
                           "Puedes ver y mover las órdenes en tiempo real con Drag & Drop en el panel Kanban.",
                'suggestions' => [
                    'Abrir Tablero Kanban',
                    'Crear orden de prueba',
                    'Ver métricas de taller'
                ]
            ];
        }

        return null;
    }

    /**
     * Respuesta general asistida por el Maestro Carpintero Inteligente
     */
    private static function generalExpertResponse(string $userMsg): array {
        return [
            'type' => 'general_chat',
            'reply' => "¡Hola! Soy **CarpinterAI**, tu asistente técnico inteligente especializado en ebanistería digital, mecanizado CNC y diseño de mobiliario paramétrico.\n\n" .
                       "Puedo ayudarte con:\n" .
                       "• **Diseñar muebles a medida con lenguaje natural:** Por ejemplo: *\"Quiero un aparador de 220 cm en roble con 3 puertas y patas cónicas\"* y lo configuraré de inmediato en el visor 3D.\n" .
                       "• **Selección técnica de maderas:** Densidades, resistencia a la humedad, durabilidad y coeficientes FSC®.\n" .
                       "• **Ingeniería de corte y CNC:** Avances, velocidades de fresa, cálculo de tablero 2440×1220 mm y despiece.\n" .
                       "• **Estructuras y herrajes:** Cálculo de pandeo de baldas y compatibilidad con sistemas Blum®.\n\n" .
                       "¿Qué pieza o proyecto deseas desarrollar hoy?",
            'suggestions' => [
                'Quiero un aparador de 200cm en roble',
                'Mesa de comedor de 240x100 en nogal',
                '¿Qué madera recomiendas para baño?',
                'Optimizar tablero de corte CNC'
            ]
        ];
    }
}
