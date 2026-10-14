<!-- HERO SECTION -->
<section class="relative w-full bg-surface-container-low py-16 md:py-24 border-b border-border-subtle overflow-hidden">
    <!-- Subtle architectural grid background -->
    <div class="absolute inset-0 opacity-[0.03] pointer-events-none" style="background-image: radial-gradient(#103E4A 1px, transparent 1px); background-size: 24px 24px;"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <!-- Left Column: Copy & CTAs -->
            <div class="lg:col-span-7 space-y-6">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-surface-card shadow-sm border border-border-subtle text-xs font-mono font-semibold text-primary">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Fabricación Directa de Taller • Motor Paramétrico PHP 8.2</span>
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-primary tracking-tight leading-[1.1]">
                    Ebanistería contemporánea con precisión <span class="text-secondary-container underline decoration-secondary/40">milimétrica</span>.
                </h1>

                <p class="text-base sm:text-lg text-text-muted max-w-2xl leading-relaxed">
                    Personaliza muebles de diseño en nuestro visor 3D interactivo en tiempo real. Obtén cotización instantánea basada en volumen y envía el despiece optimizado a CNC Homag y Biesse Rover.
                </p>

                <!-- Action Buttons -->
                <div class="pt-2 flex flex-wrap items-center gap-4">
                    <a href="/customizer" class="h-12 px-6 rounded-xl bg-secondary-container text-white font-bold text-sm hover:bg-accent-hover transition-transform active:scale-95 shadow-lg shadow-secondary-container/25 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">view_in_ar</span>
                        <span>Abrir Personalizador 3D</span>
                    </a>
                    <a href="/catalog" class="h-12 px-6 rounded-xl bg-surface-card hover:bg-surface-container text-primary font-bold text-sm border border-border-subtle shadow-sm transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">shelves</span>
                        <span>Explorar Catálogo</span>
                    </a>
                </div>

                <!-- Live Metrics -->
                <div class="pt-6 grid grid-cols-3 gap-4 border-t border-border-subtle/80 max-w-lg">
                    <div>
                        <span class="block text-2xl font-mono font-bold text-primary">±0.2mm</span>
                        <span class="text-xs text-text-muted">Tolerancia CNC</span>
                    </div>
                    <div>
                        <span class="block text-2xl font-mono font-bold text-primary">100%</span>
                        <span class="text-xs text-text-muted">Roble y Nogal FSC®</span>
                    </div>
                    <div>
                        <span class="block text-2xl font-mono font-bold text-primary">15-18d</span>
                        <span class="text-xs text-text-muted">Entrega en Planta</span>
                    </div>
                </div>
            </div>

            <!-- Right Column: Interactive Featured Card Preview -->
            <div class="lg:col-span-5">
                <div class="bg-surface-card rounded-2xl p-6 shadow-xl border border-border-subtle space-y-5 relative">
                    <div class="relative h-72 rounded-xl overflow-hidden bg-surface-container group">
                        <img src="<?= htmlspecialchars($models[0]['image']) ?>" alt="<?= htmlspecialchars($models[0]['name']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute top-3 left-3 flex flex-col gap-1">
                            <span class="px-2.5 py-1 rounded bg-secondary-container text-white font-mono text-xs font-bold shadow-sm">
                                100% PARAMÉTRICO 3D
                            </span>
                        </div>
                        <div class="absolute bottom-3 right-3 bg-surface-card/90 backdrop-blur-md px-3 py-1 rounded-lg text-xs font-mono font-bold text-primary shadow-sm border border-border-subtle">
                            Desde $<?= $models[0]['basePrice'] ?> USD
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between text-xs text-text-muted font-mono mb-1">
                            <span><?= htmlspecialchars($models[0]['category']) ?></span>
                            <span class="text-emerald-600 font-semibold">Stock Roble Miel Listo</span>
                        </div>
                        <h3 class="text-xl font-bold text-primary"><?= htmlspecialchars($models[0]['name']) ?></h3>
                        <p class="text-xs text-text-muted line-clamp-2 mt-1">
                            <?= htmlspecialchars($models[0]['description']) ?>
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <a href="/customizer?model=<?= urlencode($models[0]['id']) ?>" class="h-10 rounded-lg bg-primary text-white text-xs font-bold flex items-center justify-center gap-1.5 hover:bg-primary-variant transition-colors">
                            <span class="material-symbols-outlined text-[16px]">tune</span>
                            <span>Personalizar Medidas</span>
                        </a>
                        <a href="/nesting" class="h-10 rounded-lg bg-surface-container hover:bg-surface-container-high text-primary text-xs font-semibold flex items-center justify-center gap-1 transition-colors border border-border-subtle">
                            <span class="material-symbols-outlined text-[16px]">table_chart</span>
                            <span>Ver Despiece</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FEATURED MODELS SHOWCASE -->
<section class="py-16 md:py-20 bg-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <span class="text-xs font-mono font-bold uppercase tracking-wider text-secondary-container">Colección de Autor</span>
                <h2 class="text-3xl font-extrabold text-primary tracking-tight mt-1">Modelos Paramétricos Listos para Taller</h2>
                <p class="text-sm text-text-muted mt-1 max-w-2xl">Cada diseño cuenta con variables geométricas continuas verificadas por ingeniería de estructuras.</p>
            </div>
            <a href="/catalog" class="text-sm font-bold text-secondary-container hover:underline flex items-center gap-1">
                <span>Ver todos los modelos (<?= count($models) ?>)</span>
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach (array_slice($models, 0, 6) as $model): ?>
                <div class="bg-surface-card rounded-2xl p-5 shadow-sm hover:shadow-md transition-all border border-border-subtle flex flex-col justify-between group">
                    <div>
                        <div class="relative h-56 rounded-xl overflow-hidden bg-surface-container mb-4">
                            <img src="<?= htmlspecialchars($model['image']) ?>" alt="<?= htmlspecialchars($model['name']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <?php if (!empty($model['badge'])): ?>
                                <span class="absolute top-3 left-3 px-2 py-0.5 rounded bg-secondary-container text-white font-mono text-[10px] font-bold">
                                    <?= htmlspecialchars($model['badge']) ?>
                                </span>
                            <?php endif; ?>
                            <span class="absolute bottom-3 left-3 bg-surface-card/90 backdrop-blur-sm px-2 py-0.5 rounded text-[10px] font-mono text-text-primary font-semibold">
                                <?= htmlspecialchars($model['dimensionsLabel']) ?>
                            </span>
                        </div>

                        <span class="text-[11px] font-mono uppercase text-secondary font-bold block mb-1"><?= htmlspecialchars($model['category']) ?></span>
                        <h3 class="text-base font-bold text-primary"><?= htmlspecialchars($model['name']) ?></h3>
                        <p class="text-xs text-text-muted line-clamp-2 mt-1"><?= htmlspecialchars($model['description']) ?></p>
                    </div>

                    <div class="pt-4 mt-4 border-t border-border-subtle flex items-center justify-between">
                        <div>
                            <span class="text-[10px] text-text-muted block">Precio Base:</span>
                            <span class="text-lg font-mono font-bold text-primary">$<?= $model['basePrice'] ?> <span class="text-xs font-normal">USD</span></span>
                        </div>
                        <a href="/customizer?model=<?= urlencode($model['id']) ?>" class="px-3.5 py-2 rounded-lg bg-secondary-container hover:bg-accent-hover text-white text-xs font-semibold flex items-center gap-1 shadow-xs transition-colors">
                            <span class="material-symbols-outlined text-[16px]">view_in_ar</span>
                            <span>3D Studio</span>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 4 STEPS FABRICATION PIPELINE -->
<section class="py-16 bg-surface-container-low border-y border-border-subtle">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        <div class="text-center max-w-3xl mx-auto">
            <span class="text-xs font-mono font-bold uppercase tracking-wider text-secondary-container">Flujo Integral sin Fricción</span>
            <h2 class="text-3xl font-extrabold text-primary tracking-tight mt-1">De la Pantalla al Taller en 4 Etapas</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-surface-card p-6 rounded-2xl shadow-sm border border-border-subtle space-y-3">
                <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary font-mono font-bold text-lg">01</div>
                <h4 class="font-bold text-base text-primary">Modelado Paramétrico 3D</h4>
                <p class="text-xs text-text-muted leading-relaxed">Ajuste milimétrico de ancho, alto y profundidad en canvas con cálculo estructural continuo.</p>
            </div>
            <div class="bg-surface-card p-6 rounded-2xl shadow-sm border border-border-subtle space-y-3">
                <div class="w-12 h-12 rounded-xl bg-secondary-container/10 flex items-center justify-center text-secondary-container font-mono font-bold text-lg">02</div>
                <h4 class="font-bold text-base text-primary">Despiece & Nesting CNC</h4>
                <p class="text-xs text-text-muted leading-relaxed">Generación de listas de corte automáticas en tableros estándar 2440×1220 mm con merma menor al 12%.</p>
            </div>
            <div class="bg-surface-card p-6 rounded-2xl shadow-sm border border-border-subtle space-y-3">
                <div class="w-12 h-12 rounded-xl bg-blue-600/10 flex items-center justify-center text-blue-600 font-mono font-bold text-lg">03</div>
                <h4 class="font-bold text-base text-primary">Mecanizado de 5 Ejes</h4>
                <p class="text-xs text-text-muted leading-relaxed">Fresado de palillería continua, ingletes a 45° y taladros de espiga en centros de mecanizado Biesse y Homag.</p>
            </div>
            <div class="bg-surface-card p-6 rounded-2xl shadow-sm border border-border-subtle space-y-3">
                <div class="w-12 h-12 rounded-xl bg-emerald-600/10 flex items-center justify-center text-emerald-600 font-mono font-bold text-lg">04</div>
                <h4 class="font-bold text-base text-primary">Control QA & Entrega</h4>
                <p class="text-xs text-text-muted leading-relaxed">Inspección con calibre digital, acabado con aceite ecológico 0 VOCs Rubio Monocoat® y embalaje QR Zebra.</p>
            </div>
        </div>
    </div>
</section>
