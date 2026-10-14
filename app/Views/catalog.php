<div class="flex flex-col w-full bg-surface">
    <!-- Header Hero -->
    <section class="w-full bg-surface-container-low py-10 md:py-14 border-b border-border-subtle">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-10">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                <nav class="flex items-center gap-2 font-mono text-xs text-text-muted">
                    <a href="/" class="hover:text-primary transition-colors flex items-center gap-1">
                        <span class="material-symbols-outlined text-[15px]">home</span>
                        <span>Inicio</span>
                    </a>
                    <span>/</span>
                    <span class="text-primary font-semibold">Catálogo de Diseños Paramétricos</span>
                </nav>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-surface-card shadow-xs text-emerald-700 font-mono text-xs border border-border-subtle">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Stock Roble y Nogal listo en taller • Entrega 15-20 días hábiles</span>
                </div>
            </div>

            <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-primary tracking-tight">
                Catálogo de Muebles Personalizables
            </h1>
            <p class="text-sm md:text-base text-text-muted max-w-3xl mt-2 leading-relaxed">
                Selecciona una base estructural contemporánea y adáptala a las dimensiones de tu estancia en el visor 3D interactivo. Con mecanizado CNC Homag/Biesse de 5 ejes y herrajes Blum®.
            </p>
        </div>
    </section>

    <!-- Content & Filters -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-10 py-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Sidebar Filters -->
        <aside class="lg:col-span-3 bg-surface-card p-5 rounded-2xl border border-border-subtle shadow-xs space-y-6">
            <div>
                <span class="font-mono text-xs text-primary font-bold uppercase tracking-wider block mb-3">Buscar Modelo</span>
                <form action="/catalog" method="GET" class="relative">
                    <input type="text" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" placeholder="Aparador, mesa, nogal..." class="w-full px-3 py-2 pl-9 rounded-xl bg-canvas-cream border border-border-subtle text-xs text-primary focus:outline-none focus:ring-1 focus:ring-primary">
                    <span class="material-symbols-outlined absolute left-2.5 top-1/2 -translate-y-1/2 text-[18px] text-text-muted">search</span>
                </form>
            </div>

            <!-- Categories -->
            <div>
                <span class="font-mono text-xs text-primary font-bold uppercase tracking-wider block mb-2">Estancia</span>
                <div class="space-y-1 text-xs font-medium">
                    <?php
                    $cats = [
                        ['id' => 'all', 'label' => 'Todos los Espacios'],
                        ['id' => 'salón', 'label' => 'Salón y Estar'],
                        ['id' => 'comedor', 'label' => 'Comedor'],
                        ['id' => 'estudio', 'label' => 'Estudio & Home Office'],
                        ['id' => 'recibidor', 'label' => 'Recibidor & Pasillos']
                    ];
                    $currentCat = $_GET['cat'] ?? 'all';
                    ?>
                    <?php foreach ($cats as $c): ?>
                        <a href="/catalog?cat=<?= urlencode($c['id']) ?>" class="flex items-center justify-between p-2 rounded-lg transition-colors <?= $currentCat === $c['id'] ? 'bg-primary text-white font-bold' : 'hover:bg-surface-container text-text-primary' ?>">
                            <span><?= htmlspecialchars($c['label']) ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Woods Filter -->
            <div>
                <span class="font-mono text-xs text-primary font-bold uppercase tracking-wider block mb-2">Especie de Madera</span>
                <div class="grid grid-cols-2 gap-1.5 text-xs">
                    <a href="/catalog?wood=all" class="p-2 rounded-lg text-center border <?= (!isset($_GET['wood']) || $_GET['wood'] === 'all') ? 'bg-primary text-white border-primary' : 'bg-canvas-cream border-border-subtle text-primary hover:bg-surface-container' ?>">
                        Todas
                    </a>
                    <?php foreach ($woods as $w): ?>
                        <a href="/catalog?wood=<?= urlencode($w['id']) ?>" class="p-2 rounded-lg flex items-center gap-1.5 border <?= ($_GET['wood'] ?? '') === $w['id'] ? 'ring-2 ring-secondary-container bg-surface-container border-transparent' : 'bg-canvas-cream border-border-subtle text-primary hover:bg-surface-container' ?>">
                            <span class="w-3.5 h-3.5 rounded-full shrink-0" style="background-color: <?= $w['colorHex'] ?>"></span>
                            <span class="truncate"><?= explode(' ', $w['name'])[0] ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="pt-2 border-t border-border-subtle">
                <a href="/catalog" class="block w-full py-2 rounded-lg bg-surface-container hover:bg-surface-container-high text-primary font-mono text-xs font-bold text-center border border-border-subtle">
                    Restablecer Filtros
                </a>
            </div>
        </aside>

        <!-- Product Grid (9 Cols) -->
        <main class="lg:col-span-9 space-y-6">
            <div class="flex items-center justify-between pb-3 border-b border-border-subtle">
                <span class="text-sm font-bold text-primary font-mono">
                    Mostrando <?= count($models) ?> modelos listos para taller
                </span>
                <span class="text-xs text-text-muted font-mono hidden sm:inline">
                    Modelado asistido por CNC
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php foreach ($models as $m): ?>
                    <div class="bg-surface-card rounded-2xl p-5 shadow-xs hover:shadow-md transition-all border border-border-subtle flex flex-col justify-between group">
                        <div>
                            <div class="relative h-64 rounded-xl overflow-hidden bg-surface-container mb-4">
                                <img src="<?= htmlspecialchars($m['image']) ?>" alt="<?= htmlspecialchars($m['name']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <?php if (!empty($m['badge'])): ?>
                                    <span class="absolute top-3 left-3 px-2.5 py-1 rounded-md bg-secondary-container text-white font-mono text-xs font-bold shadow-sm">
                                        <?= htmlspecialchars($m['badge']) ?>
                                    </span>
                                <?php endif; ?>
                                <span class="absolute bottom-3 left-3 bg-surface-card/90 backdrop-blur-sm px-2.5 py-1 rounded text-xs font-mono text-primary font-bold shadow-xs">
                                    Base: <?= htmlspecialchars($m['dimensionsLabel']) ?>
                                </span>
                            </div>

                            <span class="text-xs font-mono uppercase text-secondary font-bold block mb-1"><?= htmlspecialchars($m['category']) ?></span>
                            <h3 class="text-lg font-bold text-primary"><?= htmlspecialchars($m['name']) ?></h3>
                            <p class="text-xs text-text-muted mt-1 leading-relaxed line-clamp-2"><?= htmlspecialchars($m['description']) ?></p>

                            <!-- Features chips -->
                            <div class="flex flex-wrap gap-1 mt-3">
                                <?php foreach (array_slice($m['features'], 0, 3) as $f): ?>
                                    <span class="px-2 py-0.5 rounded bg-surface-container text-[11px] text-text-muted font-mono"><?= htmlspecialchars($f) ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="pt-4 mt-4 border-t border-border-subtle flex items-center justify-between">
                            <div>
                                <span class="text-[10px] text-text-muted block font-mono">Precio Base:</span>
                                <span class="text-xl font-mono font-bold text-primary">$<?= $m['basePrice'] ?> <span class="text-xs font-normal text-text-muted">USD</span></span>
                            </div>
                            <a href="/customizer?model=<?= urlencode($m['id']) ?>" class="h-10 px-4 rounded-xl bg-secondary-container hover:bg-accent-hover text-white text-xs font-bold flex items-center gap-1.5 shadow-xs transition-transform active:scale-95">
                                <span class="material-symbols-outlined text-[18px]">view_in_ar</span>
                                <span>Personalizar 3D</span>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>
</div>
