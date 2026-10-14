// Interactive 3D Canvas Viewport Engine for CarpinterSoft
(function () {
    const canvas = document.getElementById('viewportCanvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    if (!ctx) return;

    let rotation = { x: 18, y: -25 };
    let isDragging = false;
    let prevMouse = { x: 0, y: 0 };
    let animId = null;

    // Mouse handlers
    const container = document.getElementById('viewportContainer');
    container.addEventListener('mousedown', (e) => {
        isDragging = true;
        prevMouse = { x: e.clientX, y: e.clientY };
    });

    window.addEventListener('mousemove', (e) => {
        if (!isDragging) return;
        const deltaX = e.clientX - prevMouse.x;
        const deltaY = e.clientY - prevMouse.y;
        rotation.x = Math.max(-60, Math.min(85, rotation.x + deltaY * 0.4));
        rotation.y = (rotation.y + deltaX * 0.4) % 360;
        prevMouse = { x: e.clientX, y: e.clientY };
    });

    window.addEventListener('mouseup', () => { isDragging = false; });

    // Touch support for tablets/mobile
    container.addEventListener('touchstart', (e) => {
        if (e.touches.length === 1) {
            isDragging = true;
            prevMouse = { x: e.touches[0].clientX, y: e.touches[0].clientY };
        }
    });

    window.addEventListener('touchmove', (e) => {
        if (!isDragging || e.touches.length !== 1) return;
        const deltaX = e.touches[0].clientX - prevMouse.x;
        const deltaY = e.touches[0].clientY - prevMouse.y;
        rotation.x = Math.max(-60, Math.min(85, rotation.x + deltaY * 0.4));
        rotation.y = (rotation.y + deltaX * 0.4) % 360;
        prevMouse = { x: e.touches[0].clientX, y: e.touches[0].clientY };
    });

    window.addEventListener('touchend', () => { isDragging = false; });

    function shadeColor(color, percent) {
        let R = parseInt(color.substring(1, 3), 16);
        let G = parseInt(color.substring(3, 5), 16);
        let B = parseInt(color.substring(5, 7), 16);

        R = Math.min(255, Math.max(0, Math.round((R * (100 + percent)) / 100)));
        G = Math.min(255, Math.max(0, Math.round((G * (100 + percent)) / 100)));
        B = Math.min(255, Math.max(0, Math.round((B * (100 + percent)) / 100)));

        return '#' + R.toString(16).padStart(2, '0') + G.toString(16).padStart(2, '0') + B.toString(16).padStart(2, '0');
    }

    function renderScene() {
        const width = canvas.width;
        const height = canvas.height;
        ctx.clearRect(0, 0, width, height);

        // Auto-rotation step
        if (CUSTOMIZER_DATA.autoRotate) {
            rotation.y = (rotation.y + 0.8) % 360;
        }

        // Background Radial Gradient
        const grad = ctx.createRadialGradient(width / 2, height / 2, 40, width / 2, height / 2, width * 0.7);
        grad.addColorStop(0, '#F4F2EB');
        grad.addColorStop(1, '#E0DCD1');
        ctx.fillStyle = grad;
        ctx.fillRect(0, 0, width, height);

        // Grid flooring
        ctx.save();
        ctx.strokeStyle = 'rgba(16, 62, 74, 0.08)';
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

        // Ambient ground shadow
        ctx.save();
        ctx.beginPath();
        const shadowW = (CUSTOMIZER_DATA.width / 180) * 360 * CUSTOMIZER_DATA.zoom;
        const shadowH = (CUSTOMIZER_DATA.depth / 45) * 55 * CUSTOMIZER_DATA.zoom;
        const isFloating = CUSTOMIZER_DATA.baseType === 'flotante' || CUSTOMIZER_DATA.modelId === 'veta-tv';
        ctx.ellipse(width / 2, gridY + (isFloating ? 24 : 12), shadowW, shadowH, 0, 0, Math.PI * 2);
        ctx.fillStyle = isFloating ? 'rgba(20, 35, 40, 0.08)' : 'rgba(20, 35, 40, 0.16)';
        ctx.filter = 'blur(16px)';
        ctx.fill();
        ctx.restore();

        // 3D Projection Matrix
        ctx.save();
        ctx.translate(width / 2, height * 0.52);
        ctx.scale(CUSTOMIZER_DATA.zoom, CUSTOMIZER_DATA.zoom);

        const radX = (rotation.x * Math.PI) / 180;
        const radY = (rotation.y * Math.PI) / 180;

        const baseScale = 2.0;
        const modelW = CUSTOMIZER_DATA.width * baseScale;
        const modelH = CUSTOMIZER_DATA.height * baseScale * 0.95;
        const modelD = CUSTOMIZER_DATA.depth * baseScale * 0.8;

        const hw = modelW / 2;
        const hh = modelH / 2;
        const hd = modelD / 2;

        function project([x, y, z]) {
            const cosY = Math.cos(radY);
            const sinY = Math.sin(radY);
            const x1 = x * cosY - z * sinY;
            const z1 = x * sinY + z * cosY;

            const cosX = Math.cos(radX);
            const sinX = Math.sin(radX);
            const y2 = y * cosX - z1 * sinX;
            const z2 = y * sinX + z1 * cosX;

            const distance = 950;
            const fov = distance / (distance + z2 * 0.4);
            return [x1 * fov, y2 * fov];
        }

        const baseWood = CUSTOMIZER_DATA.woodColor || '#D4A373';

        function drawPolygon(points, fill, stroke = '#2B1C10') {
            const projected = points.map(project);
            ctx.beginPath();
            ctx.moveTo(projected[0][0], projected[0][1]);
            for (let i = 1; i < projected.length; i++) {
                ctx.lineTo(projected[i][0], projected[i][1]);
            }
            ctx.closePath();
            if (!CUSTOMIZER_DATA.wireframe) {
                ctx.fillStyle = fill;
                ctx.fill();
            }
            ctx.strokeStyle = CUSTOMIZER_DATA.wireframe ? '#0D4F60' : stroke;
            ctx.lineWidth = CUSTOMIZER_DATA.wireframe ? 1.2 : 0.8;
            ctx.stroke();
        }

        // ==========================================
        // 1. MESA DE COMEDOR FUSIÓN (fusion-table)
        // ==========================================
        if (CUSTOMIZER_DATA.modelId === 'fusion-table') {
            const topThick = 24;
            const topY = -hh;
            const botY = topY + topThick;
            const floorY = hh + 30;

            // Apron under frame
            const apInset = 40;
            drawPolygon([
                [-hw + apInset, botY, -hd + apInset],
                [hw - apInset, botY, -hd + apInset],
                [hw - apInset, botY + 28, -hd + apInset],
                [-hw + apInset, botY + 28, -hd + apInset]
            ], shadeColor(baseWood, -25));

            // 4 Sturdy Legs
            const legW = 22;
            [
                [-hw + 40, -hd + 35],
                [hw - 40 - legW, -hd + 35],
                [-hw + 40, hd - 35 - legW],
                [hw - 40 - legW, hd - 35 - legW]
            ].forEach(([lx, lz]) => {
                drawPolygon([[lx, botY, lz], [lx + legW, botY, lz], [lx + legW - 4, floorY, lz], [lx + 4, floorY, lz]], shadeColor(baseWood, -15));
                drawPolygon([[lx + legW, botY, lz], [lx + legW, botY, lz + legW], [lx + legW - 4, floorY, lz + legW - 4], [lx + legW - 4, floorY, lz]], shadeColor(baseWood, -30));
            });

            // Tabletop Solid Slab with Bevel
            drawPolygon([[-hw, botY, -hd], [hw, botY, -hd], [hw, botY, hd], [-hw, botY, hd]], shadeColor(baseWood, -25));
            drawPolygon([[-hw, topY, -hd], [hw, topY, -hd], [hw, botY, -hd], [-hw, botY, -hd]], shadeColor(baseWood, -5));
            drawPolygon([[hw, topY, -hd], [hw, topY, hd], [hw, botY, hd], [hw, botY, -hd]], shadeColor(baseWood, -20));
            drawPolygon([[-hw, topY, -hd], [hw, topY, -hd], [hw, topY, hd], [-hw, topY, hd]], shadeColor(baseWood, 15));

        // ==========================================
        // 2. LIBRERO MODULAR NEXUS (nexus-bookshelf)
        // ==========================================
        } else if (CUSTOMIZER_DATA.modelId === 'nexus-bookshelf') {
            const sideThick = 18;
            const shelfThick = 16;
            const numShelves = Math.min(6, Math.max(3, CUSTOMIZER_DATA.modules + 1));
            const numBays = CUSTOMIZER_DATA.width > 200 ? 3 : 2;

            drawPolygon([[-hw, -hh, hd], [hw, -hh, hd], [hw, hh, hd], [-hw, hh, hd]], '#221914');
            drawPolygon([[-hw, -hh, -hd], [-hw + sideThick, -hh, -hd], [-hw + sideThick, hh, -hd], [-hw, hh, -hd]], shadeColor(baseWood, -15));
            drawPolygon([[hw - sideThick, -hh, -hd], [hw, -hh, -hd], [hw, hh, -hd], [hw - sideThick, hh, -hd]], shadeColor(baseWood, -25));
            drawPolygon([[-hw, -hh, -hd], [hw, -hh, -hd], [hw, -hh, hd], [-hw, -hh, hd]], shadeColor(baseWood, 18));
            drawPolygon([[-hw, hh - shelfThick, -hd], [hw, hh - shelfThick, -hd], [hw, hh, -hd], [-hw, hh, -hd]], shadeColor(baseWood, -30));

            const shelfSpacing = (hh * 2 - shelfThick) / numShelves;
            for (let s = 1; s < numShelves; s++) {
                const sy = -hh + s * shelfSpacing;
                drawPolygon([[-hw + sideThick, sy, -hd], [hw - sideThick, sy, -hd], [hw - sideThick, sy, hd], [-hw + sideThick, sy, hd]], shadeColor(baseWood, 10));
                drawPolygon([[-hw + sideThick, sy, -hd], [hw - sideThick, sy, -hd], [hw - sideThick, sy + shelfThick, -hd], [-hw + sideThick, sy + shelfThick, -hd]], shadeColor(baseWood, -5));

                if (CUSTOMIZER_DATA.hasLed) {
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
            }

            const bayWidth = (hw * 2 - sideThick * 2) / numBays;
            for (let b = 1; b < numBays; b++) {
                const bx = -hw + sideThick + b * bayWidth;
                drawPolygon([[bx, -hh + shelfThick, -hd], [bx + 14, -hh + shelfThick, -hd], [bx + 14, hh - shelfThick, -hd], [bx, hh - shelfThick, -hd]], shadeColor(baseWood, -10));
            }

        // ==========================================
        // 3. MUEBLE TV FLOTANTE VETA (veta-tv)
        // ==========================================
        } else if (CUSTOMIZER_DATA.modelId === 'veta-tv') {
            const tvH = hh * 0.75;
            drawPolygon([[-hw, -tvH, hd], [hw, -tvH, hd], [hw, tvH, hd], [-hw, tvH, hd]], '#1F1712');
            drawPolygon([[-hw, -tvH, -hd], [-hw, -tvH, hd], [-hw, tvH, hd], [-hw, tvH, -hd]], shadeColor(baseWood, -18));
            drawPolygon([[hw, -tvH, -hd], [hw, -tvH, hd], [hw, tvH, hd], [hw, tvH, -hd]], shadeColor(baseWood, -25));
            drawPolygon([[-hw, -tvH, -hd], [hw, -tvH, -hd], [hw, -tvH, hd], [-hw, -tvH, hd]], shadeColor(baseWood, 14));
            drawPolygon([[-hw, tvH, -hd], [hw, tvH, -hd], [hw, tvH, hd], [-hw, tvH, hd]], shadeColor(baseWood, -35));

            const numFlaps = CUSTOMIZER_DATA.modules;
            const flapW = (hw * 2) / numFlaps;

            if (!CUSTOMIZER_DATA.doorsOpen) {
                for (let f = 0; f < numFlaps; f++) {
                    const fx = -hw + f * flapW;
                    drawPolygon([[fx + 2, -tvH + 2, -hd - 1], [fx + flapW - 2, -tvH + 2, -hd - 1], [fx + flapW - 2, tvH - 2, -hd - 1], [fx + 2, tvH - 2, -hd - 1]], shadeColor(baseWood, 0));
                    const h1 = project([fx + flapW * 0.2, -tvH + 12, -hd - 2]);
                    const h2 = project([fx + flapW * 0.8, -tvH + 12, -hd - 2]);
                    ctx.beginPath();
                    ctx.moveTo(h1[0], h1[1]);
                    ctx.lineTo(h2[0], h2[1]);
                    ctx.strokeStyle = '#1D130C';
                    ctx.lineWidth = 2.5;
                    ctx.stroke();
                }
            } else {
                for (let f = 0; f < numFlaps; f++) {
                    const fx = -hw + f * flapW;
                    drawPolygon([[fx + 2, tvH, -hd], [fx + flapW - 2, tvH, -hd], [fx + flapW - 2, tvH, -hd - 45], [fx + 2, tvH, -hd - 45]], shadeColor(baseWood, -5));
                }
            }

        // ==========================================
        // 4. ESCRITORIO STUDIO PRO (studio-desk)
        // ==========================================
        } else if (CUSTOMIZER_DATA.modelId === 'studio-desk') {
            const deskTopY = -hh * 0.4;
            const deskBotY = deskTopY + 18;
            const floorY = hh + 30;

            [[-hw + 25, -hd + 25], [hw - 25, -hd + 25], [-hw + 25, hd - 25], [hw - 25, hd - 25]].forEach(([lx, lz]) => {
                drawPolygon([[lx, deskBotY, lz], [lx + 12, deskBotY, lz], [lx + 10, floorY, lz], [lx, floorY, lz]], shadeColor(baseWood, -20));
            });

            // Suspended Right Drawer
            const dwW = hw * 0.7;
            const dx = hw - dwW - 30;
            drawPolygon([[dx, deskBotY, -hd + 20], [hw - 30, deskBotY, -hd + 20], [hw - 30, deskBotY + 50, -hd + 20], [dx, deskBotY + 50, -hd + 20]], shadeColor(baseWood, -10));
            drawPolygon([[dx, deskBotY, -hd + 20], [dx, deskBotY, hd - 20], [dx, deskBotY + 50, hd - 20], [dx, deskBotY + 50, -hd + 20]], shadeColor(baseWood, -25));

            drawPolygon([[-hw, deskTopY, -hd], [hw, deskTopY, -hd], [hw, deskBotY, -hd], [-hw, deskBotY, -hd]], shadeColor(baseWood, -5));
            drawPolygon([[hw, deskTopY, -hd], [hw, deskTopY, hd], [hw, deskBotY, hd], [hw, deskBotY, -hd]], shadeColor(baseWood, -20));
            drawPolygon([[-hw, deskTopY, -hd], [hw, deskTopY, -hd], [hw, deskTopY, hd], [-hw, deskTopY, hd]], shadeColor(baseWood, 15));

        // ==========================================
        // 5. APARADOR ALISTONADO HORIZON (DEFAULT)
        // ==========================================
        } else {
            // Legs / Plinth
            if (CUSTOMIZER_DATA.baseType === 'conicas') {
                [[-hw + 24, -hd + 16], [hw - 24, -hd + 16], [-hw + 24, hd - 16], [hw - 24, hd - 16]].forEach(([lx, lz]) => {
                    const topPt = project([lx, hh, lz]);
                    const botPt = project([lx, hh + 46, lz]);
                    ctx.beginPath();
                    ctx.moveTo(topPt[0] - 6, topPt[1]);
                    ctx.lineTo(topPt[0] + 6, topPt[1]);
                    ctx.lineTo(botPt[0] + 3, botPt[1]);
                    ctx.lineTo(botPt[0] - 3, botPt[1]);
                    ctx.closePath();
                    ctx.fillStyle = CUSTOMIZER_DATA.wireframe ? 'transparent' : baseWood;
                    ctx.strokeStyle = CUSTOMIZER_DATA.wireframe ? '#0D4F60' : '#452A18';
                    ctx.fill();
                    ctx.stroke();
                });
            } else if (CUSTOMIZER_DATA.baseType === 'zocalo') {
                drawPolygon([[-hw + 20, hh, -hd + 15], [hw - 20, hh, -hd + 15], [hw - 20, hh + 24, -hd + 15], [-hw + 20, hh + 24, -hd + 15]], '#2A201A');
            }

            // Carcass
            drawPolygon([[-hw, -hh, hd], [hw, -hh, hd], [hw, hh, hd], [-hw, hh, hd]], '#28201A');
            drawPolygon([[-hw, -hh, -hd], [-hw, -hh, hd], [-hw, hh, hd], [-hw, hh, -hd]], shadeColor(baseWood, -18));
            drawPolygon([[hw, -hh, -hd], [hw, -hh, hd], [hw, hh, hd], [hw, hh, -hd]], shadeColor(baseWood, -25));
            drawPolygon([[-hw, -hh, -hd], [hw, -hh, -hd], [hw, -hh, hd], [-hw, -hh, hd]], shadeColor(baseWood, 14));
            drawPolygon([[-hw, hh, -hd], [hw, hh, -hd], [hw, hh, hd], [-hw, hh, hd]], shadeColor(baseWood, -35));

            if (!CUSTOMIZER_DATA.doorsOpen) {
                drawPolygon([[-hw, -hh, -hd], [hw, -hh, -hd], [hw, hh, -hd], [-hw, hh, -hd]], shadeColor(baseWood, 0));

                if (!CUSTOMIZER_DATA.wireframe) {
                    ctx.save();
                    const slatSpacing = 9;
                    const slatCount = Math.floor(modelW / slatSpacing);
                    for (let s = 1; s < slatCount; s++) {
                        const sx = -hw + s * slatSpacing;
                        const s1 = project([sx, -hh + 3, -hd - 1]);
                        const s2 = project([sx, hh - 3, -hd - 1]);
                        ctx.beginPath();
                        ctx.moveTo(s1[0], s1[1]);
                        ctx.lineTo(s2[0], s2[1]);
                        ctx.strokeStyle = s % 2 === 0 ? 'rgba(0,0,0,0.35)' : 'rgba(255,255,255,0.18)';
                        ctx.lineWidth = 1.2;
                        ctx.stroke();
                    }

                    const mWidth = modelW / CUSTOMIZER_DATA.modules;
                    for (let m = 1; m < CUSTOMIZER_DATA.modules; m++) {
                        const divX = -hw + m * mWidth;
                        const d1 = project([divX, -hh, -hd - 2]);
                        const d2 = project([divX, hh, -hd - 2]);
                        ctx.beginPath();
                        ctx.moveTo(d1[0], d1[1]);
                        ctx.lineTo(d2[0], d2[1]);
                        ctx.strokeStyle = '#103E4A';
                        ctx.lineWidth = 2.5;
                        ctx.stroke();
                    }
                    ctx.restore();
                }
            } else {
                drawPolygon([[-hw, -hh, -hd], [hw, -hh, -hd], [hw, hh, -hd], [-hw, hh, -hd]], '#4A3728');
                const mWidth = modelW / CUSTOMIZER_DATA.modules;
                for (let m = 0; m < CUSTOMIZER_DATA.modules; m++) {
                    const doorPivot = -hw + m * mWidth;
                    drawPolygon([
                        [doorPivot, -hh, -hd],
                        [doorPivot + mWidth * 0.7, -hh, -hd - 45],
                        [doorPivot + mWidth * 0.7, hh, -hd - 45],
                        [doorPivot, hh, -hd]
                    ], baseWood);
                }
            }
        }

        ctx.restore();
        animId = requestAnimationFrame(renderScene);
    }

    renderScene();

    // Global hooks
    window.setCamera = function (mode) {
        CUSTOMIZER_DATA.cameraView = mode;
        if (mode === 'front') rotation = { x: 0, y: 0 };
        else if (mode === 'top') rotation = { x: 80, y: 0 };
        else rotation = { x: 18, y: -25 };
    };

    window.toggleWireframe = function () {
        CUSTOMIZER_DATA.wireframe = !CUSTOMIZER_DATA.wireframe;
        document.getElementById('btnWireframe').classList.toggle('bg-primary', CUSTOMIZER_DATA.wireframe);
        document.getElementById('btnWireframe').classList.toggle('text-white', CUSTOMIZER_DATA.wireframe);
    };

    window.toggleAutoRotate = function () {
        CUSTOMIZER_DATA.autoRotate = !CUSTOMIZER_DATA.autoRotate;
        document.getElementById('btnRotate').classList.toggle('bg-secondary-container', CUSTOMIZER_DATA.autoRotate);
        document.getElementById('btnRotate').classList.toggle('text-white', CUSTOMIZER_DATA.autoRotate);
    };

    window.toggleDoors = function () {
        CUSTOMIZER_DATA.doorsOpen = !CUSTOMIZER_DATA.doorsOpen;
        document.getElementById('lblDoors').innerText = CUSTOMIZER_DATA.doorsOpen ? 'Cerrar Puertas' : 'Abrir Puertas';
    };
})();
