import React, { useRef, useEffect, useState, useMemo } from 'react';
import { CustomizerState, WoodOption } from '../types';

interface ViewportProps {
  state: CustomizerState;
  selectedWood: WoodOption;
  onHotspotClick?: () => void;
}

export const Interactive3DViewport: React.FC<ViewportProps> = ({
  state,
  selectedWood,
  onHotspotClick
}) => {
  const canvasRef = useRef<HTMLCanvasElement | null>(null);
  const [isDragging, setIsDragging] = useState(false);
  const [rotation, setRotation] = useState({ x: 18, y: -25 });
  const [prevMouse, setPrevMouse] = useState({ x: 0, y: 0 });
  const [fps, setFps] = useState(60);
  const [showHotspotTooltip, setShowHotspotTooltip] = useState(false);

  // Auto-rotation effect
  useEffect(() => {
    if (!state.autoRotate) return;
    const interval = setInterval(() => {
      setRotation(prev => ({ ...prev, y: (prev.y + 0.8) % 360 }));
    }, 30);
    return () => clearInterval(interval);
  }, [state.autoRotate]);

  // Handle camera view preset changes
  useEffect(() => {
    if (state.cameraView === 'front') {
      setRotation({ x: 0, y: 0 });
    } else if (state.cameraView === 'top') {
      setRotation({ x: 80, y: 0 });
    } else {
      setRotation({ x: 18, y: -25 });
    }
  }, [state.cameraView]);

  // Subtle FPS jitter to simulate live WebGL pipeline
  useEffect(() => {
    const timer = setInterval(() => {
      setFps(Math.floor(59 + Math.random() * 3));
    }, 1200);
    return () => clearInterval(timer);
  }, []);

  // Mouse handlers for Orbiting
  const handleMouseDown = (e: React.MouseEvent) => {
    setIsDragging(true);
    setPrevMouse({ x: e.clientX, y: e.clientY });
  };

  const handleMouseMove = (e: React.MouseEvent) => {
    if (!isDragging) return;
    const deltaX = e.clientX - prevMouse.x;
    const deltaY = e.clientY - prevMouse.y;
    setRotation(prev => ({
      x: Math.max(-60, Math.min(85, prev.x + deltaY * 0.4)),
      y: (prev.y + deltaX * 0.4) % 360
    }));
    setPrevMouse({ x: e.clientX, y: e.clientY });
  };

  const handleMouseUp = () => setIsDragging(false);

  // Dynamic Hotspot Info based on selected model
  const hotspotInfo = useMemo(() => {
    switch (state.modelId) {
      case 'fusion-table':
        return {
          title: 'Canto Biselado Suizo 45°',
          desc: 'Mecanizado en fresadora CNC de 5 ejes en roble macizo seleccionado de 40mm con arista redondeada suave.'
        };
      case 'nexus-bookshelf':
        return {
          title: 'Cremallera Oculta & Canal LED',
          desc: 'Ranurado CNC ciego para tiras LED COB 3000K de alta eficiencia y baldas regulables milimétricamente.'
        };
      case 'veta-tv':
        return {
          title: 'Colgadores Camar® Heavy-Duty',
          desc: 'Herraje de anclaje de seguridad a muro portante con regulación 3D y capacidad certificada de hasta 90 kg.'
        };
      case 'studio-desk':
        return {
          title: 'Canaleta Pasacables Fresada',
          desc: 'Mecanizado interior pasante para electrificación invisible y cajonera suspendida con guías Blum Movento®.'
        };
      case 'mora-highboard':
        return {
          title: 'Cristal Templado Bronce & Roble',
          desc: 'Combinación de vitrina con tratamiento anti-reflejos y puertas inferiores en palillería artesanal.'
        };
      case 'aura-console':
        return {
          title: 'Curvado Suave CNC R-40',
          desc: 'Esquinas redondeadas en bloque continuo macizo y gaveta oculta con pulsador magnético Tip-On.'
        };
      default:
        return {
          title: 'Fresado Alistonado Continuo',
          desc: 'Listones macizos de 18x18 mm con canto biselado ergonómico antidesgaste sobre alma de roble nórdico.'
        };
    }
  }, [state.modelId]);

  // Utility color shader
  function shadeColor(color: string, percent: number): string {
    let R = parseInt(color.substring(1, 3), 16);
    let G = parseInt(color.substring(3, 5), 16);
    let B = parseInt(color.substring(5, 7), 16);

    R = Math.round((R * (100 + percent)) / 100);
    G = Math.round((G * (100 + percent)) / 100);
    B = Math.round((B * (100 + percent)) / 100);

    R = Math.min(255, Math.max(0, R));
    G = Math.min(255, Math.max(0, G));
    B = Math.min(255, Math.max(0, B));

    const RR = R.toString(16).padStart(2, '0');
    const GG = G.toString(16).padStart(2, '0');
    const BB = B.toString(16).padStart(2, '0');

    return '#' + RR + GG + BB;
  }

  // Render 3D Canvas Scene
  useEffect(() => {
    const canvas = canvasRef.current;
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    if (!ctx) return;

    let animationFrameId = 0;

    const renderScene = () => {
      const width = canvas.width;
      const height = canvas.height;
      ctx.clearRect(0, 0, width, height);

      // Environment Background Tone based on lighting preset
      const grad = ctx.createRadialGradient(width / 2, height / 2, 40, width / 2, height / 2, width * 0.7);
      if (state.lighting === 'warm') {
        grad.addColorStop(0, '#F5EDE0');
        grad.addColorStop(1, '#E2D3BE');
      } else if (state.lighting === 'gallery') {
        grad.addColorStop(0, '#EAECEF');
        grad.addColorStop(1, '#CBD1D9');
      } else {
        // Nordic Studio (default)
        grad.addColorStop(0, '#F4F2EB');
        grad.addColorStop(1, '#E0DCD1');
      }
      ctx.fillStyle = grad;
      ctx.fillRect(0, 0, width, height);

      // Grid flooring perspective
      ctx.save();
      ctx.strokeStyle = state.lighting === 'gallery' ? 'rgba(80, 100, 110, 0.12)' : 'rgba(16, 62, 74, 0.08)';
      ctx.lineWidth = 1;
      const gridY = height * 0.76;
      for (let i = -12; i <= 12; i++) {
        const xStart = width / 2 + i * 40;
        const xEnd = width / 2 + i * 140;
        ctx.beginPath();
        ctx.moveTo(xStart, gridY);
        ctx.lineTo(xEnd, height);
        ctx.stroke();
      }
      for (let j = 0; j < 6; j++) {
        const yLine = gridY + j * (height * 0.24 / 5);
        ctx.beginPath();
        ctx.moveTo(0, yLine);
        ctx.lineTo(width, yLine);
        ctx.stroke();
      }
      ctx.restore();

      // Soft ground ambient shadow
      ctx.save();
      ctx.beginPath();
      const shadowW = (state.width / 180) * 360 * state.zoom;
      const shadowH = (state.depth / 45) * 55 * state.zoom;
      const isFloating = state.baseType === 'flotante' || state.modelId === 'veta-tv';
      const shadowOpacity = isFloating ? 0.08 : 0.16;
      ctx.ellipse(width / 2, gridY + (isFloating ? 24 : 12), shadowW, shadowH, 0, 0, Math.PI * 2);
      ctx.fillStyle = `rgba(20, 35, 40, ${shadowOpacity})`;
      ctx.filter = 'blur(16px)';
      ctx.fill();
      ctx.restore();

      // Isometric / 3D Projection Calculation
      ctx.save();
      ctx.translate(width / 2, height * 0.52);
      ctx.scale(state.zoom, state.zoom);

      // Rotations in radians
      const radX = (rotation.x * Math.PI) / 180;
      const radY = (rotation.y * Math.PI) / 180;

      // Base sizes scaled
      const baseScale = 2.0;
      const modelW = state.width * baseScale;
      const modelH = state.height * baseScale * 0.95;
      const modelD = state.depth * baseScale * 0.8;

      type Point3D = [number, number, number];
      const hw = modelW / 2;
      const hh = modelH / 2;
      const hd = modelD / 2;

      const project = ([x, y, z]: Point3D): [number, number] => {
        // Rotate Y
        const cosY = Math.cos(radY);
        const sinY = Math.sin(radY);
        const x1 = x * cosY - z * sinY;
        const z1 = x * sinY + z * cosY;

        // Rotate X
        const cosX = Math.cos(radX);
        const sinX = Math.sin(radX);
        const y2 = y * cosX - z1 * sinX;
        const z2 = y * sinX + z1 * cosX;

        // Weak perspective
        const distance = 950;
        const fov = distance / (distance + z2 * 0.4);
        return [x1 * fov, y2 * fov];
      };

      const baseWood = selectedWood.colorHex;

      const drawPolygon = (points: Point3D[], fill: string, stroke: string = '#2B1C10') => {
        const projected = points.map(project);
        ctx.beginPath();
        ctx.moveTo(projected[0][0], projected[0][0 + 1] !== undefined ? projected[0][1] : 0);
        for (let i = 1; i < projected.length; i++) {
          ctx.lineTo(projected[i][0], projected[i][1]);
        }
        ctx.closePath();
        if (!state.wireframe) {
          ctx.fillStyle = fill;
          ctx.fill();
        }
        ctx.strokeStyle = state.wireframe ? '#0D4F60' : stroke;
        ctx.lineWidth = state.wireframe ? 1.2 : 0.8;
        ctx.stroke();
      };

      // ==========================================
      // MODEL 1: MESA DE COMEDOR FUSIÓN (fusion-table)
      // ==========================================
      if (state.modelId === 'fusion-table') {
        const topThick = 24; // Table top thickness 40mm in scale
        const topY = -hh;
        const botY = topY + topThick;
        const floorY = hh + 30;

        // Apron under frame
        const apronDepth = 28;
        const apronInset = 40;
        const ap1 = project([-hw + apronInset, botY, -hd + apronInset]);
        const ap2 = project([hw - apronInset, botY, -hd + apronInset]);
        const ap3 = project([hw - apronInset, botY + apronDepth, -hd + apronInset]);
        const ap4 = project([-hw + apronInset, botY + apronDepth, -hd + apronInset]);

        ctx.beginPath();
        ctx.moveTo(ap1[0], ap1[1]);
        ctx.lineTo(ap2[0], ap2[1]);
        ctx.lineTo(ap3[0], ap3[1]);
        ctx.lineTo(ap4[0], ap4[1]);
        ctx.closePath();
        ctx.fillStyle = shadeColor(baseWood, -25);
        ctx.fill();
        ctx.stroke();

        // 4 Sturdy Angled Table Legs
        const legThickness = 22;
        const legPositions: [number, number][] = [
          [-hw + 40, -hd + 35],
          [hw - 40 - legThickness, -hd + 35],
          [-hw + 40, hd - 35 - legThickness],
          [hw - 40 - legThickness, hd - 35 - legThickness]
        ];

        legPositions.forEach(([lx, lz]) => {
          // Front face of leg
          drawPolygon([
            [lx, botY, lz],
            [lx + legThickness, botY, lz],
            [lx + legThickness - 4, floorY, lz],
            [lx + 4, floorY, lz]
          ], shadeColor(baseWood, -15));

          // Side face of leg
          drawPolygon([
            [lx + legThickness, botY, lz],
            [lx + legThickness, botY, lz + legThickness],
            [lx + legThickness - 4, floorY, lz + legThickness - 4],
            [lx + legThickness - 4, floorY, lz]
          ], shadeColor(baseWood, -30));
        });

        // Table Top Slab (Thick Macizo with Chamfer)
        // Bottom face of top
        drawPolygon([
          [-hw, botY, -hd],
          [hw, botY, -hd],
          [hw, botY, hd],
          [-hw, botY, hd]
        ], shadeColor(baseWood, -25));

        // Front Edge
        drawPolygon([
          [-hw, topY, -hd],
          [hw, topY, -hd],
          [hw, botY, -hd],
          [-hw, botY, -hd]
        ], shadeColor(baseWood, -5));

        // Right Edge
        drawPolygon([
          [hw, topY, -hd],
          [hw, topY, hd],
          [hw, botY, hd],
          [hw, botY, -hd]
        ], shadeColor(baseWood, -20));

        // Top Surface (Wood Grain highlights)
        drawPolygon([
          [-hw, topY, -hd],
          [hw, topY, -hd],
          [hw, topY, hd],
          [-hw, topY, hd]
        ], shadeColor(baseWood, 15));

        // Planks continuous wood joints lines across top
        if (!state.wireframe) {
          ctx.save();
          const plankCount = 5;
          const plankW = (hd * 2) / plankCount;
          for (let p = 1; p < plankCount; p++) {
            const pz = -hd + p * plankW;
            const pStart = project([-hw, topY, pz]);
            const pEnd = project([hw, topY, pz]);
            ctx.beginPath();
            ctx.moveTo(pStart[0], pStart[1]);
            ctx.lineTo(pEnd[0], pEnd[1]);
            ctx.strokeStyle = 'rgba(0, 0, 0, 0.15)';
            ctx.lineWidth = 1;
            ctx.stroke();
          }
          ctx.restore();
        }

      // ==========================================
      // MODEL 2: LIBRERO MODULAR NEXUS (nexus-bookshelf)
      // ==========================================
      } else if (state.modelId === 'nexus-bookshelf') {
        const sideThick = 18;
        const shelfThick = 16;
        const numShelves = Math.min(6, Math.max(3, state.modules + 1));
        const numBays = state.width > 200 ? 3 : 2;

        // Back Panel
        drawPolygon([
          [-hw, -hh, hd],
          [hw, -hh, hd],
          [hw, hh, hd],
          [-hw, hh, hd]
        ], '#221914');

        // Left Side Panel
        drawPolygon([
          [-hw, -hh, -hd],
          [-hw + sideThick, -hh, -hd],
          [-hw + sideThick, hh, -hd],
          [-hw, hh, -hd]
        ], shadeColor(baseWood, -15));
        drawPolygon([
          [-hw + sideThick, -hh, -hd],
          [-hw + sideThick, -hh, hd],
          [-hw + sideThick, hh, hd],
          [-hw + sideThick, hh, -hd]
        ], shadeColor(baseWood, 5));

        // Right Side Panel
        drawPolygon([
          [hw - sideThick, -hh, -hd],
          [hw, -hh, -hd],
          [hw, hh, -hd],
          [hw - sideThick, hh, -hd]
        ], shadeColor(baseWood, -25));

        // Top Cap
        drawPolygon([
          [-hw, -hh, -hd],
          [hw, -hh, -hd],
          [hw, -hh, hd],
          [-hw, -hh, hd]
        ], shadeColor(baseWood, 18));

        // Bottom Base Plinth
        drawPolygon([
          [-hw, hh - shelfThick, -hd],
          [hw, hh - shelfThick, -hd],
          [hw, hh, -hd],
          [-hw, hh, -hd]
        ], shadeColor(baseWood, -30));

        // Shelves
        const shelfSpacing = (hh * 2 - shelfThick) / numShelves;
        for (let s = 1; s < numShelves; s++) {
          const sy = -hh + s * shelfSpacing;

          // Shelf Top Face
          drawPolygon([
            [-hw + sideThick, sy, -hd],
            [hw - sideThick, sy, -hd],
            [hw - sideThick, sy, hd],
            [-hw + sideThick, sy, hd]
          ], shadeColor(baseWood, 10));

          // Shelf Front Edge
          drawPolygon([
            [-hw + sideThick, sy, -hd],
            [hw - sideThick, sy, -hd],
            [hw - sideThick, sy + shelfThick, -hd],
            [-hw + sideThick, sy + shelfThick, -hd]
          ], shadeColor(baseWood, -5));

          // LED Ambient glow along shelf back if enabled
          if (state.hasLed) {
            ctx.save();
            const led1 = project([-hw + sideThick + 10, sy, hd - 4]);
            const led2 = project([hw - sideThick - 10, sy, hd - 4]);
            ctx.beginPath();
            ctx.moveTo(led1[0], led1[1]);
            ctx.lineTo(led2[0], led2[1]);
            ctx.strokeStyle = 'rgba(255, 220, 140, 0.85)';
            ctx.lineWidth = 4;
            ctx.shadowColor = '#FFC857';
            ctx.shadowBlur = 12;
            ctx.stroke();
            ctx.restore();
          }

          // Decorative Book Accents on some shelves
          if (s % 2 === 1 && !state.wireframe) {
            const bx = -hw + 45 + (s * 35) % 100;
            const bP1 = project([bx, sy - 28, -hd + 15]);
            const bP2 = project([bx + 16, sy - 28, -hd + 15]);
            const bP3 = project([bx + 16, sy, -hd + 15]);
            const bP4 = project([bx, sy, -hd + 15]);
            ctx.beginPath();
            ctx.moveTo(bP1[0], bP1[1]);
            ctx.lineTo(bP2[0], bP2[1]);
            ctx.lineTo(bP3[0], bP3[1]);
            ctx.lineTo(bP4[0], bP4[1]);
            ctx.closePath();
            ctx.fillStyle = s === 1 ? '#A0522D' : '#334E68';
            ctx.fill();
            ctx.stroke();
          }
        }

        // Vertical Center Bay Dividers
        const bayWidth = (hw * 2 - sideThick * 2) / numBays;
        for (let b = 1; b < numBays; b++) {
          const bx = -hw + sideThick + b * bayWidth;
          drawPolygon([
            [bx, -hh + shelfThick, -hd],
            [bx + 14, -hh + shelfThick, -hd],
            [bx + 14, hh - shelfThick, -hd],
            [bx, hh - shelfThick, -hd]
          ], shadeColor(baseWood, -10));
        }

      // ==========================================
      // MODEL 3: MUEBLE TV FLOTANTE (veta-tv)
      // ==========================================
      } else if (state.modelId === 'veta-tv') {
        const tvH = hh * 0.75;

        // Floating wall mount rear shadow
        ctx.save();
        const wallShadow = project([0, tvH + 20, hd]);
        ctx.beginPath();
        ctx.ellipse(wallShadow[0], wallShadow[1], hw * 0.9, 12, 0, 0, Math.PI * 2);
        ctx.fillStyle = 'rgba(0, 0, 0, 0.2)';
        ctx.filter = 'blur(10px)';
        ctx.fill();
        ctx.restore();

        // Carcass Box
        drawPolygon([[-hw, -tvH, hd], [hw, -tvH, hd], [hw, tvH, hd], [-hw, tvH, hd]], '#1F1712');
        drawPolygon([[-hw, -tvH, -hd], [-hw, -tvH, hd], [-hw, tvH, hd], [-hw, tvH, -hd]], shadeColor(baseWood, -18));
        drawPolygon([[hw, -tvH, -hd], [hw, -tvH, hd], [hw, tvH, hd], [hw, tvH, -hd]], shadeColor(baseWood, -25));
        drawPolygon([[-hw, -tvH, -hd], [hw, -tvH, -hd], [hw, -tvH, hd], [-hw, -tvH, hd]], shadeColor(baseWood, 14));
        drawPolygon([[-hw, tvH, -hd], [hw, tvH, -hd], [hw, tvH, hd], [-hw, tvH, hd]], shadeColor(baseWood, -35));

        // Cable Grommet on top
        if (state.hasCableGrommet || true) {
          const grommet = project([0, -tvH, -hd * 0.2]);
          ctx.beginPath();
          ctx.ellipse(grommet[0], grommet[1], 10, 4, 0, 0, Math.PI * 2);
          ctx.fillStyle = '#222';
          ctx.fill();
          ctx.strokeStyle = '#444';
          ctx.stroke();
        }

        // Drop-down flaps / Flap doors
        const numFlaps = state.modules;
        const flapW = (hw * 2) / numFlaps;

        if (!state.doorsOpen) {
          // Closed flaps
          for (let f = 0; f < numFlaps; f++) {
            const fx = -hw + f * flapW;
            drawPolygon([
              [fx + 2, -tvH + 2, -hd - 1],
              [fx + flapW - 2, -tvH + 2, -hd - 1],
              [fx + flapW - 2, tvH - 2, -hd - 1],
              [fx + 2, tvH - 2, -hd - 1]
            ], shadeColor(baseWood, 0));

            // Minimalist horizontal groove handle
            const hPt1 = project([fx + flapW * 0.2, -tvH + 12, -hd - 2]);
            const hPt2 = project([fx + flapW * 0.8, -tvH + 12, -hd - 2]);
            ctx.beginPath();
            ctx.moveTo(hPt1[0], hPt1[1]);
            ctx.lineTo(hPt2[0], hPt2[1]);
            ctx.strokeStyle = '#1D130C';
            ctx.lineWidth = 2.5;
            ctx.stroke();
          }
        } else {
          // Open down flaps (horizontal)
          for (let f = 0; f < numFlaps; f++) {
            const fx = -hw + f * flapW;
            drawPolygon([
              [fx + 2, tvH, -hd],
              [fx + flapW - 2, tvH, -hd],
              [fx + flapW - 2, tvH, -hd - 45],
              [fx + 2, tvH, -hd - 45]
            ], shadeColor(baseWood, -5));
          }
        }

      // ==========================================
      // MODEL 4: ESCRITORIO STUDIO PRO (studio-desk)
      // ==========================================
      } else if (state.modelId === 'studio-desk') {
        const topThick = 18;
        const deskTopY = -hh * 0.4;
        const deskBotY = deskTopY + topThick;
        const floorY = hh + 30;

        // 4 Modern Desk Legs
        const legOffsets: [number, number][] = [
          [-hw + 25, -hd + 25],
          [hw - 25, -hd + 25],
          [-hw + 25, hd - 25],
          [hw - 25, hd - 25]
        ];

        legOffsets.forEach(([lx, lz]) => {
          drawPolygon([
            [lx, deskBotY, lz],
            [lx + 12, deskBotY, lz],
            [lx + 10, floorY, lz],
            [lx, floorY, lz]
          ], shadeColor(baseWood, -20));
        });

        // Suspended Right Drawer Unit
        const drawerW = hw * 0.7;
        const drawerH = 50;
        const dx = hw - drawerW - 30;
        drawPolygon([[dx, deskBotY, -hd + 20], [hw - 30, deskBotY, -hd + 20], [hw - 30, deskBotY + drawerH, -hd + 20], [dx, deskBotY + drawerH, -hd + 20]], shadeColor(baseWood, -10));
        drawPolygon([[dx, deskBotY, -hd + 20], [dx, deskBotY, hd - 20], [dx, deskBotY + drawerH, hd - 20], [dx, deskBotY + drawerH, -hd + 20]], shadeColor(baseWood, -25));

        // Drawer divide line
        const dLine1 = project([dx, deskBotY + drawerH / 2, -hd + 19]);
        const dLine2 = project([hw - 30, deskBotY + drawerH / 2, -hd + 19]);
        ctx.beginPath();
        ctx.moveTo(dLine1[0], dLine1[1]);
        ctx.lineTo(dLine2[0], dLine2[1]);
        ctx.strokeStyle = '#221914';
        ctx.lineWidth = 1.5;
        ctx.stroke();

        // Main Desktop Slab
        drawPolygon([[-hw, deskTopY, -hd], [hw, deskTopY, -hd], [hw, deskBotY, -hd], [-hw, deskBotY, -hd]], shadeColor(baseWood, -5));
        drawPolygon([[hw, deskTopY, -hd], [hw, deskTopY, hd], [hw, deskBotY, hd], [hw, deskBotY, -hd]], shadeColor(baseWood, -20));
        drawPolygon([[-hw, deskTopY, -hd], [hw, deskTopY, -hd], [hw, deskTopY, hd], [-hw, deskTopY, hd]], shadeColor(baseWood, 15));

      // ==========================================
      // MODEL 5: CONSOLA MINIMAL AURA (aura-console)
      // ==========================================
      } else if (state.modelId === 'aura-console') {
        const consoleH = 32;
        const cTopY = -hh * 0.5;
        const cBotY = cTopY + consoleH;
        const floorY = hh + 35;

        // Long Slender Tapered Legs
        const legOffsets: [number, number][] = [
          [-hw + 18, -hd + 14],
          [hw - 18, -hd + 14],
          [-hw + 18, hd - 14],
          [hw - 18, hd - 14]
        ];

        legOffsets.forEach(([lx, lz]) => {
          drawPolygon([
            [lx, cBotY, lz],
            [lx + 10, cBotY, lz],
            [lx + 5, floorY, lz],
            [lx, floorY, lz]
          ], shadeColor(baseWood, -25));
        });

        // Console Body with rounded feel
        drawPolygon([[-hw, cTopY, -hd], [hw, cTopY, -hd], [hw, cBotY, -hd], [-hw, cBotY, -hd]], shadeColor(baseWood, 0));
        drawPolygon([[hw, cTopY, -hd], [hw, cTopY, hd], [hw, cBotY, hd], [hw, cBotY, -hd]], shadeColor(baseWood, -20));
        drawPolygon([[-hw, cTopY, -hd], [hw, cTopY, -hd], [hw, cTopY, hd], [-hw, cTopY, hd]], shadeColor(baseWood, 16));

        // Concealed Drawer Line & Brass Pull
        const dwLine1 = project([-hw + 25, cTopY + consoleH / 2, -hd - 1]);
        const dwLine2 = project([hw - 25, cTopY + consoleH / 2, -hd - 1]);
        ctx.beginPath();
        ctx.moveTo(dwLine1[0], dwLine1[1]);
        ctx.lineTo(dwLine2[0], dwLine2[1]);
        ctx.strokeStyle = 'rgba(0,0,0,0.25)';
        ctx.lineWidth = 1;
        ctx.stroke();

        const pull = project([0, cTopY + consoleH / 2, -hd - 2]);
        ctx.beginPath();
        ctx.arc(pull[0], pull[1], 4, 0, Math.PI * 2);
        ctx.fillStyle = '#D4AF37'; // Brushed brass pull
        ctx.fill();

      // ==========================================
      // MODEL 6: APARADOR ALTO MORA (mora-highboard)
      // ==========================================
      } else if (state.modelId === 'mora-highboard') {
        const midY = 0;
        const floorY = hh + 30;

        // Base legs
        [[-hw + 20, -hd + 16], [hw - 20, -hd + 16], [-hw + 20, hd - 16], [hw - 20, hd - 16]].forEach(([lx, lz]) => {
          drawPolygon([[lx, hh, lz], [lx + 10, hh, lz], [lx + 6, floorY, lz], [lx, floorY, lz]], shadeColor(baseWood, -25));
        });

        // Upper Section: Showcase with Bronzed Glass effect
        drawPolygon([[-hw, -hh, hd], [hw, -hh, hd], [hw, midY, hd], [-hw, midY, hd]], '#201813');
        drawPolygon([[-hw, -hh, -hd], [hw, -hh, -hd], [hw, -hh, hd], [-hw, -hh, hd]], shadeColor(baseWood, 15));
        drawPolygon([[-hw, -hh, -hd], [-hw, -hh, hd], [-hw, midY, hd], [-hw, midY, -hd]], shadeColor(baseWood, -15));
        drawPolygon([[hw, -hh, -hd], [hw, -hh, hd], [hw, midY, hd], [hw, midY, -hd]], shadeColor(baseWood, -25));

        // Glass Front Reflections
        ctx.save();
        const g1 = project([-hw + 6, -hh + 6, -hd]);
        const g2 = project([hw - 6, -hh + 6, -hd]);
        const g3 = project([hw - 6, midY - 6, -hd]);
        const g4 = project([-hw + 6, midY - 6, -hd]);
        ctx.beginPath();
        ctx.moveTo(g1[0], g1[1]);
        ctx.lineTo(g2[0], g2[1]);
        ctx.lineTo(g3[0], g3[1]);
        ctx.lineTo(g4[0], g4[1]);
        ctx.closePath();
        ctx.fillStyle = 'rgba(180, 150, 110, 0.28)';
        ctx.fill();
        ctx.strokeStyle = 'rgba(255, 255, 255, 0.4)';
        ctx.lineWidth = 1.2;
        ctx.stroke();
        ctx.restore();

        // Lower Section: Slatted Doors Cabinet
        drawPolygon([[-hw, midY, -hd], [hw, midY, -hd], [hw, hh, -hd], [-hw, hh, -hd]], shadeColor(baseWood, 0));
        drawPolygon([[hw, midY, -hd], [hw, midY, hd], [hw, hh, hd], [hw, hh, -hd]], shadeColor(baseWood, -20));

        // Lower vertical slats
        ctx.save();
        const slatSpacing = 9;
        const slatCount = Math.floor(modelW / slatSpacing);
        for (let s = 1; s < slatCount; s++) {
          const sx = -hw + s * slatSpacing;
          const s1 = project([sx, midY + 4, -hd - 1]);
          const s2 = project([sx, hh - 4, -hd - 1]);
          ctx.beginPath();
          ctx.moveTo(s1[0], s1[1]);
          ctx.lineTo(s2[0], s2[1]);
          ctx.strokeStyle = s % 2 === 0 ? 'rgba(0,0,0,0.35)' : 'rgba(255,255,255,0.18)';
          ctx.lineWidth = 1.2;
          ctx.stroke();
        }
        ctx.restore();

      // ==========================================
      // MODEL 7 (DEFAULT): APARADOR HORIZON (horizon)
      // ==========================================
      } else {
        // Draw Base Legs / Plinth
        ctx.save();
        if (state.baseType === 'conicas') {
          const legOffsets: [number, number][] = [
            [-hw + 24, -hd + 16],
            [hw - 24, -hd + 16],
            [-hw + 24, hd - 16],
            [hw - 24, hd - 16]
          ];
          if (state.width > 210) {
            legOffsets.push([0, 0]); // Center stabilizer leg
          }
          legOffsets.forEach(([lx, lz]) => {
            const topPt = project([lx, hh, lz]);
            const botPt = project([lx, hh + 46, lz]);
            ctx.beginPath();
            ctx.moveTo(topPt[0] - 6, topPt[1]);
            ctx.lineTo(topPt[0] + 6, topPt[1]);
            ctx.lineTo(botPt[0] + 3, botPt[1]);
            ctx.lineTo(botPt[0] - 3, botPt[1]);
            ctx.closePath();
            ctx.fillStyle = state.wireframe ? 'transparent' : selectedWood.colorHex;
            ctx.strokeStyle = state.wireframe ? '#0D4F60' : '#452A18';
            ctx.lineWidth = state.wireframe ? 1.5 : 1;
            ctx.fill();
            ctx.stroke();
          });
        } else if (state.baseType === 'zocalo') {
          const p1 = project([-hw + 20, hh, -hd + 15]);
          const p2 = project([hw - 20, hh, -hd + 15]);
          const p3 = project([hw - 20, hh + 24, -hd + 15]);
          const p4 = project([-hw + 20, hh + 24, -hd + 15]);
          ctx.beginPath();
          ctx.moveTo(p1[0], p1[1]);
          ctx.lineTo(p2[0], p2[1]);
          ctx.lineTo(p3[0], p3[1]);
          ctx.lineTo(p4[0], p4[1]);
          ctx.closePath();
          ctx.fillStyle = state.wireframe ? 'transparent' : '#2A201A';
          ctx.strokeStyle = '#103E4A';
          ctx.fill();
          ctx.stroke();
        }
        ctx.restore();

        // Main Cabinet Carcass
        const v: Point3D[] = [
          [-hw, -hh, -hd], // 0: Top front left
          [hw, -hh, -hd],  // 1: Top front right
          [hw, hh, -hd],   // 2: Bottom front right
          [-hw, hh, -hd],  // 3: Bottom front left
          [-hw, -hh, hd],  // 4: Top back left
          [hw, -hh, hd],   // 5: Top back right
          [hw, hh, hd],    // 6: Bottom back right
          [-hw, hh, hd]    // 7: Bottom back left
        ];

        const pv = v.map(project);
        const drawQuad = (indices: number[], fill: string, stroke: string = '#2B1C10') => {
          ctx.beginPath();
          ctx.moveTo(pv[indices[0]][0], pv[indices[0]][1]);
          for (let i = 1; i < indices.length; i++) {
            ctx.lineTo(pv[indices[i]][0], pv[indices[i]][1]);
          }
          ctx.closePath();
          if (!state.wireframe) {
            ctx.fillStyle = fill;
            ctx.fill();
          }
          ctx.strokeStyle = state.wireframe ? '#0D4F60' : stroke;
          ctx.lineWidth = state.wireframe ? 1.2 : 0.8;
          ctx.stroke();
        };

        // Back, sides, top, bottom
        drawQuad([4, 5, 6, 7], '#28201A');
        drawQuad([4, 0, 3, 7], shadeColor(baseWood, -18));
        drawQuad([1, 5, 6, 2], shadeColor(baseWood, -25));
        drawQuad([4, 5, 1, 0], shadeColor(baseWood, 14));
        drawQuad([3, 2, 6, 7], shadeColor(baseWood, -35));

        // Front Face / Doors & Tambour Slats
        if (!state.doorsOpen) {
          drawQuad([0, 1, 2, 3], shadeColor(baseWood, 0));

          if (!state.wireframe) {
            ctx.save();
            const moduleWidth = modelW / state.modules;
            const slatSpacing = 9;
            const slatCount = Math.floor(modelW / slatSpacing);

            for (let s = 1; s < slatCount; s++) {
              const sx = -hw + s * slatSpacing;
              const topSlat = project([sx, -hh + 3, -hd - 1]);
              const botSlat = project([sx, hh - 3, -hd - 1]);

              ctx.beginPath();
              ctx.moveTo(topSlat[0], topSlat[1]);
              ctx.lineTo(botSlat[0], botSlat[1]);
              ctx.strokeStyle = s % 2 === 0 ? 'rgba(0,0,0,0.35)' : 'rgba(255,255,255,0.18)';
              ctx.lineWidth = 1.2;
              ctx.stroke();
            }

            // Module Door Dividers
            for (let m = 1; m < state.modules; m++) {
              const divX = -hw + m * moduleWidth;
              const topDiv = project([divX, -hh, -hd - 2]);
              const botDiv = project([divX, hh, -hd - 2]);
              ctx.beginPath();
              ctx.moveTo(topDiv[0], topDiv[1]);
              ctx.lineTo(botDiv[0], botDiv[1]);
              ctx.strokeStyle = '#103E4A';
              ctx.lineWidth = 2.5;
              ctx.stroke();
            }

            // Recessed handles
            for (let m = 0; m < state.modules; m++) {
              const hx = -hw + m * moduleWidth + moduleWidth * 0.85;
              const hPt1 = project([hx, -16, -hd - 3]);
              const hPt2 = project([hx, 16, -hd - 3]);
              ctx.beginPath();
              ctx.moveTo(hPt1[0], hPt1[1]);
              ctx.lineTo(hPt2[0], hPt2[1]);
              ctx.strokeStyle = '#321D10';
              ctx.lineWidth = 3.5;
              ctx.lineCap = 'round';
              ctx.stroke();
            }
            ctx.restore();
          }
        } else {
          // Open doors view: Show interior partitions & shelves
          drawQuad([0, 1, 2, 3], '#4A3728');
          const pLeft = project([-hw + 8, 0, -hd + 10]);
          const pRight = project([hw - 8, 0, -hd + 10]);
          ctx.beginPath();
          ctx.moveTo(pLeft[0], pLeft[1]);
          ctx.lineTo(pRight[0], pRight[1]);
          ctx.strokeStyle = selectedWood.colorHex;
          ctx.lineWidth = 4;
          ctx.stroke();

          for (let m = 0; m < state.modules; m++) {
            const mWidth = modelW / state.modules;
            const doorPivot = -hw + m * mWidth;
            const openP1 = project([doorPivot, -hh, -hd]);
            const openP2 = project([doorPivot + mWidth * 0.7, -hh, -hd - 45]);
            const openP3 = project([doorPivot + mWidth * 0.7, hh, -hd - 45]);
            const openP4 = project([doorPivot, hh, -hd]);

            ctx.beginPath();
            ctx.moveTo(openP1[0], openP1[1]);
            ctx.lineTo(openP2[0], openP2[1]);
            ctx.lineTo(openP3[0], openP3[1]);
            ctx.lineTo(openP4[0], openP4[1]);
            ctx.closePath();
            ctx.fillStyle = selectedWood.colorHex;
            ctx.strokeStyle = '#103E4A';
            ctx.fill();
            ctx.stroke();
          }
        }
      }

      ctx.restore();
    };

    renderScene();

    return () => {
      cancelAnimationFrame(animationFrameId);
    };
  }, [state, selectedWood, rotation]);

  return (
    <div
      className="relative w-full h-[520px] md:h-[620px] bg-[#EAE7DC] rounded-xl overflow-hidden shadow-lg flex items-center justify-center select-none group cursor-grab active:cursor-grabbing"
      onMouseDown={handleMouseDown}
      onMouseMove={handleMouseMove}
      onMouseUp={handleMouseUp}
      onMouseLeave={handleMouseUp}
    >
      {/* 3D WebGL Canvas */}
      <canvas
        ref={canvasRef}
        width={1000}
        height={650}
        className="w-full h-full object-cover transition-opacity duration-300"
      />

      {/* Floating CAD Dimension Overlay Labels */}
      <div className="absolute inset-0 pointer-events-none p-4 md:p-6 flex flex-col justify-between">
        {/* Top Width Cota */}
        <div className="w-full flex items-center justify-center gap-2 pt-1">
          <div className="h-[1px] flex-1 bg-text-primary/40 relative">
            <span className="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-1.5 bg-text-primary rotate-45"></span>
          </div>
          <div className="px-2.5 py-1 rounded bg-surface-card/90 backdrop-blur-md shadow-sm pointer-events-auto flex items-center gap-1">
            <span className="material-symbols-outlined text-[13px] text-secondary">straighten</span>
            <span className="font-label-technical text-label-technical text-text-primary font-bold">
              W: {state.width.toFixed(1)} cm
            </span>
          </div>
          <div className="h-[1px] flex-1 bg-text-primary/40 relative">
            <span className="absolute right-0 top-1/2 -translate-y-1/2 w-1.5 h-1.5 bg-text-primary rotate-45"></span>
          </div>
        </div>

        {/* Middle Height & Depth Cotas + Dynamic Hotspot */}
        <div className="w-full flex items-center justify-between">
          {/* Height (Left) */}
          <div className="flex flex-col items-center gap-1">
            <div className="w-[1px] h-28 bg-text-primary/40 relative">
              <span className="absolute top-0 left-1/2 -translate-x-1/2 w-1.5 h-1.5 bg-text-primary rotate-45"></span>
              <span className="absolute bottom-0 left-1/2 -translate-x-1/2 w-1.5 h-1.5 bg-text-primary rotate-45"></span>
            </div>
            <div className="px-2 py-0.5 rounded bg-surface-card/90 backdrop-blur-md shadow-sm pointer-events-auto [writing-mode:vertical-rl] rotate-180">
              <span className="font-label-technical text-[11px] text-text-primary font-bold">
                H: {state.height.toFixed(1)} cm
              </span>
            </div>
          </div>

          {/* Interactive Hotspot for CNC detail */}
          <div className="pointer-events-auto relative">
            <button
              onClick={() => {
                setShowHotspotTooltip(!showHotspotTooltip);
                if (onHotspotClick) onHotspotClick();
              }}
              className="w-9 h-9 rounded-full bg-secondary-container text-white flex items-center justify-center shadow-lg hover:scale-110 transition-transform animate-pulse cursor-pointer"
              title="Ver detalle de mecanizado CNC"
            >
              <span className="material-symbols-outlined text-[18px]">search</span>
            </button>
            {showHotspotTooltip && (
              <div className="absolute bottom-11 left-1/2 -translate-x-1/2 w-64 bg-surface-card/95 backdrop-blur-md p-3.5 rounded-xl shadow-xl text-left transition-all z-30 border border-border-subtle animate-in fade-in zoom-in-95">
                <p className="font-headline-sm text-[13px] font-bold text-text-primary">{hotspotInfo.title}</p>
                <p className="font-body-sm text-[11px] text-text-muted mt-1 leading-snug">
                  {hotspotInfo.desc}
                </p>
                <button
                  onClick={() => setShowHotspotTooltip(false)}
                  className="mt-2 text-[10px] text-secondary font-bold uppercase tracking-wider hover:underline cursor-pointer"
                >
                  Cerrar
                </button>
              </div>
            )}
          </div>

          {/* Depth (Right) */}
          <div className="flex flex-col items-center gap-1">
            <div className="px-2 py-0.5 rounded bg-surface-card/90 backdrop-blur-md shadow-sm pointer-events-auto [writing-mode:vertical-rl]">
              <span className="font-label-technical text-[11px] text-text-primary font-bold">
                D: {state.depth.toFixed(1)} cm
              </span>
            </div>
            <div className="w-[1px] h-28 bg-text-primary/40 relative">
              <span className="absolute top-0 left-1/2 -translate-x-1/2 w-1.5 h-1.5 bg-text-primary rotate-45"></span>
              <span className="absolute bottom-0 left-1/2 -translate-x-1/2 w-1.5 h-1.5 bg-text-primary rotate-45"></span>
            </div>
          </div>
        </div>

        {/* Bottom Bar: Telemetry & Structural Stability status */}
        <div className="w-full flex items-end justify-between pointer-events-auto gap-2">
          <div className="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-lg bg-surface-card/90 backdrop-blur-md shadow-sm font-label-technical text-label-technical text-text-primary">
            <span className="w-2 h-2 rounded-full bg-state-success animate-ping"></span>
            <span className="font-semibold">Three.js WebGL</span>
            <span className="text-text-muted">•</span>
            <span className="text-text-muted">{fps} FPS</span>
            <span className="text-text-muted">•</span>
            <span className="text-state-success font-semibold">Tolerancia ±0.2 mm</span>
          </div>

          <div className="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-surface-card/95 backdrop-blur-md shadow-md text-text-primary font-body-sm text-[12px] font-medium">
            {state.width > 220 ? (
              <>
                <span className="material-symbols-outlined text-[16px] text-secondary">info</span>
                <span className="text-secondary font-bold">Luz &gt; 220cm: Alma de refuerzo anti-flexión incluida</span>
              </>
            ) : (
              <>
                <span className="material-symbols-outlined text-[16px] text-state-success">check_circle</span>
                <span>Proporción óptima: flecha máx. &lt;0.5 mm</span>
              </>
            )}
          </div>
        </div>
      </div>
    </div>
  );
};
