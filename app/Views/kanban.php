<div class="flex flex-col w-full bg-background min-h-screen">
    <!-- Top Header & Controls -->
    <section class="w-full px-4 md:px-8 pt-4 pb-6 flex flex-col gap-6 bg-surface border-b border-border-subtle">
        <div class="flex flex-col xl:flex-row items-start xl:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="font-mono text-xs uppercase tracking-wider text-text-muted">Planta de Mecanizado & Carpintería</span>
                    <span class="w-1.5 h-1.5 rounded-full bg-secondary-container animate-pulse"></span>
                    <span class="font-mono text-xs text-primary font-medium">Biesse Rover + Sierra Homag Online</span>
                </div>
                <div class="flex items-baseline gap-3 flex-wrap">
                    <h1 class="text-2xl md:text-3xl lg:text-4xl text-primary font-bold tracking-tight">
                        Cola de Producción & Flujo de Taller
                    </h1>
                    <span class="bg-surface-container text-primary font-mono text-xs px-2.5 py-1 rounded-full font-semibold border border-border-subtle">
                        Semana <?= date('W') ?> • PHP 8.2 & SQLite
                    </span>
                </div>
            </div>

            <!-- Action buttons -->
            <div class="flex flex-wrap items-center gap-2.5">
                <button onclick="toggleCompactMode()" id="btnCompact" class="flex items-center gap-1.5 px-3.5 h-10 rounded-xl bg-surface-container hover:bg-surface-container-high text-primary font-semibold text-xs transition-all border border-border-subtle">
                    <span class="material-symbols-outlined text-[18px]">view_comfortable</span>
                    <span id="lblCompact">Vista Compacta</span>
                </button>
                <button onclick="window.print()" class="flex items-center gap-1.5 px-3.5 h-10 rounded-xl bg-surface-container hover:bg-surface-container-high text-primary font-semibold text-xs transition-all border border-border-subtle">
                    <span class="material-symbols-outlined text-[18px]">print</span>
                    <span>Hoja de Carga</span>
                </button>
                <a href="/nesting" class="flex items-center gap-1.5 px-3.5 h-10 rounded-xl bg-surface-container hover:bg-surface-container-high text-primary font-semibold text-xs transition-all border border-border-subtle">
                    <span class="material-symbols-outlined text-[18px]">table_chart</span>
                    <span>Nesting CNC</span>
                </a>
                <button onclick="document.getElementById('newOrderModal').classList.remove('hidden')" class="flex items-center gap-1.5 px-4 h-10 rounded-xl bg-secondary-container text-white font-bold text-xs hover:bg-accent-hover transition-transform active:scale-95 shadow-md shadow-secondary-container/20">
                    <span class="material-symbols-outlined text-[20px]">add_circle</span>
                    <span>+ Nuevo Pedido</span>
                </button>
            </div>
        </div>

        <!-- 4 Dynamic KPIs -->
        <?php
        $totalOrders = count($orders);
        $cncCount = count(array_filter($orders, fn($o) => $o['column_id'] === 'cnc'));
        $urgentCount = count(array_filter($orders, fn($o) => $o['urgency'] === 'urgent' || !empty($o['alert'])));
        $totalPieces = array_sum(array_column($orders, 'piece_count'));
        ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-4 rounded-2xl bg-surface-card shadow-xs border border-border-subtle flex flex-col justify-between">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-xs font-mono uppercase text-text-muted">Pedidos en Planta</span>
                        <div class="text-2xl md:text-3xl font-mono font-bold text-primary mt-1"><?= $totalOrders ?></div>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-[22px]">conveyor_belt</span>
                    </div>
                </div>
                <div class="pt-3 mt-3 bg-surface-container-low px-2.5 py-1 rounded-lg text-xs font-mono text-text-muted flex justify-between">
                    <span>Total Piezas CNC:</span>
                    <strong class="text-primary"><?= $totalPieces ?> piezas</strong>
                </div>
            </div>

            <div class="p-4 rounded-2xl bg-surface-card shadow-xs border border-border-subtle flex flex-col justify-between">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-xs font-mono uppercase text-text-muted">Corte CNC Activo</span>
                        <div class="text-2xl md:text-3xl font-mono font-bold text-primary mt-1"><?= $cncCount ?> <span class="text-xs text-text-muted font-normal">en cola</span></div>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-amber-600/10 flex items-center justify-center text-secondary-container">
                        <span class="material-symbols-outlined text-[22px]">precision_manufacturing</span>
                    </div>
                </div>
                <div class="pt-3 mt-3 bg-surface-container-low px-2.5 py-1 rounded-lg text-xs font-mono text-text-muted flex justify-between">
                    <span>Centros activos:</span>
                    <strong class="text-primary">Biesse + Homag</strong>
                </div>
            </div>

            <div class="p-4 rounded-2xl bg-surface-card shadow-xs border border-border-subtle flex flex-col justify-between">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-xs font-mono uppercase text-text-muted">Lead Time Medio</span>
                        <div class="text-2xl md:text-3xl font-mono font-bold text-primary mt-1">14.2 <span class="text-xs text-text-muted font-normal">días</span></div>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-emerald-600/10 flex items-center justify-center text-emerald-600">
                        <span class="material-symbols-outlined text-[22px]">timer</span>
                    </div>
                </div>
                <div class="pt-3 mt-3 bg-surface-container-low px-2.5 py-1 rounded-lg text-xs font-mono text-emerald-700 font-semibold flex justify-between">
                    <span>-1.8 días vs Objetivo</span>
                    <span>Meta: 16d</span>
                </div>
            </div>

            <div class="p-4 rounded-2xl bg-surface-card shadow-xs border border-border-subtle flex flex-col justify-between">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-xs font-mono uppercase text-rose-600">Atención Requerida</span>
                        <div class="text-2xl md:text-3xl font-mono font-bold text-rose-600 mt-1"><?= $urgentCount ?></div>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-rose-600/10 flex items-center justify-center text-rose-600 animate-pulse">
                        <span class="material-symbols-outlined text-[22px]">warning</span>
                    </div>
                </div>
                <div class="pt-3 mt-3 bg-rose-50 px-2.5 py-1 rounded-lg text-xs font-mono text-rose-800 flex justify-between">
                    <span>Órdenes con aviso</span>
                    <span>Revisar herrajes</span>
                </div>
            </div>
        </div>
    </section>

    <!-- KANBAN BOARD CONTAINER WITH DRAG & DROP -->
    <section class="w-full px-4 md:px-8 py-6 bg-canvas-cream flex-1 overflow-x-auto">
        <div class="flex items-start gap-4 min-w-[1720px] pb-6">
            <?php foreach ($columns as $col): ?>
                <?php
                $colOrders = array_filter($orders, fn($o) => $o['column_id'] === $col['id']);
                ?>
                <div class="w-72 shrink-0 flex flex-col gap-3" ondragover="event.preventDefault()" ondrop="handleDrop(event, '<?= $col['id'] ?>')">
                    <!-- Column Header -->
                    <div class="bg-surface-card p-3.5 rounded-2xl flex items-center justify-between shadow-xs border border-border-subtle">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full <?= $col['dotColor'] ?>"></span>
                            <h3 class="font-bold text-sm text-primary"><?= htmlspecialchars($col['title']) ?></h3>
                        </div>
                        <span class="bg-surface-container px-2.5 py-0.5 rounded-full font-mono text-xs font-bold text-primary">
                            <?= count($colOrders) ?>
                        </span>
                    </div>
                    <div class="text-[11px] font-mono text-text-muted px-1"><?= htmlspecialchars($col['subtitle']) ?></div>

                    <!-- Drop area -->
                    <div id="col_<?= $col['id'] ?>" class="flex flex-col gap-3 min-h-[480px] p-2 rounded-2xl bg-surface-container-low/50 border-2 border-dashed border-border-subtle transition-all">
                        <?php if (empty($colOrders)): ?>
                            <div class="flex-1 flex flex-col items-center justify-center text-text-muted text-xs p-6 text-center">
                                <span class="material-symbols-outlined text-[24px] opacity-40 mb-1">inbox</span>
                                <p class="font-mono text-[11px]">Sin pedidos en esta fase</p>
                                <p class="font-mono text-[10px] opacity-60">Arrastra una tarjeta aquí</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($colOrders as $ord): ?>
                                <div
                                    id="card_<?= $ord['id'] ?>"
                                    draggable="true"
                                    ondragstart="handleDragStart(event, '<?= $ord['id'] ?>')"
                                    class="kanban-card bg-surface-card rounded-xl p-3.5 shadow-xs hover:shadow-md transition-all flex flex-col gap-2 cursor-grab active:cursor-grabbing border border-border-subtle"
                                    onclick="openOrderDetail(<?= htmlspecialchars(json_encode($ord), ENT_QUOTES, 'UTF-8') ?>)"
                                >
                                    <div class="flex items-center justify-between">
                                        <span class="font-mono text-xs font-bold text-primary"><?= htmlspecialchars($ord['order_number']) ?></span>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold <?= htmlspecialchars($ord['status_badge_class']) ?>">
                                            <?= htmlspecialchars($ord['status_badge']) ?>
                                        </span>
                                    </div>

                                    <div class="flex items-start gap-2.5 card-body">
                                        <img src="<?= htmlspecialchars($ord['thumbnail']) ?>" alt="" class="w-12 h-12 rounded-lg object-cover shrink-0 border border-border-subtle">
                                        <div class="min-w-0 flex-1">
                                            <h4 class="font-bold text-xs text-primary truncate"><?= htmlspecialchars($ord['product_name']) ?></h4>
                                            <p class="text-[11px] text-text-muted truncate"><?= htmlspecialchars($ord['client']) ?> • <?= htmlspecialchars($ord['project']) ?></p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-1.5 flex-wrap text-[11px] font-mono card-details">
                                        <span class="px-2 py-0.5 rounded bg-surface-container text-primary flex items-center gap-1">
                                            <span class="w-2 h-2 rounded-full" style="background-color: <?= $ord['wood_color'] ?>"></span>
                                            <?= htmlspecialchars($ord['wood_name']) ?>
                                        </span>
                                        <span class="px-1.5 py-0.5 rounded bg-surface-container text-text-muted">
                                            <?= $ord['piece_count'] ?> pzs
                                        </span>
                                    </div>

                                    <?php if (!empty($ord['alert'])): ?>
                                        <div class="p-1.5 rounded bg-rose-50 text-rose-700 text-[10px] font-mono border border-rose-200">
                                            <?= htmlspecialchars($ord['alert']) ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="flex items-center justify-between pt-1 border-t border-border-subtle text-[11px] font-mono">
                                        <span class="text-text-muted flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[13px]">calendar_today</span> <?= htmlspecialchars($ord['delivery_date']) ?>
                                        </span>
                                        <div class="flex items-center gap-1">
                                            <button onclick="event.stopPropagation(); changeStage('<?= $ord['id'] ?>', -1)" class="px-1.5 py-0.5 rounded bg-surface-container hover:bg-surface-container-high text-primary font-bold text-[10px]" title="Retroceder">&lt;</button>
                                            <button onclick="event.stopPropagation(); changeStage('<?= $ord['id'] ?>', 1)" class="px-2 py-0.5 rounded bg-primary text-white font-bold text-[10px] hover:bg-primary-variant" title="Avanzar">&gt;</button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>

<!-- MODAL NUEVO PEDIDO DIRECTO -->
<div id="newOrderModal" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-surface-card rounded-2xl shadow-2xl max-w-md w-full p-6 flex flex-col gap-4 border border-border-subtle">
        <div class="flex justify-between items-center pb-2 border-b border-border-subtle">
            <div>
                <span class="text-xs font-mono uppercase text-text-muted">Orden Directa</span>
                <h3 class="text-lg font-bold text-primary">+ Nuevo Pedido de Taller</h3>
            </div>
            <button onclick="document.getElementById('newOrderModal').classList.add('hidden')" class="text-text-muted hover:text-primary">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form id="newOrderForm" onsubmit="submitNewOrder(event)" class="space-y-3 text-xs">
            <div>
                <label class="font-mono text-text-muted block mb-1">Cliente</label>
                <input type="text" name="client" required placeholder="Ej. Laura Sanz" class="w-full px-3 py-2 rounded-lg bg-canvas-cream border border-border-subtle text-primary focus:outline-none focus:ring-1 focus:ring-primary">
            </div>
            <div>
                <label class="font-mono text-text-muted block mb-1">Mueble / Modelo</label>
                <select name="product_name" class="w-full px-3 py-2 rounded-lg bg-canvas-cream border border-border-subtle text-primary font-medium">
                    <?php foreach ($models as $m): ?>
                        <option value="<?= htmlspecialchars($m['name']) ?>"><?= htmlspecialchars($m['name']) ?> (<?= htmlspecialchars($m['dimensionsLabel']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="font-mono text-text-muted block mb-1">Madera</label>
                    <select name="wood_name" class="w-full px-3 py-2 rounded-lg bg-canvas-cream border border-border-subtle text-primary">
                        <?php foreach ($woods as $w): ?>
                            <option value="<?= htmlspecialchars($w['name']) ?>"><?= htmlspecialchars($w['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="font-mono text-text-muted block mb-1">N° Piezas</label>
                    <input type="number" name="piece_count" value="20" min="1" max="100" class="w-full px-3 py-2 rounded-lg bg-canvas-cream border border-border-subtle text-primary">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="font-mono text-text-muted block mb-1">Fase Inicial</label>
                    <select name="column_id" class="w-full px-3 py-2 rounded-lg bg-canvas-cream border border-border-subtle text-primary">
                        <?php foreach ($columns as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['title']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="font-mono text-text-muted block mb-1">Prioridad</label>
                    <select name="urgency" class="w-full px-3 py-2 rounded-lg bg-canvas-cream border border-border-subtle text-primary">
                        <option value="normal">Normal</option>
                        <option value="soon">Próximo (&lt;48h)</option>
                        <option value="urgent">Urgente</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="font-mono text-text-muted block mb-1">Fecha Entrega</label>
                <input type="text" name="delivery_date" value="<?= date('d M', strtotime('+12 days')) ?>" class="w-full px-3 py-2 rounded-lg bg-canvas-cream border border-border-subtle text-primary">
            </div>

            <div class="pt-2 flex gap-2">
                <button type="button" onclick="document.getElementById('newOrderModal').classList.add('hidden')" class="flex-1 py-2 rounded-lg bg-surface-container text-primary font-bold">Cancelar</button>
                <button type="submit" class="flex-1 py-2 rounded-lg bg-secondary-container text-white font-bold hover:bg-accent-hover shadow-sm">Crear Pedido</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL DETALLE DE ORDEN -->
<div id="orderDetailModal" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-surface-card rounded-2xl shadow-2xl max-w-md w-full p-6 flex flex-col gap-4 border border-border-subtle">
        <div class="flex justify-between items-start pb-2 border-b border-border-subtle">
            <div>
                <span class="text-xs font-mono uppercase text-text-muted" id="dtOrderNum">ORD-XXXX</span>
                <h3 class="text-lg font-bold text-primary" id="dtProduct">Nombre</h3>
                <p class="text-xs text-text-muted" id="dtClient">Cliente</p>
            </div>
            <button onclick="document.getElementById('orderDetailModal').classList.add('hidden')" class="text-text-muted hover:text-primary">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <div class="space-y-2.5 p-4 rounded-xl bg-canvas-cream border border-border-subtle text-xs font-mono">
            <div class="flex justify-between py-1 border-b border-border-subtle/60">
                <span class="text-text-muted">Dimensiones:</span>
                <span class="font-bold text-primary" id="dtDims">-</span>
            </div>
            <div class="flex justify-between py-1 border-b border-border-subtle/60">
                <span class="text-text-muted">Madera:</span>
                <span class="font-bold text-primary" id="dtWood">-</span>
            </div>
            <div class="flex justify-between py-1 border-b border-border-subtle/60">
                <span class="text-text-muted">Piezas CNC:</span>
                <span class="font-bold text-primary" id="dtPieces">-</span>
            </div>
            <div class="flex justify-between py-1 border-b border-border-subtle/60">
                <span class="text-text-muted">Entrega:</span>
                <span class="font-bold text-emerald-700" id="dtDate">-</span>
            </div>
        </div>

        <div class="flex gap-2">
            <a href="/nesting" class="flex-1 py-2.5 rounded-lg bg-surface-container hover:bg-surface-container-high text-primary font-bold text-center text-xs border border-border-subtle">
                Ver Despiece CNC
            </a>
            <button onclick="document.getElementById('orderDetailModal').classList.add('hidden')" class="px-4 py-2.5 rounded-lg bg-primary text-white font-bold text-xs">
                Cerrar
            </button>
        </div>
    </div>
</div>

<script>
    const STAGE_FLOW = ['dxf', 'cnc', 'edges', 'assembly', 'finish', 'packaging'];
    let draggedId = null;

    function handleDragStart(e, id) {
        draggedId = id;
        e.dataTransfer.setData('text/plain', id);
    }

    function handleDrop(e, columnId) {
        e.preventDefault();
        const id = e.dataTransfer.getData('text/plain') || draggedId;
        if (!id) return;

        fetch('/api/orders/update-column', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ order_id: id, column_id: columnId })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            }
        });
    }

    function changeStage(orderId, delta) {
        const card = document.getElementById('card_' + orderId);
        const currentCol = card.parentElement.id.replace('col_', '');
        const currentIdx = STAGE_FLOW.indexOf(currentCol);
        const nextIdx = currentIdx + delta;
        if (nextIdx >= 0 && nextIdx < STAGE_FLOW.length) {
            handleDrop({ preventDefault: () => {}, dataTransfer: { getData: () => orderId } }, STAGE_FLOW[nextIdx]);
        }
    }

    function submitNewOrder(e) {
        e.preventDefault();
        const form = e.target;
        const data = Object.fromEntries(new FormData(form));

        fetch('/api/orders/create', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            }
        });
    }

    function openOrderDetail(ord) {
        document.getElementById('dtOrderNum').innerText = ord.order_number;
        document.getElementById('dtProduct').innerText = ord.product_name;
        document.getElementById('dtClient').innerText = ord.client + ' • ' + ord.project;
        document.getElementById('dtDims').innerText = ord.dimensions;
        document.getElementById('dtWood').innerText = ord.wood_name;
        document.getElementById('dtPieces').innerText = ord.piece_count + ' piezas';
        document.getElementById('dtDate').innerText = ord.delivery_date;
        document.getElementById('orderDetailModal').classList.remove('hidden');
    }

    let isCompact = false;
    function toggleCompactMode() {
        isCompact = !isCompact;
        document.querySelectorAll('.card-body img').forEach(el => el.classList.toggle('hidden', isCompact));
        document.querySelectorAll('.card-details').forEach(el => el.classList.toggle('hidden', isCompact));
        document.getElementById('lblCompact').innerText = isCompact ? 'Vista Normal' : 'Vista Compacta';
    }
</script>
