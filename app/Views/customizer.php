<div class="flex flex-col w-full bg-canvas-cream min-h-screen">
    <!-- Sub-Header Técnico & Herramientas Studio 3D -->
    <section class="w-full bg-surface-card px-4 md:px-8 py-3 shadow-xs z-30 border-b border-border-subtle">
        <div class="max-w-[1920px] mx-auto flex flex-col xl:flex-row xl:items-center justify-between gap-3">
            <!-- Breadcrumb + Selector de Pieza -->
            <div class="flex flex-col gap-1 min-w-0">
                <div class="flex items-center gap-2 font-mono text-xs text-text-muted">
                    <a href="/catalog" class="hover:text-primary transition-colors">Catálogo</a>
                    <span>/</span>
                    <span><?= htmlspecialchars($model['category']) ?></span>
                    <span>/</span>
                    <span class="text-primary font-semibold truncate"><?= htmlspecialchars($model['name']) ?> (V4.2 PHP)</span>
                </div>
                <div class="flex flex-wrap items-center gap-2 md:gap-3">
                    <h1 class="text-lg md:text-xl text-primary font-bold tracking-tight">
                        <?= htmlspecialchars($model['name']) ?>
                    </h1>

                    <!-- Selector Rápido de Modelo -->
                    <select
                        id="modelSelector"
                        onchange="location.href='/customizer?model=' + this.value"
                        class="font-mono text-xs bg-surface-container hover:bg-surface-container-high text-primary px-3 py-1.5 rounded-lg border border-border-subtle cursor-pointer focus:outline-none focus:ring-1 focus:ring-primary font-semibold"
                        title="Cambiar modelo a personalizar"
                    >
                        <?php foreach ($allModels as $m): ?>
                            <option value="<?= htmlspecialchars($m['id']) ?>" <?= $m['id'] === $model['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($m['name']) ?> (<?= htmlspecialchars($m['category']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-primary/10 text-primary font-mono text-[11px] font-semibold">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        100% Paramétrico 3D
                    </span>
                    <span class="hidden sm:inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-surface-container font-mono text-[11px] text-text-muted font-medium">
                        <span class="material-symbols-outlined text-[14px] text-secondary">precision_manufacturing</span>
                        Calibrado CNC Taller Central
                    </span>
                </div>
            </div>

            <!-- Global Action Buttons -->
            <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                <button onclick="window.history.back()" class="p-2 text-text-primary hover:bg-surface-container rounded-lg transition-colors border border-border-subtle" title="Volver">
                    <span class="material-symbols-outlined text-[18px]">undo</span>
                </button>
                <button onclick="document.getElementById('arModal').classList.remove('hidden')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-surface-container hover:bg-surface-container-high text-primary font-semibold text-xs md:text-sm transition-all border border-border-subtle">
                    <span class="material-symbols-outlined text-[18px] text-secondary">view_in_ar</span>
                    <span>AR / QR Móvil</span>
                </button>
                <a href="/nesting" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-surface-container hover:bg-surface-container-high text-primary font-semibold text-xs md:text-sm transition-all border border-border-subtle">
                    <span class="material-symbols-outlined text-[18px] text-text-muted">download_for_offline</span>
                    <span>Lista Corte CNC</span>
                </a>
                <button onclick="navigator.clipboard?.writeText(window.location.href); alert('¡Enlace de configuración paramétrica copiado!');" class="p-2 rounded-lg bg-surface-container hover:bg-surface-container-high text-primary transition-colors border border-border-subtle" title="Compartir configuración">
                    <span class="material-symbols-outlined text-[18px]">share</span>
                </button>
            </div>
        </div>
    </section>

    <!-- WORKSPACE DE 3 COLUMNAS -->
    <div class="w-full max-w-[1920px] mx-auto p-4 md:p-6 grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- 1. PANEL IZQUIERDO: Configuración Paramétrica (3 Cols) -->
        <aside class="lg:col-span-3 w-full bg-surface-card rounded-2xl p-4 md:p-5 shadow-sm flex flex-col gap-4 border border-border-subtle order-2 lg:order-1">
            <div class="flex items-center justify-between pb-1 border-b border-border-subtle">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px] text-secondary-container">tune</span>
                    <h2 class="text-base text-primary font-bold">Parámetros de Taller</h2>
                </div>
                <span class="font-mono text-[11px] text-text-muted bg-surface-container px-2 py-0.5 rounded">
                    PHP V4.2
                </span>
            </div>

            <!-- Tab Switcher -->
            <div class="grid grid-cols-3 gap-1 p-1 bg-surface-container rounded-lg font-mono text-xs text-center">
                <button onclick="switchTab('dims')" id="tabBtnDims" class="py-1.5 px-1 rounded transition-all bg-surface-card text-primary font-bold shadow-xs">Medidas</button>
                <button onclick="switchTab('wood')" id="tabBtnWood" class="py-1.5 px-1 rounded transition-all text-text-muted hover:text-primary">Maderas</button>
                <button onclick="switchTab('hardware')" id="tabBtnHardware" class="py-1.5 px-1 rounded transition-all text-text-muted hover:text-primary">Herrajes</button>
            </div>

            <!-- TAB 1: DIMENSIONES -->
            <div id="tabDims" class="flex flex-col gap-4">
                <!-- Ancho Total (W) -->
                <div class="flex flex-col gap-1.5 bg-canvas-cream p-3 rounded-xl border border-border-subtle">
                    <div class="flex items-center justify-between">
                        <label class="font-mono text-xs text-primary font-semibold uppercase flex items-center gap-1">
                            <span>Ancho (W)</span>
                            <span class="text-text-muted font-normal">(<?= $model['minWidth'] ?> - <?= $model['maxWidth'] ?> cm)</span>
                        </label>
                        <div class="flex items-center gap-1 bg-surface-card px-2 py-0.5 rounded shadow-xs border border-border-subtle font-mono text-sm font-bold text-primary">
                            <span id="labelWidth"><?= $width ?></span> cm
                        </div>
                    </div>
                    <input type="range" id="inputWidth" min="<?= $model['minWidth'] ?>" max="<?= $model['maxWidth'] ?>" step="1" value="<?= $width ?>" class="w-full accent-secondary-container cursor-pointer h-1.5 bg-surface-container-high rounded-lg" oninput="updateWidth(this.value)">
                    <div class="flex items-center justify-between pt-1">
                        <div class="flex items-center gap-1 font-mono text-xs">
                            <button onclick="adjustParam('width', -5)" class="px-2 py-0.5 rounded bg-surface-card hover:bg-surface-container text-primary font-semibold shadow-xs border border-border-subtle">-5cm</button>
                            <button onclick="adjustParam('width', 5)" class="px-2 py-0.5 rounded bg-surface-card hover:bg-surface-container text-primary font-semibold shadow-xs border border-border-subtle">+5cm</button>
                        </div>
                        <span class="text-[10px] font-mono text-text-muted">Paso: 1 cm</span>
                    </div>
                </div>

                <!-- Altura Total (H) -->
                <div class="flex flex-col gap-1.5 bg-canvas-cream p-3 rounded-xl border border-border-subtle">
                    <div class="flex items-center justify-between">
                        <label class="font-mono text-xs text-primary font-semibold uppercase flex items-center gap-1">
                            <span>Altura (H)</span>
                            <span class="text-text-muted font-normal">(<?= $model['minHeight'] ?> - <?= $model['maxHeight'] ?> cm)</span>
                        </label>
                        <div class="flex items-center gap-1 bg-surface-card px-2 py-0.5 rounded shadow-xs border border-border-subtle font-mono text-sm font-bold text-primary">
                            <span id="labelHeight"><?= $height ?></span> cm
                        </div>
                    </div>
                    <input type="range" id="inputHeight" min="<?= $model['minHeight'] ?>" max="<?= $model['maxHeight'] ?>" step="1" value="<?= $height ?>" class="w-full accent-secondary-container cursor-pointer h-1.5 bg-surface-container-high rounded-lg" oninput="updateHeight(this.value)">
                </div>

                <!-- Profundidad (D) -->
                <div class="flex flex-col gap-1.5 bg-canvas-cream p-3 rounded-xl border border-border-subtle">
                    <div class="flex items-center justify-between">
                        <label class="font-mono text-xs text-primary font-semibold uppercase flex items-center gap-1">
                            <span>Fondo (D)</span>
                            <span class="text-text-muted font-normal">(<?= $model['minDepth'] ?> - <?= $model['maxDepth'] ?> cm)</span>
                        </label>
                        <div class="flex items-center gap-1 bg-surface-card px-2 py-0.5 rounded shadow-xs border border-border-subtle font-mono text-sm font-bold text-primary">
                            <span id="labelDepth"><?= $depth ?></span> cm
                        </div>
                    </div>
                    <input type="range" id="inputDepth" min="<?= $model['minDepth'] ?>" max="<?= $model['maxDepth'] ?>" step="1" value="<?= $depth ?>" class="w-full accent-secondary-container cursor-pointer h-1.5 bg-surface-container-high rounded-lg" oninput="updateDepth(this.value)">
                </div>

                <!-- Distribución & Módulos -->
                <div class="flex flex-col gap-2">
                    <span class="font-mono text-xs text-primary font-semibold uppercase">
                        <?= $model['id'] === 'fusion-table' ? 'Capacidad de Comensales' : ($model['id'] === 'nexus-bookshelf' ? 'Niveles de Baldas' : 'Módulos & Puertas') ?>
                    </span>
                    <div class="grid grid-cols-3 gap-2">
                        <?php foreach ([2, 3, 4] as $m): ?>
                            <button
                                onclick="updateModules(<?= $m ?>)"
                                id="btnMod<?= $m ?>"
                                class="p-2 rounded-xl text-center flex flex-col items-center gap-0.5 transition-all cursor-pointer <?= $modules === $m ? 'bg-primary text-white shadow-sm' : 'bg-surface-container hover:bg-surface-container-high text-primary' ?>"
                            >
                                <span class="text-base font-bold font-mono"><?= $m ?></span>
                                <span class="text-[10px] opacity-80 font-mono">
                                    <?= $model['id'] === 'fusion-table' ? ($m == 2 ? '6 Com.' : ($m == 3 ? '8 Com.' : '10-12')) : ($m == 2 ? 'Doble' : ($m == 3 ? '3 Cuerpos' : '4 Cuerpos')) ?>
                                </span>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Base Estructural -->
                <div class="flex flex-col gap-2">
                    <span class="font-mono text-xs text-primary font-semibold uppercase">Base Estructural</span>
                    <div class="flex flex-col gap-1.5 text-xs">
                        <label class="flex items-center justify-between p-2.5 rounded-xl bg-canvas-cream cursor-pointer hover:bg-surface-container transition-colors border border-border-subtle">
                            <span class="flex items-center gap-2 text-primary font-medium">
                                <input type="radio" name="base_type" value="conicas" <?= $baseType === 'conicas' ? 'checked' : '' ?> onchange="updateBase('conicas')" class="accent-secondary-container">
                                Patas Cónicas 15cm Macizas
                            </span>
                            <span class="text-emerald-600 font-bold font-mono text-[11px]">Incluido</span>
                        </label>
                        <label class="flex items-center justify-between p-2.5 rounded-xl bg-canvas-cream cursor-pointer hover:bg-surface-container transition-colors border border-border-subtle">
                            <span class="flex items-center gap-2 text-primary font-medium">
                                <input type="radio" name="base_type" value="zocalo" <?= $baseType === 'zocalo' ? 'checked' : '' ?> onchange="updateBase('zocalo')" class="accent-secondary-container">
                                Zócalo Retranqueado
                            </span>
                            <span class="text-text-muted font-bold font-mono text-[11px]">+45 USD</span>
                        </label>
                        <label class="flex items-center justify-between p-2.5 rounded-xl bg-canvas-cream cursor-pointer hover:bg-surface-container transition-colors border border-border-subtle">
                            <span class="flex items-center gap-2 text-primary font-medium">
                                <input type="radio" name="base_type" value="flotante" <?= $baseType === 'flotante' ? 'checked' : '' ?> onchange="updateBase('flotante')" class="accent-secondary-container">
                                Suspensión a Pared
                            </span>
                            <span class="text-text-muted font-bold font-mono text-[11px]">+80 USD</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- TAB 2: MADERAS -->
            <div id="tabWood" class="hidden flex-col gap-3">
                <span class="font-mono text-xs text-primary font-semibold uppercase">Especie Maciza FSC®</span>
                <div class="grid grid-cols-1 gap-2">
                    <?php foreach ($woods as $wood): ?>
                        <button
                            onclick="updateWood('<?= $wood['id'] ?>', '<?= $wood['name'] ?>', '<?= $wood['colorHex'] ?>')"
                            id="btnWood_<?= $wood['id'] ?>"
                            class="flex items-center justify-between p-2.5 rounded-xl bg-canvas-cream text-left transition-all border <?= $woodId === $wood['id'] ? 'ring-2 ring-secondary-container border-transparent bg-surface-container' : 'border-border-subtle hover:bg-surface-container' ?>"
                        >
                            <div class="flex items-center gap-2.5">
                                <span class="w-8 h-8 rounded-full shadow-inner shrink-0" style="background-color: <?= $wood['colorHex'] ?>"></span>
                                <div>
                                    <h4 class="text-xs font-bold text-primary"><?= htmlspecialchars($wood['name']) ?></h4>
                                    <p class="text-[10px] text-text-muted"><?= htmlspecialchars($wood['subtitle']) ?></p>
                                </div>
                            </div>
                            <span class="font-mono text-[11px] font-bold text-primary">
                                <?= $wood['extraPrice'] > 0 ? "+{$wood['extraPrice']} USD" : 'Base' ?>
                            </span>
                        </button>
                    <?php endforeach; ?>
                </div>

                <div class="pt-2 border-t border-border-subtle">
                    <span class="font-mono text-xs text-primary font-semibold uppercase block mb-1.5">Acabado Protector</span>
                    <label class="flex items-center gap-2 p-2 rounded-lg bg-canvas-cream text-xs cursor-pointer border border-border-subtle mb-1">
                        <input type="radio" name="finish" value="rubio" <?= $finishType === 'rubio' ? 'checked' : '' ?> onchange="updateFinish('rubio')" class="accent-secondary-container">
                        <span>Aceite Rubio Monocoat® (0 VOCs)</span>
                    </label>
                    <label class="flex items-center gap-2 p-2 rounded-lg bg-canvas-cream text-xs cursor-pointer border border-border-subtle">
                        <input type="radio" name="finish" value="barniz" <?= $finishType === 'barniz' ? 'checked' : '' ?> onchange="updateFinish('barniz')" class="accent-secondary-container">
                        <span>Barniz Poliuretano Acrílico Mate</span>
                    </label>
                </div>
            </div>

            <!-- TAB 3: HERRAJES -->
            <div id="tabHardware" class="hidden flex-col gap-3">
                <span class="font-mono text-xs text-primary font-semibold uppercase">Mecanismos Blum®</span>
                <div class="p-3 bg-canvas-cream rounded-xl border border-border-subtle text-xs space-y-1">
                    <p class="font-bold text-primary flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px] text-secondary">verified</span>
                        <span>Blum® Clip-Top Blumotion</span>
                    </p>
                    <p class="text-[11px] text-text-muted">Bisagras invisibles con amortiguación hidráulica soft-close probadas para 100.000 aperturas.</p>
                </div>

                <label class="flex items-center justify-between p-3 rounded-xl bg-canvas-cream cursor-pointer border border-border-subtle text-xs">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="chkLed" <?= $hasLed ? 'checked' : '' ?> onchange="updateLed(this.checked)" class="accent-secondary-container w-4 h-4">
                        <div>
                            <span class="font-bold text-primary block">Tira LED Cálida 3000K</span>
                            <span class="text-[10px] text-text-muted">Sensor magnético oculto de apertura</span>
                        </div>
                    </div>
                    <span class="font-mono font-bold text-primary">+65 USD</span>
                </label>

                <label class="flex items-center justify-between p-3 rounded-xl bg-canvas-cream cursor-pointer border border-border-subtle text-xs">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="chkGrommet" <?= $hasGrommet ? 'checked' : '' ?> onchange="updateGrommet(this.checked)" class="accent-secondary-container w-4 h-4">
                        <div>
                            <span class="font-bold text-primary block">Pasacables Fresado Macizo</span>
                            <span class="text-[10px] text-text-muted">Canaleta CNC para electrificación</span>
                        </div>
                    </div>
                    <span class="font-mono font-bold text-primary">+30 USD</span>
                </label>
            </div>
        </aside>

        <!-- 2. PANEL CENTRAL: Viewport 3D Canvas Interactivo (6 Cols) -->
        <main class="lg:col-span-6 flex flex-col gap-3 order-1 lg:order-2">
            <!-- Canvas Container -->
            <div class="relative w-full h-[520px] md:h-[620px] bg-[#EAE7DC] rounded-2xl overflow-hidden shadow-lg border border-border-subtle group cursor-grab active:cursor-grabbing select-none" id="viewportContainer">
                <canvas id="viewportCanvas" width="1000" height="650" class="w-full h-full object-cover"></canvas>

                <!-- Floating Cotas (Dimension Labels Overlay) -->
                <div class="absolute inset-0 pointer-events-none p-5 flex flex-col justify-between">
                    <!-- Ancho Top Cota -->
                    <div class="w-full flex items-center justify-center gap-2">
                        <div class="h-[1px] flex-1 bg-primary/40 relative">
                            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-1.5 bg-primary rotate-45"></span>
                        </div>
                        <div class="px-2.5 py-1 rounded-lg bg-surface-card/90 backdrop-blur-md shadow-xs pointer-events-auto flex items-center gap-1 font-mono text-xs font-bold text-primary">
                            <span class="material-symbols-outlined text-[14px] text-secondary">straighten</span>
                            <span id="cotaWidth">W: <?= $width ?> cm</span>
                        </div>
                        <div class="h-[1px] flex-1 bg-primary/40 relative">
                            <span class="absolute right-0 top-1/2 -translate-y-1/2 w-1.5 h-1.5 bg-primary rotate-45"></span>
                        </div>
                    </div>

                    <!-- Middle Height & Depth Cotas -->
                    <div class="w-full flex items-center justify-between">
                        <div class="px-2 py-0.5 rounded-lg bg-surface-card/90 backdrop-blur-md shadow-xs pointer-events-auto [writing-mode:vertical-rl] rotate-180 font-mono text-xs font-bold text-primary">
                            <span id="cotaHeight">H: <?= $height ?> cm</span>
                        </div>
                        <div class="px-2 py-0.5 rounded-lg bg-surface-card/90 backdrop-blur-md shadow-xs pointer-events-auto [writing-mode:vertical-rl] font-mono text-xs font-bold text-primary">
                            <span id="cotaDepth">D: <?= $depth ?> cm</span>
                        </div>
                    </div>

                    <!-- Bottom Telemetry Bar -->
                    <div class="w-full flex items-end justify-between pointer-events-auto gap-2">
                        <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-surface-card/90 backdrop-blur-md shadow-xs font-mono text-[11px] text-primary">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                            <span class="font-bold">WebGL Canvas 3D</span>
                            <span>•</span>
                            <span id="cotaModel"><?= htmlspecialchars($model['name']) ?></span>
                            <span>•</span>
                            <span class="text-emerald-600 font-bold">±0.2mm CNC</span>
                        </div>

                        <button onclick="toggleDoors()" id="btnDoors" class="px-3 py-1.5 rounded-lg bg-surface-card/95 backdrop-blur-md hover:bg-surface-container text-primary font-mono text-xs font-bold shadow-md transition-all flex items-center gap-1.5 cursor-pointer border border-border-subtle">
                            <span class="material-symbols-outlined text-[16px]">door_front</span>
                            <span id="lblDoors">Abrir Puertas</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Viewport Toolbar (Cameras, Lighting, Wireframe) -->
            <div class="flex flex-wrap items-center justify-between gap-2 p-2 bg-surface-card rounded-xl border border-border-subtle shadow-xs text-xs font-mono">
                <div class="flex items-center gap-1">
                    <span class="text-text-muted px-2">Cámara:</span>
                    <button onclick="setCamera('persp')" class="px-2.5 py-1 rounded bg-surface-container hover:bg-surface-container-high text-primary font-semibold">Persp</button>
                    <button onclick="setCamera('front')" class="px-2.5 py-1 rounded bg-surface-container hover:bg-surface-container-high text-primary font-semibold">Frente</button>
                    <button onclick="setCamera('top')" class="px-2.5 py-1 rounded bg-surface-container hover:bg-surface-container-high text-primary font-semibold">Planta</button>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="toggleWireframe()" id="btnWireframe" class="px-2.5 py-1 rounded bg-surface-container hover:bg-surface-container-high text-primary font-semibold flex items-center gap-1">
                        <span class="material-symbols-outlined text-[15px]">grid_4x4</span>
                        <span>Malla CAD</span>
                    </button>
                    <button onclick="toggleAutoRotate()" id="btnRotate" class="px-2.5 py-1 rounded bg-surface-container hover:bg-surface-container-high text-primary font-semibold flex items-center gap-1">
                        <span class="material-symbols-outlined text-[15px]">360</span>
                        <span>Rotar</span>
                    </button>
                </div>
            </div>
        </main>

        <!-- 3. PANEL DERECHO: Desglose de Cotización & Fabricación (3 Cols) -->
        <aside class="lg:col-span-3 w-full bg-surface-card rounded-2xl p-5 shadow-sm border border-border-subtle flex flex-col gap-4 order-3">
            <div class="flex items-center justify-between pb-2 border-b border-border-subtle">
                <div>
                    <span class="text-[10px] font-mono uppercase tracking-wider text-text-muted block">Motor Algorítmico CNC</span>
                    <h3 class="text-base font-bold text-primary">Cotización en Firme</h3>
                </div>
                <span class="text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded text-[11px] font-mono font-bold border border-emerald-200">
                    Calculado
                </span>
            </div>

            <!-- Total Hero Price -->
            <div class="p-4 rounded-xl bg-canvas-cream border border-border-subtle text-center space-y-1">
                <span class="text-xs font-mono text-text-muted uppercase">Presupuesto Directo de Taller</span>
                <div class="text-3xl md:text-4xl font-extrabold text-primary font-mono tracking-tight">
                    $<span id="quoteTotal"><?= $quote['total'] ?></span> <span class="text-xs font-normal text-text-muted">USD</span>
                </div>
                <p class="text-[11px] text-text-muted font-mono">IVA incluido • Plazo estimado: <?= $model['deliveryDays'] ?> días</p>
            </div>

            <!-- Itemized Costs Breakdown -->
            <div class="space-y-2 text-xs font-mono">
                <div class="flex justify-between py-1 border-b border-border-subtle/60 text-text-muted">
                    <span>Madera Maciza FSC® (<span id="quoteVol"><?= $quote['volume'] ?></span> m³):</span>
                    <span class="font-bold text-primary">$<span id="costWood"><?= $quote['woodCost'] ?></span></span>
                </div>
                <div class="flex justify-between py-1 border-b border-border-subtle/60 text-text-muted">
                    <span>Mecanizado & Nesting CNC:</span>
                    <span class="font-bold text-primary">$<span id="costCnc"><?= $quote['cncCost'] ?></span></span>
                </div>
                <div class="flex justify-between py-1 border-b border-border-subtle/60 text-text-muted">
                    <span>Herrajes Blum® & Ensamble:</span>
                    <span class="font-bold text-primary">$<span id="costHardware"><?= $quote['hardwareCost'] ?></span></span>
                </div>
                <div class="flex justify-between py-1 border-b border-border-subtle/60 text-text-muted">
                    <span>Acabado Monocoat® (0 VOCs):</span>
                    <span class="font-bold text-primary">$<span id="costFinish"><?= $quote['finishCost'] ?></span></span>
                </div>
                <div class="flex justify-between py-1 border-b border-border-subtle/60 text-text-muted">
                    <span>Opcionales (LED / Base / Pasacables):</span>
                    <span class="font-bold text-primary">$<span id="costExtras"><?= $quote['extrasCost'] ?></span></span>
                </div>
            </div>

            <!-- Guarantee Box -->
            <div class="p-3 bg-surface-container rounded-xl text-xs space-y-1 border border-border-subtle">
                <p class="font-bold text-primary flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px] text-secondary">verified</span>
                    <span>Fabricación Certificada 10 Años</span>
                </p>
                <p class="text-[11px] text-text-muted">Ingeniería estructural comprobada para cargas de hasta 120 kg continuas.</p>
            </div>

            <!-- Add to Cart CTA -->
            <button onclick="addToCart()" class="w-full py-3.5 rounded-xl bg-secondary-container hover:bg-accent-hover text-white text-sm font-bold shadow-md shadow-secondary-container/25 transition-transform active:scale-95 flex items-center justify-center gap-2 cursor-pointer">
                <span class="material-symbols-outlined text-[20px]">shopping_bag</span>
                <span>Añadir al Presupuesto</span>
            </button>
            <a href="/nesting" class="w-full py-2.5 rounded-xl bg-surface-container hover:bg-surface-container-high text-primary text-xs font-bold text-center block transition-colors border border-border-subtle">
                Exportar Plano DXF de Corte
            </a>
        </aside>
    </div>
</div>

<!-- MODAL AR / QR MOBILE -->
<div id="arModal" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-surface-card rounded-2xl shadow-2xl max-w-sm w-full p-6 text-center space-y-4 border border-border-subtle">
        <div class="flex justify-between items-center pb-2 border-b border-border-subtle">
            <h3 class="font-bold text-primary text-base">Realidad Aumentada (AR)</h3>
            <button onclick="document.getElementById('arModal').classList.add('hidden')" class="text-text-muted hover:text-primary">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-4 bg-white rounded-xl shadow-inner inline-block border border-border-subtle">
            <!-- Simulated QR code for WebXR AR Quick Look -->
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=https://carpintersoft.local/customizer?model=<?= urlencode($model['id']) ?>" alt="QR AR Móvil" class="w-44 h-44 mx-auto">
        </div>
        <p class="text-xs text-text-muted leading-relaxed">
            Apunta con la cámara de tu smartphone iOS o Android para proyectar este mueble en tu salón a escala 1:1.
        </p>
        <button onclick="document.getElementById('arModal').classList.add('hidden')" class="w-full py-2 rounded-lg bg-surface-container hover:bg-surface-container-high text-primary font-bold text-xs">
            Cerrar
        </button>
    </div>
</div>

<!-- 3D Canvas Controller & Viewport Engine Script -->
<script>
    const CUSTOMIZER_DATA = {
        modelId: "<?= $model['id'] ?>",
        modelName: "<?= addslashes($model['name']) ?>",
        image: "<?= $model['image'] ?>",
        basePrice: <?= $model['basePrice'] ?>,
        baseWidth: <?= $model['baseWidth'] ?>,
        baseHeight: <?= $model['baseHeight'] ?>,
        baseDepth: <?= $model['baseDepth'] ?>,
        width: <?= $width ?>,
        height: <?= $height ?>,
        depth: <?= $depth ?>,
        modules: <?= $modules ?>,
        woodId: "<?= $woodId ?>",
        woodColor: "<?= $quote['wood']['colorHex'] ?>",
        woodName: "<?= addslashes($quote['wood']['name']) ?>",
        finishType: "<?= $finishType ?>",
        baseType: "<?= $baseType ?>",
        hasLed: <?= $hasLed ? 'true' : 'false' ?>,
        hasGrommet: <?= $hasGrommet ? 'true' : 'false' ?>,
        doorsOpen: false,
        wireframe: false,
        autoRotate: false,
        cameraView: 'persp',
        zoom: 1
    };

    function switchTab(tab) {
        document.getElementById('tabDims').classList.toggle('hidden', tab !== 'dims');
        document.getElementById('tabWood').classList.toggle('hidden', tab !== 'wood');
        document.getElementById('tabHardware').classList.toggle('hidden', tab !== 'hardware');

        document.getElementById('tabBtnDims').className = tab === 'dims' ? 'py-1.5 px-1 rounded transition-all bg-surface-card text-primary font-bold shadow-xs' : 'py-1.5 px-1 rounded transition-all text-text-muted hover:text-primary';
        document.getElementById('tabBtnWood').className = tab === 'wood' ? 'py-1.5 px-1 rounded transition-all bg-surface-card text-primary font-bold shadow-xs' : 'py-1.5 px-1 rounded transition-all text-text-muted hover:text-primary';
        document.getElementById('tabBtnHardware').className = tab === 'hardware' ? 'py-1.5 px-1 rounded transition-all bg-surface-card text-primary font-bold shadow-xs' : 'py-1.5 px-1 rounded transition-all text-text-muted hover:text-primary';
    }
</script>
<script src="/js/viewport3d.js"></script>
<script src="/js/customizer.js"></script>
