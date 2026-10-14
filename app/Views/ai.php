<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-6 border-b border-border-subtle mb-6">
        <div>
            <div class="flex items-center gap-2 text-xs font-mono text-text-muted uppercase tracking-wider mb-1">
                <a href="/" class="hover:text-primary transition-colors">Inicio</a>
                <span>/</span>
                <span class="text-primary font-bold">CarpinterAI Studio</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-primary tracking-tight font-mono flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-secondary-container text-white flex items-center justify-center shadow-md">
                    <span class="material-symbols-outlined text-[24px]">psychology</span>
                </span>
                CarpinterAI • Maestro Ebanista & Asistente CNC
            </h1>
            <p class="text-xs sm:text-sm text-text-muted mt-1 max-w-2xl">
                Diseña mobiliario a medida en lenguaje natural, resuelve dudas de tolerancias, maderas FSC®, parámetros de fresado CNC y cálculo de resistencia estructural.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-state-success/15 text-state-success text-xs font-mono font-bold">
                <span class="w-2 h-2 rounded-full bg-state-success animate-pulse"></span>
                Motor IA Activo
            </span>
            <a href="/customizer" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-surface-card hover:bg-surface-container border border-border-subtle text-primary text-xs font-bold font-mono transition-all">
                <span class="material-symbols-outlined text-[16px] text-secondary-container">view_in_ar</span>
                <span>Visor 3D</span>
            </a>
        </div>
    </div>

    <!-- Main Studio Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Left: Chat Interface (8 cols) -->
        <div class="lg:col-span-8 flex flex-col bg-surface-card rounded-2xl border border-border-subtle shadow-sm overflow-hidden h-[680px]">
            
            <!-- Chat Header -->
            <div class="p-4 bg-surface-container border-b border-border-subtle flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <div class="w-9 h-9 rounded-xl bg-primary text-white flex items-center justify-center shadow-xs">
                            <span class="material-symbols-outlined text-[20px] text-secondary">handyman</span>
                        </div>
                        <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full bg-state-success border-2 border-white"></span>
                    </div>
                    <div>
                        <div class="font-bold text-sm text-primary font-mono flex items-center gap-1.5">
                            <span>CarpinterAI</span>
                            <span class="text-[10px] px-1.5 py-0.2 rounded bg-secondary-container text-white font-bold">v2.5</span>
                        </div>
                        <div class="text-[11px] text-text-muted">Especialista en madera maciza, tableros y CNC</div>
                    </div>
                </div>

                <button onclick="clearChatHistory()" class="text-xs font-mono text-text-muted hover:text-state-error transition-colors flex items-center gap-1 p-1.5 rounded-lg hover:bg-surface" title="Reiniciar conversación">
                    <span class="material-symbols-outlined text-[18px]">restart_alt</span>
                    <span class="hidden sm:inline">Reiniciar</span>
                </button>
            </div>

            <!-- Messages Stream Area -->
            <div id="aiChatMessages" class="flex-1 p-5 overflow-y-auto space-y-4 font-sans text-xs sm:text-sm bg-surface/50">
                <!-- Welcome Bot Message -->
                <div class="flex items-start gap-3 max-w-3xl">
                    <div class="w-8 h-8 rounded-lg bg-primary text-secondary flex items-center justify-center shrink-0 mt-0.5">
                        <span class="material-symbols-outlined text-[18px]">smart_toy</span>
                    </div>
                    <div class="bg-surface-card border border-border-subtle rounded-2xl rounded-tl-xs p-4 shadow-xs text-text-primary">
                        <p class="font-semibold text-primary mb-1">¡Bienvenido al taller digital inteligente!</p>
                        <p class="text-text-muted leading-relaxed">
                            Soy tu asistente técnico de ebanistería y CNC. Puedes pedirme diseñar cualquier mueble describiendo medidas o hacerme consultas sobre maderas, tolerancias y corte:
                        </p>
                        <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                            <button onclick="sendQuickPrompt('Quiero un aparador de 220cm en roble con 3 puertas y patas conicas')" class="text-left p-2.5 rounded-xl border border-border-subtle bg-surface-container/60 hover:bg-secondary/15 hover:border-secondary transition-all flex items-center justify-between group">
                                <span class="font-mono text-primary font-medium">✨ Diseñar aparador 220cm roble</span>
                                <span class="material-symbols-outlined text-[16px] text-text-muted group-hover:text-secondary-container">arrow_forward</span>
                            </button>
                            <button onclick="sendQuickPrompt('Mesa de comedor de 240x100 en nogal macizo')" class="text-left p-2.5 rounded-xl border border-border-subtle bg-surface-container/60 hover:bg-secondary/15 hover:border-secondary transition-all flex items-center justify-between group">
                                <span class="font-mono text-primary font-medium">✨ Mesa comedor 240x100 nogal</span>
                                <span class="material-symbols-outlined text-[16px] text-text-muted group-hover:text-secondary-container">arrow_forward</span>
                            </button>
                            <button onclick="sendQuickPrompt('¿Qué madera recomiendas para baño o zona húmeda?')" class="text-left p-2.5 rounded-xl border border-border-subtle bg-surface-container/60 hover:bg-secondary/15 hover:border-secondary transition-all flex items-center justify-between group">
                                <span class="font-mono text-primary font-medium">💧 Madera para baños y humedad</span>
                                <span class="material-symbols-outlined text-[16px] text-text-muted group-hover:text-secondary-container">arrow_forward</span>
                            </button>
                            <button onclick="sendQuickPrompt('¿Qué parámetros de avance y RPM usar para fresar roble en CNC?')" class="text-left p-2.5 rounded-xl border border-border-subtle bg-surface-container/60 hover:bg-secondary/15 hover:border-secondary transition-all flex items-center justify-between group">
                                <span class="font-mono text-primary font-medium">⚙️ Parámetros de fresado CNC</span>
                                <span class="material-symbols-outlined text-[16px] text-text-muted group-hover:text-secondary-container">arrow_forward</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Suggestion Chips Bar -->
            <div id="aiChipsBar" class="px-4 py-2 border-t border-border-subtle bg-surface-container/40 flex items-center gap-2 overflow-x-auto whitespace-nowrap text-[11px] font-mono">
                <span class="text-text-muted font-bold">Sugerencias:</span>
                <button onclick="sendQuickPrompt('¿Cómo evitar que una balda de 1 metro se combe?')" class="px-2.5 py-1 rounded-lg border border-border-subtle bg-surface-card hover:bg-surface text-primary transition-colors">
                    📐 Deflexión balda 1m
                </button>
                <button onclick="sendQuickPrompt('Comparar Roble vs Nogal en precio y durabilidad')" class="px-2.5 py-1 rounded-lg border border-border-subtle bg-surface-card hover:bg-surface text-primary transition-colors">
                    🌳 Roble vs Nogal
                </button>
                <button onclick="sendQuickPrompt('Quiero un mueble TV flotante de 200cm con pasacables y LED')" class="px-2.5 py-1 rounded-lg border border-border-subtle bg-surface-card hover:bg-surface text-primary transition-colors">
                    📺 Mueble TV 200cm LED
                </button>
            </div>

            <!-- Input Bar -->
            <div class="p-3 sm:p-4 bg-surface-card border-t border-border-subtle">
                <form id="aiChatForm" onsubmit="handleSendAiMessage(event)" class="flex items-center gap-2">
                    <div class="relative flex-1">
                        <input type="text" id="aiInputPrompt" required placeholder="Escribe tu consulta o describe el mueble que deseas diseñar..." class="w-full text-xs sm:text-sm pl-4 pr-10 py-3 rounded-xl border border-border-subtle bg-surface focus:outline-none focus:border-secondary-container font-sans text-primary placeholder-text-muted shadow-inner">
                        <button type="button" onclick="document.getElementById('aiInputPrompt').value = '';" class="absolute right-3 top-3 text-text-muted hover:text-primary">
                            <span class="material-symbols-outlined text-[18px]">clear</span>
                        </button>
                    </div>

                    <button type="submit" id="btnSendAi" class="px-5 py-3 rounded-xl bg-secondary-container hover:bg-accent-hover text-white font-mono font-bold text-xs sm:text-sm tracking-wide transition-all shadow-md flex items-center gap-1.5 shrink-0 cursor-pointer">
                        <span>Enviar</span>
                        <span class="material-symbols-outlined text-[18px]">send</span>
                    </button>
                </form>
            </div>

        </div>

        <!-- Right: Intelligent Tools & Live Config Card (4 cols) -->
        <div class="lg:col-span-4 flex flex-col gap-5">
            
            <!-- Dynamic Furniture Card (Populated by AI) -->
            <div id="aiGeneratedCard" class="bg-surface-card rounded-2xl border-2 border-secondary-container shadow-md p-5 relative overflow-hidden transition-all hidden">
                <div class="flex items-center justify-between pb-3 border-b border-border-subtle mb-3">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary-container text-xl">auto_fix_high</span>
                        <h2 class="font-mono font-bold text-xs uppercase tracking-wider text-primary">Diseño Sugerido por IA</h2>
                    </div>
                    <span id="aiCardPrice" class="font-mono font-extrabold text-base text-secondary-container">€1,515</span>
                </div>

                <div class="space-y-2 text-xs">
                    <h3 id="aiCardTitle" class="font-bold text-sm text-primary font-mono">Aparador Alistonado Horizon</h3>
                    <div id="aiCardDims" class="font-mono text-[11px] text-text-muted bg-surface-container px-2.5 py-1 rounded-lg inline-block">
                        220 × 85 × 45 cm • 3 Módulos
                    </div>
                    
                    <div class="grid grid-cols-2 gap-2 text-[11px] pt-1">
                        <div>
                            <span class="text-text-muted block font-mono">Madera:</span>
                            <span id="aiCardWood" class="font-semibold text-primary">Roble Nórdico Miel</span>
                        </div>
                        <div>
                            <span class="text-text-muted block font-mono">Acabado:</span>
                            <span id="aiCardFinish" class="font-semibold text-primary">Aceite Rubio®</span>
                        </div>
                    </div>

                    <div class="pt-3">
                        <a id="aiCardBtnAction" href="/customizer" class="w-full py-2.5 rounded-xl bg-primary hover:bg-primary-variant text-white font-mono font-bold text-xs tracking-wide transition-all shadow-md flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-secondary">view_in_ar</span>
                            <span>Cargar en Visor 3D</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Technical Knowledge Cheat-Sheet -->
            <div class="bg-surface-card rounded-2xl border border-border-subtle shadow-xs p-5">
                <h3 class="font-mono font-bold text-xs uppercase tracking-wider text-primary flex items-center gap-2 mb-3">
                    <span class="material-symbols-outlined text-secondary-container text-lg">science</span>
                    Guía Rápida de Maderas FSC®
                </h3>

                <div class="space-y-3 text-xs">
                    <div class="p-2.5 rounded-xl bg-surface-container/60 border border-border-subtle">
                        <div class="flex justify-between items-baseline mb-0.5">
                            <span class="font-bold text-primary font-mono">Roble Europeo (Quercus)</span>
                            <span class="text-[10px] font-mono text-state-success font-bold">720 kg/m³</span>
                        </div>
                        <p class="text-[11px] text-text-muted">Dureza Janka 6.000 N. Excelente para tapas y frentes alistonados.</p>
                    </div>

                    <div class="p-2.5 rounded-xl bg-surface-container/60 border border-border-subtle">
                        <div class="flex justify-between items-baseline mb-0.5">
                            <span class="font-bold text-primary font-mono">Nogal Americano (Juglans)</span>
                            <span class="text-[10px] font-mono text-amber-600 font-bold">640 kg/m³</span>
                        </div>
                        <p class="text-[11px] text-text-muted">Veta chocolate de alto valor estético. Acabado al aceite natural.</p>
                    </div>

                    <div class="p-2.5 rounded-xl bg-surface-container/60 border border-border-subtle">
                        <div class="flex justify-between items-baseline mb-0.5">
                            <span class="font-bold text-primary font-mono">Castaño Ibérico (Castanea)</span>
                            <span class="text-[10px] font-mono text-blue-600 font-bold">590 kg/m³</span>
                        </div>
                        <p class="text-[11px] text-text-muted">Rico en taninos antifúngicos. La mejor opción para baños y humedad.</p>
                    </div>
                </div>
            </div>

            <!-- CNC Quick Diagnostic -->
            <div class="bg-surface-card rounded-2xl border border-border-subtle shadow-xs p-5">
                <h3 class="font-mono font-bold text-xs uppercase tracking-wider text-primary flex items-center gap-2 mb-2">
                    <span class="material-symbols-outlined text-secondary-container text-lg">precision_manufacturing</span>
                    Norma Técnica de Taller
                </h3>
                <ul class="text-xs space-y-2 text-text-muted">
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-[16px] text-state-success shrink-0 mt-0.5">check_circle</span>
                        <span><strong>Tolerancia de corte:</strong> ±0.15 mm con fresa helicoidal de compresión Ø12.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-[16px] text-state-success shrink-0 mt-0.5">check_circle</span>
                        <span><strong>Deflexión límite:</strong> $L/200$ para baldas bajo carga de 35 kg.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-[16px] text-state-success shrink-0 mt-0.5">check_circle</span>
                        <span><strong>Canteado:</strong> Adhesivo PUR a 140°C resistente a vapor.</span>
                    </li>
                </ul>
            </div>

        </div>

    </div>
</div>

<!-- CarpinterAI Chat Script -->
<script>
async function handleSendAiMessage(e) {
    e.preventDefault();
    const input = document.getElementById('aiInputPrompt');
    const msg = input.value.trim();
    if (!msg) return;

    // Append user message
    appendMessage('user', msg);
    input.value = '';

    // Typing indicator
    const typingId = appendTypingIndicator();

    const btn = document.getElementById('btnSendAi');
    btn.disabled = true;

    try {
        const resp = await fetch('/api/ai/chat', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ message: msg })
        });
        const data = await resp.json();
        removeTypingIndicator(typingId);

        // Append bot reply
        appendMessage('bot', data.reply);

        // If furniture config returned, populate dynamic card
        if (data.type === 'furniture_config' && data.config) {
            showFurnitureCard(data.config);
        }

        // Update suggestion chips
        if (data.suggestions && data.suggestions.length) {
            updateChips(data.suggestions);
        }
    } catch (err) {
        console.error(err);
        removeTypingIndicator(typingId);
        appendMessage('bot', 'Hubo un error de conexión con el motor de IA. Por favor, intenta de nuevo.');
    } finally {
        btn.disabled = false;
    }
}

function sendQuickPrompt(promptText) {
    document.getElementById('aiInputPrompt').value = promptText;
    document.getElementById('aiChatForm').dispatchEvent(new Event('submit'));
}

function appendMessage(sender, text) {
    const container = document.getElementById('aiChatMessages');
    const wrapper = document.createElement('div');
    wrapper.className = sender === 'user' 
        ? 'flex items-start gap-3 justify-end' 
        : 'flex items-start gap-3 max-w-3xl';

    const formattedText = formatMarkdown(text);

    if (sender === 'user') {
        wrapper.innerHTML = `
            <div class="bg-primary text-white rounded-2xl rounded-tr-xs px-4 py-3 shadow-xs max-w-xl">
                <p class="leading-relaxed">${escapeHtml(text)}</p>
            </div>
            <div class="w-8 h-8 rounded-lg bg-secondary-container text-white flex items-center justify-center shrink-0 mt-0.5">
                <span class="material-symbols-outlined text-[18px]">person</span>
            </div>
        `;
    } else {
        wrapper.innerHTML = `
            <div class="w-8 h-8 rounded-lg bg-primary text-secondary flex items-center justify-center shrink-0 mt-0.5">
                <span class="material-symbols-outlined text-[18px]">smart_toy</span>
            </div>
            <div class="bg-surface-card border border-border-subtle rounded-2xl rounded-tl-xs p-4 shadow-xs text-text-primary leading-relaxed max-w-2xl">
                ${formattedText}
            </div>
        `;
    }

    container.appendChild(wrapper);
    container.scrollTop = container.scrollHeight;
}

function appendTypingIndicator() {
    const container = document.getElementById('aiChatMessages');
    const id = 'typing-' + Date.now();
    const wrapper = document.createElement('div');
    wrapper.id = id;
    wrapper.className = 'flex items-start gap-3';
    wrapper.innerHTML = `
        <div class="w-8 h-8 rounded-lg bg-primary text-secondary flex items-center justify-center shrink-0 mt-0.5">
            <span class="material-symbols-outlined text-[18px]">smart_toy</span>
        </div>
        <div class="bg-surface-card border border-border-subtle rounded-2xl rounded-tl-xs px-4 py-3 shadow-xs flex items-center gap-1.5 text-text-muted">
            <span class="w-2 h-2 rounded-full bg-secondary-container animate-bounce"></span>
            <span class="w-2 h-2 rounded-full bg-secondary-container animate-bounce [animation-delay:0.2s]"></span>
            <span class="w-2 h-2 rounded-full bg-secondary-container animate-bounce [animation-delay:0.4s]"></span>
            <span class="text-xs font-mono ml-2">Analizando parámetros de taller...</span>
        </div>
    `;
    container.appendChild(wrapper);
    container.scrollTop = container.scrollHeight;
    return id;
}

function removeTypingIndicator(id) {
    const el = document.getElementById(id);
    if (el) el.remove();
}

function showFurnitureCard(cfg) {
    const card = document.getElementById('aiGeneratedCard');
    document.getElementById('aiCardTitle').innerText = cfg.model_name;
    document.getElementById('aiCardPrice').innerText = '€' + Number(cfg.price).toLocaleString();
    document.getElementById('aiCardDims').innerText = `${cfg.width} × ${cfg.height} × ${cfg.depth} cm • ${cfg.modules} Módulos`;
    document.getElementById('aiCardWood').innerText = cfg.wood_name;
    document.getElementById('aiCardFinish').innerText = cfg.finish_name;
    document.getElementById('aiCardBtnAction').href = cfg.url;
    
    card.classList.remove('hidden');
    card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function updateChips(suggestions) {
    const bar = document.getElementById('aiChipsBar');
    let html = '<span class="text-text-muted font-bold">Sugerencias:</span>';
    suggestions.forEach(s => {
        html += `<button onclick="sendQuickPrompt('${escapeHtml(s)}')" class="px-2.5 py-1 rounded-lg border border-border-subtle bg-surface-card hover:bg-surface text-primary transition-colors">${escapeHtml(s)}</button>`;
    });
    bar.innerHTML = html;
}

function clearChatHistory() {
    const container = document.getElementById('aiChatMessages');
    container.innerHTML = `
        <div class="flex items-start gap-3 max-w-3xl">
            <div class="w-8 h-8 rounded-lg bg-primary text-secondary flex items-center justify-center shrink-0 mt-0.5">
                <span class="material-symbols-outlined text-[18px]">smart_toy</span>
            </div>
            <div class="bg-surface-card border border-border-subtle rounded-2xl rounded-tl-xs p-4 shadow-xs text-text-primary">
                <p class="font-semibold text-primary mb-1">Conversación reiniciada</p>
                <p class="text-text-muted">¿Qué mueble o consulta técnica de corte deseas revisar?</p>
            </div>
        </div>
    `;
}

function formatMarkdown(text) {
    let out = escapeHtml(text);
    // Bold
    out = out.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
    // Headers
    out = out.replace(/^### (.*$)/gim, '<h4 class="font-bold font-mono text-sm text-primary mt-2 mb-1">$1</h4>');
    // Bullet points
    out = out.replace(/^• (.*$)/gim, '<li class="ml-4 list-disc">$1</li>');
    // Line breaks
    out = out.replace(/\n/g, '<br>');
    return out;
}

function escapeHtml(str) {
    return str
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}
</script>
