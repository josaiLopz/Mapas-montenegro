<?php
/**
 * @var \App\View\AppView $this
 * @var array $estados
 * @var int|null $estadoSeleccionado
 */
?>

<section class="geo-page">

    <header class="geo-header">
        <div class="geo-header-text">
            <h1>Geocodificación automática</h1>
            <p>Busca y actualiza coordenadas usando Google Maps Geocoding API con validación por estado.</p>
        </div>
        <?= $this->Html->link('← Volver', ['action' => 'index'], ['class' => 'btn btn-ghost']) ?>
    </header>

    <div class="geo-config-card">
        <div class="geo-config-grid">

            <div class="geo-field">
                <label for="sel-estado">Estado</label>
                <select id="sel-estado">
                    <option value="">— Selecciona un estado —</option>
                    <?php foreach ($estados as $id => $nombre): ?>
                        <option value="<?= (int)$id ?>"
                                data-nombre="<?= h($nombre) ?>"
                                <?= $estadoSeleccionado == $id ? 'selected' : '' ?>>
                            <?= h($nombre) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="geo-field">
                <label for="api-key">Google Maps API Key</label>
                <div class="api-key-wrap">
                    <input type="password" id="api-key" placeholder="AIza..." autocomplete="off" />
                    <button type="button" id="toggle-key" title="Mostrar/ocultar">
                        <svg viewBox="0 0 24 24"><path d="M12 5C6.5 5 2.1 8.1 1 12c1.1 3.9 5.5 7 11 7s9.9-3.1 11-7c-1.1-3.9-5.5-7-11-7Zm0 11a4 4 0 1 1 0-8 4 4 0 0 1 0 8Z"/></svg>
                    </button>
                </div>
            </div>

            <div class="geo-field">
                <label for="delay-ms">Pausa entre requests (ms)</label>
                <input type="number" id="delay-ms" value="300" min="100" max="2000" step="100" />
            </div>

        </div>

        <div class="geo-info-box" id="geo-info-box" style="display:none">
            <svg viewBox="0 0 24 24"><path d="M12 2a10 10 0 1 0 0 20A10 10 0 0 0 12 2Zm1 15h-2v-6h2v6Zm0-8h-2V7h2v2Z"/></svg>
            <span id="geo-info-text"></span>
        </div>

        <div class="geo-actions-row">
            <button id="btn-cargar" class="btn btn-secondary" disabled>
                <svg viewBox="0 0 24 24"><path d="M17.65 6.35A7.958 7.958 0 0 0 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08A5.99 5.99 0 0 1 12 18c-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35Z"/></svg>
                Cargar escuelas
            </button>
            <button id="btn-iniciar" class="btn btn-primary" disabled>
                <svg viewBox="0 0 24 24"><path d="M12 2a10 10 0 1 0 0 20A10 10 0 0 0 12 2Zm-2 14.5v-9l6 4.5-6 4.5Z"/></svg>
                Iniciar geocodificación
            </button>
            <button id="btn-pausar" class="btn btn-warning" disabled>
                <svg viewBox="0 0 24 24"><path d="M6 19h4V5H6v14Zm8-14v14h4V5h-4Z"/></svg>
                Pausar
            </button>
        </div>
    </div>

    <div class="geo-progress-card" id="progress-card" style="display:none">
        <div class="progress-header">
            <span id="progress-label">Iniciando…</span>
            <span id="progress-pct">0%</span>
        </div>
        <div class="progress-track">
            <div class="progress-fill" id="progress-fill"></div>
        </div>
        <div class="progress-stats">
            <span class="stat stat-ok"      id="stat-ok">✔ 0 actualizadas</span>
            <span class="stat stat-warn"    id="stat-warn">⚠ 0 fuera de estado</span>
            <span class="stat stat-skip"    id="stat-skip">— 0 sin resultado</span>
            <span class="stat stat-err"     id="stat-err">✖ 0 errores</span>
            <span class="stat stat-total"   id="stat-total">de 0 escuelas</span>
        </div>
    </div>

    <div class="geo-table-card" id="table-card" style="display:none">
        <div class="table-toolbar">
            <h2 id="table-title">Escuelas</h2>
            <div class="table-filters">
                <button class="filter-btn active" data-filter="all">Todas</button>
                <button class="filter-btn" data-filter="ok">Actualizadas</button>
                <button class="filter-btn" data-filter="warn">Fuera de estado</button>
                <button class="filter-btn" data-filter="pending">Pendientes</button>
                <button class="filter-btn" data-filter="skip">Sin resultado</button>
                <button class="filter-btn" data-filter="error">Error</button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="geo-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>CCT</th>
                        <th>Tipo</th>
                        <th>Municipio</th>
                        <th>Lat anterior</th>
                        <th>Lng anterior</th>
                        <th>Lat nueva</th>
                        <th>Lng nueva</th>
                        <th>Query usada</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody id="geo-tbody"></tbody>
            </table>
        </div>
    </div>

</section>

<style>
:root {
    --brand:      #8c1d2f;
    --brand-dark: #6e1524;
    --warn:       #b45309;
    --warn-bg:    #fef3c7;
    --ok:         #166534;
    --ok-bg:      #dcfce7;
    --skip:       #6b7280;
    --skip-bg:    #f3f4f6;
    --err:        #991b1b;
    --err-bg:     #fee2e2;
    --info:       #1e40af;
    --info-bg:    #dbeafe;
    --surface:    #ffffff;
    --border:     #e9dfd2;
    --text:       #2f251a;
    --muted:      #6d655a;
    --radius:     12px;
}

.geo-page { display: grid; gap: 16px; max-width: 1400px; margin: 0 auto; }
.geo-header { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; flex-wrap: wrap; }
.geo-header h1 { margin: 0; color: var(--text); font-size: 1.6rem; }
.geo-header p  { margin: 4px 0 0; color: var(--muted); }

.geo-config-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 20px; display: grid; gap: 16px; }
.geo-config-grid { display: grid; grid-template-columns: 1fr 1fr auto; gap: 14px; align-items: end; }
.geo-field { display: grid; gap: 6px; }
.geo-field label { font-size: .85rem; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: .04em; }
.geo-field select,
.geo-field input[type="number"] { border: 1px solid var(--border); border-radius: 8px; padding: 9px 12px; font-size: .95rem; color: var(--text); background: #fffaf5; width: 100%; box-sizing: border-box; }
.api-key-wrap { display: flex; gap: 6px; }
.api-key-wrap input { flex: 1; border: 1px solid var(--border); border-radius: 8px; padding: 9px 12px; font-size: .95rem; color: var(--text); background: #fffaf5; min-width: 0; }
.api-key-wrap button { flex-shrink: 0; width: 38px; height: 38px; border: 1px solid var(--border); border-radius: 8px; background: #fffaf5; cursor: pointer; display: flex; align-items: center; justify-content: center; }
.api-key-wrap button svg { width: 18px; height: 18px; fill: var(--muted); }
.geo-actions-row { display: flex; gap: 10px; flex-wrap: wrap; }

.geo-info-box { display: flex; align-items: center; gap: 8px; background: var(--info-bg); color: var(--info); border: 1px solid #93c5fd; border-radius: 8px; padding: 10px 14px; font-size: .88rem; font-weight: 500; }
.geo-info-box svg { width: 18px; height: 18px; fill: currentColor; flex-shrink: 0; }

.btn { display: inline-flex; align-items: center; gap: 7px; padding: 9px 16px; border-radius: 8px; border: 1px solid transparent; font-weight: 600; font-size: .9rem; cursor: pointer; text-decoration: none; transition: background .15s, opacity .15s; line-height: 1.2; }
.btn svg { width: 16px; height: 16px; fill: currentColor; flex-shrink: 0; }
.btn:disabled { opacity: .45; cursor: not-allowed; }
.btn-primary   { background: var(--brand);   color: #fff; border-color: var(--brand); }
.btn-primary:hover:not(:disabled)   { background: var(--brand-dark); }
.btn-secondary { background: #fff8ee; color: #4b3e31; border-color: var(--border); }
.btn-secondary:hover:not(:disabled) { background: #f9ebda; }
.btn-warning   { background: var(--warn-bg); color: var(--warn); border-color: #fcd34d; }
.btn-warning:hover:not(:disabled)   { background: #fde68a; }
.btn-ghost     { background: transparent; color: var(--muted); border-color: var(--border); }
.btn-ghost:hover { background: #f5f0eb; color: var(--text); }

.geo-progress-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 20px; display: grid; gap: 10px; }
.progress-header { display: flex; justify-content: space-between; font-weight: 600; color: var(--text); }
.progress-track  { height: 12px; background: #f2ebe1; border-radius: 999px; overflow: hidden; }
.progress-fill   { height: 100%; width: 0%; background: linear-gradient(90deg, var(--brand), #c0394f); border-radius: 999px; transition: width .3s ease; }
.progress-stats  { display: flex; gap: 12px; flex-wrap: wrap; font-size: .85rem; font-weight: 600; }
.stat { padding: 3px 10px; border-radius: 999px; }
.stat-ok    { background: var(--ok-bg);   color: var(--ok); }
.stat-warn  { background: var(--warn-bg); color: var(--warn); }
.stat-skip  { background: var(--skip-bg); color: var(--skip); }
.stat-err   { background: var(--err-bg);  color: var(--err); }
.stat-total { color: var(--muted); }

.geo-table-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 16px; overflow: hidden; }
.table-toolbar { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 12px; }
.table-toolbar h2 { margin: 0; font-size: 1.1rem; color: var(--text); }
.table-filters { display: flex; gap: 6px; flex-wrap: wrap; }
.filter-btn { padding: 4px 12px; border-radius: 999px; border: 1px solid var(--border); background: #fff8ee; color: var(--muted); font-size: .8rem; font-weight: 600; cursor: pointer; }
.filter-btn.active { background: var(--brand); color: #fff; border-color: var(--brand); }

.table-responsive { overflow-x: auto; }
.geo-table { width: 100%; border-collapse: collapse; font-size: .88rem; min-width: 1100px; }
.geo-table th { background: #fffaf3; color: var(--muted); font-weight: 700; font-size: .75rem; text-transform: uppercase; letter-spacing: .04em; padding: 8px 10px; border-bottom: 2px solid var(--border); text-align: left; white-space: nowrap; }
.geo-table td { padding: 8px 10px; border-bottom: 1px solid #f0e8de; vertical-align: middle; color: var(--text); white-space: nowrap; }

.geo-table tr[data-status="ok"]      { background: #f0fdf4; }
.geo-table tr[data-status="warn"]    { background: #fffbeb; }
.geo-table tr[data-status="error"]   { background: #fff1f1; }
.geo-table tr[data-status="skip"]    { background: #f9f9f9; }
.geo-table tr[data-status="running"] { background: #eff6ff; }
.geo-table tr.hidden { display: none; }

.badge { display: inline-flex; align-items: center; gap: 4px; padding: 2px 9px; border-radius: 999px; font-size: .78rem; font-weight: 700; }
.badge-pending { background: #e5e7eb; color: #6b7280; }
.badge-running { background: var(--info-bg); color: var(--info); }
.badge-ok      { background: var(--ok-bg);   color: var(--ok); }
.badge-warn    { background: var(--warn-bg);  color: var(--warn); }
.badge-skip    { background: var(--skip-bg);  color: var(--skip); }
.badge-error   { background: var(--err-bg);   color: var(--err); }

.coord-old   { color: #aaa; font-size: .82rem; }
.coord-new   { color: var(--ok); font-weight: 600; }
.coord-warn  { color: var(--warn); font-weight: 600; }
.coord-empty { color: #d1d5db; }
.query-cell  { max-width: 220px; overflow: hidden; text-overflow: ellipsis; color: var(--muted); font-size: .78rem; }

@keyframes spin { to { transform: rotate(360deg); } }
.spinner { display: inline-block; width: 11px; height: 11px; border: 2px solid currentColor; border-top-color: transparent; border-radius: 50%; animation: spin .6s linear infinite; vertical-align: middle; }

@media (max-width: 860px) { .geo-config-grid { grid-template-columns: 1fr 1fr; } }
@media (max-width: 580px) { .geo-config-grid { grid-template-columns: 1fr; } .geo-actions-row .btn { width: 100%; justify-content: center; } }
</style>

<script>
(function () {

    /* ══════════════════════════════════════════════════════════════
       BOUNDING BOXES de los 32 estados de México
       Formato: { minLat, maxLat, minLng, maxLng }
       Clave = nombre del estado tal como viene en tu BD (normalizado)
    ══════════════════════════════════════════════════════════════ */
    const ESTADO_BBOX = {
        // id_cct_prefix → bbox  (los 2 primeros dígitos del CCT)
        '01': { minLat: 21.50, maxLat: 22.52, minLng: -103.00, maxLng: -101.74, nombre: 'Aguascalientes' },
        '02': { minLat: 27.80, maxLat: 32.72, minLng: -118.41, maxLng: -113.73, nombre: 'Baja California' },
        '03': { minLat: 22.87, maxLat: 28.00, minLng: -115.08, maxLng: -109.39, nombre: 'Baja California Sur' },
        '04': { minLat: 17.82, maxLat: 20.84, minLng: -92.51, maxLng:  -90.36, nombre: 'Campeche' },
        '05': { minLat: 24.53, maxLat: 29.87, minLng: -102.30, maxLng:  -99.83, nombre: 'Coahuila' },
        '06': { minLat: 18.68, maxLat: 19.55, minLng: -104.71, maxLng: -103.52, nombre: 'Colima' },
        '07': { minLat: 14.54, maxLat: 17.99, minLng:  -94.25, maxLng:  -90.35, nombre: 'Chiapas' },
        '08': { minLat: 25.60, maxLat: 31.79, minLng: -109.10, maxLng: -103.28, nombre: 'Chihuahua' },
        '09': { minLat: 19.05, maxLat: 19.59, minLng:  -99.36, maxLng:  -98.94, nombre: 'Ciudad de México' },
        '10': { minLat: 22.53, maxLat: 26.90, minLng: -107.19, maxLng: -103.76, nombre: 'Durango' },
        '11': { minLat: 19.92, maxLat: 21.88, minLng: -102.20, maxLng: -100.03, nombre: 'Guanajuato' },
        '12': { minLat: 16.31, maxLat: 18.90, minLng:  -102.20, maxLng:  -98.00, nombre: 'Guerrero' },
        '13': { minLat: 19.59, maxLat: 21.40, minLng:  -99.36, maxLng:  -97.99, nombre: 'Hidalgo' },
        '14': { minLat: 19.00, maxLat: 22.75, minLng: -105.74, maxLng: -101.52, nombre: 'Jalisco' },
        '15': { minLat: 18.35, maxLat: 20.30, minLng: -100.62, maxLng:  -98.00, nombre: 'México' },
        '16': { minLat: 17.91, maxLat: 20.39, minLng: -103.74, maxLng: -100.05, nombre: 'Michoacán' },
        '17': { minLat: 18.15, maxLat: 19.13, minLng:  -99.49, maxLng:  -98.64, nombre: 'Morelos' },
        '18': { minLat: 20.60, maxLat: 23.08, minLng: -105.77, maxLng: -103.73, nombre: 'Nayarit' },
        '19': { minLat: 23.16, maxLat: 27.79, minLng: -101.23, maxLng:  -98.44, nombre: 'Nuevo León' },
        '20': { minLat: 15.65, maxLat: 18.70, minLng:  -98.53, maxLng:  -93.87, nombre: 'Oaxaca' },
        '21': { minLat: 17.84, maxLat: 21.21, minLng:  -99.07, maxLng:  -96.72, nombre: 'Puebla' },
        '22': { minLat: 20.00, maxLat: 21.67, minLng: -100.55, maxLng:  -99.04, nombre: 'Querétaro' },
        '23': { minLat: 17.49, maxLat: 21.59, minLng:  -89.97, maxLng:  -86.74, nombre: 'Quintana Roo' },
        '24': { minLat: 21.18, maxLat: 24.58, minLng: -102.31, maxLng:  -98.31, nombre: 'San Luis Potosí' },
        '25': { minLat: 22.49, maxLat: 27.03, minLng: -109.45, maxLng: -105.65, nombre: 'Sinaloa' },
        '26': { minLat: 26.10, maxLat: 32.49, minLng: -115.05, maxLng: -108.45, nombre: 'Sonora' },
        '27': { minLat: 17.15, maxLat: 18.65, minLng:  -94.13, maxLng:  -90.99, nombre: 'Tabasco' },
        '28': { minLat: 22.15, maxLat: 27.68, minLng:  -99.90, maxLng:  -97.14, nombre: 'Tamaulipas' },
        '29': { minLat: 19.10, maxLat: 19.85, minLng:  -98.71, maxLng:  -97.63, nombre: 'Tlaxcala' },
        '30': { minLat: 17.14, maxLat: 22.47, minLng:  -98.67, maxLng:  -93.61, nombre: 'Veracruz' },
        '31': { minLat: 19.56, maxLat: 21.66, minLng:  -90.51, maxLng:  -87.53, nombre: 'Yucatán' },
        '32': { minLat: 21.01, maxLat: 25.11, minLng: -104.33, maxLng: -100.73, nombre: 'Zacatecas' },
    };

    /* Mapa nombre de estado → prefijo CCT para búsqueda inversa */
    const NOMBRE_A_PREFIJO = {};
    for (const [k, v] of Object.entries(ESTADO_BBOX)) {
        NOMBRE_A_PREFIJO[v.nombre.toLowerCase()] = k;
    }

    /* ── CSRF ──────────────────────────────────────────────────────── */
    function getCsrfToken() {
        const meta  = document.querySelector('meta[name="csrfToken"]');
        if (meta)  return meta.getAttribute('content');
        const input = document.querySelector('input[name="_csrfToken"]');
        if (input) return input.value;
        const match = document.cookie.match(/(?:^|;\s*)csrfToken=([^;]+)/);
        return match ? decodeURIComponent(match[1]) : '';
    }

    function fetchJson(url, body) {
        return fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type':     'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token':     getCsrfToken(),
            },
            body: JSON.stringify(body),
        });
    }

    /* ── Estado global ─────────────────────────────────────────────── */
    let schools      = [];
    let estadoBbox   = null;   // bbox del estado seleccionado
    let estadoNombre = '';     // nombre del estado seleccionado
    let paused       = false;
    let running      = false;
    let stopFlag     = false;
    let currentIndex = 0;
    let cntOk = 0, cntWarn = 0, cntSkip = 0, cntErr = 0;

    const LOAD_URL = '<?= $this->Url->build(['controller' => 'Schools', 'action' => 'geocodificarLote']) ?>';
    const SAVE_URL = '<?= $this->Url->build(['controller' => 'Schools', 'action' => 'guardarCoordenadas']) ?>';

    /* ── DOM ───────────────────────────────────────────────────────── */
    const selEstado    = document.getElementById('sel-estado');
    const inputKey     = document.getElementById('api-key');
    const inputDelay   = document.getElementById('delay-ms');
    const btnCargar    = document.getElementById('btn-cargar');
    const btnIniciar   = document.getElementById('btn-iniciar');
    const btnPausar    = document.getElementById('btn-pausar');
    const progressCard = document.getElementById('progress-card');
    const tableCard    = document.getElementById('table-card');
    const progressFill = document.getElementById('progress-fill');
    const progressLbl  = document.getElementById('progress-label');
    const progressPct  = document.getElementById('progress-pct');
    const statOk       = document.getElementById('stat-ok');
    const statWarn     = document.getElementById('stat-warn');
    const statSkip     = document.getElementById('stat-skip');
    const statErr      = document.getElementById('stat-err');
    const statTotal    = document.getElementById('stat-total');
    const tbody        = document.getElementById('geo-tbody');
    const tableTitle   = document.getElementById('table-title');
    const infoBox      = document.getElementById('geo-info-box');
    const infoText     = document.getElementById('geo-info-text');

    document.getElementById('toggle-key').addEventListener('click', () => {
        inputKey.type = inputKey.type === 'password' ? 'text' : 'password';
    });

    /* ── Cambio de estado: obtener bbox ────────────────────────────── */
    selEstado.addEventListener('change', () => {
        const opt = selEstado.options[selEstado.selectedIndex];
        estadoNombre = opt ? (opt.dataset.nombre || '') : '';

        // Buscar bbox por nombre
        estadoBbox = null;
        const nombreNorm = estadoNombre.toLowerCase()
            .replace('é','e').replace('á','a').replace('í','i').replace('ó','o').replace('ú','u');

        for (const [prefijo, bbox] of Object.entries(ESTADO_BBOX)) {
            const bboxNorm = bbox.nombre.toLowerCase()
                .replace('é','e').replace('á','a').replace('í','i').replace('ó','o').replace('ú','u');
            if (nombreNorm.includes(bboxNorm) || bboxNorm.includes(nombreNorm)) {
                estadoBbox = { ...bbox, prefijo };
                break;
            }
        }

        if (estadoBbox) {
            infoText.textContent = `Validación activa: coordenadas deben caer dentro de ${estadoBbox.nombre} (prefijo CCT: ${estadoBbox.prefijo})`;
            infoBox.style.display = '';
        } else {
            infoText.textContent = `No se encontró bbox para "${estadoNombre}" — se guardará sin validar posición.`;
            infoBox.style.display = '';
        }

        btnCargar.disabled         = !selEstado.value;
        btnIniciar.disabled        = true;
        tbody.innerHTML            = '';
        tableCard.style.display    = 'none';
        progressCard.style.display = 'none';
        schools = [];
        cntOk = cntWarn = cntSkip = cntErr = 0;
    });

    /* ── Cargar escuelas ───────────────────────────────────────────── */
    btnCargar.addEventListener('click', async () => {
        const estadoId = selEstado.value;
        if (!estadoId) return;

        btnCargar.disabled  = true;
        btnIniciar.disabled = true;
        btnCargar.innerHTML = '<span class="spinner"></span> Cargando…';

        try {
            const res = await fetchJson(LOAD_URL, { estado_id: parseInt(estadoId) });
            const ct  = res.headers.get('content-type') || '';
            if (!ct.includes('application/json')) {
                const txt = await res.text();
                console.error('Respuesta no-JSON:', txt.substring(0, 500));
                throw new Error(`El servidor devolvió HTML (${res.status}). Verifica que geocodificarLote esté en unlockedActions.`);
            }
            const data = await res.json();
            if (!data.ok) throw new Error(data.message || 'Error al cargar');

            schools = data.schools;
            renderTabla();
            tableCard.style.display = '';
            btnIniciar.disabled     = false;
            tableTitle.textContent  = `${schools.length} escuelas — ${estadoNombre}`;

        } catch (e) {
            alert('Error al cargar escuelas: ' + e.message);
        } finally {
            btnCargar.disabled  = false;
            btnCargar.innerHTML = '<svg viewBox="0 0 24 24"><path d="M17.65 6.35A7.958 7.958 0 0 0 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08A5.99 5.99 0 0 1 12 18c-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35Z"/></svg> Cargar escuelas';
        }
    });

    /* ── Render tabla ──────────────────────────────────────────────── */
    function renderTabla() {
        cntOk = cntWarn = cntSkip = cntErr = 0;
        tbody.innerHTML = '';
        schools.forEach((s, i) => {
            const tr = document.createElement('tr');
            tr.id = `row-${s.id}`;
            tr.dataset.status = 'pending';
            tr.innerHTML = `
                <td>${i + 1}</td>
                <td title="${esc(s.nombre)}">${esc(truncate(s.nombre, 32))}</td>
                <td><code>${esc(s.cct)}</code></td>
                <td>${esc(s.tipo || '—')}</td>
                <td>${esc(s.municipio || '—')}</td>
                <td class="coord-old">${s.lat ? s.lat : '<span class="coord-empty">—</span>'}</td>
                <td class="coord-old">${s.lng ? s.lng : '<span class="coord-empty">—</span>'}</td>
                <td id="nlat-${s.id}" class="coord-empty">—</td>
                <td id="nlng-${s.id}" class="coord-empty">—</td>
                <td id="query-${s.id}" class="query-cell" title="">—</td>
                <td id="badge-${s.id}"><span class="badge badge-pending">Pendiente</span></td>
            `;
            tbody.appendChild(tr);
        });
        actualizarStats(0);
    }

    /* ── Iniciar ───────────────────────────────────────────────────── */
    btnIniciar.addEventListener('click', () => {
        const apiKey = inputKey.value.trim();
        if (!apiKey) { alert('Ingresa tu Google Maps API Key'); inputKey.focus(); return; }
        if (running) return;

        progressCard.style.display = '';
        btnIniciar.disabled = true;
        btnPausar.disabled  = false;
        running      = true;
        paused       = false;
        stopFlag     = false;
        currentIndex = 0;
        cntOk = cntWarn = cntSkip = cntErr = 0;

        schools.forEach(s => {
            setBadge(s.id, 'pending', 'Pendiente');
            setRow(s.id, 'pending');
            const nlat = document.getElementById(`nlat-${s.id}`);
            const nlng = document.getElementById(`nlng-${s.id}`);
            const qcell = document.getElementById(`query-${s.id}`);
            if (nlat)  { nlat.textContent = '—';  nlat.className = 'coord-empty'; }
            if (nlng)  { nlng.textContent = '—';  nlng.className = 'coord-empty'; }
            if (qcell) { qcell.textContent = '—'; qcell.title = ''; }
        });

        procesarContinuo(apiKey);
    });

    /* ── Pausar / reanudar ─────────────────────────────────────────── */
    btnPausar.addEventListener('click', () => {
        if (!running) return;
        paused = !paused;
        btnPausar.innerHTML = paused
            ? '<svg viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg> Reanudar'
            : '<svg viewBox="0 0 24 24"><path d="M6 19h4V5H6v14Zm8-14v14h4V5h-4Z"/></svg> Pausar';
        if (!paused) procesarContinuo();
    });

    /* ══════════════════════════════════════════════════════════════
       CONSTRUCCIÓN DE QUERY MEJORADA
       Estrategia multicapa: intenta del más específico al más genérico
    ══════════════════════════════════════════════════════════════ */
    function buildQueries(school, estadoNombreLocal) {
        const nombre    = school.nombre || '';
        const cct       = school.cct    || '';
        const tipo      = school.tipo   || '';
        const municipio = school.municipio || '';

        // Nivel educativo como texto natural
        const nivel = tipo.toLowerCase().includes('preescolar') ? 'preescolar'
                    : tipo.toLowerCase().includes('primaria')   ? 'primaria'
                    : tipo.toLowerCase().includes('secundaria') ? 'secundaria'
                    : 'escuela';

        // El CCT codifica el estado en los 2 primeros dígitos
        // y el tipo de sostenimiento: P=pública, E=estatal, F=federal, etc.
        const queries = [];

        // ① Más específica: CCT exacto + nombre + municipio + estado
        if (cct && municipio && estadoNombreLocal) {
            queries.push(`${nivel} "${nombre}" ${cct} ${municipio} ${estadoNombreLocal} Mexico`);
        }

        // ② CCT + nombre + estado (sin municipio)
        if (cct && estadoNombreLocal) {
            queries.push(`${nivel} "${nombre}" ${cct} ${estadoNombreLocal} Mexico`);
        }

        // ③ Nombre + municipio + estado (sin CCT, por si el CCT no está indexado)
        if (municipio && estadoNombreLocal) {
            queries.push(`${nivel} "${nombre}" ${municipio} ${estadoNombreLocal} Mexico`);
        }

        // ④ Fallback: nombre + estado
        if (estadoNombreLocal) {
            queries.push(`${nivel} ${nombre} ${estadoNombreLocal} Mexico`);
        }

        // ⑤ Último recurso: solo nombre + Mexico
        queries.push(`${nivel} ${nombre} Mexico`);

        return queries;
    }

    /* ══════════════════════════════════════════════════════════════
       VALIDACIÓN DE BBOX
       Retorna true si lat/lng cae dentro del bounding box del estado
    ══════════════════════════════════════════════════════════════ */
    function dentroDelEstado(lat, lng, bbox) {
        if (!bbox) return true; // sin bbox, aceptar siempre
        return lat >= bbox.minLat && lat <= bbox.maxLat &&
               lng >= bbox.minLng && lng <= bbox.maxLng;
    }

    /* ══════════════════════════════════════════════════════════════
       VALIDACIÓN DE CCT
       Los 2 primeros dígitos del CCT deben coincidir con el prefijo del estado
    ══════════════════════════════════════════════════════════════ */
    function cctCoincidesConEstado(cct, bbox) {
        if (!bbox || !cct || cct.length < 2) return true;
        const prefijoCct = cct.substring(0, 2);
        return prefijoCct === bbox.prefijo;
    }

    /* ── Loop principal ────────────────────────────────────────────── */
    async function procesarContinuo(apiKey) {
        const key   = apiKey || inputKey.value.trim();
        const delay = parseInt(inputDelay.value) || 300;
        const total = schools.length;

        while (currentIndex < total) {
            if (stopFlag) break;
            if (paused)   return;

            const school = schools[currentIndex];
            setBadge(school.id, 'running', '<span class="spinner"></span> Buscando…');
            setRow(school.id, 'running');
            scrollToRow(school.id);

            const queries      = buildQueries(school, estadoNombre);
            const qcell        = document.getElementById(`query-${school.id}`);
            let lat = null, lng = null, status = 'skip';
            let queryUsada = '';
            let fueraDeEstado = false;

            /* Intentar cada query hasta encontrar resultado válido */
            for (const query of queries) {
                if (stopFlag) break;

                try {
                    const geoUrl = `https://maps.googleapis.com/maps/api/geocode/json?address=${encodeURIComponent(query)}&key=${encodeURIComponent(key)}&language=es&region=mx`;
                    const res    = await fetch(geoUrl);
                    const data   = await res.json();

                    if (data.status === 'REQUEST_DENIED') {
                        throw new Error('API Key inválida o sin permisos Geocoding. ' + (data.error_message || ''));
                    }
                    if (data.status === 'OVER_DAILY_LIMIT' || data.status === 'OVER_QUERY_LIMIT') {
                        throw new Error('Límite de consultas alcanzado.');
                    }

                    if (data.status === 'OK' && data.results.length > 0) {
                        const loc  = data.results[0].geometry.location;
                        const tLat = loc.lat;
                        const tLng = loc.lng;

                        if (dentroDelEstado(tLat, tLng, estadoBbox)) {
                            // ✔ Coordenada dentro del estado → aceptar
                            lat       = tLat;
                            lng       = tLng;
                            status    = 'ok';
                            queryUsada = query;
                            break; // salir del loop de queries
                        } else {
                            // Coordenada fuera del estado → guardar como candidato
                            // pero seguir intentando con la siguiente query
                            if (lat === null) {
                                lat       = tLat;
                                lng       = tLng;
                                queryUsada = query;
                                fueraDeEstado = true;
                            }
                        }
                    }
                    // ZERO_RESULTS → intentar siguiente query
                } catch (e) {
                    if (e.message.includes('API Key') || e.message.includes('Límite')) {
                        alert('⚠ ' + e.message);
                        stopFlag = true;
                        running  = false;
                        btnIniciar.disabled = false;
                        btnPausar.disabled  = true;
                        setBadge(school.id, 'error', '✖ Detenido');
                        setRow(school.id, 'error');
                        actualizarStats(currentIndex);
                        return;
                    }
                    status = 'error';
                    break;
                }

                await sleep(80); // pequeña pausa entre reintentos
            }

            /* Determinar status final */
            if (status !== 'error') {
                if (lat !== null && !fueraDeEstado) {
                    status = 'ok';
                } else if (lat !== null && fueraDeEstado) {
                    status = 'warn'; // encontró algo pero fuera del estado
                } else {
                    status = 'skip';
                    lat = null; lng = null;
                }
            }

            /* Mostrar query usada */
            if (qcell) {
                qcell.textContent = queryUsada ? truncate(queryUsada, 40) : '—';
                qcell.title       = queryUsada || '';
            }

            /* Guardar en servidor solo si ok o warn */
            if ((status === 'ok' || status === 'warn') && lat !== null) {
                try {
                    const saveRes  = await fetchJson(SAVE_URL, { id: school.id, lat, lng });
                    const saveData = await saveRes.json();
                    if (!saveData.ok) { status = 'error'; lat = null; lng = null; }
                } catch {
                    status = 'error'; lat = null; lng = null;
                }
            }

            /* Actualizar UI */
            const nlat = document.getElementById(`nlat-${school.id}`);
            const nlng = document.getElementById(`nlng-${school.id}`);

            if (status === 'ok') {
                if (nlat) { nlat.textContent = lat.toFixed(7); nlat.className = 'coord-new'; }
                if (nlng) { nlng.textContent = lng.toFixed(7); nlng.className = 'coord-new'; }
                setBadge(school.id, 'ok', '✔ Actualizada');
                cntOk++;
            } else if (status === 'warn') {
                if (nlat) { nlat.textContent = lat.toFixed(7); nlat.className = 'coord-warn'; }
                if (nlng) { nlng.textContent = lng.toFixed(7); nlng.className = 'coord-warn'; }
                setBadge(school.id, 'warn', '⚠ Fuera de estado');
                cntWarn++;
            } else if (status === 'skip') {
                setBadge(school.id, 'skip', '— Sin resultado');
                cntSkip++;
            } else {
                setBadge(school.id, 'error', '✖ Error');
                cntErr++;
            }

            setRow(school.id, status);
            currentIndex++;
            actualizarStats(currentIndex);
            await sleep(delay);
        }

        if (!stopFlag && currentIndex >= total) {
            progressLbl.textContent  = `✔ Completado — ${total} escuelas procesadas`;
            progressFill.style.width = '100%';
            progressPct.textContent  = '100%';
            running = false;
            btnIniciar.disabled = false;
            btnPausar.disabled  = true;
            btnPausar.innerHTML = '<svg viewBox="0 0 24 24"><path d="M6 19h4V5H6v14Zm8-14v14h4V5h-4Z"/></svg> Pausar';
        }
    }

    /* ── Utils ─────────────────────────────────────────────────────── */
    function actualizarStats(procesadas) {
        const total = schools.length;
        const pct   = total > 0 ? Math.round((procesadas / total) * 100) : 0;
        progressFill.style.width = pct + '%';
        progressPct.textContent  = pct + '%';
        if (procesadas < total) progressLbl.textContent = `Procesando ${procesadas} de ${total}…`;
        statOk.textContent    = `✔ ${cntOk} actualizadas`;
        statWarn.textContent  = `⚠ ${cntWarn} fuera de estado`;
        statSkip.textContent  = `— ${cntSkip} sin resultado`;
        statErr.textContent   = `✖ ${cntErr} errores`;
        statTotal.textContent = `de ${total} escuelas`;
    }

    function setBadge(id, type, html) {
        const el = document.getElementById(`badge-${id}`);
        if (el) el.innerHTML = `<span class="badge badge-${type}">${html}</span>`;
    }

    function setRow(id, status) {
        const tr = document.getElementById(`row-${id}`);
        if (tr) tr.dataset.status = status;
    }

    function scrollToRow(id) {
        const tr = document.getElementById(`row-${id}`);
        if (tr) tr.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function sleep(ms) { return new Promise(r => setTimeout(r, ms)); }

    function esc(s) {
        return (s ?? '').toString()
            .replace(/&/g, '&amp;').replace(/</g, '&lt;')
            .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function truncate(s, n) { return s && s.length > n ? s.slice(0, n) + '…' : (s || ''); }

    /* ── Filtros ───────────────────────────────────────────────────── */
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const f = btn.dataset.filter;
            document.querySelectorAll('#geo-tbody tr').forEach(tr => {
                if (f === 'all') {
                    tr.classList.remove('hidden');
                } else if (f === 'pending') {
                    tr.classList.toggle('hidden',
                        tr.dataset.status !== 'pending' && tr.dataset.status !== 'running');
                } else {
                    tr.classList.toggle('hidden', tr.dataset.status !== f);
                }
            });
        });
    });

})();
</script>