<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$cartCount = count($_SESSION['cart'] ?? []);
$currentRoute = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
?>
<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'CarpinterSoft • Ebanistería Digital 3D' ?></title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#103E4A',
                        'primary-variant': '#1B5E6B',
                        'primary-container': '#237B8C',
                        secondary: '#D4A373',
                        'secondary-container': '#C27C44',
                        'accent-hover': '#A8622F',
                        surface: '#FDFBF7',
                        'surface-card': '#FFFFFF',
                        'surface-container': '#F4F0E8',
                        'surface-container-high': '#EAE5DB',
                        'surface-container-low': '#FAF7F0',
                        'canvas-cream': '#F5F2EB',
                        'border-subtle': '#E8E2D5',
                        'text-primary': '#1F2937',
                        'text-muted': '#6B7280',
                        'state-success': '#15803D',
                        'state-error': '#DC2626'
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
                        mono: ['"Space Grotesk"', 'monospace']
                    }
                }
            }
        }
    </script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Google Material Symbols -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            vertical-align: middle;
        }
    </style>
</head>
<body class="min-h-full flex flex-col bg-surface text-text-primary font-sans antialiased">
    <!-- Top Navigation Header -->
    <header className="fixed top-0 left-0 right-0 z-40 bg-surface-card/95 backdrop-blur-md border-b border-border-subtle shadow-xs" style="position: sticky; top: 0; z-index: 40; background-color: rgba(255, 255, 255, 0.95); backdrop-filter: blur(8px); border-bottom: 1px solid #E8E2D5;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">
            <!-- Brand Logo -->
            <a href="/" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center text-white shadow-md group-hover:scale-105 transition-transform">
                    <span class="material-symbols-outlined text-[22px] text-secondary">handyman</span>
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-lg md:text-xl text-primary tracking-tight font-mono leading-none">
                        Carpinter<span class="text-secondary-container">Soft</span>
                    </span>
                    <span class="text-[10px] text-text-muted uppercase tracking-wider font-semibold font-mono mt-0.5">
                        CNC 3D Paramétrico • PHP 8.2
                    </span>
                </div>
            </a>

            <!-- Navigation Links -->
            <nav class="hidden md:flex items-center gap-1 lg:gap-2 text-xs lg:text-sm font-semibold">
                <a href="/" class="px-3 py-2 rounded-lg transition-colors <?= $currentRoute === '/' ? 'bg-surface-container text-primary font-bold' : 'text-text-muted hover:text-primary hover:bg-surface-container-low' ?>">
                    Inicio
                </a>
                <a href="/customizer" class="px-3 py-2 rounded-lg transition-colors flex items-center gap-1.5 <?= str_starts_with($currentRoute, '/customizer') ? 'bg-primary text-white font-bold shadow-sm' : 'text-text-muted hover:text-primary hover:bg-surface-container-low' ?>">
                    <span class="material-symbols-outlined text-[16px] text-secondary">view_in_ar</span>
                    <span>Personalizador 3D</span>
                </a>
                <a href="/catalog" class="px-3 py-2 rounded-lg transition-colors <?= str_starts_with($currentRoute, '/catalog') ? 'bg-surface-container text-primary font-bold' : 'text-text-muted hover:text-primary hover:bg-surface-container-low' ?>">
                    Catálogo
                </a>
                <a href="/kanban" class="px-3 py-2 rounded-lg transition-colors flex items-center gap-1.5 <?= str_starts_with($currentRoute, '/kanban') ? 'bg-surface-container text-primary font-bold' : 'text-text-muted hover:text-primary hover:bg-surface-container-low' ?>">
                    <span class="material-symbols-outlined text-[16px] text-secondary-container">view_column</span>
                    <span>Taller Kanban</span>
                </a>
                <a href="/nesting" class="px-3 py-2 rounded-lg transition-colors <?= str_starts_with($currentRoute, '/nesting') ? 'bg-surface-container text-primary font-bold' : 'text-text-muted hover:text-primary hover:bg-surface-container-low' ?>">
                    Despiece CNC
                </a>
                <a href="/ai" class="px-3 py-2 rounded-lg transition-colors flex items-center gap-1.5 <?= str_starts_with($currentRoute, '/ai') ? 'bg-secondary-container text-white font-bold shadow-xs' : 'text-text-muted hover:text-primary hover:bg-surface-container-low' ?>">
                    <span class="material-symbols-outlined text-[16px] <?= str_starts_with($currentRoute, '/ai') ? 'text-white' : 'text-secondary-container' ?>">psychology</span>
                    <span>CarpinterAI</span>
                    <span class="px-1.5 py-0.5 rounded text-[9px] font-mono uppercase font-extrabold <?= str_starts_with($currentRoute, '/ai') ? 'bg-white/20 text-white' : 'bg-secondary-container/15 text-secondary-container' ?>">IA</span>
                </a>
            </nav>

            <!-- Actions: Cart & Quick CTA -->
            <div class="flex items-center gap-3">
                <a href="/checkout" class="relative p-2.5 rounded-xl bg-surface-container hover:bg-surface-container-high text-primary transition-colors flex items-center justify-center border border-border-subtle" title="Ver presupuesto de fabricación">
                    <span class="material-symbols-outlined text-[20px]">shopping_bag</span>
                    <?php if ($cartCount > 0): ?>
                        <span class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-secondary-container text-white text-[11px] font-bold flex items-center justify-center shadow-sm">
                            <?= $cartCount ?>
                        </span>
                    <?php endif; ?>
                </a>

                <a href="/customizer" class="hidden sm:inline-flex items-center gap-1.5 px-4 h-10 rounded-xl bg-secondary-container text-white text-xs lg:text-sm font-semibold hover:bg-accent-hover transition-transform active:scale-95 shadow-md shadow-secondary-container/20">
                    <span class="material-symbols-outlined text-[18px]">straighten</span>
                    <span>Configurar Mueble</span>
                </a>
            </div>
        </div>
    </header>
    <main class="flex-1 flex flex-col">
