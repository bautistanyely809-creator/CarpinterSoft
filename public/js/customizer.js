// Customizer Controller logic for CarpinterSoft
function recalculateQuote() {
    fetch('/api/quote/calculate', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            model_id: CUSTOMIZER_DATA.modelId,
            width: CUSTOMIZER_DATA.width,
            height: CUSTOMIZER_DATA.height,
            depth: CUSTOMIZER_DATA.depth,
            modules: CUSTOMIZER_DATA.modules,
            wood_id: CUSTOMIZER_DATA.woodId,
            finish_type: CUSTOMIZER_DATA.finishType,
            base_type: CUSTOMIZER_DATA.baseType,
            has_led: CUSTOMIZER_DATA.hasLed,
            has_grommet: CUSTOMIZER_DATA.hasGrommet
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.total) {
            document.getElementById('quoteTotal').innerText = data.total;
            document.getElementById('quoteVol').innerText = data.volume;
            document.getElementById('costWood').innerText = data.woodCost;
            document.getElementById('costCnc').innerText = data.cncCost;
            document.getElementById('costHardware').innerText = data.hardwareCost;
            document.getElementById('costFinish').innerText = data.finishCost;
            document.getElementById('costExtras').innerText = data.extrasCost;
        }
    })
    .catch(err => {
        console.error('Error calculando cotización:', err);
    });
}

function updateWidth(val) {
    CUSTOMIZER_DATA.width = Number(val);
    document.getElementById('labelWidth').innerText = val;
    document.getElementById('cotaWidth').innerText = 'W: ' + val + ' cm';
    recalculateQuote();
}

function updateHeight(val) {
    CUSTOMIZER_DATA.height = Number(val);
    document.getElementById('labelHeight').innerText = val;
    document.getElementById('cotaHeight').innerText = 'H: ' + val + ' cm';
    recalculateQuote();
}

function updateDepth(val) {
    CUSTOMIZER_DATA.depth = Number(val);
    document.getElementById('labelDepth').innerText = val;
    document.getElementById('cotaDepth').innerText = 'D: ' + val + ' cm';
    recalculateQuote();
}

function adjustParam(param, delta) {
    if (param === 'width') {
        const inp = document.getElementById('inputWidth');
        const nextVal = Math.min(Number(inp.max), Math.max(Number(inp.min), Number(inp.value) + delta));
        inp.value = nextVal;
        updateWidth(nextVal);
    }
}

function updateModules(m) {
    CUSTOMIZER_DATA.modules = m;
    [2, 3, 4].forEach(val => {
        const btn = document.getElementById('btnMod' + val);
        if (btn) {
            btn.className = val === m
                ? 'p-2 rounded-xl text-center flex flex-col items-center gap-0.5 transition-all cursor-pointer bg-primary text-white shadow-sm'
                : 'p-2 rounded-xl text-center flex flex-col items-center gap-0.5 transition-all cursor-pointer bg-surface-container hover:bg-surface-container-high text-primary';
        }
    });
    recalculateQuote();
}

function updateWood(woodId, woodName, colorHex) {
    CUSTOMIZER_DATA.woodId = woodId;
    CUSTOMIZER_DATA.woodName = woodName;
    CUSTOMIZER_DATA.woodColor = colorHex;

    document.querySelectorAll('[id^="btnWood_"]').forEach(btn => {
        btn.className = btn.id === 'btnWood_' + woodId
            ? 'flex items-center justify-between p-2.5 rounded-xl bg-canvas-cream text-left transition-all border ring-2 ring-secondary-container border-transparent bg-surface-container'
            : 'flex items-center justify-between p-2.5 rounded-xl bg-canvas-cream text-left transition-all border border-border-subtle hover:bg-surface-container';
    });
    recalculateQuote();
}

function updateBase(baseType) {
    CUSTOMIZER_DATA.baseType = baseType;
    recalculateQuote();
}

function updateFinish(finishType) {
    CUSTOMIZER_DATA.finishType = finishType;
    recalculateQuote();
}

function updateLed(checked) {
    CUSTOMIZER_DATA.hasLed = checked;
    recalculateQuote();
}

function updateGrommet(checked) {
    CUSTOMIZER_DATA.hasGrommet = checked;
    recalculateQuote();
}

function addToCart() {
    const total = document.getElementById('quoteTotal').innerText;
    fetch('/api/cart/add', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            modelName: `${CUSTOMIZER_DATA.modelName} (${CUSTOMIZER_DATA.width} × ${CUSTOMIZER_DATA.height} × ${CUSTOMIZER_DATA.depth} cm)`,
            image: CUSTOMIZER_DATA.image,
            width: CUSTOMIZER_DATA.width,
            height: CUSTOMIZER_DATA.height,
            depth: CUSTOMIZER_DATA.depth,
            woodName: CUSTOMIZER_DATA.woodName,
            finishName: CUSTOMIZER_DATA.finishType === 'rubio' ? 'Aceite Rubio Monocoat® (0 VOCs)' : 'Barniz Poliuretano Acrílico',
            baseName: CUSTOMIZER_DATA.baseType === 'conicas' ? 'Patas Cónicas 15cm' : (CUSTOMIZER_DATA.baseType === 'zocalo' ? 'Zócalo Continuo' : 'Suspensión Flotante'),
            price: Number(total) || CUSTOMIZER_DATA.basePrice
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            window.location.href = '/checkout';
        }
    })
    .catch(() => {
        window.location.href = '/checkout';
    });
}
