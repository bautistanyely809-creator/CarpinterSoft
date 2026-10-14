    </main>

    <!-- Footer -->
    <footer class="bg-primary text-white pt-14 pb-8 border-t border-border-subtle/20 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 pb-12 border-b border-white/10">
                <!-- Col 1: Brand & Philosophy -->
                <div class="space-y-4 md:col-span-1">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-secondary">
                            <span class="material-symbols-outlined text-[18px]">handyman</span>
                        </div>
                        <span class="font-bold text-lg text-white font-mono">Carpinter<span class="text-secondary">Soft</span></span>
                    </div>
                    <p class="text-xs text-white/70 leading-relaxed">
                        Plataforma industrial de ebanistería contemporánea y fabricación digital. Del modelo paramétrico 3D al mecanizado en CNC de 5 ejes sin intermediarios.
                    </p>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-[11px] font-mono text-secondary">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>CNC Homag & Biesse Rover Online</span>
                    </div>
                </div>

                <!-- Col 2: Navigation Links -->
                <div>
                    <h4 class="font-mono text-xs uppercase tracking-wider text-secondary font-bold mb-3">Módulos del Sistema</h4>
                    <ul class="space-y-2 text-xs text-white/80 font-medium">
                        <li><a href="/customizer" class="hover:text-white hover:underline flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px]">view_in_ar</span> Personalizador 3D</a></li>
                        <li><a href="/catalog" class="hover:text-white hover:underline flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px]">shelves</span> Catálogo de Muebles</a></li>
                        <li><a href="/kanban" class="hover:text-white hover:underline flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px]">view_column</span> Tablero Kanban de Taller</a></li>
                        <li><a href="/nesting" class="hover:text-white hover:underline flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px]">table_chart</span> Despiece & Nesting CNC</a></li>
                        <li><a href="/checkout" class="hover:text-white hover:underline flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px]">receipt_long</span> Presupuesto & Pedidos</a></li>
                    </ul>
                </div>

                <!-- Col 3: Maderas Certificadas -->
                <div>
                    <h4 class="font-mono text-xs uppercase tracking-wider text-secondary font-bold mb-3">Maderas FSC® 100%</h4>
                    <ul class="space-y-1.5 text-xs text-white/70">
                        <li class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#D4A373]"></span>
                            <span>Roble Nórdico Miel (Francia)</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#583E26]"></span>
                            <span>Nogal Americano Macizo (Norteamérica)</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#7C472A]"></span>
                            <span>Tzalam de Bosque Sostenible (Yucatán)</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#A0522D]"></span>
                            <span>Cedro Rojo Aromático Macizo</span>
                        </li>
                    </ul>
                </div>

                <!-- Col 4: Garantías Técnicas -->
                <div class="space-y-3">
                    <h4 class="font-mono text-xs uppercase tracking-wider text-secondary font-bold">Garantía de Precisión</h4>
                    <div class="bg-white/5 p-3 rounded-xl border border-white/10 space-y-1 text-xs">
                        <p class="font-bold text-white flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px] text-secondary">verified</span>
                            <span>Tolerancia Mecánica ±0.2 mm</span>
                        </p>
                        <p class="text-[11px] text-white/70">
                            Herrajes Blum® Clip-Top Blumotion con 100.000 ciclos garantizados y acabado ecológico 0 VOCs Rubio Monocoat®.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Bottom Copyright & Architecture Notice -->
            <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-white/60 font-mono">
                <p>© <?= date('Y') ?> CarpinterSoft • Sistema de Fabricación Digital en PHP 8.2 & Canvas 3D</p>
                <div class="flex items-center gap-3">
                    <span>SQLite / MySQL Ready</span>
                    <span>•</span>
                    <span>XAMPP Compatible</span>
                    <span>•</span>
                    <span class="text-secondary font-semibold">v4.2 Pro</span>
                </div>
            </div>
    </footer>

    <!-- ======================================================== -->
    <!-- Floating Global CarpinterAI Widget                       -->
    <!-- ======================================================== -->
    <div id="carpinterAiFloatingWidget" class="fixed bottom-5 right-5 z-50 flex flex-col items-end">
        <!-- Chat Drawer / Popup Window -->
        <div id="aiFloatingWindow" class="hidden mb-3 w-[360px] sm:w-[410px] h-[520px] bg-surface-card rounded-2xl border border-border-subtle shadow-2xl flex flex-col overflow-hidden animate-in fade-in slide-in-from-bottom-4 duration-200">
            <!-- Header -->
            <div class="p-3.5 bg-primary text-white flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-white/10 text-secondary flex items-center justify-center">
                        <span class="material-symbols-outlined text-[18px]">psychology</span>
                    </div>
                    <div>
                        <div class="font-bold text-xs font-mono flex items-center gap-1.5">
                            <span>CarpinterAI</span>
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        </div>
                        <div class="text-[10px] text-white/70">Asistente Inteligente de Taller</div>
                    </div>
                </div>

                <div class="flex items-center gap-1">
                    <a href="/ai" class="p-1 rounded-md text-white/70 hover:text-white hover:bg-white/10 transition-colors" title="Abrir en pantalla completa">
                        <span class="material-symbols-outlined text-[18px]">open_in_full</span>
                    </a>
                    <button onclick="toggleAiWidget()" class="p-1 rounded-md text-white/70 hover:text-white hover:bg-white/10 transition-colors">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>
            </div>

            <!-- Messages Stream -->
            <div id="floatingAiMessages" class="flex-1 p-3.5 overflow-y-auto space-y-3 bg-surface/40 text-xs">
                <div class="flex items-start gap-2">
                    <div class="w-6 h-6 rounded-md bg-primary text-secondary flex items-center justify-center shrink-0 mt-0.5">
                        <span class="material-symbols-outlined text-[14px]">smart_toy</span>
                    </div>
                    <div class="bg-surface-card border border-border-subtle rounded-xl rounded-tl-xs p-3 text-text-primary shadow-xs">
                        <p class="font-bold text-primary mb-1">¡Hola! Soy CarpinterAI.</p>
                        <p class="text-text-muted text-[11px] leading-relaxed">
                            Pídeme diseñar un mueble describiendo medidas o consulta tolerancias de corte CNC y resistencia de maderas.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Quick Suggestions -->
            <div class="px-3 py-1.5 border-t border-border-subtle bg-surface-container/30 flex items-center gap-1.5 overflow-x-auto text-[10px] font-mono">
                <button onclick="sendFloatingPrompt('Aparador de 200cm en roble')" class="px-2 py-0.5 rounded border border-border-subtle bg-surface-card hover:bg-surface text-primary shrink-0">
                    Aparador 200cm
                </button>
                <button onclick="sendFloatingPrompt('Mesa comedor 240x100 nogal')" class="px-2 py-0.5 rounded border border-border-subtle bg-surface-card hover:bg-surface text-primary shrink-0">
                    Mesa 240x100
                </button>
                <button onclick="sendFloatingPrompt('Madera para baño')" class="px-2 py-0.5 rounded border border-border-subtle bg-surface-card hover:bg-surface text-primary shrink-0">
                    Madera baño
                </button>
            </div>

            <!-- Input Form -->
            <div class="p-2.5 bg-surface-card border-t border-border-subtle">
                <form id="floatingAiForm" onsubmit="handleSendFloatingAi(event)" class="flex items-center gap-1.5">
                    <input type="text" id="floatingAiInput" required placeholder="Escribe al Maestro Ebanista IA..." class="flex-1 text-xs px-3 py-2 rounded-xl border border-border-subtle bg-surface focus:outline-none focus:border-secondary-container text-primary font-sans">
                    <button type="submit" class="p-2 rounded-xl bg-secondary-container hover:bg-accent-hover text-white flex items-center justify-center shadow-xs cursor-pointer">
                        <span class="material-symbols-outlined text-[16px]">send</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Floating Launcher Button -->
        <button onclick="toggleAiWidget()" id="aiFloatingBtn" class="flex items-center gap-2.5 px-4 py-3 rounded-2xl bg-primary hover:bg-primary-variant text-white shadow-xl shadow-primary/30 border border-secondary/40 transition-all hover:scale-105 active:scale-95 group cursor-pointer">
            <div class="relative flex items-center justify-center">
                <span class="material-symbols-outlined text-[20px] text-secondary group-hover:rotate-12 transition-transform">psychology</span>
                <span class="absolute -top-1 -right-1 w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
            </div>
            <div class="flex flex-col text-left">
                <span class="font-mono font-bold text-xs tracking-tight leading-none text-white flex items-center gap-1">
                    Carpinter<span class="text-secondary">AI</span>
                </span>
                <span class="text-[9px] text-white/70 font-mono">Maestro Inteligente</span>
            </div>
        </button>
    </div>

    <script>
    function toggleAiWidget() {
        const win = document.getElementById('aiFloatingWindow');
        win.classList.toggle('hidden');
        if (!win.classList.contains('hidden')) {
            document.getElementById('floatingAiInput').focus();
        }
    }

    async function handleSendFloatingAi(e) {
        e.preventDefault();
        const input = document.getElementById('floatingAiInput');
        const msg = input.value.trim();
        if (!msg) return;

        appendFloatingMessage('user', msg);
        input.value = '';

        try {
            const resp = await fetch('/api/ai/chat', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ message: msg })
            });
            const data = await resp.json();

            appendFloatingMessage('bot', data.reply, data.config);
        } catch (err) {
            console.error(err);
            appendFloatingMessage('bot', 'No se pudo contactar con CarpinterAI. Verifica tu conexión.');
        }
    }

    function sendFloatingPrompt(text) {
        document.getElementById('floatingAiInput').value = text;
        document.getElementById('floatingAiForm').dispatchEvent(new Event('submit'));
    }

    function appendFloatingMessage(sender, text, config = null) {
        const container = document.getElementById('floatingAiMessages');
        const wrapper = document.createElement('div');
        wrapper.className = sender === 'user' ? 'flex justify-end' : 'flex items-start gap-2';

        if (sender === 'user') {
            wrapper.innerHTML = `
                <div class="bg-primary text-white rounded-xl rounded-tr-xs px-3 py-2 max-w-[85%] shadow-xs">
                    <p class="leading-relaxed">${escapeHtmlSimple(text)}</p>
                </div>
            `;
        } else {
            let cardHtml = '';
            if (config) {
                cardHtml = `
                    <div class="mt-2 p-2.5 bg-surface-container rounded-lg border border-secondary/30 text-[11px] font-mono">
                        <div class="font-bold text-primary flex justify-between">
                            <span>${escapeHtmlSimple(config.model_name)}</span>
                            <span class="text-secondary-container">€${Number(config.price).toLocaleString()}</span>
                        </div>
                        <div class="text-[10px] text-text-muted mt-0.5">${config.width}×${config.height}×${config.depth} cm • ${escapeHtmlSimple(config.wood_name)}</div>
                        <a href="${config.url}" class="mt-2 block text-center py-1.5 px-2 bg-secondary-container hover:bg-accent-hover text-white rounded font-bold text-[10px] transition-colors">
                            Ver en Visor 3D →
                        </a>
                    </div>
                `;
            }

            wrapper.innerHTML = `
                <div class="w-6 h-6 rounded-md bg-primary text-secondary flex items-center justify-center shrink-0 mt-0.5">
                    <span class="material-symbols-outlined text-[14px]">smart_toy</span>
                </div>
                <div class="bg-surface-card border border-border-subtle rounded-xl rounded-tl-xs p-2.5 text-text-primary shadow-xs max-w-[85%] leading-relaxed text-[11px]">
                    ${formatSimpleMarkdown(text)}
                    ${cardHtml}
                </div>
            `;
        }

        container.appendChild(wrapper);
        container.scrollTop = container.scrollHeight;
    }

    function formatSimpleMarkdown(txt) {
        let out = escapeHtmlSimple(txt);
        out = out.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        out = out.replace(/^• (.*$)/gim, '<div class="flex items-start gap-1"><span class="text-secondary-container">•</span><span>$1</span></div>');
        out = out.replace(/\n/g, '<br>');
        return out;
    }

    function escapeHtmlSimple(str) {
        return (str || '')
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
    </script>
</body>
</html>
