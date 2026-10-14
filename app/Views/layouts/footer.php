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
        </div>
    </footer>
</body>
</html>
