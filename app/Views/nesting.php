<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
    <!-- Breadcrumb & Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-6 border-b border-border-subtle">
        <div>
            <div class="flex items-center gap-2 text-xs font-mono text-text-muted uppercase tracking-wider mb-1">
                <a href="/" class="hover:text-primary transition-colors">Inicio</a>
                <span>/</span>
                <span class="text-primary font-bold">Despiece CNC & Nesting</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-primary tracking-tight font-mono flex items-center gap-3">
                <span class="material-symbols-outlined text-secondary-container text-3xl">grid_view</span>
                Optimizador de Corte & Nesting Industrial
            </h1>
            <p class="text-xs sm:text-sm text-text-muted mt-1 max-w-2xl">
                Algoritmo bidimensional Guillotine Bin-Packing para optimización de tablero estándar (2440 × 1220 mm). Generación automática de planos y coordenadas CNC.
            </p>
        </div>

        <!-- Export Actions -->
        <div class="flex flex-wrap items-center gap-2.5">
            <button onclick="downloadCutlistCSV()" class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl border border-border-subtle bg-surface-card hover:bg-surface-container text-primary text-xs font-bold transition-all shadow-xs">
                <span class="material-symbols-outlined text-[18px] text-secondary-container">table_view</span>
                <span>Exportar CSV Escuadradora</span>
            </button>
            <button onclick="downloadDXFNotice()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary hover:bg-primary-variant text-white text-xs font-bold transition-all shadow-md">
                <span class="material-symbols-outlined text-[18px] text-secondary">precision_manufacturing</span>
                <span>Descargar DXF / G-Code</span>
            </button>
            <button onclick="window.print()" class="p-2.5 rounded-xl border border-border-subtle bg-surface-card hover:bg-surface-container text-text-muted hover:text-primary transition-all" title="Imprimir Etiquetas Taller">
                <span class="material-symbols-outlined text-[20px]">print</span>
            </button>
        </div>
    </div>

    <!-- KPI Metric Cards -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 sm:gap-4 my-6">
        <div class="bg-surface-card p-4 rounded-2xl border border-border-subtle shadow-xs">
            <div class="flex items-center justify-between text-text-muted mb-1">
                <span class="text-[11px] font-mono uppercase tracking-wider font-semibold">Aprovechamiento</span>
                <span class="material-symbols-outlined text-state-success text-[18px]">verified</span>
            </div>
            <div class="text-2xl font-extrabold text-state-success font-mono">89.4%</div>
            <p class="text-[11px] text-text-muted mt-0.5">Merma residual: 10.6%</p>
        </div>

        <div class="bg-surface-card p-4 rounded-2xl border border-border-subtle shadow-xs">
            <div class="flex items-center justify-between text-text-muted mb-1">
                <span class="text-[11px] font-mono uppercase tracking-wider font-semibold">Piezas Totales</span>
                <span class="material-symbols-outlined text-secondary-container text-[18px]">view_quilt</span>
            </div>
            <div class="text-2xl font-extrabold text-primary font-mono"><?= $totalPieces ?> <span class="text-xs text-text-muted font-normal">piezas</span></div>
            <p class="text-[11px] text-text-muted mt-0.5">8 referencias técnicas</p>
        </div>

        <div class="bg-surface-card p-4 rounded-2xl border border-border-subtle shadow-xs">
            <div class="flex items-center justify-between text-text-muted mb-1">
                <span class="text-[11px] font-mono uppercase tracking-wider font-semibold">Superficie Neta</span>
                <span class="material-symbols-outlined text-primary text-[18px]">square_foot</span>
            </div>
            <div class="text-2xl font-extrabold text-primary font-mono"><?= number_format($totalAreaM2, 2) ?> <span class="text-xs text-text-muted font-normal">m²</span></div>
            <p class="text-[11px] text-text-muted mt-0.5">Madera real útil</p>
        </div>

        <div class="bg-surface-card p-4 rounded-2xl border border-border-subtle shadow-xs">
            <div class="flex items-center justify-between text-text-muted mb-1">
                <span class="text-[11px] font-mono uppercase tracking-wider font-semibold">Tableros 2440×1220</span>
                <span class="material-symbols-outlined text-secondary-container text-[18px]">layers</span>
            </div>
            <div class="text-2xl font-extrabold text-primary font-mono">2 <span class="text-xs text-text-muted font-normal">tableros</span></div>
            <p class="text-[11px] text-text-muted mt-0.5">FSC® 100% Sostenible</p>
        </div>

        <div class="bg-surface-card p-4 rounded-2xl border border-border-subtle shadow-xs col-span-2 md:col-span-1">
            <div class="flex items-center justify-between text-text-muted mb-1">
                <span class="text-[11px] font-mono uppercase tracking-wider font-semibold">Ancho Sierra (Kerf)</span>
                <span class="material-symbols-outlined text-text-muted text-[18px]">content_cut</span>
            </div>
            <div class="text-2xl font-extrabold text-primary font-mono">3.2 <span class="text-xs text-text-muted font-normal">mm</span></div>
            <p class="text-[11px] text-text-muted mt-0.5">Fresa helicoidal CNC</p>
        </div>
    </div>

    <!-- Nesting Visual Board Layout -->
    <div class="bg-surface-card rounded-2xl border border-border-subtle shadow-xs p-5 mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-border-subtle mb-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[18px]">crop_free</span>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-primary font-mono uppercase tracking-wider">
                        Mapa Gráfico del Tablero Primario (Tablero A — Rechapado Roble 19mm)
                    </h2>
                    <p class="text-xs text-text-muted">Formato estándar 2440 × 1220 mm • Espesor calibrado 19mm • Sentido veta horizontal</p>
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                <button class="px-3 py-1.5 rounded-lg text-xs font-mono font-bold bg-primary text-white shadow-xs">
                    Tablero 1 (Estructural)
                </button>
                <button class="px-3 py-1.5 rounded-lg text-xs font-mono font-semibold bg-surface-container text-text-muted hover:text-primary transition-colors">
                    Tablero 2 (Frentes Macizos)
                </button>
            </div>
        </div>

        <!-- SVG Visual Diagram of Sheet Nesting -->
        <div class="w-full overflow-x-auto pb-2">
            <div class="min-w-[850px] bg-stone-900 rounded-xl p-4 shadow-inner relative font-mono text-white select-none">
                <!-- Outer Board Dimension Marks -->
                <div class="flex justify-between text-[11px] text-stone-400 mb-2 px-1">
                    <span>(0, 0) Escuadra Origen CNC</span>
                    <span>Longitud X: 2440 mm</span>
                    <span>(2440, 0)</span>
                </div>

                <!-- Board Canvas Container -->
                <div class="relative w-full h-[380px] bg-stone-800 rounded-lg border-2 border-stone-700 overflow-hidden shadow-2xl flex flex-col justify-between" style="background-image: radial-gradient(#333 1px, transparent 1px); background-size: 20px 20px;">
                    
                    <!-- Nested Parts visually placed according to 2D packing -->
                    <!-- Part P-02: Base Inferior (1764 x 432) -->
                    <div class="absolute border border-blue-400/80 bg-blue-600/30 rounded-xs flex flex-col justify-between p-2 hover:bg-blue-600/50 transition-colors group cursor-pointer"
                         style="left: 0.5%; top: 2%; width: 72%; height: 35%;"
                         title="P-02: Base Inferior Estructural (1764 × 432 mm)">
                        <div class="flex items-center justify-between text-[11px] font-bold text-blue-200">
                            <span>P-02 • Base Inferior</span>
                            <span class="text-[9px] bg-blue-900/80 px-1.5 py-0.5 rounded text-blue-200">1764 × 432 mm</span>
                        </div>
                        <div class="text-[10px] text-blue-300/80 flex items-center justify-between">
                            <span>Veta ➔ Longitudinal</span>
                            <span class="material-symbols-outlined text-[14px]">tune</span>
                        </div>
                    </div>

                    <!-- Part P-08: Trasera Oculta (1774 x 663) represented in lower slot -->
                    <div class="absolute border border-emerald-400/80 bg-emerald-600/30 rounded-xs flex flex-col justify-between p-2 hover:bg-emerald-600/50 transition-colors group cursor-pointer"
                         style="left: 0.5%; top: 40%; width: 72%; height: 55%;"
                         title="P-08: Trasera Oculta Ventilada (1774 × 663 mm)">
                        <div class="flex items-center justify-between text-[11px] font-bold text-emerald-200">
                            <span>P-08 • Trasera Oculta</span>
                            <span class="text-[9px] bg-emerald-900/80 px-1.5 py-0.5 rounded text-emerald-200">1774 × 663 mm</span>
                        </div>
                        <div class="text-[10px] text-emerald-300/80 flex items-center justify-between">
                            <span>Pasacables CNC R20</span>
                            <span class="material-symbols-outlined text-[14px]">radio_button_checked</span>
                        </div>
                    </div>

                    <!-- Right Column: Costados y Baldas -->
                    <!-- Part P-03: Costado Izq (672 x 432) -->
                    <div class="absolute border border-amber-400/80 bg-amber-600/30 rounded-xs flex flex-col justify-between p-1.5 hover:bg-amber-600/50 transition-colors cursor-pointer"
                         style="left: 74%; top: 2%; width: 25%; height: 32%;"
                         title="P-03: Costado Izquierdo (672 × 432 mm)">
                        <div class="text-[10px] font-bold text-amber-200 flex justify-between">
                            <span>P-03 • Costado Izq</span>
                            <span class="text-[8px]">672×432</span>
                        </div>
                        <span class="text-[9px] text-amber-300/70">Inglete 45°</span>
                    </div>

                    <!-- Part P-04: Costado Der (672 x 432) -->
                    <div class="absolute border border-amber-400/80 bg-amber-600/30 rounded-xs flex flex-col justify-between p-1.5 hover:bg-amber-600/50 transition-colors cursor-pointer"
                         style="left: 74%; top: 36%; width: 25%; height: 32%;"
                         title="P-04: Costado Derecho (672 × 432 mm)">
                        <div class="text-[10px] font-bold text-amber-200 flex justify-between">
                            <span>P-04 • Costado Der</span>
                            <span class="text-[8px]">672×432</span>
                        </div>
                        <span class="text-[9px] text-amber-300/70">Inglete 45°</span>
                    </div>

                    <!-- Remanente / Merma -->
                    <div class="absolute border border-dashed border-red-500/40 bg-red-950/20 rounded-xs flex items-center justify-center p-2 text-center"
                         style="left: 74%; top: 70%; width: 25%; height: 26%;">
                        <div class="text-[10px] text-red-400/70 font-mono">
                            <span class="material-symbols-outlined text-[16px] block mb-0.5">delete_outline</span>
                            Merma Reutilizable (560 × 310 mm)
                        </div>
                    </div>

                </div>

                <!-- Footer Board Dimension Marks -->
                <div class="flex justify-between text-[11px] text-stone-400 mt-2 px-1">
                    <span>(0, 1220)</span>
                    <span>Ancho Y: 1220 mm • Espesor: 19 mm</span>
                    <span>(2440, 1220)</span>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-between text-xs text-text-muted mt-3 pt-3 border-t border-border-subtle gap-2">
            <div class="flex items-center gap-4">
                <span class="inline-flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-blue-500/50 border border-blue-400 inline-block"></span> Base & Estructura</span>
                <span class="inline-flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-amber-500/50 border border-amber-400 inline-block"></span> Costados Inglete</span>
                <span class="inline-flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-emerald-500/50 border border-emerald-400 inline-block"></span> Trasera Fondo</span>
                <span class="inline-flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-red-900/30 border border-dashed border-red-500/50 inline-block"></span> Merma Reciclable</span>
            </div>
            <div class="font-mono text-primary font-semibold">
                Fresa: Ø3.2mm • Avance: 14 m/min • RPM: 18.000
            </div>
        </div>
    </div>

    <!-- Cutlist Specification Table -->
    <div class="bg-surface-card rounded-2xl border border-border-subtle shadow-xs overflow-hidden">
        <div class="p-5 border-b border-border-subtle flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-base font-bold text-primary font-mono uppercase tracking-wider flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary-container text-xl">receipt_long</span>
                    Lista Maestra de Despiece Técnico (BOM)
                </h2>
                <p class="text-xs text-text-muted mt-0.5">Tolerancia de escuadrado ±0.15mm • Clasificación dimensional en milímetros</p>
            </div>

            <div class="flex items-center gap-2">
                <span class="text-xs font-mono text-text-muted">Filtrar:</span>
                <select id="filterMaterial" onchange="filterCutlist(this.value)" class="text-xs font-semibold rounded-lg border border-border-subtle bg-surface-container px-3 py-1.5 text-primary focus:outline-none">
                    <option value="all">Todos los materiales (<?= count($parts) ?>)</option>
                    <option value="macizo">Roble Macizo</option>
                    <option value="rechapado">Rechapado 19mm</option>
                    <option value="mdf">MDF Lacado</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-surface-container text-primary font-mono uppercase tracking-wider border-b border-border-subtle">
                        <th class="py-3.5 px-4 font-bold text-[11px]">ID</th>
                        <th class="py-3.5 px-4 font-bold text-[11px]">Pieza / Componente</th>
                        <th class="py-3.5 px-4 font-bold text-[11px]">Material</th>
                        <th class="py-3.5 px-4 font-bold text-[11px] text-center">Cant.</th>
                        <th class="py-3.5 px-4 font-bold text-[11px] text-right">Largo (mm)</th>
                        <th class="py-3.5 px-4 font-bold text-[11px] text-right">Ancho (mm)</th>
                        <th class="py-3.5 px-4 font-bold text-[11px] text-right">Espesor</th>
                        <th class="py-3.5 px-4 font-bold text-[11px]">Sentido Veta</th>
                        <th class="py-3.5 px-4 font-bold text-[11px]">Canteado</th>
                        <th class="py-3.5 px-4 font-bold text-[11px]">Mecanizado CNC</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-subtle font-sans" id="cutlistTableBody">
                    <?php foreach ($parts as $p): ?>
                        <tr class="hover:bg-surface-container-low transition-colors cutlist-row" data-material="<?= strtolower($p['material']) ?>">
                            <td class="py-3.5 px-4 font-mono font-bold text-primary">
                                <span class="px-2 py-0.5 rounded bg-surface-container border border-border-subtle">
                                    <?= htmlspecialchars($p['id']) ?>
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-primary text-[13px]"><?= htmlspecialchars($p['label']) ?></div>
                                <div class="text-[11px] text-text-muted"><?= htmlspecialchars($p['sublabel']) ?></div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-semibold border <?= $p['materialColor'] ?>">
                                    <?= htmlspecialchars($p['materialBadge']) ?>
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center font-mono font-extrabold text-primary text-sm">
                                <?= $p['quantity'] ?>
                            </td>
                            <td class="py-3.5 px-4 text-right font-mono font-semibold text-primary">
                                <?= number_format($p['length'], 0) ?>
                            </td>
                            <td class="py-3.5 px-4 text-right font-mono font-semibold text-primary">
                                <?= number_format($p['width'], 0) ?>
                            </td>
                            <td class="py-3.5 px-4 text-right font-mono text-text-muted">
                                <?= $p['thickness'] ?> mm
                            </td>
                            <td class="py-3.5 px-4">
                                <?php if ($p['grain'] === 'vertical'): ?>
                                    <span class="inline-flex items-center gap-1 font-mono text-[11px] text-primary">
                                        <span class="material-symbols-outlined text-[14px] text-secondary-container">swap_vert</span>
                                        Vertical
                                    </span>
                                <?php elseif ($p['grain'] === 'longitudinal'): ?>
                                    <span class="inline-flex items-center gap-1 font-mono text-[11px] text-primary">
                                        <span class="material-symbols-outlined text-[14px] text-secondary-container">swap_horiz</span>
                                        Longitudinal
                                    </span>
                                <?php else: ?>
                                    <span class="text-text-muted font-mono text-[11px]">— No aplica</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-[11px] text-text-muted">
                                <?= htmlspecialchars($p['edges']) ?>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-1.5">
                                    <?php if (!empty($p['isCritical'])): ?>
                                        <span class="w-2 h-2 rounded-full bg-secondary-container animate-pulse" title="Mecanizado de precisión crítica"></span>
                                    <?php endif; ?>
                                    <span class="text-[11px] font-mono text-primary font-medium">
                                        <?= htmlspecialchars($p['cncOperation']) ?>
                                    </span>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="p-4 bg-surface-container border-t border-border-subtle flex flex-col sm:flex-row sm:items-center justify-between text-xs text-text-muted gap-2">
            <span>Certificación PEFC / FSC® Cadena de Custodia C-049821</span>
            <span class="font-mono">Generado por CarpinterSoft CNC Engine v4.2 • PHP 8.2</span>
        </div>
    </div>
</div>

<!-- Modal / Notice Script -->
<script>
function downloadDXFNotice() {
    alert("Iniciando compilación G-Code & archivo CAD DXF...\n\nArchivo generado: 'Horizon-Aparador-CNC-Maestro.dxf'\nCapas: CORTE_EXTERIOR, TALADROS_SISTEMA32, FRESADO_GOLA, CAZOLETAS_BLUM.\n\nEl archivo se descargará en su navegador.");
    
    // Simulate downloading a dummy DXF file
    const element = document.createElement('a');
    const dxfContent = "0\nSECTION\n2\nHEADER\n9\n$ACADVER\n1\nAC1015\n0\nENDSEC\n0\nSECTION\n2\nENTITIES\n0\nTEXT\n1\nCARPINTERSOFT-CNC-EXPORT-OK\n0\nENDSEC\n0\nEOF";
    element.setAttribute('href', 'data:application/dxf;charset=utf-8,' + encodeURIComponent(dxfContent));
    element.setAttribute('download', 'Horizon-Aparador-CNC-Maestro.dxf');
    element.style.display = 'none';
    document.body.appendChild(element);
    element.click();
    document.body.removeChild(element);
}

function downloadCutlistCSV() {
    let csv = "ID;Pieza;Material;Cantidad;Largo_mm;Ancho_mm;Espesor_mm;Veta;Canteado;Mecanizado_CNC\n";
    <?php foreach ($parts as $p): ?>
    csv += "<?= $p['id'] ?>;\"<?= addslashes($p['label']) ?>\";\"<?= addslashes($p['material']) ?>\";<?= $p['quantity'] ?>;<?= $p['length'] ?>;<?= $p['width'] ?>;<?= $p['thickness'] ?>;\"<?= $p['grain'] ?>\";\"<?= addslashes($p['edges']) ?>\";\"<?= addslashes($p['cncOperation']) ?>\"\n";
    <?php endforeach; ?>
    
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement("a");
    link.setAttribute("href", url);
    link.setAttribute("download", "Despiece-CNC-Horizon-Aparador.csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function filterCutlist(val) {
    const rows = document.querySelectorAll('.cutlist-row');
    rows.forEach(r => {
        const mat = r.getAttribute('data-material') || '';
        if (val === 'all') {
            r.style.display = '';
        } else if (val === 'macizo' && mat.includes('macizo')) {
            r.style.display = '';
        } else if (val === 'rechapado' && mat.includes('rechapado')) {
            r.style.display = '';
        } else if (val === 'mdf' && mat.includes('mdf')) {
            r.style.display = '';
        } else {
            r.style.display = 'none';
        }
    });
}
</script>
