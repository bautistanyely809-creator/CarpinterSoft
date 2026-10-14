import React, { useState } from 'react';
import { AppView, KanbanOrder } from '../types';
import { KANBAN_ORDERS, FURNITURE_MODELS, WOOD_OPTIONS } from '../data/furnitureData';

interface KanbanTallerPageProps {
  onNavigate: (view: AppView, modelId?: string) => void;
}

export const KanbanTallerPage: React.FC<KanbanTallerPageProps> = ({ onNavigate }) => {
  const [orders, setOrders] = useState<KanbanOrder[]>(KANBAN_ORDERS);
  const [search, setSearch] = useState('');
  const [selectedOrder, setSelectedOrder] = useState<KanbanOrder | null>(null);
  const [filterLine, setFilterLine] = useState('all');
  const [compactMode, setCompactMode] = useState(false);
  const [tvMode, setTvMode] = useState(false);
  const [showNewOrderModal, setShowNewOrderModal] = useState(false);

  // Drag and drop states
  const [draggedOrderId, setDraggedOrderId] = useState<string | null>(null);
  const [dragOverColId, setDragOverColId] = useState<KanbanOrder['columnId'] | null>(null);

  // New order form state
  const [newOrderForm, setNewOrderForm] = useState({
    client: '',
    project: 'Residencial',
    productName: FURNITURE_MODELS[0].name,
    dimensions: '180 × 85 × 45 cm',
    woodName: WOOD_OPTIONS[0].name,
    woodColor: WOOD_OPTIONS[0].colorHex,
    pieceCount: 24,
    columnId: 'dxf' as KanbanOrder['columnId'],
    urgency: 'normal' as 'normal' | 'soon' | 'urgent',
    deliveryDate: '28 Oct'
  });

  const columns: { id: KanbanOrder['columnId']; title: string; subtitle: string; dotColor: string }[] = [
    { id: 'dxf', title: '1. Validación & DXF', subtitle: 'Validación paramétrica & tolerancias', dotColor: 'bg-text-muted' },
    { id: 'cnc', title: '2. Corte CNC', subtitle: 'Nesting activo & seccionado', dotColor: 'bg-secondary-container' },
    { id: 'edges', title: '3. Calibrado & Cantos', subtitle: 'Encolado PUR 1-2mm & golas', dotColor: 'bg-primary-variant' },
    { id: 'assembly', title: '4. Premontaje', subtitle: 'Ajuste de escuadras & herrajes', dotColor: 'bg-primary' },
    { id: 'finish', title: '5. Lijado & Acabado', subtitle: 'Lijado grano 180-320 & Rubio', dotColor: 'bg-secondary-fixed-dim' },
    { id: 'packaging', title: '6. Control & Embalaje', subtitle: 'Inspección ±0.2mm & QR Zebra', dotColor: 'bg-state-success' }
  ];

  const stageFlow: KanbanOrder['columnId'][] = ['dxf', 'cnc', 'edges', 'assembly', 'finish', 'packaging'];

  const getStatusBadge = (colId: KanbanOrder['columnId']): { label: string; className: string } => {
    switch (colId) {
      case 'dxf':
        return { label: 'DXF Verificado', className: 'bg-surface-container text-text-muted' };
      case 'cnc':
        return { label: 'EN MÁQUINA', className: 'bg-secondary-container text-white' };
      case 'edges':
        return { label: 'Canteado OK', className: 'bg-primary-container text-on-primary' };
      case 'assembly':
        return { label: 'En Banco', className: 'bg-primary text-white' };
      case 'finish':
        return { label: 'En Cabina', className: 'bg-amber-600 text-white' };
      case 'packaging':
        return { label: 'LISTO EXPEDICIÓN', className: 'bg-state-success text-white' };
    }
  };

  const advanceOrder = (orderId: string) => {
    setOrders(prev => prev.map(ord => {
      if (ord.id !== orderId) return ord;
      const currentIdx = stageFlow.indexOf(ord.columnId);
      if (currentIdx < stageFlow.length - 1) {
        const nextCol = stageFlow[currentIdx + 1];
        const badge = getStatusBadge(nextCol);
        return {
          ...ord,
          columnId: nextCol,
          statusBadge: badge.label,
          statusBadgeClass: badge.className
        };
      }
      return ord;
    }));
  };

  const retreatOrder = (orderId: string) => {
    setOrders(prev => prev.map(ord => {
      if (ord.id !== orderId) return ord;
      const currentIdx = stageFlow.indexOf(ord.columnId);
      if (currentIdx > 0) {
        const prevCol = stageFlow[currentIdx - 1];
        const badge = getStatusBadge(prevCol);
        return {
          ...ord,
          columnId: prevCol,
          statusBadge: badge.label,
          statusBadgeClass: badge.className
        };
      }
      return ord;
    }));
  };

  const moveOrderToColumn = (orderId: string, targetCol: KanbanOrder['columnId']) => {
    const badge = getStatusBadge(targetCol);
    setOrders(prev => prev.map(ord => {
      if (ord.id !== orderId) return ord;
      return {
        ...ord,
        columnId: targetCol,
        statusBadge: badge.label,
        statusBadgeClass: badge.className
      };
    }));
  };

  const handleCreateOrder = (e: React.FormEvent) => {
    e.preventDefault();
    const newId = `ord-${Date.now().toString().slice(-4)}`;
    const orderNum = `ORD-2025-${Math.floor(1000 + Math.random() * 9000)}`;
    const badge = getStatusBadge(newOrderForm.columnId);
    const matchedModel = FURNITURE_MODELS.find(m => m.name === newOrderForm.productName) || FURNITURE_MODELS[0];

    const newOrder: KanbanOrder = {
      id: newId,
      orderNumber: orderNum,
      client: newOrderForm.client.trim() || 'Cliente Particular',
      project: newOrderForm.project.trim() || 'Residencial',
      productName: newOrderForm.productName,
      dimensions: newOrderForm.dimensions,
      woodName: newOrderForm.woodName,
      woodColor: newOrderForm.woodColor,
      pieceCount: Number(newOrderForm.pieceCount) || 20,
      columnId: newOrderForm.columnId,
      statusBadge: badge.label,
      statusBadgeClass: badge.className,
      deliveryDate: newOrderForm.deliveryDate,
      thumbnail: matchedModel.image,
      urgency: newOrderForm.urgency
    };

    setOrders(prev => [newOrder, ...prev]);
    setShowNewOrderModal(false);
    setSelectedOrder(newOrder);
  };

  // Drag and drop handlers
  const handleDragStart = (e: React.DragEvent, orderId: string) => {
    e.dataTransfer.setData('text/plain', orderId);
    e.dataTransfer.effectAllowed = 'move';
    setDraggedOrderId(orderId);
  };

  const handleDragOver = (e: React.DragEvent, colId: KanbanOrder['columnId']) => {
    e.preventDefault();
    e.dataTransfer.dropEffect = 'move';
    if (dragOverColId !== colId) {
      setDragOverColId(colId);
    }
  };

  const handleDragLeave = (e: React.DragEvent, colId: KanbanOrder['columnId']) => {
    e.preventDefault();
    if (dragOverColId === colId) {
      setDragOverColId(null);
    }
  };

  const handleDrop = (e: React.DragEvent, colId: KanbanOrder['columnId']) => {
    e.preventDefault();
    const orderId = e.dataTransfer.getData('text/plain') || draggedOrderId;
    if (orderId) {
      moveOrderToColumn(orderId, colId);
    }
    setDraggedOrderId(null);
    setDragOverColId(null);
  };

  // Filtered orders list
  const filteredOrders = orders.filter(o => {
    const matchSearch =
      o.orderNumber.toLowerCase().includes(search.toLowerCase()) ||
      o.client.toLowerCase().includes(search.toLowerCase()) ||
      o.productName.toLowerCase().includes(search.toLowerCase()) ||
      o.project.toLowerCase().includes(search.toLowerCase());

    if (!matchSearch) return false;

    if (filterLine === 'salon') {
      const p = o.productName.toLowerCase();
      return p.includes('aparador') || p.includes('horizon') || p.includes('tv') || p.includes('consola') || p.includes('mora') || p.includes('salón');
    }
    if (filterLine === 'mesas') {
      const p = o.productName.toLowerCase();
      return p.includes('mesa') || p.includes('fusión') || p.includes('comedor');
    }
    if (filterLine === 'dormitorios') {
      const p = o.productName.toLowerCase();
      return p.includes('cama') || p.includes('cómoda') || p.includes('dormitorio') || p.includes('kyoto') || p.includes('tatami');
    }
    return true;
  });

  // Dynamic KPIs calculations
  const totalOrdersCount = orders.length;
  const cncOrdersCount = orders.filter(o => o.columnId === 'cnc').length;
  const urgentOrdersCount = orders.filter(o => o.urgency === 'urgent' || Boolean(o.alert)).length;
  const totalPiecesInShop = orders.reduce((acc, o) => acc + o.pieceCount, 0);

  return (
    <div className={`flex flex-col w-full bg-background min-h-screen ${tvMode ? 'p-6 bg-slate-900 text-white' : ''}`}>
      {/* 1. SECCIÓN CABECERA & CONTROLES TALLER */}
      <section className="w-full px-4 md:px-8 pt-4 pb-6 flex flex-col gap-6 bg-surface border-b border-border-subtle">
        {/* Fila superior: Título, Estado de Conexión y Botones */}
        <div className="flex flex-col xl:flex-row items-start xl:items-center justify-between gap-4">
          <div>
            <div className="flex items-center gap-2 mb-1">
              <span className="font-label-technical text-label-technical uppercase tracking-wider text-text-muted">
                Planta de Mecanizado & Carpintería
              </span>
              <span className="inline-block w-1.5 h-1.5 rounded-full bg-secondary-container animate-pulse"></span>
              <span className="font-label-technical text-label-technical text-text-primary font-medium">
                Biesse Rover + Sierra Homag Online
              </span>
            </div>
            <div className="flex items-baseline gap-3 flex-wrap">
              <h1 className="font-headline-xl text-2xl md:text-3xl lg:text-4xl text-primary font-bold tracking-tight">
                Cola de Producción & Flujo de Taller
              </h1>
              <span className="bg-surface-container-high text-primary font-label-technical text-xs px-2.5 py-1 rounded-full font-semibold">
                Semana 43 • Octubre 2025
              </span>
            </div>
          </div>

          {/* Quick Actions */}
          <div className="flex flex-wrap items-center gap-2.5 w-full xl:w-auto">
            <button
              onClick={() => setCompactMode(!compactMode)}
              className={`flex items-center gap-1.5 px-3.5 h-10 rounded-lg text-xs md:text-sm font-semibold transition-all shadow-sm cursor-pointer border ${
                compactMode
                  ? 'bg-primary text-white border-primary'
                  : 'bg-surface-container text-primary hover:bg-surface-container-high border-border-subtle'
              }`}
              title="Alternar entre vista detallada y vista compacta de alta densidad"
            >
              <span className="material-symbols-outlined text-[18px]">
                {compactMode ? 'view_agenda' : 'view_comfortable'}
              </span>
              <span>{compactMode ? 'Vista Normal' : 'Vista Compacta'}</span>
            </button>

            <button
              onClick={() => setTvMode(!tvMode)}
              className={`flex items-center gap-1.5 px-3.5 h-10 rounded-lg text-xs md:text-sm font-semibold transition-all shadow-sm cursor-pointer border ${
                tvMode
                  ? 'bg-amber-600 text-white border-amber-600'
                  : 'bg-surface-container text-primary hover:bg-surface-container-high border-border-subtle'
              }`}
              title="Pantalla completa para monitor de planta (Smart TV)"
            >
              <span className="material-symbols-outlined text-[18px]">tv</span>
              <span>{tvMode ? 'Salir TV' : 'Modo Monitor'}</span>
            </button>

            <button
              onClick={() => window.print()}
              className="flex items-center gap-1.5 px-3.5 h-10 rounded-lg bg-surface-container text-primary hover:bg-surface-container-high transition-all shadow-sm font-headline-sm text-xs md:text-sm font-semibold cursor-pointer border border-border-subtle"
            >
              <span className="material-symbols-outlined text-[18px]">print</span>
              <span className="hidden sm:inline">Hoja de Carga</span>
            </button>

            <button
              onClick={() => onNavigate('despiece-lista-de-corte')}
              className="flex items-center gap-1.5 px-3.5 h-10 rounded-lg bg-surface-container text-primary hover:bg-surface-container-high transition-all shadow-sm font-headline-sm text-xs md:text-sm font-semibold cursor-pointer border border-border-subtle"
            >
              <span className="material-symbols-outlined text-[18px]">table_chart</span>
              <span>Nesting CNC</span>
            </button>

            <button
              onClick={() => setShowNewOrderModal(true)}
              className="flex items-center gap-1.5 px-4 h-10 rounded-lg bg-secondary-container text-white font-headline-sm text-xs md:text-sm font-semibold hover:bg-accent-hover transition-transform active:scale-95 shadow-md shadow-secondary-container/20 cursor-pointer"
            >
              <span className="material-symbols-outlined text-[20px]">add_circle</span>
              <span>+ Nuevo Pedido</span>
            </button>
          </div>
        </div>

        {/* Search & Filters Bar */}
        <div className="grid grid-cols-1 md:grid-cols-12 gap-3 bg-surface-container-low p-3 rounded-xl border border-border-subtle">
          <div className="md:col-span-5 relative flex items-center">
            <span className="material-symbols-outlined absolute left-3 text-text-muted text-[18px]">search</span>
            <input
              type="text"
              value={search}
              onChange={(e) => setSearch(e.target.value)}
              placeholder="Buscar por ORD-XXXX, mueble o cliente..."
              className="w-full h-10 pl-9 pr-3 rounded-lg bg-surface text-body-sm text-on-surface placeholder:text-text-muted focus:outline-none focus:ring-2 focus:ring-primary shadow-sm text-xs"
            />
          </div>

          <div className="md:col-span-4">
            <div className="relative flex items-center">
              <span className="material-symbols-outlined absolute left-3 text-text-muted text-[18px]">shelves</span>
              <select
                value={filterLine}
                onChange={(e) => setFilterLine(e.target.value)}
                className="w-full h-10 pl-9 pr-8 rounded-lg bg-surface text-xs text-on-surface font-medium focus:outline-none focus:ring-2 focus:ring-primary shadow-sm appearance-none cursor-pointer border border-border-subtle"
              >
                <option value="all">Todas las Líneas de Producto</option>
                <option value="salon">Línea Muebles de Salón & Almacenaje</option>
                <option value="mesas">Mesas & Roble Macizo FSC®</option>
                <option value="dormitorios">Dormitorios & Camas Tatami</option>
              </select>
              <span className="material-symbols-outlined absolute right-3 text-text-muted text-[16px] pointer-events-none">expand_more</span>
            </div>
          </div>

          <div className="md:col-span-3 flex items-center justify-between md:justify-end gap-2">
            <span className="text-xs font-label-technical text-text-muted">
              Mostrando <strong className="text-primary">{filteredOrders.length}</strong> de {totalOrdersCount} órdenes
            </span>
            {filterLine !== 'all' && (
              <button
                onClick={() => setFilterLine('all')}
                className="text-[11px] text-secondary-container hover:underline cursor-pointer"
              >
                Limpiar filtro
              </button>
            )}
          </div>
        </div>

        {/* 4 KPIs DINÁMICOS DE TALLER */}
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <div className="p-4 rounded-xl bg-surface-card shadow-sm flex flex-col justify-between border border-border-subtle">
            <div className="flex items-start justify-between">
              <div>
                <span className="font-label-technical text-label-technical uppercase tracking-wider text-text-muted">Pedidos en Planta</span>
                <div className="flex items-baseline gap-2 mt-1">
                  <span className="font-price-hero text-2xl md:text-3xl text-primary font-bold">{totalOrdersCount}</span>
                  <span className="font-label-technical text-xs font-semibold text-text-muted">órdenes</span>
                </div>
              </div>
              <div className="w-10 h-10 rounded-lg bg-primary-container/10 flex items-center justify-center text-primary">
                <span className="material-symbols-outlined text-[22px]">conveyor_belt</span>
              </div>
            </div>
            <div className="flex items-center justify-between pt-3 mt-3 bg-surface-container-low px-2 py-1 rounded">
              <span className="font-label-technical text-xs text-text-muted">Total Piezas CNC:</span>
              <span className="font-label-numeric text-xs font-bold text-primary">{totalPiecesInShop} piezas</span>
            </div>
          </div>

          <div className="p-4 rounded-xl bg-surface-card shadow-sm flex flex-col justify-between border border-border-subtle">
            <div className="flex items-start justify-between">
              <div>
                <span className="font-label-technical text-label-technical uppercase tracking-wider text-text-muted">Corte CNC Activo</span>
                <div className="flex items-baseline gap-2 mt-1">
                  <span className="font-price-hero text-2xl md:text-3xl text-primary font-bold">{cncOrdersCount}</span>
                  <span className="font-label-technical text-xs font-semibold text-secondary-container">en bancada</span>
                </div>
              </div>
              <div className="w-10 h-10 rounded-lg bg-secondary-container/10 flex items-center justify-center text-secondary">
                <span className="material-symbols-outlined text-[22px]">precision_manufacturing</span>
              </div>
            </div>
            <div className="mt-3">
              <div className="w-full h-1.5 bg-surface-container rounded-full overflow-hidden">
                <div className="h-full bg-secondary-container rounded-full" style={{ width: `${Math.min(100, Math.round((cncOrdersCount / 4) * 100))}%` }}></div>
              </div>
              <div className="flex items-center justify-between mt-1 text-[11px] font-label-technical text-text-muted">
                <span>Capacidad simultánea:</span>
                <span className="font-semibold text-primary">Biesse Rover #2 + Homag #1</span>
              </div>
            </div>
          </div>

          <div className="p-4 rounded-xl bg-surface-card shadow-sm flex flex-col justify-between border border-border-subtle">
            <div className="flex items-start justify-between">
              <div>
                <span className="font-label-technical text-label-technical uppercase tracking-wider text-text-muted">Lead Time Medio</span>
                <div className="flex items-baseline gap-2 mt-1">
                  <span className="font-price-hero text-2xl md:text-3xl text-primary font-bold">14.2</span>
                  <span className="font-label-technical text-xs text-text-muted">días hábiles</span>
                </div>
              </div>
              <div className="w-10 h-10 rounded-lg bg-state-success/10 flex items-center justify-center text-state-success">
                <span className="material-symbols-outlined text-[22px]">timer</span>
              </div>
            </div>
            <div className="flex items-center justify-between pt-3 mt-3 bg-surface-container-low px-2 py-1 rounded">
              <div className="flex items-center gap-1 text-state-success font-semibold text-xs font-label-technical">
                <span className="material-symbols-outlined text-[16px]">arrow_downward</span>
                <span>-1.8 días vs Objetivo</span>
              </div>
              <span className="font-label-technical text-xs text-text-muted">Meta: 16d</span>
            </div>
          </div>

          <div className="p-4 rounded-xl bg-surface-card shadow-sm flex flex-col justify-between border border-border-subtle">
            <div className="flex items-start justify-between">
              <div>
                <span className="font-label-technical text-label-technical uppercase tracking-wider text-state-error">Atención Requerida</span>
                <div className="flex items-baseline gap-2 mt-1">
                  <span className="font-price-hero text-2xl md:text-3xl text-state-error font-bold">{urgentOrdersCount}</span>
                  <span className="font-label-technical text-xs font-semibold text-state-error">con aviso o urgentes</span>
                </div>
              </div>
              <div className="w-10 h-10 rounded-lg bg-state-error/10 flex items-center justify-center text-state-error animate-pulse">
                <span className="material-symbols-outlined text-[22px]">warning</span>
              </div>
            </div>
            <div className="flex items-center justify-between pt-3 mt-3 bg-error-container/40 px-2 py-1 rounded">
              <span className="font-label-technical text-xs text-on-error-container truncate">Revisión de herrajes & stock</span>
              <span className="material-symbols-outlined text-[16px] text-state-error">chevron_right</span>
            </div>
          </div>
        </div>
      </section>

      {/* 2. TABLERO KANBAN DE FABRICACIÓN (6 COLUMNAS CON DRAG & DROP) */}
      <section className="w-full px-4 md:px-8 py-6 bg-canvas-cream flex-1">
        <div className="flex items-center justify-between mb-4">
          <div className="flex items-center gap-2 text-text-muted font-label-technical text-xs">
            <span className="material-symbols-outlined text-[16px] text-secondary">drag_indicator</span>
            <span>Arrastra tarjetas entre columnas o usa los botones &lt; / &gt; para avanzar/retroceder fase</span>
          </div>
          <div className="flex items-center gap-3 text-text-muted font-label-technical text-xs">
            <span className="flex items-center gap-1"><span className="w-2 h-2 rounded-full bg-state-success"></span> Entrega Holgada</span>
            <span className="flex items-center gap-1"><span className="w-2 h-2 rounded-full bg-secondary-container"></span> Próximo (&lt;48h)</span>
            <span className="flex items-center gap-1"><span className="w-2 h-2 rounded-full bg-state-error"></span> Alerta de Material</span>
          </div>
        </div>

        {/* Scroll Horizontal Kanban Container */}
        <div className="overflow-x-auto pb-6">
          <div className="flex items-start gap-4 min-w-[1720px]">
            {columns.map((col) => {
              const colOrders = filteredOrders.filter(o => o.columnId === col.id);
              const isOver = dragOverColId === col.id;

              return (
                <div
                  key={col.id}
                  className="w-72 flex-shrink-0 flex flex-col gap-3"
                  onDragOver={(e) => handleDragOver(e, col.id)}
                  onDragLeave={(e) => handleDragLeave(e, col.id)}
                  onDrop={(e) => handleDrop(e, col.id)}
                >
                  {/* Column Header */}
                  <div className="bg-surface-card p-3 rounded-xl flex items-center justify-between shadow-sm border border-border-subtle">
                    <div className="flex items-center gap-2">
                      <span className={`w-2.5 h-2.5 rounded-full ${col.dotColor}`}></span>
                      <h3 className="font-headline-sm text-sm font-bold text-primary">{col.title}</h3>
                    </div>
                    <span className="bg-surface-container px-2 py-0.5 rounded-full font-label-technical text-xs font-bold text-primary shadow-xs">
                      {colOrders.length}
                    </span>
                  </div>
                  <div className="text-[11px] font-label-technical text-text-muted px-1">{col.subtitle}</div>

                  {/* Orders Cards inside Column (Drop Zone) */}
                  <div
                    className={`flex flex-col gap-3 min-h-[460px] p-2 rounded-xl border transition-all ${
                      isOver
                        ? 'bg-secondary-container/10 border-2 border-dashed border-secondary-container shadow-inner'
                        : 'bg-surface-container-low/40 border-dashed border-border-subtle'
                    }`}
                  >
                    {colOrders.length === 0 ? (
                      <div className="flex-1 flex flex-col items-center justify-center text-text-muted text-xs p-6 text-center border border-dashed border-border-subtle rounded-lg">
                        <span className="material-symbols-outlined text-[24px] opacity-40 mb-1">inbox</span>
                        <p className="font-label-technical text-[11px]">Sin pedidos en esta fase</p>
                        <p className="font-label-technical text-[10px] opacity-60">Arrastra una tarjeta aquí</p>
                      </div>
                    ) : (
                      colOrders.map((order) => {
                        const colIdx = stageFlow.indexOf(order.columnId);
                        const canRetreat = colIdx > 0;
                        const canAdvance = colIdx < stageFlow.length - 1;
                        const isBeingDragged = draggedOrderId === order.id;

                        if (compactMode) {
                          // Vista Compacta
                          return (
                            <div
                              key={order.id}
                              draggable={true}
                              onDragStart={(e) => handleDragStart(e, order.id)}
                              className={`bg-surface-card rounded-lg p-2.5 shadow-sm hover:shadow-md transition-all flex flex-col gap-1.5 cursor-grab active:cursor-grabbing border ${
                                isBeingDragged ? 'opacity-40 scale-95' : 'border-border-subtle'
                              }`}
                              onClick={() => setSelectedOrder(order)}
                            >
                              <div className="flex items-center justify-between">
                                <span className="font-label-technical text-xs font-bold text-text-primary">{order.orderNumber}</span>
                                <span className={`px-1.5 py-0.5 rounded text-[9px] font-label-technical font-semibold ${order.statusBadgeClass}`}>
                                  {order.statusBadge}
                                </span>
                              </div>
                              <h4 className="font-headline-sm text-xs font-bold text-primary truncate">
                                {order.productName}
                              </h4>
                              <div className="flex items-center justify-between text-[10px] font-label-technical text-text-muted">
                                <span className="truncate">{order.client}</span>
                                <span>{order.pieceCount} pzs</span>
                              </div>
                              <div className="flex items-center justify-between pt-1 border-t border-border-subtle">
                                <span className="text-[10px] text-text-muted">{order.deliveryDate}</span>
                                <div className="flex items-center gap-1">
                                  {canRetreat && (
                                    <button
                                      onClick={(e) => {
                                        e.stopPropagation();
                                        retreatOrder(order.id);
                                      }}
                                      className="p-1 rounded bg-surface-container hover:bg-surface-container-high text-text-muted cursor-pointer"
                                      title="Retroceder a fase anterior"
                                    >
                                      <span className="material-symbols-outlined text-[13px]">arrow_back</span>
                                    </button>
                                  )}
                                  {canAdvance && (
                                    <button
                                      onClick={(e) => {
                                        e.stopPropagation();
                                        advanceOrder(order.id);
                                      }}
                                      className="px-1.5 py-0.5 rounded bg-primary text-white text-[10px] font-bold hover:bg-primary-variant cursor-pointer"
                                      title="Avanzar a siguiente fase"
                                    >
                                      &gt;
                                    </button>
                                  )}
                                </div>
                              </div>
                            </div>
                          );
                        }

                        // Vista Estándar Completa
                        return (
                          <div
                            key={order.id}
                            draggable={true}
                            onDragStart={(e) => handleDragStart(e, order.id)}
                            className={`bg-surface-card rounded-xl p-3 shadow-sm hover:shadow-md transition-all flex flex-col gap-2 cursor-grab active:cursor-grabbing border ${
                              isBeingDragged
                                ? 'opacity-40 scale-95 border-secondary-container'
                                : order.id === 'ord-894'
                                ? 'ring-2 ring-secondary-container shadow-md'
                                : 'border-border-subtle'
                            }`}
                            onClick={() => setSelectedOrder(order)}
                          >
                            <div className="flex items-start justify-between gap-1">
                              <div className="flex items-center gap-1.5">
                                <span className="font-label-technical text-xs font-bold text-text-primary">{order.orderNumber}</span>
                                <span className={`px-1.5 py-0.5 rounded text-[10px] font-label-technical font-semibold ${order.statusBadgeClass}`}>
                                  {order.statusBadge}
                                </span>
                              </div>
                              <div className="flex items-center gap-1">
                                {canRetreat && (
                                  <button
                                    onClick={(e) => {
                                      e.stopPropagation();
                                      retreatOrder(order.id);
                                    }}
                                    className="p-1 rounded bg-surface-container hover:bg-surface-container-high transition-colors text-text-muted cursor-pointer"
                                    title="Retroceder fase de taller"
                                  >
                                    <span className="material-symbols-outlined text-[14px]">arrow_back</span>
                                  </button>
                                )}
                                {canAdvance && (
                                  <button
                                    onClick={(e) => {
                                      e.stopPropagation();
                                      advanceOrder(order.id);
                                    }}
                                    className="p-1 rounded bg-surface-container hover:bg-primary hover:text-white transition-colors text-text-muted cursor-pointer"
                                    title="Avanzar a siguiente fase"
                                  >
                                    <span className="material-symbols-outlined text-[14px]">arrow_forward</span>
                                  </button>
                                )}
                              </div>
                            </div>

                            {/* Thumbnail & Title */}
                            <div className="flex gap-2 items-start">
                              <img src={order.thumbnail} alt={order.productName} className="w-12 h-12 rounded-lg object-cover shrink-0 border border-border-subtle" />
                              <div className="flex-1 min-w-0">
                                <h4 className="font-headline-sm text-xs font-bold text-primary truncate leading-tight">
                                  {order.productName}
                                </h4>
                                <p className="font-body-sm text-[11px] text-text-muted truncate mt-0.5">
                                  {order.client} • {order.project}
                                </p>
                              </div>
                            </div>

                            {/* Specs */}
                            <div className="flex items-center gap-1.5 flex-wrap text-[11px] font-label-technical">
                              <span className="px-1.5 py-0.5 rounded bg-surface-container text-text-primary flex items-center gap-1">
                                <span className="w-2 h-2 rounded-full" style={{ backgroundColor: order.woodColor }}></span>
                                {order.woodName}
                              </span>
                              <span className="px-1.5 py-0.5 rounded bg-surface-container text-text-muted">
                                {order.pieceCount} piezas
                              </span>
                            </div>

                            {/* CNC Progress if active */}
                            {order.progressPercent && (
                              <div className="bg-surface-container-low p-2 rounded-lg flex flex-col gap-1 text-[11px] font-label-technical">
                                <div className="flex items-center justify-between">
                                  <span className="font-bold text-primary">{order.machine}</span>
                                  <span className="font-bold text-secondary-container">{order.progressPercent}%</span>
                                </div>
                                <div className="w-full h-1.5 bg-surface-container rounded-full overflow-hidden">
                                  <div className="h-full bg-secondary-container rounded-full" style={{ width: `${order.progressPercent}%` }}></div>
                                </div>
                                <div className="flex justify-between text-text-muted text-[10px]">
                                  <span>Op: {order.operator}</span>
                                  <span className="text-secondary font-bold">{order.remainingTime}</span>
                                </div>
                              </div>
                            )}

                            {/* Alert if any */}
                            {order.alert && (
                              <div className="p-1.5 bg-error-container/40 rounded text-[10px] font-label-technical text-on-error-container">
                                {order.alert}
                              </div>
                            )}

                            {/* Date & Advance CTA */}
                            <div className="flex items-center justify-between pt-1 border-t border-border-subtle text-[11px] font-label-technical">
                              <span className="flex items-center gap-1 text-text-muted">
                                <span className="material-symbols-outlined text-[13px]">calendar_today</span> {order.deliveryDate}
                              </span>
                              <div className="flex items-center gap-1">
                                {canRetreat && (
                                  <button
                                    onClick={(e) => {
                                      e.stopPropagation();
                                      retreatOrder(order.id);
                                    }}
                                    className="px-2 py-0.5 bg-surface-container text-text-primary text-[10px] font-semibold rounded hover:bg-surface-container-high transition-colors cursor-pointer"
                                  >
                                    &lt; Atrás
                                  </button>
                                )}
                                {canAdvance && (
                                  <button
                                    onClick={(e) => {
                                      e.stopPropagation();
                                      advanceOrder(order.id);
                                    }}
                                    className="px-2 py-0.5 bg-primary text-white text-[10px] font-bold rounded hover:bg-primary-variant transition-colors cursor-pointer"
                                  >
                                    Avanzar &gt;
                                  </button>
                                )}
                              </div>
                            </div>
                          </div>
                        );
                      })
                    )}
                  </div>
                </div>
              );
            })}
          </div>
        </div>
      </section>

      {/* 3. PANEL INFERIOR DE ACTIVIDAD EN TIEMPO REAL */}
      <section className="w-full px-4 md:px-8 py-3 bg-surface border-t border-border-subtle flex flex-col lg:flex-row items-start lg:items-center justify-between gap-3 shadow-sm">
        <div className="flex items-center gap-4 flex-wrap text-xs">
          <div className="flex items-center gap-2">
            <span className="w-2.5 h-2.5 rounded-full bg-state-success animate-ping"></span>
            <span className="font-headline-sm text-sm font-bold text-primary">Cola Activa: {totalOrdersCount} pedidos en planta</span>
          </div>
          <span className="text-text-muted">•</span>
          <div className="flex items-center gap-1 text-text-muted font-label-technical">
            <span className="material-symbols-outlined text-[16px] text-primary">dns</span>
            <span>Servidor de Corte: <strong>Homag CADMATIC 5.0 sincronizado</strong></span>
          </div>
          <span className="text-text-muted hidden md:inline">•</span>
          <div className="hidden md:flex items-center gap-1 text-text-muted font-label-technical">
            <span className="material-symbols-outlined text-[16px] text-secondary">notifications_active</span>
            <span>Último evento: <em>ORD-2025-0894 alcanzó 65% en Biesse Rover #2</em></span>
          </div>
        </div>

        <div className="flex items-center gap-2">
          <button
            onClick={() => setOrders(KANBAN_ORDERS)}
            className="px-2.5 py-1 rounded-lg bg-surface-container text-text-muted hover:text-text-primary font-label-technical text-xs transition-colors cursor-pointer"
            title="Restablecer tablero inicial"
          >
            Restablecer Demo
          </button>
        </div>
      </section>

      {/* MODAL 1: DETALLE DE PEDIDO & GESTIÓN DE FASE */}
      {selectedOrder && (
        <div className="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
          <div className="bg-surface-card rounded-2xl shadow-2xl max-w-lg w-full p-6 flex flex-col gap-4 border border-border-subtle animate-in fade-in zoom-in-95">
            <div className="flex items-start justify-between">
              <div>
                <span className="font-label-technical text-xs text-text-muted uppercase">Detalle de Fabricación</span>
                <h3 className="font-headline-sm text-xl font-bold text-text-primary mt-0.5">{selectedOrder.orderNumber} - {selectedOrder.productName}</h3>
                <p className="font-body-sm text-xs text-text-muted">Cliente: {selectedOrder.client} • {selectedOrder.project}</p>
              </div>
              <button onClick={() => setSelectedOrder(null)} className="text-text-muted hover:text-text-primary cursor-pointer p-1">
                <span className="material-symbols-outlined">close</span>
              </button>
            </div>

            <div className="space-y-3 bg-canvas-cream p-4 rounded-xl text-xs border border-border-subtle">
              <div className="flex justify-between py-1 border-b border-border-subtle">
                <span className="text-text-muted">Dimensiones del Mueble:</span>
                <span className="font-bold text-text-primary">{selectedOrder.dimensions}</span>
              </div>
              <div className="flex justify-between py-1 border-b border-border-subtle">
                <span className="text-text-muted">Especie maderera:</span>
                <span className="font-bold text-text-primary">{selectedOrder.woodName}</span>
              </div>
              <div className="flex justify-between py-1 border-b border-border-subtle">
                <span className="text-text-muted">Número total de piezas:</span>
                <span className="font-bold text-text-primary">{selectedOrder.pieceCount} piezas CNC</span>
              </div>
              <div className="flex justify-between py-1 border-b border-border-subtle">
                <span className="text-text-muted">Compromiso de Entrega:</span>
                <span className="font-bold text-state-success">{selectedOrder.deliveryDate}</span>
              </div>
              {selectedOrder.machine && (
                <div className="flex justify-between py-1 border-b border-border-subtle">
                  <span className="text-text-muted">Centro de mecanizado:</span>
                  <span className="font-bold text-secondary-container">{selectedOrder.machine} ({selectedOrder.progressPercent}%)</span>
                </div>
              )}
              {/* Selector directo de columna */}
              <div className="flex items-center justify-between pt-1">
                <span className="text-text-muted font-semibold">Mover de fase directamente:</span>
                <select
                  value={selectedOrder.columnId}
                  onChange={(e) => {
                    const targetCol = e.target.value as KanbanOrder['columnId'];
                    moveOrderToColumn(selectedOrder.id, targetCol);
                    setSelectedOrder(prev => prev ? { ...prev, columnId: targetCol } : null);
                  }}
                  className="font-label-technical text-xs bg-surface-card px-2.5 py-1 rounded border border-border-subtle text-primary font-bold cursor-pointer"
                >
                  {columns.map(c => (
                    <option key={c.id} value={c.id}>{c.title}</option>
                  ))}
                </select>
              </div>
            </div>

            <div className="flex gap-2">
              <button
                onClick={() => {
                  advanceOrder(selectedOrder.id);
                  setSelectedOrder(null);
                }}
                className="flex-1 py-2.5 rounded-lg bg-secondary-container text-white font-headline-sm text-sm font-semibold hover:bg-accent-hover transition-colors cursor-pointer"
              >
                Avanzar Fase &gt;
              </button>
              <button
                onClick={() => {
                  setSelectedOrder(null);
                  onNavigate('despiece-lista-de-corte');
                }}
                className="px-4 py-2.5 rounded-lg bg-surface-container hover:bg-surface-container-high text-text-primary font-headline-sm text-sm font-semibold cursor-pointer"
              >
                Ver Despiece CNC
              </button>
            </div>
          </div>
        </div>
      )}

      {/* MODAL 2: CREAR NUEVO PEDIDO DIRECTO EN TALLER */}
      {showNewOrderModal && (
        <div className="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
          <div className="bg-surface-card rounded-2xl shadow-2xl max-w-md w-full p-6 flex flex-col gap-4 border border-border-subtle animate-in fade-in zoom-in-95">
            <div className="flex items-start justify-between">
              <div>
                <span className="font-label-technical text-xs text-text-muted uppercase">Orden Rápida</span>
                <h3 className="font-headline-sm text-lg font-bold text-text-primary mt-0.5">+ Nuevo Pedido de Planta</h3>
              </div>
              <button onClick={() => setShowNewOrderModal(false)} className="text-text-muted hover:text-text-primary cursor-pointer p-1">
                <span className="material-symbols-outlined">close</span>
              </button>
            </div>

            <form onSubmit={handleCreateOrder} className="flex flex-col gap-3 text-xs">
              <div>
                <label className="font-label-technical text-text-muted block mb-1">Cliente</label>
                <input
                  type="text"
                  required
                  placeholder="Ej. Laura Sanz"
                  value={newOrderForm.client}
                  onChange={(e) => setNewOrderForm({ ...newOrderForm, client: e.target.value })}
                  className="w-full px-3 py-2 rounded-lg bg-canvas-cream border border-border-subtle text-text-primary focus:outline-none focus:ring-1 focus:ring-primary"
                />
              </div>

              <div>
                <label className="font-label-technical text-text-muted block mb-1">Mueble / Modelo</label>
                <select
                  value={newOrderForm.productName}
                  onChange={(e) => {
                    const model = FURNITURE_MODELS.find(m => m.name === e.target.value);
                    setNewOrderForm({
                      ...newOrderForm,
                      productName: e.target.value,
                      dimensions: model ? model.dimensionsLabel : newOrderForm.dimensions
                    });
                  }}
                  className="w-full px-3 py-2 rounded-lg bg-canvas-cream border border-border-subtle text-text-primary focus:outline-none focus:ring-1 focus:ring-primary cursor-pointer font-medium"
                >
                  {FURNITURE_MODELS.map(m => (
                    <option key={m.id} value={m.name}>{m.name} ({m.dimensionsLabel})</option>
                  ))}
                </select>
              </div>

              <div className="grid grid-cols-2 gap-2">
                <div>
                  <label className="font-label-technical text-text-muted block mb-1">Madera</label>
                  <select
                    value={newOrderForm.woodName}
                    onChange={(e) => {
                      const wood = WOOD_OPTIONS.find(w => w.name === e.target.value);
                      setNewOrderForm({
                        ...newOrderForm,
                        woodName: e.target.value,
                        woodColor: wood ? wood.colorHex : '#b89758'
                      });
                    }}
                    className="w-full px-3 py-2 rounded-lg bg-canvas-cream border border-border-subtle text-text-primary focus:outline-none focus:ring-1 focus:ring-primary cursor-pointer"
                  >
                    {WOOD_OPTIONS.map(w => (
                      <option key={w.id} value={w.name}>{w.name}</option>
                    ))}
                  </select>
                </div>
                <div>
                  <label className="font-label-technical text-text-muted block mb-1">N° de Piezas</label>
                  <input
                    type="number"
                    min="1"
                    max="100"
                    value={newOrderForm.pieceCount}
                    onChange={(e) => setNewOrderForm({ ...newOrderForm, pieceCount: Number(e.target.value) })}
                    className="w-full px-3 py-2 rounded-lg bg-canvas-cream border border-border-subtle text-text-primary focus:outline-none focus:ring-1 focus:ring-primary"
                  />
                </div>
              </div>

              <div className="grid grid-cols-2 gap-2">
                <div>
                  <label className="font-label-technical text-text-muted block mb-1">Fase Inicial</label>
                  <select
                    value={newOrderForm.columnId}
                    onChange={(e) => setNewOrderForm({ ...newOrderForm, columnId: e.target.value as KanbanOrder['columnId'] })}
                    className="w-full px-3 py-2 rounded-lg bg-canvas-cream border border-border-subtle text-text-primary focus:outline-none focus:ring-1 focus:ring-primary cursor-pointer"
                  >
                    {columns.map(c => (
                      <option key={c.id} value={c.id}>{c.title}</option>
                    ))}
                  </select>
                </div>
                <div>
                  <label className="font-label-technical text-text-muted block mb-1">Urgencia</label>
                  <select
                    value={newOrderForm.urgency}
                    onChange={(e) => setNewOrderForm({ ...newOrderForm, urgency: e.target.value as 'normal' | 'soon' | 'urgent' })}
                    className="w-full px-3 py-2 rounded-lg bg-canvas-cream border border-border-subtle text-text-primary focus:outline-none focus:ring-1 focus:ring-primary cursor-pointer"
                  >
                    <option value="normal">Normal</option>
                    <option value="soon">Próximo (&lt;48h)</option>
                    <option value="urgent">Urgente</option>
                  </select>
                </div>
              </div>

              <div>
                <label className="font-label-technical text-text-muted block mb-1">Fecha Entrega Prometida</label>
                <input
                  type="text"
                  placeholder="Ej. 30 Oct"
                  value={newOrderForm.deliveryDate}
                  onChange={(e) => setNewOrderForm({ ...newOrderForm, deliveryDate: e.target.value })}
                  className="w-full px-3 py-2 rounded-lg bg-canvas-cream border border-border-subtle text-text-primary focus:outline-none focus:ring-1 focus:ring-primary"
                />
              </div>

              <div className="flex gap-2 pt-2">
                <button
                  type="button"
                  onClick={() => setShowNewOrderModal(false)}
                  className="flex-1 py-2.5 rounded-lg bg-surface-container hover:bg-surface-container-high text-text-primary font-semibold cursor-pointer"
                >
                  Cancelar
                </button>
                <button
                  type="submit"
                  className="flex-1 py-2.5 rounded-lg bg-secondary-container text-white font-semibold hover:bg-accent-hover transition-colors shadow-sm cursor-pointer"
                >
                  Crear Pedido
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
};
