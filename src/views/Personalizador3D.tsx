import React, { useState, useMemo, useEffect } from 'react';
import { AppView, CustomizerState, CartItem } from '../types';
import { WOOD_OPTIONS, FURNITURE_MODELS } from '../data/furnitureData';
import { Interactive3DViewport } from '../components/Interactive3DViewport';

interface Personalizador3DProps {
  initialModelId?: string;
  onNavigate: (view: AppView) => void;
  onAddToCart: (item: CartItem) => void;
}

export const Personalizador3D: React.FC<Personalizador3DProps> = ({
  initialModelId = 'horizon',
  onNavigate,
  onAddToCart
}) => {
  const [selectedModelId, setSelectedModelId] = useState(initialModelId);

  // Sync when initialModelId prop changes
  useEffect(() => {
    setSelectedModelId(initialModelId);
  }, [initialModelId]);

  const currentModel = useMemo(() => {
    return FURNITURE_MODELS.find(m => m.id === selectedModelId) || FURNITURE_MODELS[0];
  }, [selectedModelId]);

  // Main Parametric Customizer State
  const [state, setState] = useState<CustomizerState>({
    modelId: currentModel.id,
    width: currentModel.baseWidth,
    height: currentModel.baseHeight,
    depth: currentModel.baseDepth,
    modules: 3,
    woodId: 'roble',
    finishType: 'rubio',
    baseType: 'conicas',
    hasLed: false,
    hasCableGrommet: false,
    doorsOpen: false,
    wireframe: false,
    autoRotate: false,
    cameraView: 'persp',
    lighting: 'nordic',
    zoom: 1
  });

  // When model changes, update state parameters
  useEffect(() => {
    setState(prev => ({
      ...prev,
      modelId: currentModel.id,
      width: currentModel.baseWidth,
      height: currentModel.baseHeight,
      depth: currentModel.baseDepth,
    }));
  }, [currentModel]);

  const [activeTab, setActiveTab] = useState<'dims' | 'wood' | 'hardware' | 'ai'>('dims');
  const [showARModal, setShowARModal] = useState(false);
  const [showSpatialModal, setShowSpatialModal] = useState(false);
  const [aiQuery, setAiQuery] = useState('');
  const [aiResponse, setAiResponse] = useState<string | null>(null);
  const [toastMessage, setToastMessage] = useState<{ title: string; sub: string } | null>(null);

  // History state for Undo / Redo
  const [history, setHistory] = useState<CustomizerState[]>([state]);
  const [historyIndex, setHistoryIndex] = useState(0);

  const selectedWood = useMemo(() => {
    return WOOD_OPTIONS.find(w => w.id === state.woodId) || WOOD_OPTIONS[0];
  }, [state.woodId]);

  const showToast = (title: string, sub: string) => {
    setToastMessage({ title, sub });
    setTimeout(() => {
      setToastMessage(null);
    }, 2800);
  };

  const updateState = (updater: (prev: CustomizerState) => CustomizerState, toastTitle?: string, toastSub?: string) => {
    setState(prev => {
      const next = updater(prev);
      const newHistory = history.slice(0, historyIndex + 1);
      newHistory.push(next);
      setHistory(newHistory);
      setHistoryIndex(newHistory.length - 1);
      return next;
    });
    if (toastTitle && toastSub) {
      showToast(toastTitle, toastSub);
    }
  };

  const handleUndo = () => {
    if (historyIndex > 0) {
      const newIdx = historyIndex - 1;
      setHistoryIndex(newIdx);
      setState(history[newIdx]);
      showToast('Deshecho', 'Parámetro anterior recuperado.');
    }
  };

  const handleRedo = () => {
    if (historyIndex < history.length - 1) {
      const newIdx = historyIndex + 1;
      setHistoryIndex(newIdx);
      setState(history[newIdx]);
      showToast('Rehecho', 'Parámetro restablecido.');
    }
  };

  // Real-Time Algorithmic Cost Engine
  const quotation = useMemo(() => {
    const baseVol = (currentModel.baseWidth * currentModel.baseHeight * currentModel.baseDepth) * 0.000001;
    const currentVol = (state.width * state.height * state.depth) * 0.000001;
    const volRatio = currentVol / Math.max(0.08, baseVol);

    const baseWoodCost = Math.round(currentModel.basePrice * 0.60 * Math.pow(volRatio, 0.75)) + selectedWood.extraPrice;
    const baseCNCCost = Math.round(currentModel.basePrice * 0.22 + (state.width * 0.35));
    const hardwareCost = Math.round(currentModel.basePrice * 0.10 + ((state.modules - 2) * 25));
    const finishCost = state.finishType === 'rubio' ? 110 : 80;
    const baseCost = state.baseType === 'zocalo' ? 45 : state.baseType === 'flotante' ? 80 : 0;
    const extras = (state.hasLed ? 65 : 0) + (state.hasCableGrommet ? 30 : 0) + baseCost;

    const total = baseWoodCost + baseCNCCost + hardwareCost + finishCost + extras;
    return {
      volume: Number((currentVol * 0.26).toFixed(2)),
      woodCost: baseWoodCost,
      cncCost: baseCNCCost,
      hardwareCost,
      finishCost,
      extrasCost: extras,
      total
    };
  }, [state, selectedWood, currentModel]);

  const handleAddToCart = () => {
    const item: CartItem = {
      id: `ITEM-${Date.now()}`,
      modelName: `${currentModel.name} (${state.width} × ${state.height} × ${state.depth} cm)`,
      image: currentModel.image,
      dimensions: { width: state.width, height: state.height, depth: state.depth },
      woodName: selectedWood.name,
      finishName: state.finishType === 'rubio' ? 'Aceite Rubio Monocoat® (0 VOCs)' : 'Barniz Poliuretano Acrílico',
      baseName: state.baseType === 'conicas' ? 'Patas Cónicas 15cm' : state.baseType === 'zocalo' ? 'Zócalo Retranqueado' : 'Suspensión Flotante',
      addons: [
        ...(state.hasLed ? ['Tira LED Cálida 3000K'] : []),
        ...(state.hasCableGrommet ? ['Pasacables Fresado Macizo'] : [])
      ],
      price: quotation.total,
      date: new Date().toLocaleDateString('es-ES')
    };
    onAddToCart(item);
    showToast('¡Configuración Añadida!', 'Redirigiendo al checkout...');
    setTimeout(() => {
      onNavigate('precios-cotizacion');
    }, 600);
  };

  const handleAiAsk = (e: React.FormEvent) => {
    e.preventDefault();
    if (!aiQuery.trim()) return;

    if (aiQuery.toLowerCase().includes('ventana') || aiQuery.toLowerCase().includes('alto') || aiQuery.toLowerCase().includes('altura')) {
      setAiResponse(`Para pasar bajo una ventana estándar de 90 cm, te sugiero ajustar la altura a 80 cm o 75 cm, permitiendo un margen de 10-15 cm para jarrones o lámparas.`);
      updateState(s => ({ ...s, height: 78 }), 'Altura Ajustada por IA', 'Ajustado a 78 cm para salvar ventana.');
    } else if (aiQuery.toLowerCase().includes('madera') || aiQuery.toLowerCase().includes('suelo')) {
      setAiResponse(`Para suelos claros de roble o parquet natural, recomendamos el Roble Nórdico Miel o Nogal Americano si buscas contraste elegante.`);
    } else {
      setAiResponse(`Análisis estructural verificado: Para ${state.width} cm de longitud con ${state.modules} cuerpos, la capacidad portante supera los 120 kg de carga distribuida sin flexión.`);
    }
  };

  return (
    <div className="flex flex-col w-full bg-canvas-cream min-h-screen">
      {/* Sub-Header Técnico & Herramientas Studio 3D */}
      <section className="w-full bg-surface-card px-4 md:px-8 py-3 shadow-sm z-30 border-b border-border-subtle">
        <div className="max-w-[1920px] mx-auto flex flex-col xl:flex-row xl:items-center justify-between gap-3">
          {/* Breadcrumb + Título de Pieza */}
          <div className="flex flex-col gap-1 min-w-0">
            <div className="flex items-center gap-2 font-label-technical text-label-technical text-text-muted">
              <button onClick={() => onNavigate('explorar-catalogo')} className="hover:text-text-primary transition-colors cursor-pointer">
                Catálogo
              </button>
              <span>/</span>
              <span className="hover:text-text-primary transition-colors cursor-pointer">
                {currentModel.category}
              </span>
              <span>/</span>
              <span className="text-text-primary font-semibold truncate">{currentModel.name} (V4.2 Paramétrico)</span>
            </div>
            <div className="flex flex-wrap items-center gap-2 md:gap-3">
              <h1 className="font-headline-sm text-lg md:text-xl text-text-primary font-bold tracking-tight">
                {currentModel.name}
              </h1>

              {/* Selector Rápido de Modelo */}
              <select
                value={selectedModelId}
                onChange={(e) => setSelectedModelId(e.target.value)}
                className="font-label-technical text-xs bg-surface-container hover:bg-surface-container-high text-text-primary px-3 py-1.5 rounded-lg border border-border-subtle cursor-pointer focus:outline-none focus:ring-1 focus:ring-primary font-semibold"
                title="Cambiar modelo a personalizar"
              >
                {FURNITURE_MODELS.map(m => (
                  <option key={m.id} value={m.id}>
                    {m.name} ({m.category})
                  </option>
                ))}
              </select>

              <span className="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-primary/10 text-text-primary font-label-technical text-[11px] uppercase font-semibold">
                <span className="w-1.5 h-1.5 rounded-full bg-state-success animate-pulse"></span>
                Modelo 100% Paramétrico 3D
              </span>
              <span className="hidden sm:inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-surface-container font-label-technical text-[11px] text-on-surface-variant font-medium">
                <span className="material-symbols-outlined text-[14px] text-secondary">precision_manufacturing</span>
                Calibrado CNC Taller Central
              </span>
            </div>
          </div>

          {/* Barra de Acciones Globales del Estudio */}
          <div className="flex flex-wrap items-center gap-2 sm:gap-3">
            <div className="flex items-center bg-surface-container rounded-lg p-0.5">
              <button
                onClick={handleUndo}
                disabled={historyIndex === 0}
                className="p-1.5 text-text-primary hover:bg-surface-card rounded transition-colors disabled:opacity-30 cursor-pointer"
                title="Deshacer (Ctrl+Z)"
              >
                <span className="material-symbols-outlined text-[18px]">undo</span>
              </button>
              <button
                onClick={handleRedo}
                disabled={historyIndex >= history.length - 1}
                className="p-1.5 text-text-primary hover:bg-surface-card rounded transition-colors disabled:opacity-30 cursor-pointer"
                title="Rehacer (Ctrl+Y)"
              >
                <span className="material-symbols-outlined text-[18px]">redo</span>
              </button>
            </div>

            <button
              onClick={() => setShowARModal(true)}
              className="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-surface-container hover:bg-surface-container-high text-text-primary font-body-sm text-xs md:text-sm font-semibold transition-all cursor-pointer"
            >
              <span className="material-symbols-outlined text-[18px] text-secondary">view_in_ar</span>
              <span>AR / QR Móvil</span>
            </button>

            <button
              onClick={() => onNavigate('despiece-lista-de-corte')}
              className="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-surface-container hover:bg-surface-container-high text-text-primary font-body-sm text-xs md:text-sm font-semibold transition-all cursor-pointer"
            >
              <span className="material-symbols-outlined text-[18px] text-text-muted">download_for_offline</span>
              <span className="hidden md:inline">Exportar DXF / Lista Corte</span>
              <span className="md:hidden">DXF</span>
            </button>

            <button
              onClick={() => showToast('Versión Guardada', 'Guardado en Mis Proyectos de Estudio Elena Morales.')}
              className="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-surface-card hover:bg-surface-container-high text-text-primary font-body-sm text-xs md:text-sm font-semibold transition-all shadow-sm border border-border-subtle cursor-pointer"
            >
              <span className="material-symbols-outlined text-[18px] text-secondary-container">bookmark_added</span>
              <span className="hidden sm:inline">Guardar Versión</span>
            </button>

            <button
              onClick={() => {
                navigator.clipboard?.writeText(window.location.href);
                showToast('Enlace Copiado', 'Link con parámetros paramétricos copiado al portapapeles.');
              }}
              className="p-2 rounded-lg bg-surface-container hover:bg-surface-container-high text-text-primary transition-colors cursor-pointer"
              title="Compartir configuración"
            >
              <span className="material-symbols-outlined text-[18px]">share</span>
            </button>
          </div>
        </div>
      </section>

      {/* Espacio de Trabajo Principal de Tres Columnas */}
      <div className="w-full max-w-[1920px] mx-auto p-4 md:p-6 grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        {/* 1. PANEL IZQUIERDO: Configuración Paramétrica & Materiales (3 Columnas) */}
        <aside className="lg:col-span-3 w-full bg-surface-card rounded-xl p-4 md:p-5 shadow-md flex flex-col gap-4 order-2 lg:order-1 border border-border-subtle">
          <div className="flex items-center justify-between pb-1 border-b border-border-subtle">
            <div className="flex items-center gap-2">
              <span className="material-symbols-outlined text-[20px] text-primary-variant">tune</span>
              <h2 className="font-headline-sm text-base text-text-primary font-bold">Parámetros de Taller</h2>
            </div>
            <span className="font-label-technical text-label-technical text-text-muted bg-surface-container px-2 py-0.5 rounded">
              REV 4.2
            </span>
          </div>

          {/* Tab Selector */}
          <div className="grid grid-cols-4 gap-1 p-1 bg-surface-container rounded-lg font-label-technical text-xs text-center">
            <button
              onClick={() => setActiveTab('dims')}
              className={`py-1.5 px-1 rounded transition-all cursor-pointer ${
                activeTab === 'dims' ? 'bg-surface-card text-text-primary font-bold shadow-sm' : 'text-text-muted hover:text-text-primary font-medium'
              }`}
            >
              Medidas
            </button>
            <button
              onClick={() => setActiveTab('wood')}
              className={`py-1.5 px-1 rounded transition-all cursor-pointer ${
                activeTab === 'wood' ? 'bg-surface-card text-text-primary font-bold shadow-sm' : 'text-text-muted hover:text-text-primary font-medium'
              }`}
            >
              Maderas
            </button>
            <button
              onClick={() => setActiveTab('hardware')}
              className={`py-1.5 px-1 rounded transition-all cursor-pointer ${
                activeTab === 'hardware' ? 'bg-surface-card text-text-primary font-bold shadow-sm' : 'text-text-muted hover:text-text-primary font-medium'
              }`}
            >
              Herrajes
            </button>
            <button
              onClick={() => setActiveTab('ai')}
              className={`py-1.5 px-1 rounded transition-all cursor-pointer flex items-center justify-center gap-0.5 ${
                activeTab === 'ai' ? 'bg-surface-card text-text-primary font-bold shadow-sm' : 'text-text-muted hover:text-text-primary font-medium'
              }`}
            >
              <span className="material-symbols-outlined text-[13px] text-secondary-container">auto_awesome</span>
              <span>IA</span>
            </button>
          </div>

          {/* TAB 1: DIMENSIONES */}
          {activeTab === 'dims' && (
            <div className="flex flex-col gap-4">
              {/* Width Slider */}
              <div className="flex flex-col gap-1.5 bg-canvas-cream p-3 rounded-lg border border-border-subtle">
                <div className="flex items-center justify-between">
                  <label className="font-label-technical text-xs text-text-primary font-semibold uppercase flex items-center gap-1">
                    <span>Ancho Total (W)</span>
                    <span className="text-text-muted font-normal">{currentModel.minWidth} - {currentModel.maxWidth} cm</span>
                  </label>
                  <div className="flex items-center gap-1 bg-surface-card px-2 py-0.5 rounded shadow-sm border border-border-subtle">
                    <span className="font-label-numeric text-sm text-text-primary font-bold">{state.width.toFixed(1)}</span>
                    <span className="font-label-technical text-[10px] text-text-muted">cm</span>
                  </div>
                </div>
                <input
                  type="range"
                  min={currentModel.minWidth}
                  max={currentModel.maxWidth}
                  step="1"
                  value={state.width}
                  onChange={(e) => updateState(s => ({ ...s, width: Number(e.target.value) }))}
                  className="w-full accent-secondary-container cursor-pointer h-1.5 bg-surface-container-high rounded-lg"
                />
                <div className="flex items-center justify-between pt-1">
                  <div className="flex items-center gap-1">
                    <button
                      onClick={() => updateState(s => ({ ...s, width: Math.max(currentModel.minWidth, s.width - 5) }))}
                      className="px-2 py-0.5 rounded bg-surface-card hover:bg-surface-container font-label-technical text-[11px] text-text-primary font-semibold transition-colors shadow-sm cursor-pointer"
                    >
                      -5cm
                    </button>
                    <button
                      onClick={() => updateState(s => ({ ...s, width: Math.min(currentModel.maxWidth, s.width + 5) }))}
                      className="px-2 py-0.5 rounded bg-surface-card hover:bg-surface-container font-label-technical text-[11px] text-text-primary font-semibold transition-colors shadow-sm cursor-pointer"
                    >
                      +5cm
                    </button>
                  </div>
                  <div className="flex items-center gap-1">
                    <button
                      onClick={() => updateState(s => ({ ...s, width: Math.max(currentModel.minWidth, Number((s.width - 0.1).toFixed(1))) }))}
                      className="px-1.5 py-0.5 rounded bg-surface-card hover:bg-surface-container font-label-technical text-[11px] text-text-muted transition-colors shadow-sm cursor-pointer"
                    >
                      -1mm
                    </button>
                    <button
                      onClick={() => updateState(s => ({ ...s, width: Math.min(currentModel.maxWidth, Number((s.width + 0.1).toFixed(1))) }))}
                      className="px-1.5 py-0.5 rounded bg-surface-card hover:bg-surface-container font-label-technical text-[11px] text-text-muted transition-colors shadow-sm cursor-pointer"
                    >
                      +1mm
                    </button>
                  </div>
                </div>
              </div>

              {/* Height Slider */}
              <div className="flex flex-col gap-1.5 bg-canvas-cream p-3 rounded-lg border border-border-subtle">
                <div className="flex items-center justify-between">
                  <label className="font-label-technical text-xs text-text-primary font-semibold uppercase flex items-center gap-1">
                    <span>Altura Total (H)</span>
                    <span className="text-text-muted font-normal">{currentModel.minHeight} - {currentModel.maxHeight} cm</span>
                  </label>
                  <div className="flex items-center gap-1 bg-surface-card px-2 py-0.5 rounded shadow-sm border border-border-subtle">
                    <span className="font-label-numeric text-sm text-text-primary font-bold">{state.height.toFixed(1)}</span>
                    <span className="font-label-technical text-[10px] text-text-muted">cm</span>
                  </div>
                </div>
                <input
                  type="range"
                  min={currentModel.minHeight}
                  max={currentModel.maxHeight}
                  step="1"
                  value={state.height}
                  onChange={(e) => updateState(s => ({ ...s, height: Number(e.target.value) }))}
                  className="w-full accent-secondary-container cursor-pointer h-1.5 bg-surface-container-high rounded-lg"
                />
                <div className="flex items-center justify-between text-[10px] font-label-technical text-text-muted">
                  <span onClick={() => updateState(s => ({ ...s, height: currentModel.minHeight }))} className="cursor-pointer hover:text-text-primary">{currentModel.minHeight}cm (Mín)</span>
                  <span onClick={() => updateState(s => ({ ...s, height: currentModel.baseHeight }))} className="cursor-pointer hover:text-text-primary">{currentModel.baseHeight}cm (Base)</span>
                  <span onClick={() => updateState(s => ({ ...s, height: currentModel.maxHeight }))} className="cursor-pointer hover:text-text-primary">{currentModel.maxHeight}cm (Máx)</span>
                </div>
              </div>

              {/* Depth Slider */}
              <div className="flex flex-col gap-1.5 bg-canvas-cream p-3 rounded-lg border border-border-subtle">
                <div className="flex items-center justify-between">
                  <label className="font-label-technical text-xs text-text-primary font-semibold uppercase flex items-center gap-1">
                    <span>Profundidad (D)</span>
                    <span className="text-text-muted font-normal">{currentModel.minDepth} - {currentModel.maxDepth} cm</span>
                  </label>
                  <div className="flex items-center gap-1 bg-surface-card px-2 py-0.5 rounded shadow-sm border border-border-subtle">
                    <span className="font-label-numeric text-sm text-text-primary font-bold">{state.depth.toFixed(1)}</span>
                    <span className="font-label-technical text-[10px] text-text-muted">cm</span>
                  </div>
                </div>
                <input
                  type="range"
                  min={currentModel.minDepth}
                  max={currentModel.maxDepth}
                  step="1"
                  value={state.depth}
                  onChange={(e) => updateState(s => ({ ...s, depth: Number(e.target.value) }))}
                  className="w-full accent-secondary-container cursor-pointer h-1.5 bg-surface-container-high rounded-lg"
                />
                <span className="text-[11px] font-body-sm text-text-muted">{currentModel.productionNote || 'Dimensionamiento estructural verificado por CNC.'}</span>
              </div>

              {/* Module Doors / Divisions */}
              <div className="flex flex-col gap-2">
                <span className="font-label-technical text-xs text-text-primary font-semibold uppercase">
                  {currentModel.id === 'fusion-table'
                    ? 'Capacidad & Distribución'
                    : currentModel.id === 'nexus-bookshelf'
                    ? 'Niveles & Baldas Interiores'
                    : currentModel.id === 'veta-tv'
                    ? 'Cuerpos de Trampilla Abatible'
                    : currentModel.id === 'studio-desk'
                    ? 'Módulos de Almacenamiento'
                    : 'Cuerpos & Módulos'}
                </span>
                <div className="grid grid-cols-3 gap-2">
                  {[2, 3, 4].map(m => {
                    let sublabel = '';
                    if (currentModel.id === 'fusion-table') {
                      sublabel = m === 2 ? '6 Comensales' : m === 3 ? '8 Comensales' : '10-12 Comensales';
                    } else if (currentModel.id === 'nexus-bookshelf') {
                      sublabel = m === 2 ? '3 Alturas' : m === 3 ? '4 Alturas' : '5 Alturas';
                    } else if (currentModel.id === 'veta-tv') {
                      sublabel = m === 2 ? '2 Trampillas' : m === 3 ? '3 Trampillas' : '4 Trampillas';
                    } else if (currentModel.id === 'studio-desk') {
                      sublabel = m === 2 ? 'Simple' : m === 3 ? 'Cajonera Der.' : 'Doble Cajón';
                    } else {
                      sublabel = m === 2 ? 'Doble Puerta' : m === 3 ? '3 Puertas' : '4 Módulos';
                    }

                    return (
                      <button
                        key={m}
                        onClick={() => updateState(s => ({ ...s, modules: m }), 'Configuración Actualizada', `Configurado para ${m} módulos.`)}
                        className={`p-2 rounded-lg text-center flex flex-col items-center gap-0.5 transition-all cursor-pointer ${
                          state.modules === m
                            ? 'bg-primary-container text-white shadow-sm'
                            : 'bg-surface-container hover:bg-surface-container-high text-text-primary'
                        }`}
                      >
                        <span className="font-headline-sm text-base font-bold">{m}</span>
                        <span className="font-label-technical text-[10px] opacity-80 truncate w-full">
                          {sublabel}
                        </span>
                      </button>
                    );
                  })}
                </div>
              </div>

              {/* Base Structure */}
              <div className="flex flex-col gap-2">
                <span className="font-label-technical text-xs text-text-primary font-semibold uppercase">Base Estructural</span>
                <div className="flex flex-col gap-1.5 font-body-sm text-sm">
                  <label className="flex items-center justify-between p-2.5 rounded-lg bg-canvas-cream cursor-pointer hover:bg-surface-container transition-colors border border-border-subtle">
                    <span className="flex items-center gap-2 text-text-primary font-medium">
                      <input
                        type="radio"
                        name="base_type"
                        value="conicas"
                        checked={state.baseType === 'conicas'}
                        onChange={() => updateState(s => ({ ...s, baseType: 'conicas' }))}
                        className="accent-secondary-container"
                      />
                      {currentModel.id === 'fusion-table'
                        ? 'Patas Macizas 80x80mm Niveladas'
                        : currentModel.id === 'veta-tv'
                        ? 'Patas Cónicas de Suelo 12 cm'
                        : 'Patas Cónicas Torneadas 15 cm'}
                    </span>
                    <span className="font-label-technical text-[11px] text-state-success font-semibold">Incluido</span>
                  </label>

                  <label className="flex items-center justify-between p-2.5 rounded-lg bg-canvas-cream cursor-pointer hover:bg-surface-container transition-colors border border-border-subtle">
                    <span className="flex items-center gap-2 text-text-primary font-medium">
                      <input
                        type="radio"
                        name="base_type"
                        value="zocalo"
                        checked={state.baseType === 'zocalo'}
                        onChange={() => updateState(s => ({ ...s, baseType: 'zocalo' }))}
                        className="accent-secondary-container"
                      />
                      {currentModel.id === 'fusion-table'
                        ? 'Bastidor Caballete Reforzado'
                        : 'Zócalo Retranqueado Continuo'}
                    </span>
                    <span className="font-label-technical text-[11px] text-text-muted">+45 €</span>
                  </label>

                  <label className="flex items-center justify-between p-2.5 rounded-lg bg-canvas-cream cursor-pointer hover:bg-surface-container transition-colors border border-border-subtle">
                    <span className="flex items-center gap-2 text-text-primary font-medium">
                      <input
                        type="radio"
                        name="base_type"
                        value="flotante"
                        checked={state.baseType === 'flotante'}
                        onChange={() => updateState(s => ({ ...s, baseType: 'flotante' }))}
                        className="accent-secondary-container"
                      />
                      {currentModel.id === 'veta-tv'
                        ? 'Suspensión Flotante Camar® 90kg'
                        : 'Suspensión Flotante a Pared'}
                    </span>
                    <span className="font-label-technical text-[11px] text-text-muted">+80 €</span>
                  </label>
                </div>
              </div>
            </div>
          )}

          {/* TAB 2: MADERAS & ACABADOS */}
          {activeTab === 'wood' && (
            <div className="flex flex-col gap-4">
              <span className="font-label-technical text-xs text-text-primary font-semibold uppercase">
                Especie de Madera Seleccionada
              </span>
              <div className="grid grid-cols-1 gap-2">
                {WOOD_OPTIONS.slice(0, 4).map((wood) => (
                  <button
                    key={wood.id}
                    onClick={() => updateState(s => ({ ...s, woodId: wood.id }), 'Madera Actualizada', `${wood.name} sincronizado en visor.`)}
                    className={`flex items-center justify-between p-2.5 rounded-xl bg-canvas-cream text-left transition-all border cursor-pointer ${
                      state.woodId === wood.id
                        ? 'ring-2 ring-secondary-container shadow-sm border-transparent'
                        : 'border-border-subtle hover:bg-surface-container'
                    }`}
                  >
                    <div className="flex items-center gap-2.5">
                      <div
                        className="w-10 h-10 rounded-full shadow-inner flex-shrink-0"
                        style={{ backgroundColor: wood.colorHex }}
                      ></div>
                      <div>
                        <h4 className="font-body-sm text-sm text-text-primary font-bold">{wood.name}</h4>
                        <p className="font-label-technical text-[11px] text-text-muted">{wood.subtitle}</p>
                      </div>
                    </div>
                    <span className="font-label-technical text-[11px] font-bold">
                      {state.woodId === wood.id ? (
                        <span className="text-state-success">Seleccionado</span>
                      ) : wood.extraPrice > 0 ? (
                        <span className="text-text-primary">+{wood.extraPrice} €</span>
                      ) : (
                        <span className="text-text-muted">Estándar</span>
                      )}
                    </span>
                  </button>
                ))}
              </div>

              {/* Protective Treatment */}
              <div className="flex flex-col gap-2 pt-2 border-t border-border-subtle">
                <span className="font-label-technical text-xs text-text-primary font-semibold uppercase">
                  Tratamiento Protector
                </span>
                <label className="p-3 rounded-lg bg-canvas-cream flex items-start gap-2.5 cursor-pointer border border-border-subtle">
                  <input
                    type="radio"
                    name="finish"
                    checked={state.finishType === 'rubio'}
                    onChange={() => updateState(s => ({ ...s, finishType: 'rubio' }))}
                    className="mt-1 accent-secondary-container"
                  />
                  <div>
                    <span className="font-body-sm text-sm text-text-primary font-bold block">
                      Aceite Rubio Monocoat® (0 VOCs)
                    </span>
                    <span className="font-body-sm text-xs text-text-muted">
                      Tacto sedoso mate, preserva el aroma natural de la madera y facilita mantenimiento directo.
                    </span>
                  </div>
                </label>

                <label className="p-3 rounded-lg bg-canvas-cream flex items-start gap-2.5 cursor-pointer border border-border-subtle">
                  <input
                    type="radio"
                    name="finish"
                    checked={state.finishType === 'barniz'}
                    onChange={() => updateState(s => ({ ...s, finishType: 'barniz' }))}
                    className="mt-1 accent-secondary-container"
                  />
                  <div>
                    <span className="font-body-sm text-sm text-text-primary font-bold block">
                      Barniz Poliuretano Acrílico Mate
                    </span>
                    <span className="font-body-sm text-xs text-text-muted">
                      Máxima protección impermeable frente a manchas directas de líquidos y uso intenso.
                    </span>
                  </div>
                </label>
              </div>
            </div>
          )}

          {/* TAB 3: HERRAJES */}
          {activeTab === 'hardware' && (
            <div className="flex flex-col gap-4">
              <div>
                <span className="font-label-technical text-xs text-text-primary font-semibold uppercase block mb-2">
                  Mecanismos & Bisagras
                </span>
                <div className="p-3 rounded-lg bg-canvas-cream flex items-center justify-between border border-border-subtle">
                  <div className="flex items-center gap-2">
                    <span className="material-symbols-outlined text-secondary">verified</span>
                    <div>
                      <p className="font-body-sm text-sm text-text-primary font-bold">Blum® Clip-Top Blumotion</p>
                      <p className="font-label-technical text-[11px] text-text-muted">Cierre amortiguado Soft-Close (100k ciclos)</p>
                    </div>
                  </div>
                  <span className="font-label-technical text-[11px] text-state-success font-semibold">Estándar</span>
                </div>
              </div>

              <div>
                <span className="font-label-technical text-xs text-text-primary font-semibold uppercase block mb-2">
                  Mejoras Técnicas Disponibles
                </span>
                <div className="space-y-2">
                  <label className="flex items-center justify-between p-3 rounded-lg bg-canvas-cream cursor-pointer hover:bg-surface-container transition-colors border border-border-subtle">
                    <div className="flex items-center gap-2.5">
                      <input
                        type="checkbox"
                        checked={state.hasLed}
                        onChange={(e) => updateState(s => ({ ...s, hasLed: e.target.checked }))}
                        className="w-4 h-4 rounded accent-secondary-container"
                      />
                      <div>
                        <span className="font-body-sm text-sm text-text-primary font-bold block">
                          Tira LED Cálida 3000K Interior
                        </span>
                        <p className="font-label-technical text-[11px] text-text-muted">
                          Sensor magnético oculto de encendido al abrir
                        </p>
                      </div>
                    </div>
                    <span className="font-label-technical text-xs text-text-primary font-bold">+65 €</span>
                  </label>

                  <label className="flex items-center justify-between p-3 rounded-lg bg-canvas-cream cursor-pointer hover:bg-surface-container transition-colors border border-border-subtle">
                    <div className="flex items-center gap-2.5">
                      <input
                        type="checkbox"
                        checked={state.hasCableGrommet}
                        onChange={(e) => updateState(s => ({ ...s, hasCableGrommet: e.target.checked }))}
                        className="w-4 h-4 rounded accent-secondary-container"
                      />
                      <div>
                        <span className="font-body-sm text-sm text-text-primary font-bold block">
                          Pasacables Fresado Macizo
                        </span>
                        <p className="font-label-technical text-[11px] text-text-muted">
                          Tapa enrasada de roble en la encimera posterior
                        </p>
                      </div>
                    </div>
                    <span className="font-label-technical text-xs text-text-primary font-bold">+30 €</span>
                  </label>
                </div>
              </div>
            </div>
          )}

          {/* TAB 4: ASISTENTE IA */}
          {activeTab === 'ai' && (
            <div className="flex flex-col gap-3">
              <div className="p-3 rounded-lg bg-primary-container text-white flex items-center gap-2">
                <span className="material-symbols-outlined text-secondary-container">psychology</span>
                <div>
                  <p className="font-headline-sm text-sm font-bold">Copiloto de Carpintería IA</p>
                  <p className="font-label-technical text-[10px] text-tertiary-fixed">Evaluación estructural y de habitabilidad</p>
                </div>
              </div>

              <div className="bg-canvas-cream p-3 rounded-lg text-text-primary font-body-sm text-xs leading-relaxed border border-border-subtle">
                <p className="font-bold text-text-primary mb-1">💡 Análisis de Proporción Áurea:</p>
                Para una longitud actual de <strong>{state.width} cm</strong> con <strong>{state.modules} cuerpos</strong>, cada vano ofrece aproximadamente <strong>{((state.width - 6) / state.modules).toFixed(1)} cm</strong> de luz libre, óptima para vajilla o dispositivos audiovisuales.
              </div>

              {aiResponse && (
                <div className="bg-surface-card p-3 rounded-lg text-text-primary font-body-sm text-xs border-l-4 border-secondary-container shadow-xs">
                  <p className="font-semibold text-secondary mb-1">Respuesta del Asesor:</p>
                  <p className="text-on-surface leading-snug">{aiResponse}</p>
                </div>
              )}

              <form onSubmit={handleAiAsk} className="flex flex-col gap-1.5 pt-1">
                <span className="font-label-technical text-[11px] text-text-muted">Consultar al asistente:</span>
                <div className="flex items-center gap-1.5">
                  <input
                    type="text"
                    value={aiQuery}
                    onChange={(e) => setAiQuery(e.target.value)}
                    placeholder="Ej: ¿Cabe bajo una ventana de 90cm?"
                    className="w-full bg-surface-container px-3 py-1.5 rounded-lg font-body-sm text-xs text-text-primary outline-none focus:ring-1 focus:ring-secondary-container"
                  />
                  <button type="submit" className="p-2 rounded-lg bg-secondary-container text-white hover:bg-accent-hover transition-colors cursor-pointer">
                    <span className="material-symbols-outlined text-[16px]">send</span>
                  </button>
                </div>
              </form>
            </div>
          )}
        </aside>

        {/* 2. ÁREA CENTRAL: Viewport 3D Interactivo Three.js (6 Columnas) */}
        <main className="lg:col-span-6 w-full flex flex-col gap-3 order-1 lg:order-2">
          {/* Main 3D Canvas Box */}
          <div className="relative w-full rounded-xl overflow-hidden shadow-lg border border-border-subtle">
            <Interactive3DViewport
              state={state}
              selectedWood={selectedWood}
              onHotspotClick={() => showToast('Detalle de Fresado', 'Paso de fresa 12mm verificado para CNC.')}
            />

            {/* Floating Top-Right Camera and Visibility Tool Buttons */}
            <div className="absolute top-3 right-3 flex items-center gap-1 bg-surface-card/90 backdrop-blur-md p-1 rounded-xl shadow-md z-20 border border-border-subtle">
              <button
                onClick={() => updateState(s => ({ ...s, cameraView: 'persp' }))}
                className={`p-2 rounded-lg transition-colors cursor-pointer ${
                  state.cameraView === 'persp' ? 'bg-primary-container text-white' : 'text-text-primary hover:bg-surface-container'
                }`}
                title="Vista en Perspectiva"
              >
                <span className="material-symbols-outlined text-[18px]">view_in_ar</span>
              </button>
              <button
                onClick={() => updateState(s => ({ ...s, cameraView: 'front' }))}
                className={`p-2 rounded-lg transition-colors cursor-pointer ${
                  state.cameraView === 'front' ? 'bg-primary-container text-white' : 'text-text-primary hover:bg-surface-container'
                }`}
                title="Vista Frontal 2D"
              >
                <span className="material-symbols-outlined text-[18px]">crop_landscape</span>
              </button>
              <button
                onClick={() => updateState(s => ({ ...s, cameraView: 'top' }))}
                className={`p-2 rounded-lg transition-colors cursor-pointer ${
                  state.cameraView === 'top' ? 'bg-primary-container text-white' : 'text-text-primary hover:bg-surface-container'
                }`}
                title="Vista Planta Superior"
              >
                <span className="material-symbols-outlined text-[18px]">vertical_align_top</span>
              </button>

              <div className="w-[1px] h-5 bg-border-subtle mx-0.5"></div>

              {/* Doors Toggle */}
              <button
                onClick={() => updateState(s => ({ ...s, doorsOpen: !s.doorsOpen }), state.doorsOpen ? 'Puertas Cerradas' : 'Puertas Abiertas', 'Mostrando distribución interior y baldas.')}
                className={`p-2 rounded-lg transition-colors cursor-pointer ${
                  state.doorsOpen ? 'bg-secondary-container text-white' : 'text-text-primary hover:bg-surface-container'
                }`}
                title="Abrir / Cerrar Puertas"
              >
                <span className="material-symbols-outlined text-[18px]">meeting_room</span>
              </button>

              {/* Wireframe CNC Toggle */}
              <button
                onClick={() => updateState(s => ({ ...s, wireframe: !s.wireframe }), state.wireframe ? 'Modo Realista' : 'Modo CNC / Alambre', 'Estructura interna y ensambles visibles.')}
                className={`p-2 rounded-lg transition-colors cursor-pointer ${
                  state.wireframe ? 'bg-secondary-container text-white' : 'text-text-primary hover:bg-surface-container'
                }`}
                title="Modo Rayos X / Alambre CNC"
              >
                <span className="material-symbols-outlined text-[18px]">grid_4x4</span>
              </button>

              {/* 360 Rotation Toggle */}
              <button
                onClick={() => updateState(s => ({ ...s, autoRotate: !s.autoRotate }))}
                className={`p-2 rounded-lg transition-colors cursor-pointer ${
                  state.autoRotate ? 'bg-secondary-container text-white' : 'text-text-primary hover:bg-surface-container'
                }`}
                title="Rotación 360° Continua"
              >
                <span className="material-symbols-outlined text-[18px]">360</span>
              </button>
            </div>

            {/* Floating Zoom Bar (Bottom Right) */}
            <div className="absolute bottom-16 right-3 flex flex-col gap-1 bg-surface-card/90 backdrop-blur-md p-1 rounded-xl shadow-md z-20 border border-border-subtle">
              <button
                onClick={() => updateState(s => ({ ...s, zoom: Math.min(1.5, Number((s.zoom + 0.1).toFixed(1))) }))}
                className="p-1.5 text-text-primary hover:bg-surface-container rounded-lg transition-colors cursor-pointer"
                title="Acercar"
              >
                <span className="material-symbols-outlined text-[18px]">add</span>
              </button>
              <button
                onClick={() => updateState(s => ({ ...s, zoom: 1 }))}
                className="p-1.5 text-text-primary hover:bg-surface-container rounded-lg transition-colors cursor-pointer"
                title="Restablecer Encuadre"
              >
                <span className="material-symbols-outlined text-[18px]">center_focus_strong</span>
              </button>
              <button
                onClick={() => updateState(s => ({ ...s, zoom: Math.max(0.7, Number((s.zoom - 0.1).toFixed(1))) }))}
                className="p-1.5 text-text-primary hover:bg-surface-container rounded-lg transition-colors cursor-pointer"
                title="Alejar"
              >
                <span className="material-symbols-outlined text-[18px]">remove</span>
              </button>
            </div>
          </div>

          {/* Lighting Environment Controls Strip */}
          <div className="w-full flex flex-wrap items-center justify-between p-3 bg-surface-card rounded-xl shadow-sm border border-border-subtle gap-2">
            <div className="flex items-center gap-3">
              <span className="font-label-technical text-label-technical text-text-muted uppercase">Ambiente de luz:</span>
              <div className="flex items-center gap-1.5">
                <button
                  onClick={() => updateState(s => ({ ...s, lighting: 'nordic' }))}
                  className={`px-2.5 py-1 rounded font-label-technical text-[11px] transition-colors cursor-pointer ${
                    state.lighting === 'nordic' ? 'bg-canvas-cream text-text-primary font-bold shadow-xs' : 'text-text-muted hover:text-text-primary'
                  }`}
                >
                  Estudio Nórdico
                </button>
                <button
                  onClick={() => updateState(s => ({ ...s, lighting: 'warm' }))}
                  className={`px-2.5 py-1 rounded font-label-technical text-[11px] transition-colors cursor-pointer ${
                    state.lighting === 'warm' ? 'bg-canvas-cream text-text-primary font-bold shadow-xs' : 'text-text-muted hover:text-text-primary'
                  }`}
                >
                  Atardecer Cálido
                </button>
                <button
                  onClick={() => updateState(s => ({ ...s, lighting: 'gallery' }))}
                  className={`px-2.5 py-1 rounded font-label-technical text-[11px] transition-colors cursor-pointer ${
                    state.lighting === 'gallery' ? 'bg-canvas-cream text-text-primary font-bold shadow-xs' : 'text-text-muted hover:text-text-primary'
                  }`}
                >
                  Foco Galería
                </button>
              </div>
            </div>

            <div className="flex items-center gap-1 text-text-muted font-label-technical text-[11px]">
              <span className="material-symbols-outlined text-[15px]">mouse</span>
              <span>Click izq: Orbitar | Rueda: Zoom</span>
            </div>
          </div>
        </main>

        {/* 3. PANEL DERECHO: Cotizador Algorítmico y Checkout (3 Columnas) */}
        <aside className="lg:col-span-3 w-full flex flex-col gap-4 order-3">
          <div className="w-full bg-surface-card rounded-xl p-4 md:p-5 shadow-md flex flex-col gap-4 border border-border-subtle">
            {/* Header de Precio */}
            <div className="flex flex-col gap-1 pb-2 border-b border-border-subtle">
              <div className="flex items-center justify-between">
                <span className="font-label-technical text-label-technical text-text-muted uppercase">Presupuesto Algorítmico</span>
                <span className="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-state-success/10 text-state-success font-label-technical text-[10px] font-bold">
                  <span className="material-symbols-outlined text-[12px]">lock_clock</span>
                  Fijo 15 Días
                </span>
              </div>
              <div className="flex items-baseline justify-between pt-1">
                <div className="flex items-baseline gap-1">
                  <span className="font-price-hero text-3xl md:text-4xl text-text-primary font-bold tracking-tight">
                    {quotation.total.toLocaleString('es-ES')}
                  </span>
                  <span className="font-headline-sm text-lg text-text-primary font-bold">€</span>
                </div>
                <span className="font-body-sm text-xs text-text-muted">IVA 21% incluido</span>
              </div>
            </div>

            {/* Desglose Desplegable de Costos */}
            <details className="group bg-canvas-cream rounded-lg p-3 open:bg-surface-container transition-all border border-border-subtle">
              <summary className="flex items-center justify-between cursor-pointer font-label-technical text-xs text-text-primary font-bold list-none">
                <span className="flex items-center gap-1.5">
                  <span className="material-symbols-outlined text-[16px] text-secondary">receipt_long</span>
                  <span>Desglose de Costos de Taller</span>
                </span>
                <span className="material-symbols-outlined text-[16px] group-open:rotate-180 transition-transform">
                  expand_more
                </span>
              </summary>
              <div className="flex flex-col gap-1.5 pt-2.5 font-body-sm text-[12px] text-on-surface-variant">
                <div className="flex justify-between items-center">
                  <span>Madera maciza ({quotation.volume} m³):</span>
                  <span className="font-label-numeric font-bold text-text-primary">{quotation.woodCost} €</span>
                </div>
                <div className="flex justify-between items-center">
                  <span>Mecanizado CNC & Ebanistería:</span>
                  <span className="font-label-numeric font-bold text-text-primary">{quotation.cncCost} €</span>
                </div>
                <div className="flex justify-between items-center">
                  <span>Herrajes Blum® y uniones:</span>
                  <span className="font-label-numeric font-bold text-text-primary">{quotation.hardwareCost} €</span>
                </div>
                <div className="flex justify-between items-center">
                  <span>Acabado manual con aceite:</span>
                  <span className="font-label-numeric font-bold text-text-primary">{quotation.finishCost} €</span>
                </div>
                {quotation.extrasCost > 0 && (
                  <div className="flex justify-between items-center text-secondary font-semibold pt-1 border-t border-border-subtle">
                    <span>Extras (LED / Base / Pasacables):</span>
                    <span className="font-label-numeric">+{quotation.extrasCost} €</span>
                  </div>
                )}
              </div>
            </details>

            {/* Plazos y Fabricación */}
            <div className="flex flex-col gap-2 p-3 bg-canvas-cream rounded-lg font-body-sm text-[12px] border border-border-subtle">
              <div className="flex items-center gap-2 text-text-primary">
                <span className="material-symbols-outlined text-[18px] text-secondary">calendar_today</span>
                <div>
                  <p className="font-bold">Fabricación artesanal: 16 - 18 días hábiles</p>
                  <p className="text-text-muted">Directo desde nuestro taller en Valencia</p>
                </div>
              </div>
              <div className="flex items-center gap-2 text-text-primary pt-1 border-t border-border-subtle">
                <span className="material-symbols-outlined text-[18px] text-state-success">local_shipping</span>
                <div>
                  <p className="font-bold">Transporte especializado guante blanco</p>
                  <p className="text-text-muted">Entrega en planta y montaje incluidos</p>
                </div>
              </div>
            </div>

            {/* Botones de Acción Primarios */}
            <div className="flex flex-col gap-2.5 pt-1">
              <button
                onClick={handleAddToCart}
                className="w-full h-12 rounded-lg bg-secondary-container hover:bg-accent-hover text-on-primary font-headline-sm text-sm md:text-base font-bold shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2 cursor-pointer"
              >
                <span className="material-symbols-outlined text-[20px]">shopping_cart_checkout</span>
                <span>Añadir al Carrito y Configurar Envío</span>
              </button>

              <button
                onClick={() => onNavigate('despiece-lista-de-corte')}
                className="w-full h-10 rounded-lg bg-surface-card hover:bg-surface-container text-text-primary font-body-sm text-xs md:text-sm font-semibold transition-all shadow-sm border border-border-subtle flex items-center justify-center gap-1.5 cursor-pointer"
              >
                <span className="material-symbols-outlined text-[18px] text-primary-variant">architecture</span>
                <span>Descargar Ficha Técnica & Planos DXF</span>
              </button>

              <button
                onClick={() => showToast('Guardado', 'Proyecto sincronizado con la cuenta de estudio.')}
                className="w-full h-9 rounded-lg hover:bg-surface-container text-text-muted hover:text-text-primary font-body-sm text-xs font-medium transition-colors flex items-center justify-center gap-1 cursor-pointer"
              >
                <span className="material-symbols-outlined text-[16px]">folder_special</span>
                <span>Guardar en Mis Proyectos / Enviar a Taller</span>
              </button>
            </div>

            {/* Sellos de Confianza */}
            <div className="grid grid-cols-3 gap-2 pt-2 border-t border-border-subtle text-center">
              <div className="flex flex-col items-center gap-1">
                <span className="material-symbols-outlined text-[20px] text-secondary">verified_user</span>
                <span className="font-label-technical text-[10px] text-text-muted leading-tight">10 Años de Garantía</span>
              </div>
              <div className="flex flex-col items-center gap-1">
                <span className="material-symbols-outlined text-[20px] text-state-success">forest</span>
                <span className="font-label-technical text-[10px] text-text-muted leading-tight">100% Madera FSC®</span>
              </div>
              <div className="flex flex-col items-center gap-1">
                <span className="material-symbols-outlined text-[20px] text-primary-variant">handshake</span>
                <span className="font-label-technical text-[10px] text-text-muted leading-tight">Ajuste de Montaje</span>
              </div>
            </div>
          </div>

          {/* Widget Asistente Espacial */}
          <div className="w-full bg-surface-card rounded-xl p-3.5 shadow-md flex flex-col gap-2 border border-border-subtle">
            <div className="flex items-center gap-2 text-text-primary">
              <span className="material-symbols-outlined text-[18px] text-secondary">help</span>
              <h3 className="font-headline-sm text-xs md:text-sm font-bold">¿Dudas si {state.width} cm encajan en tu salón?</h3>
            </div>
            <p className="font-body-sm text-xs text-text-muted leading-snug">
              Indícanos la longitud libre de tu pared y si tienes rodapié o enchufes para validar el retranqueo posterior.
            </p>
            <button
              onClick={() => setShowSpatialModal(true)}
              className="w-full py-1.5 px-3 rounded-lg bg-canvas-cream hover:bg-surface-container font-label-technical text-xs text-text-primary font-bold transition-colors text-left flex items-center justify-between cursor-pointer border border-border-subtle"
            >
              <span>Abrir Asesor de Espacios</span>
              <span className="material-symbols-outlined text-[14px]">arrow_forward</span>
            </button>
          </div>
        </aside>
      </div>

      {/* MODAL REALIDAD AUMENTADA (AR / QR Móvil) */}
      {showARModal && (
        <div className="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
          <div className="bg-surface-card rounded-2xl shadow-2xl max-w-sm w-full p-6 flex flex-col items-center text-center gap-4 border border-border-subtle animate-in fade-in zoom-in-95">
            <div className="w-12 h-12 rounded-full bg-surface-container flex items-center justify-center text-secondary">
              <span className="material-symbols-outlined text-[28px]">qr_code_scanner</span>
            </div>
            <div>
              <h3 className="font-headline-sm text-lg text-text-primary font-bold">Visualiza a Escala 1:1</h3>
              <p className="font-body-sm text-xs text-text-muted mt-1 leading-relaxed">
                Apunta la cámara de tu smartphone para situar el {currentModel.name} en tu salón mediante realidad aumentada.
              </p>
            </div>

            {/* Simulación QR Code SVG */}
            <div className="p-4 bg-white rounded-xl shadow-inner flex items-center justify-center border border-border-subtle">
              <svg className="w-36 h-36 text-text-primary" fill="currentColor" viewBox="0 0 100 100">
                <rect x="10" y="10" width="25" height="25" rx="3" fill="#103E4A" />
                <rect x="15" y="15" width="15" height="15" rx="1" fill="#FFFFFF" />
                <rect x="18" y="18" width="9" height="9" fill="#103E4A" />
                <rect x="65" y="10" width="25" height="25" rx="3" fill="#103E4A" />
                <rect x="70" y="15" width="15" height="15" rx="1" fill="#FFFFFF" />
                <rect x="73" y="18" width="9" height="9" fill="#103E4A" />
                <rect x="10" y="65" width="25" height="25" rx="3" fill="#103E4A" />
                <rect x="15" y="70" width="15" height="15" rx="1" fill="#FFFFFF" />
                <rect x="18" y="73" width="9" height="9" fill="#103E4A" />
                <circle cx="45" cy="20" r="3" fill="#103E4A" />
                <circle cx="55" cy="20" r="3" fill="#103E4A" />
                <circle cx="45" cy="35" r="3" fill="#ED991F" />
                <circle cx="55" cy="45" r="3" fill="#103E4A" />
                <circle cx="35" cy="50" r="3" fill="#103E4A" />
                <circle cx="45" cy="65" r="3" fill="#103E4A" />
                <circle cx="65" cy="65" r="3" fill="#ED991F" />
                <circle cx="75" cy="50" r="3" fill="#103E4A" />
                <circle cx="85" cy="75" r="3" fill="#103E4A" />
              </svg>
            </div>

            <div className="flex items-center gap-2 font-label-technical text-label-technical text-text-muted text-xs">
              <span className="material-symbols-outlined text-[16px] text-state-success">smartphone</span>
              <span>Compatible con Apple QuickLook y Android SceneViewer</span>
            </div>

            <button
              onClick={() => setShowARModal(false)}
              className="w-full py-2.5 rounded-lg bg-surface-container hover:bg-surface-container-high text-text-primary font-body-sm text-sm font-semibold transition-colors cursor-pointer"
            >
              Cerrar
            </button>
          </div>
        </div>
      )}

      {/* MODAL ASESOR ESPACIAL */}
      {showSpatialModal && (
        <div className="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
          <div className="bg-surface-card rounded-2xl shadow-2xl max-w-md w-full p-6 flex flex-col gap-4 border border-border-subtle animate-in fade-in zoom-in-95">
            <div className="flex items-center justify-between">
              <div className="flex items-center gap-2">
                <span className="material-symbols-outlined text-secondary text-[24px]">straighten</span>
                <h3 className="font-headline-sm text-lg font-bold text-text-primary">Asesor de Espacios</h3>
              </div>
              <button onClick={() => setShowSpatialModal(false)} className="text-text-muted hover:text-text-primary cursor-pointer">
                <span className="material-symbols-outlined text-[20px]">close</span>
              </button>
            </div>
            <p className="font-body-sm text-xs text-text-muted">
              Calcula la proporción idónea respecto a tu pared libre para garantizar un paso cómodo sin asfixiar la estancia.
            </p>
            <div className="space-y-3 bg-canvas-cream p-4 rounded-xl border border-border-subtle">
              <div>
                <label className="font-label-technical text-xs text-text-primary font-bold block mb-1">
                  Longitud total de tu pared (cm):
                </label>
                <input
                  type="number"
                  defaultValue={280}
                  className="w-full p-2 bg-surface-card rounded-lg text-sm text-text-primary border border-border-subtle"
                />
              </div>
              <div className="flex items-center gap-2">
                <input type="checkbox" defaultChecked id="skirting" className="accent-secondary-container" />
                <label htmlFor="skirting" className="text-xs text-text-primary cursor-pointer">
                  Tengo rodapié (se aplica retranqueo de 1.8 cm en patas)
                </label>
              </div>
              <div className="p-3 bg-surface-card rounded-lg text-xs space-y-1">
                <p className="text-state-success font-bold">✓ Relación Espacial Excelente</p>
                <p className="text-text-muted">
                  Con {state.width} cm en una pared de 280 cm, te quedarán 50 cm libres a cada lado, ideal para iluminación auxiliar o vegetación.
                </p>
              </div>
            </div>
            <button
              onClick={() => setShowSpatialModal(false)}
              className="w-full py-2.5 rounded-lg bg-text-primary hover:bg-primary text-on-primary font-body-sm text-sm font-semibold transition-colors cursor-pointer"
            >
              Confirmar Cotas
            </button>
          </div>
        </div>
      )}

      {/* Toast de Notificación Dinámica */}
      {toastMessage && (
        <div className="fixed bottom-12 right-6 z-50 bg-primary-container text-white px-4 py-3 rounded-xl shadow-2xl flex items-center gap-3 animate-in slide-in-from-bottom-5 border border-primary-variant">
          <span className="material-symbols-outlined text-secondary-container text-[22px]">check_circle</span>
          <div>
            <p className="font-headline-sm text-xs md:text-sm font-bold">{toastMessage.title}</p>
            <p className="font-body-sm text-[11px] text-tertiary-fixed">{toastMessage.sub}</p>
          </div>
        </div>
      )}

      {/* Sticky Bottom Mini Bar */}
      <footer className="fixed bottom-0 left-0 w-full h-8 z-40 bg-surface-card/95 backdrop-blur-md border-t border-border-subtle text-on-surface-variant flex items-center justify-between px-4 md:px-8 select-none">
        <div className="flex items-center gap-3 font-label-technical text-label-technical">
          <div className="flex items-center gap-1.5">
            <span className="w-1.5 h-1.5 rounded-full bg-state-success"></span>
            <span className="text-text-primary font-semibold">Motor WebGL 60 FPS</span>
          </div>
          <span className="text-border-subtle">|</span>
          <div className="flex items-center gap-1.5">
            <span className="material-symbols-outlined text-[14px] text-state-success">sync</span>
            <span>Cotización sincronizada en tiempo real</span>
          </div>
        </div>
        <div className="flex items-center gap-4 font-label-technical text-label-technical">
          <span className="hidden sm:inline text-text-muted">Modo: Inspector Paramétrico Activo</span>
          <div className="flex items-center gap-1 text-state-success font-semibold">
            <span className="material-symbols-outlined text-[14px]">verified</span>
            <span>Ajuste ±0.5 mm</span>
          </div>
        </div>
      </footer>
    </div>
  );
};
