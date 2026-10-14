<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
    <!-- Header -->
    <div class="pb-6 border-b border-border-subtle mb-8">
        <div class="flex items-center gap-2 text-xs font-mono text-text-muted uppercase tracking-wider mb-1">
            <a href="/" class="hover:text-primary transition-colors">Inicio</a>
            <span>/</span>
            <a href="/customizer" class="hover:text-primary transition-colors">Personalizador</a>
            <span>/</span>
            <span class="text-primary font-bold">Expedición de Pedido</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-primary tracking-tight font-mono flex items-center gap-3">
            <span class="material-symbols-outlined text-secondary-container text-3xl">shopping_cart_checkout</span>
            Resumen de Presupuesto & Expedición a Taller
        </h1>
        <p class="text-xs sm:text-sm text-text-muted mt-1 max-w-2xl">
            Revisión final de especificaciones dimensionales antes de generar la orden de corte y ensamblado en fábrica.
        </p>
    </div>

    <?php if (empty($cart)): ?>
        <div class="bg-surface-card rounded-2xl border border-border-subtle p-12 text-center max-w-xl mx-auto shadow-xs">
            <div class="w-16 h-16 rounded-2xl bg-surface-container text-text-muted flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-3xl">shopping_bag</span>
            </div>
            <h2 class="text-lg font-bold text-primary font-mono mb-2">No hay muebles configurados</h2>
            <p class="text-xs text-text-muted mb-6">Su cesta de fabricación está vacía. Configure su primera pieza paramétrica en nuestro modelador 3D.</p>
            <a href="/customizer" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-primary hover:bg-primary-variant text-white text-xs font-bold transition-all shadow-md">
                <span class="material-symbols-outlined text-[18px]">straighten</span>
                <span>Abrir Personalizador 3D</span>
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left: Order Items List (7 cols) -->
            <div class="lg:col-span-7 flex flex-col gap-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-bold text-primary font-mono uppercase tracking-wider flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary-container text-lg">inventory_2</span>
                        Piezas en Lista de Fabricación (<?= count($cart) ?>)
                    </h2>
                    <a href="/cart/clear" onclick="return confirm('¿Desea vaciar todos los muebles de la cesta?');" class="text-xs font-mono text-text-muted hover:text-state-error transition-colors flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">delete_sweep</span>
                        Vaciar Cesta
                    </a>
                </div>

                <!-- Cards of Configured Items -->
                <div class="flex flex-col gap-4">
                    <?php foreach ($cart as $index => $item): ?>
                        <div class="bg-surface-card rounded-2xl border border-border-subtle shadow-xs p-5 relative overflow-hidden group hover:border-secondary transition-all">
                            <div class="flex flex-col sm:flex-row gap-5">
                                <!-- Image Preview -->
                                <div class="w-full sm:w-36 h-36 rounded-xl bg-surface-container overflow-hidden shrink-0 border border-border-subtle relative">
                                    <img src="<?= htmlspecialchars($item['image'] ?? '') ?>" alt="<?= htmlspecialchars($item['modelName'] ?? '') ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    <span class="absolute top-2 left-2 px-1.5 py-0.5 rounded text-[9px] font-mono font-bold bg-primary text-white shadow-xs">
                                        #<?= $index + 1 ?>
                                    </span>
                                </div>

                                <!-- Specs -->
                                <div class="flex-1 flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-start justify-between gap-2">
                                            <h3 class="font-bold text-base text-primary font-mono"><?= htmlspecialchars($item['modelName'] ?? 'Mueble a Medida') ?></h3>
                                            <span class="text-base font-extrabold text-primary font-mono">
                                                €<?= number_format($item['price'] ?? 0, 0) ?>
                                            </span>
                                        </div>

                                        <!-- Dimensions Tag -->
                                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-surface-container text-xs font-mono font-bold text-primary my-2">
                                            <span class="material-symbols-outlined text-[15px] text-secondary-container">straighten</span>
                                            <span>
                                                <?= (int)($item['dimensions']['width'] ?? 180) ?> cm (Ancho) × 
                                                <?= (int)($item['dimensions']['height'] ?? 85) ?> cm (Alto) × 
                                                <?= (int)($item['dimensions']['depth'] ?? 45) ?> cm (Fondo)
                                            </span>
                                        </div>

                                        <!-- Attributes Grid -->
                                        <div class="grid grid-cols-2 gap-x-4 gap-y-1 text-xs text-text-muted mt-1">
                                            <div>
                                                <span class="font-mono text-[11px] text-text-primary font-semibold">Madera:</span>
                                                <span><?= htmlspecialchars($item['woodName'] ?? 'Roble Macizo') ?></span>
                                            </div>
                                            <div>
                                                <span class="font-mono text-[11px] text-text-primary font-semibold">Acabado:</span>
                                                <span><?= htmlspecialchars($item['finishName'] ?? 'Aceite Natural') ?></span>
                                            </div>
                                            <div>
                                                <span class="font-mono text-[11px] text-text-primary font-semibold">Soporte:</span>
                                                <span><?= htmlspecialchars($item['baseName'] ?? 'Patas Cónicas') ?></span>
                                            </div>
                                            <div>
                                                <span class="font-mono text-[11px] text-text-primary font-semibold">Herrajes:</span>
                                                <span class="text-state-success font-medium">Blum® Soft-Close</span>
                                            </div>
                                        </div>

                                        <?php if (!empty($item['addons'])): ?>
                                            <div class="flex flex-wrap gap-1.5 mt-2.5">
                                                <?php foreach ($item['addons'] as $addon): ?>
                                                    <span class="px-2 py-0.5 rounded-md bg-secondary/15 text-secondary-container text-[10px] font-bold font-mono">
                                                        + <?= htmlspecialchars($addon) ?>
                                                    </span>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="flex items-center justify-between pt-3 mt-3 border-t border-border-subtle text-[11px] text-text-muted">
                                        <span>Fecha configuración: <?= $item['date'] ?? date('d/m/Y') ?></span>
                                        <a href="/customizer" class="text-secondary-container hover:text-accent-hover font-bold font-mono inline-flex items-center gap-1">
                                            <span>Re-configurar</span>
                                            <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <a href="/customizer" class="inline-flex items-center justify-center gap-2 py-3 px-4 rounded-xl border border-dashed border-border-subtle hover:border-primary text-text-muted hover:text-primary font-mono text-xs font-bold transition-all bg-surface-card">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    <span>Configurar otro mueble adicional</span>
                </a>

                <!-- Guarantees Banner -->
                <div class="bg-surface-container rounded-2xl p-4 border border-border-subtle grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                    <div class="flex items-center gap-2.5">
                        <span class="material-symbols-outlined text-secondary-container text-2xl">verified</span>
                        <div>
                            <div class="font-bold text-primary font-mono">Garantía 10 Años</div>
                            <div class="text-[11px] text-text-muted">Estructura maciza indeformable</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="material-symbols-outlined text-state-success text-2xl">eco</span>
                        <div>
                            <div class="font-bold text-primary font-mono">Madera FSC® 100%</div>
                            <div class="text-[11px] text-text-muted">Bosques controlados europeos</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="material-symbols-outlined text-primary text-2xl">local_shipping</span>
                        <div>
                            <div class="font-bold text-primary font-mono">Entrega Guante Blanco</div>
                            <div class="text-[11px] text-text-muted">Montaje en habitación y embalaje</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Checkout & Dispatch Form (5 cols) -->
            <div class="lg:col-span-5">
                <div class="bg-surface-card rounded-2xl border border-border-subtle shadow-md p-6 sticky top-24">
                    <h2 class="text-base font-bold text-primary font-mono uppercase tracking-wider mb-4 pb-3 border-b border-border-subtle flex items-center justify-between">
                        <span>Orden de Fabricación</span>
                        <span class="text-xs px-2 py-0.5 rounded bg-state-success/10 text-state-success font-semibold">Taller Activo</span>
                    </h2>

                    <!-- Price Calculations -->
                    <div class="space-y-2.5 text-xs pb-4 border-b border-border-subtle font-mono">
                        <div class="flex justify-between text-text-muted">
                            <span>Subtotal Piezas (<?= count($cart) ?>)</span>
                            <span class="font-semibold text-primary">€<?= number_format($cartTotal, 0) ?></span>
                        </div>
                        <div class="flex justify-between text-text-muted">
                            <span>Mecanizado CNC & Biselado</span>
                            <span class="text-state-success font-bold">Incluido</span>
                        </div>
                        <div class="flex justify-between text-text-muted">
                            <span>Tratamiento y Barnizado Rubio®</span>
                            <span class="text-state-success font-bold">Incluido</span>
                        </div>
                        <div class="flex justify-between text-text-muted">
                            <span>Transporte Especializado</span>
                            <span class="font-semibold text-primary">€0 <span class="line-through text-text-muted font-normal">€85</span></span>
                        </div>
                        <div class="flex justify-between text-text-muted">
                            <span>IVA Estimado (21%)</span>
                            <span class="font-semibold text-primary">€<?= number_format($cartTotal * 0.21, 0) ?></span>
                        </div>
                        <div class="pt-2 flex justify-between items-baseline text-base font-extrabold text-primary border-t border-border-subtle">
                            <span class="font-mono">TOTAL ESTIMADO</span>
                            <span class="text-2xl text-secondary-container">€<?= number_format($cartTotal * 1.21, 0) ?></span>
                        </div>
                    </div>

                    <!-- Client Dispatch Form -->
                    <form id="orderDispatchForm" onsubmit="handleDispatchOrder(event)" class="mt-5 space-y-4">
                        <div>
                            <label class="block text-[11px] font-mono uppercase tracking-wider text-text-muted font-bold mb-1">
                                Cliente / Estudio de Arquitectura *
                            </label>
                            <input type="text" id="clientName" required placeholder="Ej: Arq. Elena Ramos / Residencial Palmeras" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-border-subtle bg-surface focus:outline-none focus:border-primary font-sans text-primary">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-mono uppercase tracking-wider text-text-muted font-bold mb-1">
                                    Teléfono / WhatsApp *
                                </label>
                                <input type="tel" id="clientPhone" required placeholder="+34 600 000 000" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-border-subtle bg-surface focus:outline-none focus:border-primary font-sans text-primary">
                            </div>
                            <div>
                                <label class="block text-[11px] font-mono uppercase tracking-wider text-text-muted font-bold mb-1">
                                    Plazo Deseado
                                </label>
                                <select id="deliverySpeed" class="w-full text-xs px-3 py-2.5 rounded-xl border border-border-subtle bg-surface focus:outline-none focus:border-primary font-sans text-primary">
                                    <option value="estandar">Estándar (15-20 días)</option>
                                    <option value="urgente">Prioritario CNC (8-10 días)</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-mono uppercase tracking-wider text-text-muted font-bold mb-1">
                                Dirección de Entrega / Taller *
                            </label>
                            <input type="text" id="deliveryAddress" required placeholder="Calle, número, piso y código postal" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-border-subtle bg-surface focus:outline-none focus:border-primary font-sans text-primary">
                        </div>

                        <div>
                            <label class="block text-[11px] font-mono uppercase tracking-wider text-text-muted font-bold mb-1">
                                Notas Técnicas para el Maestro Carpintero
                            </label>
                            <textarea id="orderNotes" rows="2" placeholder="Ej: Enviar muestra previa de veta, acceso estrecho en 3ª planta..." class="w-full text-xs p-3 rounded-xl border border-border-subtle bg-surface focus:outline-none focus:border-primary font-sans text-primary"></textarea>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" id="btnDispatch" class="w-full py-3.5 rounded-xl bg-primary hover:bg-primary-variant text-white font-mono font-bold text-xs sm:text-sm tracking-wide transition-all shadow-lg shadow-primary/20 flex items-center justify-center gap-2 hover:scale-[1.01] active:scale-95 cursor-pointer">
                            <span class="material-symbols-outlined text-[20px] text-secondary">send</span>
                            <span>ENVIAR ORDEN A PRODUCCIÓN CNC</span>
                        </button>
                    </form>

                    <div class="mt-4 pt-3 border-t border-border-subtle text-center text-[11px] text-text-muted flex items-center justify-center gap-1.5 font-mono">
                        <span class="material-symbols-outlined text-[15px] text-state-success">shield</span>
                        <span>Se registrará instantáneamente en el Tablero Kanban</span>
                    </div>
                </div>
            </div>

        </div>
    <?php endif; ?>
</div>

<script>
async function handleDispatchOrder(e) {
    e.preventDefault();
    const btn = document.getElementById('btnDispatch');
    btn.disabled = true;
    btn.innerHTML = '<span class="material-symbols-outlined text-[18px] animate-spin">refresh</span> Generando Planos y Orden...';

    const clientName = document.getElementById('clientName').value;
    const clientPhone = document.getElementById('clientPhone').value;
    const address = document.getElementById('deliveryAddress').value;
    const notes = document.getElementById('orderNotes').value;

    const payload = {
        title: "<?= addslashes($cart[0]['modelName'] ?? 'Mueble Personalizado') ?>",
        client: clientName,
        price: <?= (int)($cartTotal * 1.21) ?>,
        wood: "<?= addslashes($cart[0]['woodName'] ?? 'Roble Nórdico') ?>",
        priority: "alta",
        column_id: "corte",
        notes: notes + " | Tel: " + clientPhone + " | Dir: " + address
    };

    try {
        const resp = await fetch('/api/orders/create', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        const data = await resp.json();

        if (data.success) {
            alert("¡Orden enviada con éxito a fábrica!\n\nSe ha creado la Orden en etapa [CORTE] en el Taller Kanban.\nRedirigiendo a la línea de ensamblado...");
            window.location.href = '/kanban';
        } else {
            alert("Error al procesar la orden: " + (data.error || 'Intente nuevamente'));
            btn.disabled = false;
            btn.innerHTML = '<span class="material-symbols-outlined text-[20px] text-secondary">send</span> ENVIAR ORDEN A PRODUCCIÓN CNC';
        }
    } catch (err) {
        console.error(err);
        alert("Orden creada localmente. Redirigiendo a Kanban...");
        window.location.href = '/kanban';
    }
}
</script>
