<?php
// templates/Schools/filtros.php
?>

<?= $this->Form->create(null, ['id' => 'filtros-form']) ?>
<?= $this->Form->hidden('mode', ['value' => $mode ?? 'admin']) ?>
<?= $this->Html->css('filtros') ?>
<style>
/* ══════════════════════════════════════════════
   KPI SIDEBAR
══════════════════════════════════════════════ */
#kpi-sidebar {
  padding-bottom: 12px;
  margin-bottom: 12px;
  border-bottom: 1px solid #eef0f3; 
}
.kpi-section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
}
.kpi-section-title {
  margin: 0;
  font-size: 10px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .8px;
  color: #9ca3af;
}
.kpi-collapse-btn {
  background: none;
  border: none;
  cursor: pointer;
  font-size: 11px;
  color: #adb5bd;
  padding: 0 2px;
  line-height: 1;
  transition: color .2s;
}
.kpi-collapse-btn:hover { color: #495057; }

/* Grid de tarjetas */
.kpi-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 6px;
  margin-bottom: 7px;
}
.kpi-tile {
  background: #f8f9fa;
  border: 1px solid #eef0f3;
  border-radius: 8px;
  padding: 8px 10px;
  position: relative;
}
.kpi-tile.full { grid-column: 1 / -1; }
.kpi-tile-label {
  font-size: 10px;
  color: #9ca3af;
  text-transform: uppercase;
  letter-spacing: .4px;
  margin-bottom: 2px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.kpi-tile-val {
  font-size: 19px;
  font-weight: 700;
  color: #1a202c;
  line-height: 1.1;
  font-variant-numeric: tabular-nums;
}
.kpi-tile-sub {
  font-size: 10px;
  color: #6c757d;
  margin-top: 2px;
  line-height: 1.4;
}

/* Botón editar meta inline */
.meta-edit-btn {
  background: none;
  border: none;
  cursor: pointer;
  color: #8b1d2c;
  font-size: 11px;
  padding: 0;
  opacity: .7;
  transition: opacity .2s;
}
.meta-edit-btn:hover { opacity: 1; }
.meta-inline-form {
  display: none;
  gap: 4px;
  align-items: center;
  margin-top: 4px;
}
.meta-inline-form.open { display: flex; }
.meta-inline-input {
  flex: 1;
  font-size: 13px;
  font-weight: 700;
  border: 1.5px solid #8b1d2c;
  border-radius: 6px;
  padding: 3px 7px;
  outline: none;
  color: #1a202c;
  background: #fff;
  font-variant-numeric: tabular-nums;
  min-width: 0;
}
.meta-inline-save {
  background: #8b1d2c;
  color: #fff;
  border: none;
  border-radius: 6px;
  padding: 3px 8px;
  font-size: 11px;
  cursor: pointer;
  white-space: nowrap;
}
.meta-inline-cancel {
  background: #e9ecef;
  color: #495057;
  border: none;
  border-radius: 6px;
  padding: 3px 7px;
  font-size: 11px;
  cursor: pointer;
}

/* Barra de cobertura */
.kpi-progress-track {
  height: 7px;
  background: #e9ecef;
  border-radius: 999px;
  margin-top: 6px;
  overflow: hidden;
}
.kpi-progress-fill {
  height: 100%;
  border-radius: 999px;
  background: #198754;
  width: 0%;
  transition: width .6s cubic-bezier(.4,0,.2,1), background .3s;
}

/* ── Semáforos ── */
.kpi-sem-block { margin-bottom: 7px; }
.kpi-sem-title {
  font-size: 10px;
  color: #9ca3af;
  text-transform: uppercase;
  letter-spacing: .4px;
  margin-bottom: 5px;
}
.sem-row {
  display: flex;
  align-items: center;
}
.sem-step {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 3px;
}
.sem-line {
  flex: 1;
  height: 2px;
  background: #dee2e6;
  margin-bottom: 18px;
}
.sem-dot {
  width: 16px;
  height: 16px;
  border-radius: 50%;
  background: #dee2e6;
  border: 2px solid #ced4da;
  display: block;
  transition: background .35s, border-color .35s, box-shadow .35s;
}
.sem-dot.verde {
  background: #198754;
  border-color: #146c43;
  box-shadow: 0 0 0 3px rgba(25,135,84,.15);
}
.sem-dot.amarillo {
  background: #ffc107;
  border-color: #d4a000;
  box-shadow: 0 0 0 3px rgba(255,193,7,.18);
}
.sem-dot.rojo {
  background: #dc3545;
  border-color: #b02a37;
  box-shadow: 0 0 0 3px rgba(220,53,69,.15);
}
.sem-lbl {
  font-size: 9.5px;
  color: #6c757d;
  text-align: center;
  line-height: 1.3;
}
.sem-lbl b { color: #495057; }

/* loading skeleton */
.kpi-skeleton {
  display: inline-block;
  height: 1em;
  width: 60px;
  background: linear-gradient(90deg, #e9ecef 25%, #f8f9fa 50%, #e9ecef 75%);
  background-size: 200% 100%;
  animation: shimmer 1.4s infinite;
  border-radius: 4px;
  vertical-align: middle;
}
@keyframes shimmer {
  0%   { background-position: 200% 0; }
  100% { background-position: -200% 0; }
}
</style>

<div class="wrap">
  <button type="button" id="toggle-filters" class="corner-btn" title="Colapsar filtros"></button>
  <div class="topbar">
    <div class="tabs">
      <div class="tab active" data-tab="ubicacion">Ubicación</div>
      <div class="tab" data-tab="escuelas">Escuelas</div>
      <div class="tab" data-tab="comercial">Comercial</div>
    </div>

    <div class="actions">
      <button type="button" id="btn-limpiar" class="btn btn-light">Limpiar</button>
      <button type="button" id="btn-buscar" class="btn btn-danger">Buscar</button>
      <?= $this->Html->link('Nueva Escuela', ['action'=>'add'], ['class'=>'btn btn-success']) ?>
    </div>
  </div>

  <div id="filters-body">
    <!-- UBICACIÓN -->
    <div class="panel" id="panel-ubicacion">
      <div class="grid">
        <div class="col-4">
          <?= $this->Form->control('estado_id', [
            'label'=>'Estado',
            'options'=>$estados,
            'id'=>'estado',
            'empty'=>'Cualquiera'
          ]) ?>
        </div>

        <div class="col-4">
          <?= $this->Form->control('municipio_id', [
            'label'=>'Municipio',
            'options'=>[],
            'id'=>'municipio',
            'empty'=>'Cualquiera'
          ]) ?>
        </div>

<?php if (!empty($restrictedUser)): ?>
    <div class="col-4">
      <?= $this->Form->control('user_id', [
        'label' => 'Distribuidor',
        'options' => $users,
        'id' => 'user_id',
        'value' => $currentUserId,
        'disabled' => true
      ]) ?>
      <?= $this->Form->hidden('user_id', ['value' => $currentUserId]) ?>
    </div>
<?php else: ?>
    <div class="col-4">
      <?= $this->Form->control('user_id', [
        'label'=>'Distribuidor',
        'options'=>$users,
        'id'=>'user_id',
        'empty'=>'Cualquiera'
      ]) ?>
    </div>
<?php endif; ?>

        <div class="col-12" style="display:flex; align-items:center; gap:8px; margin-top:4px;">
          <?= $this->Form->control('territorios', [
            'label'=>'Territorios',
            'type'=>'checkbox',
            'id'=>'territorios',
            'templates' => ['inputContainer' => '{{content}}']
          ]) ?>
        </div>
      </div>
    </div>

    <!-- ESCUELAS -->
    <div class="panel hidden" id="panel-escuelas">
      <div class="grid">
        <div class="col-3">
          <?= $this->Form->control('tipo', [
            'label'=>'Tipo',
            'options'=>$tipos,
            'id'=>'tipo'
          ]) ?>
        </div>

        <div class="col-3">
          <?= $this->Form->control('sector', [
            'label'=>'Sector',
            'options'=>$sectores,
            'id'=>'sector'
          ]) ?>
        </div>

        <div class="col-3">
          <?= $this->Form->control('turno', [
            'label'=>'Turno',
            'options'=>$turnos,
            'id'=>'turno'
          ]) ?>
        </div>

        <div class="col-3">
          <?= $this->Form->control('alumnos_rango', [
            'label'=>'# De Alumnos',
            'type'=>'text',
            'id'=>'alumnos_rango',
            'placeholder'=>'0, 500, -1000, +2000'
          ]) ?>
        </div>

        <div class="col-6">
          <?= $this->Form->control('nombre', [
            'label'=>'Nombre de escuela',
            'id'=>'nombre'
          ]) ?>
        </div>

        <div class="col-6">
          <?= $this->Form->control('cct', [
            'label'=>'CCT',
            'id'=>'cct'
          ]) ?>
        </div>
      </div>
    </div>

    <!-- COMERCIAL -->
    <div class="panel hidden" id="panel-comercial">
      <div class="grid">
        <div class="col-3">
          <?= $this->Form->control('estatus', [
            'label'=>'Estatus',
            'options'=>$estatus,
            'id'=>'estatus'
          ]) ?>
        </div>

        <div class="col-3">
          <?= $this->Form->control('verificada', [
            'label'=>'Verificada',
            'type'=>'select',
            'options'=>$siNo,
            'id'=>'verificada'
          ]) ?>
        </div>

        <div class="col-3">
          <?= $this->Form->control('editorial_actual', [
            'label' => 'Tipo Compras',
            'type' => 'select',
            'options' => [
                'Campo formativo' => 'Campo formativo',
                'Integrados' => 'Integrados',
                'Sistemas educativos' => 'Sistemas educativos'],
            'empty' => 'Cualquiera',
            'id' => 'editorial_actual',
          ]); ?>
        </div>

        <div class="col-3">
          <?= $this->Form->control('venta_montenegro', [
            'label'=>'Venta Montenegro',
            'type'=>'select',
            'options'=>$siNo,
            'id'=>'venta_montenegro'
          ]) ?>
        </div>

        <div class="col-6">
          <?= $this->Form->control('competencia', [
            'label'=>'Competencia',
            'id'=>'competencia'
          ]) ?>
        </div>

        <div class="col-6">
          <?= $this->Form->control('fecha_decision', [
            'label'=>'Fecha decisión',
            'type'=>'date',
            'id'=>'fecha_decision',
            'empty' => ['year' => 'Año', 'month' => 'Mes', 'day' => 'Día'],
          ]) ?>
        </div>
      </div>
    </div>
    <div class="alert alert-info" style="margin-top:12px;">
      <strong>Resultados encontrados:</strong> <span id="contador">0</span>
    </div>
  </div>
</div>
<?= $this->Form->end() ?>

<div id="map-layout" class="map-layout">
  <button type="button" id="toggle-results-floating" class="corner-btn map-corner" title="Ocultar resultados"></button>

  <div id="map-wrap" style="position:relative;">
    <div id="territories-legend" class="territories-legend"></div>
    <div id="map" style="height:760px; border:1px solid #e6e6e6; border-radius:12px;"></div>
  </div>

  <!-- ══════════════════════════════════════════
       RESULTS PANEL — con KPIs arriba
  ══════════════════════════════════════════ -->
  <div id="results-panel" style="border:1px solid #e6e6e6; border-radius:12px; padding:12px; background:#fff; position:relative;">

    <!-- ─── KPI SIDEBAR ─── -->
    <div id="kpi-sidebar">
      <div class="kpi-section-header">
        <span class="kpi-section-title">Indicadores comerciales</span>
        <button type="button" id="kpi-collapse-btn" class="kpi-collapse-btn" title="Colapsar">▲</button>
      </div>

      <div id="kpi-body">

        <!-- Tarjetas numéricas -->
        <div class="kpi-grid">

          <!-- Meta (con botón editar) -->
          <div class="kpi-tile">
            <div class="kpi-tile-label">
              <span id="kpi-meta-label">Meta global</span>

              <button type="button" class="meta-edit-btn" id="meta-edit-trigger" title="Editar meta">✎</button>
            </div>
            <div class="kpi-tile-val" id="kpi-meta"><span class="kpi-skeleton"></span></div>
            <!-- Formulario inline editar meta -->
            <div class="meta-inline-form" id="meta-inline-form">
              <input type="number" class="meta-inline-input" id="meta-inline-input" min="0" step="1" placeholder="0">
              <button type="button" class="meta-inline-save" id="meta-inline-save">Guardar</button>
              <button type="button" class="meta-inline-cancel" id="meta-inline-cancel">✕</button>
            </div>
          </div>

          <!-- Proyección -->
          <div class="kpi-tile">
            <div class="kpi-tile-label">Proyección</div>
            <div class="kpi-tile-val" id="kpi-proyeccion"><span class="kpi-skeleton"></span></div>
          </div>

          <!-- Cierre — ocupa todo el ancho -->
          <div class="kpi-tile full">
            <div class="kpi-tile-label">Cierre 2026</div>
            <div style="display:flex; align-items:baseline; gap:8px; flex-wrap:wrap;">
              <div class="kpi-tile-val" id="kpi-cierre"><span class="kpi-skeleton"></span></div>
              <div class="kpi-tile-sub" id="kpi-cierre-sub"></div>
            </div>
          </div>

          <!-- Cobertura — ocupa todo el ancho -->
          <div class="kpi-tile full">
            <div class="kpi-tile-label">
              Cobertura de cierres
              <span id="kpi-cobertura-pct" style="font-weight:700; color:#1a202c;">—%</span>
            </div>
            <div class="kpi-progress-track">
              <div class="kpi-progress-fill" id="kpi-cobertura-bar"></div>
            </div>
          </div>

        </div><!-- /kpi-grid -->

        <!-- Semáforo interno (privado) -->
        <div class="kpi-sem-block">
          <div class="kpi-sem-title">Ciclo privado</div>
          <div class="sem-row">
            <div class="sem-step">
              <span class="sem-dot" id="sem-i-mar"></span>
              <span class="sem-lbl">Mar<br><b>20%</b></span>
            </div>
            <div class="sem-line"></div>
            <div class="sem-step">
              <span class="sem-dot" id="sem-i-abr"></span>
              <span class="sem-lbl">Abr–May<br><b>60%</b></span>
            </div>
            <div class="sem-line"></div>
            <div class="sem-step">
              <span class="sem-dot" id="sem-i-jun"></span>
              <span class="sem-lbl">Jun<br><b>100%</b></span>
            </div>
          </div>
        </div>

        <!-- Semáforo público (SEP) -->
        <div class="kpi-sem-block" style="margin-bottom:0;">
          <div class="kpi-sem-title">Ciclo público (SEP)</div>
          <div class="sem-row">
            <div class="sem-step">
              <span class="sem-dot" id="sem-p-jul"></span>
              <span class="sem-lbl">Jul<br><b>10%</b></span>
            </div>
            <div class="sem-line"></div>
            <div class="sem-step">
              <span class="sem-dot" id="sem-p-ago"></span>
              <span class="sem-lbl">Ago<br><b>50%</b></span>
            </div>
            <div class="sem-line"></div>
            <div class="sem-step">
              <span class="sem-dot" id="sem-p-sep"></span>
              <span class="sem-lbl">Sep<br><b>100%</b></span>
            </div>
          </div>
        </div>

      </div><!-- /kpi-body -->
    </div><!-- /kpi-sidebar -->

    <!-- ─── TABLA RESULTADOS ─── -->
    <h5 style="margin:0 0 10px 0;">Resultados</h5>
    <div class="results-scroll">
      <table id="tabla-nombres" style="width:100%; border-collapse:collapse;" border="1">
        <thead>
          <tr><th>Escuela</th></tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>

    <div class="visits-inline-note">La agenda de visitas esta disponible en el boton "Agenda" de la barra superior.</div>
  </div>
</div>

<!-- ══════════ MODALES EXISTENTES (sin cambios) ══════════ -->
<div id="visits-modal" class="modal-backdrop">
  <div class="modal-card visits-modal-card">
    <div class="modal-head">
      <strong>Agenda de visitas</strong>
      <button type="button" id="visits-modal-close" style="border:0; background:#eee; border-radius:8px; padding:6px 10px; cursor:pointer;">Cerrar</button>
    </div>
    <div class="modal-body">
      <div id="visits-panel" class="visits-panel" style="margin-top:0; border-top:0; padding-top:0;">
        <div class="visits-toolbar">
          <strong>Agenda de visitas</strong>
          <div class="btn-group">
            <button type="button" id="visits-scope-mine" class="btn btn-light btn-sm">Mis</button>
            <button type="button" id="visits-scope-all" class="btn btn-light btn-sm">Global</button>
          </div>
        </div>
        <div class="visits-toolbar">
          <div class="btn-group">
            <button type="button" id="visits-status-scheduled" class="btn btn-light btn-sm">Pendientes</button>
            <button type="button" id="visits-status-completed" class="btn btn-light btn-sm">Completadas</button>
          </div>
          <button type="button" id="visits-refresh" class="btn btn-light btn-sm">Actualizar</button>
        </div>
        <div id="visit-route-info" class="route-info" style="display:none;"></div>
        <div id="visits-list" class="visits-list"></div>
      </div>
    </div>
  </div>
</div>

<div id="toast-ok"
  style="
    position:fixed;
    bottom:20px;
    right:20px;
    background:#198754;
    color:#fff;
    padding:12px 16px;
    border-radius:10px;
    box-shadow:0 6px 18px rgba(0,0,0,.2);
    font-size:14px;
    opacity:0;
    pointer-events:none;
    transition:opacity .3s ease;
    z-index:9999;
  ">
  ✔ Ubicación guardada correctamente
</div>

<div id="edit-modal" style="position:fixed; inset:0; background:rgba(0,0,0,.45); display:none; align-items:center; justify-content:center; z-index:10000;">
  <div id="edit-modal-card" style="background:#fff; width:min(1320px, 86vw); max-height:92vh; border-radius:6px; box-shadow:0 16px 36px rgba(0,0,0,.28); overflow:hidden; padding:10px 12px; display:flex; flex-direction:column;">
    <iframe id="edit-modal-iframe" title="Editar escuela" style="width:100%; height:420px; border:0; display:block; background:#fff;"></iframe>
  </div>
</div>

<div id="schedule-modal" class="modal-backdrop">
  <div class="modal-card">
    <div class="modal-head">
      <strong>Agendar visita</strong>
      <button type="button" id="schedule-modal-close" style="border:0; background:#eee; border-radius:8px; padding:6px 10px; cursor:pointer;">Cerrar</button>
    </div>
    <div class="modal-body">
      <div style="font-size:12px; color:#555; margin-bottom:8px;">
        Escuela: <span id="schedule-school-name">-</span>
      </div>
      <div class="form-group" style="margin-bottom:8px;">
        <label for="schedule-datetime" style="font-size:12px;">Fecha y hora</label>
        <input id="schedule-datetime" type="datetime-local" class="form-control">
      </div>
      <div class="form-group">
        <label for="schedule-notes" style="font-size:12px;">Notas (opcional)</label>
        <textarea id="schedule-notes" class="form-control" rows="3"></textarea>
      </div>
    </div>
    <div class="modal-actions">
      <button type="button" id="schedule-save" class="btn-success-soft">Agendar</button>
      <button type="button" id="schedule-cancel" class="btn-soft">Cancelar</button>
    </div>
  </div>
</div>

<div id="complete-modal" class="modal-backdrop">
  <div class="modal-card">
    <div class="modal-head">
      <strong>Completar visita</strong>
      <button type="button" id="complete-modal-close" style="border:0; background:#eee; border-radius:8px; padding:6px 10px; cursor:pointer;">Cerrar</button>
    </div>
    <div class="modal-body">
      <div class="form-group" style="margin-bottom:8px;">
        <label for="complete-notes" style="font-size:12px;">Notas (opcional)</label>
        <textarea id="complete-notes" class="form-control" rows="3"></textarea>
      </div>
      <div class="form-group">
        <label for="complete-evidence" style="font-size:12px;">Evidencia (opcional)</label>
        <input id="complete-evidence" type="file" class="form-control" accept=".jpg,.jpeg,.png,.webp,.pdf">
        <div style="font-size:11px; color:#666; margin-top:4px;">Max 10MB. JPG/PNG/WEBP/PDF.</div>
      </div>
    </div>
    <div class="modal-actions">
      <button type="button" id="complete-save" class="btn-success-soft">Completar</button>
      <button type="button" id="complete-cancel" class="btn-soft">Cancelar</button>
    </div>
  </div>
</div>

<script>
let map;
let dataLayer;
let territoriesLayer = null;
let territoriesLayerReady = false;
let territoriesLayerLoadingPromise = null;
let territoriesStateIndex = new Map();
let territoriesUserColors = new Map();
let territoriesInfoWindow = null;
let activeFeature = null;
let infoWindow;
let directionsService;
let directionsRenderer;

let routePickMode = false;
let routePickVisit = null;

let editMarker = null;
let pendingCoords = null;
let originalCoords = null;
let infoMoreActions = false;

const csrfToken = "<?= h($this->request->getAttribute('csrfToken') ?? '') ?>";

// ── URLs ──
const filtrarUrl           = "<?= $this->Url->build(['controller'=>'Schools','action'=>'filtrarSchools'], ['escape'=>false]) ?>";
const contarUrl            = "<?= $this->Url->build(['controller'=>'Schools','action'=>'contarFiltrado'], ['escape'=>false]) ?>";
const guardarUrl           = "<?= $this->Url->build(['controller'=>'Schools','action'=>'guardarCoordenadas'], ['escape'=>false]) ?>";
const editUrlTpl           = "<?= $this->Url->build(['controller'=>'Schools','action'=>'editModal','__ID__'], ['escape'=>false]) ?>";
const municipiosUrlTpl     = "<?= $this->Url->build(['controller'=>'Schools','action'=>'municipiosPorEstado','__ID__'], ['escape' => false]) ?>";
const territoriosResumenUrl= "<?= $this->Url->build(['controller'=>'Schools','action'=>'territoriosResumen'], ['escape'=>false]) ?>";
const territoriesGeoJsonUrl= "<?= $this->Url->build('/geo/mx_estados.geojson', ['escape'=>false]) ?>";
const materialsUrlTpl      = "<?= $this->Url->build('/schools/__ID__/materials-manager', ['escape'=>false]) ?>";
const visitsScheduleUrl    = "<?= $this->Url->build(['controller'=>'Visits','action'=>'schedule'], ['escape'=>false]) ?>";
const visitsListUrl        = "<?= $this->Url->build(['controller'=>'Visits','action'=>'listVisits'], ['escape'=>false]) ?>";
const visitsStartUrl       = "<?= $this->Url->build(['controller'=>'Visits','action'=>'startRoute'], ['escape'=>false]) ?>";
const visitsCompleteUrl    = "<?= $this->Url->build(['controller'=>'Visits','action'=>'complete'], ['escape'=>false]) ?>";

// ── URLs KPI (nuevas) ──
const kpisSidebarUrl = "<?= $this->Url->build(['controller'=>'Schools','action'=>'kpisSidebar'], ['escape'=>false]) ?>";
const metaGuardarUrl = "<?= $this->Url->build(['controller'=>'Schools','action'=>'guardarMeta'], ['escape'=>false]) ?>";

let visitsScope = 'mine';
let visitsStatus = 'scheduled';
let scheduleSchoolId = null;
let scheduleSchoolName = '';
let completeVisitId = null;
let editModalResizeTimers = [];

// valor de meta actual (para pre-llenar el input de edición)
let currentMetaValue = 0;

const territoriesPalette = ['#0d6efd','#198754','#fd7e14','#dc3545','#20c997','#6f42c1','#0dcaf0','#d63384','#6610f2','#795548'];
const territoriesMixedColor = '#495057';
const territoriesEmptyColor = '#e9ecef';

// ═══════════════════════════════════════════
//  KPI — helpers
// ═══════════════════════════════════════════
function semColor(cobertura, umbral) { 
  if (cobertura >= umbral)        return 'verde'; 
  if (cobertura >= umbral * 0.65) return 'amarillo'; 
  return 'rojo'; 
} 

function setSemDot(id, cobertura, umbral, active) {
  const el = document.getElementById(id);
  if (!el) return;
  if (!active) {
    el.className = 'sem-dot';
    return;
  }
  el.className = 'sem-dot ' + semColor(cobertura, umbral);
}

function actualizarKPIs(data) {
  const meta       = Number(data.meta       || 0);
  const proyeccion = Number(data.proyeccion || 0);
  const cierre     = Number(data.cierre     || 0);
  const cobertura  = Number(data.cobertura  || 0);
const metaLabelEl = document.getElementById('kpi-meta-label');
if (metaLabelEl && data.meta_label) metaLabelEl.textContent = data.meta_label;

  currentMetaValue = meta;

  const fmt = n => n.toLocaleString('es-MX', { maximumFractionDigits: 0 });

  document.getElementById('kpi-meta').textContent       = fmt(meta);
  document.getElementById('kpi-proyeccion').textContent = fmt(proyeccion);
  document.getElementById('kpi-cierre').textContent     = fmt(cierre);

  const vsMeta = meta > 0       ? (cierre / meta * 100).toFixed(1)       : '—';
  const vsProy = proyeccion > 0 ? (cierre / proyeccion * 100).toFixed(1) : '—';
  document.getElementById('kpi-cierre-sub').textContent =
    `vs meta ${vsMeta}%  ·  vs proy ${vsProy}%`;

  document.getElementById('kpi-cobertura-pct').textContent = cobertura.toFixed(1) + '%';

  const bar = document.getElementById('kpi-cobertura-bar');
  bar.style.width      = Math.min(cobertura, 100) + '%';
  bar.style.background = cobertura >= 80 ? '#198754'
                       : cobertura >= 50 ? '#ffc107'
                       : '#dc3545';

  const month = new Date().getMonth() + 1;
  const sectorSel = document.getElementById('sector');
  const sectorVal = sectorSel ? String(sectorSel.value || '') : '';
  const showPriv = !sectorVal || sectorVal === 'Privado';
  const showPub  = !sectorVal || sectorVal === 'Publico';

  // Semáforo privado (activa por mes; futuro queda gris)
  setSemDot('sem-i-mar', cobertura, 20,  showPriv && month >= 3);
  setSemDot('sem-i-abr', cobertura, 60,  showPriv && month >= 4); // Abr–May
  setSemDot('sem-i-jun', cobertura, 100, showPriv && month >= 6);

  // Semáforo público (SEP)
  setSemDot('sem-p-jul', cobertura, 10, showPub && month >= 7);
  setSemDot('sem-p-ago', cobertura, 50, showPub && month >= 8);
  setSemDot('sem-p-sep', cobertura, 90, showPub && month >= 9);
} 

function cargarKPIs(formData) {
  const headers = { 'Accept': 'application/json' };
  if (csrfToken) headers['X-CSRF-Token'] = csrfToken;
  fetch(kpisSidebarUrl, { method: 'POST', headers, body: formData, credentials: 'same-origin' })
    .then(r => r.json())
    .then(data => { if (data && data.ok) actualizarKPIs(data); })
    .catch(console.error);
}

// ═══════════════════════════════════════════
//  KPI — guardar meta inline
// ═══════════════════════════════════════════
function guardarMeta(valor) {
  // Enviamos los filtros actuales para que la meta se guarde en el contexto correcto (usuario/estado)
  const fd = new FormData(document.getElementById('filtros-form'));
  fd.append('valor', valor);
  fd.append('anio',  new Date().getFullYear());

  const headers = { 'Accept': 'application/json' };
  if (csrfToken) headers['X-CSRF-Token'] = csrfToken;

  return fetch(metaGuardarUrl, { method: 'POST', headers, body: fd, credentials: 'same-origin' })
    .then(r => r.json());
}

// ═══════════════════════════════════════════
//  Colores estatus / helpers territorios
// ═══════════════════════════════════════════
function getStatusColor(estatus) {
  switch (String(estatus)) {
    case 'noAtendida':        return '#6c757d';
    case 'escuelaPromocion':  return '#0dcaf0';
    case 'ventaConfirmada':   return '#198754';
    case 'prohibicion':       return '#dc3545';
    case 'ventaMarcas':       return '#fd7e14';
    default:                  return '#1976d2';
  }
}

function getEstatusText(estatus) {
  return ({
    noAtendida: 'No atendida',
    escuelaPromocion: 'Escuela en promoción',
    ventaConfirmada: 'Venta confirmada',
    prohibicion: 'Prohibición',
    ventaMarcas: 'Venta otras marcas'
  })[String(estatus)] || (estatus || '—');
}

function normalizeTerritoryKey(value) {
  return String(value || '')
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '')
    .replace(/[^a-zA-Z0-9]/g, '')
    .toUpperCase();
}

function getTerritoryColorByUser(userId) {
  const key = String(userId ?? '');
  if (territoriesUserColors.has(key)) return territoriesUserColors.get(key);
  let hash = 0;
  for (let i = 0; i < key.length; i++) {
    hash = ((hash << 5) - hash) + key.charCodeAt(i);
    hash |= 0;
  }
  const color = territoriesPalette[Math.abs(hash) % territoriesPalette.length];
  territoriesUserColors.set(key, color);
  return color;
}

function getTerritoryStateData(feature) {
  if (!feature) return null;
  const directName = feature.getProperty('name') || feature.getProperty('NOMGEO') || '';
  const key = normalizeTerritoryKey(directName);
  if (key && territoriesStateIndex.has(key)) return territoriesStateIndex.get(key);
  return null;
}

function getTerritoriesFeatureStyle(feature) {
  const stateData = getTerritoryStateData(feature);
  if (!stateData) {
    return { fillColor: territoriesEmptyColor, fillOpacity: 0.08, strokeColor: '#adb5bd', strokeWeight: 1, clickable: true };
  }
  const usersCount  = Number(stateData.users_count || 0);
  const users       = Array.isArray(stateData.users) ? stateData.users : [];
  const primaryUser = users[0] || null;
  const fillColor   = getTerritoryColorByUser((primaryUser || {}).user_id ?? 0);
  return {
    fillColor,
    fillOpacity:   usersCount > 1 ? 0.26 : 0.34,
    strokeColor:   usersCount > 1 ? '#212529' : '#495057',
    strokeWeight:  usersCount > 1 ? 1.8 : 1.2,
    clickable: true,
  };
}

function renderTerritoriesLegend() {
  const legend = document.getElementById('territories-legend');
  if (!legend) return;
  const estadoSel = document.getElementById('estado');
  const selectedEstadoId = estadoSel ? String(estadoSel.value || '') : '';

  if (selectedEstadoId) {
    let selectedState = null;
    territoriesStateIndex.forEach((state) => {
      if (String(state.estado_id || '') === selectedEstadoId) selectedState = state;
    });
    if (selectedState) {
      const users = Array.isArray(selectedState.users) ? selectedState.users : [];
      let html = '<div class="legend-title">Usuarios en estado filtrado</div>';
      html += `<div class="legend-item"><span><b>${selectedState.estado || 'Estado'}</b></span></div>`;
      html += `<div class="legend-item"><span>Escuelas: <b>${selectedState.total_schools || 0}</b></span></div>`;
      users.forEach((u) => {
        const color = getTerritoryColorByUser(u.user_id ?? 0);
        html += `<div class="legend-item"><span class="swatch" style="background:${color}"></span><span>${u.user_name || 'Sin asignar'} (${u.count || 0})</span></div>`;
      });
      if (!users.length) {
        html += `<div class="legend-item"><span class="swatch" style="background:${territoriesEmptyColor}"></span><span>Sin datos</span></div>`;
      }
      legend.innerHTML = html;
      legend.style.display = '';
      return;
    }
  }

  const rows = [];
  territoriesStateIndex.forEach((state) => { (state.users || []).forEach((u) => rows.push(u)); });
  const userCountMap = new Map();
  rows.forEach((u) => {
    const id = String(u.user_id ?? '0');
    const prev = userCountMap.get(id);
    if (!prev || Number(u.count || 0) > Number(prev.count || 0)) userCountMap.set(id, u);
  });
  const users = Array.from(userCountMap.values()).slice(0, 8);
  let html = '<div class="legend-title">Territorios por usuario</div>';
  html += `<div class="legend-item"><span style="font-size:11px;color:#555;">Estados con varios usuarios se muestran con borde mas fuerte.</span></div>`;
  users.forEach((u) => {
    const color = getTerritoryColorByUser(u.user_id ?? 0);
    html += `<div class="legend-item"><span class="swatch" style="background:${color}"></span><span>${u.user_name || 'Sin asignar'}</span></div>`;
  });
  if (!users.length) {
    html += `<div class="legend-item"><span class="swatch" style="background:${territoriesEmptyColor}"></span><span>Sin datos</span></div>`;
  }
  legend.innerHTML = html;
  legend.style.display = '';
}

function clearTerritoriesLegend() {
  const legend = document.getElementById('territories-legend');
  if (!legend) return;
  legend.style.display = 'none';
  legend.innerHTML = '';
}

function ensureTerritoriesLayer() {
  if (territoriesLayerReady && territoriesLayer) return Promise.resolve();
  if (territoriesLayerLoadingPromise) return territoriesLayerLoadingPromise;
  if (!map || !google?.maps?.Data) return Promise.resolve();

  territoriesLayer = new google.maps.Data();
  territoriesInfoWindow = new google.maps.InfoWindow();
  territoriesLayer.setStyle(getTerritoriesFeatureStyle);

  territoriesLayer.addListener('click', (e) => {
    const stateData = getTerritoryStateData(e.feature);
    if (!stateData || !territoriesInfoWindow) return;
    const users = Array.isArray(stateData.users) ? stateData.users : [];
    const rows = users.map((u) => {
      const color = getTerritoryColorByUser(u.user_id ?? 0);
      return `<div style="display:flex;align-items:center;gap:6px;margin:3px 0;"><span style="width:10px;height:10px;border-radius:999px;background:${color};display:inline-block;"></span><span>${u.user_name || 'Sin asignar'} (${u.count || 0})</span></div>`;
    }).join('');
    territoriesInfoWindow.setContent(`
      <div style="font-size:12px;max-width:260px;">
        <div style="font-weight:700;margin-bottom:6px;">${stateData.estado || 'Estado'}</div>
        <div>Total escuelas: <b>${stateData.total_schools || 0}</b></div>
        <div style="margin-top:4px;">Usuarios: <b>${stateData.users_count || 0}</b></div>
        <div style="margin-top:6px;">${rows || '<span>Sin usuarios</span>'}</div>
      </div>
    `);
    territoriesInfoWindow.setPosition(e.latLng);
    territoriesInfoWindow.open(map);
  });

  territoriesLayerLoadingPromise = new Promise((resolve) => {
    territoriesLayer.loadGeoJson(territoriesGeoJsonUrl, null, () => {
      territoriesLayerReady = true;
      resolve();
    });
  });
  return territoriesLayerLoadingPromise;
}

function setTerritoriesVisible(visible) {
  if (!territoriesLayer) return;
  territoriesLayer.setMap(visible ? map : null);
  if (!visible) {
    clearTerritoriesLegend();
    if (territoriesInfoWindow) territoriesInfoWindow.close();
  }
}

function fetchTerritoriesSummary(form) {
  if (!form) return Promise.resolve([]);
  const formData = new FormData(form);
  const headers = { 'Accept': 'application/json' };
  if (csrfToken) headers['X-CSRF-Token'] = csrfToken;
  return fetch(territoriosResumenUrl, { method: 'POST', headers, body: formData, credentials: 'same-origin' })
    .then(r => r.json())
    .then((data) => (data && data.ok && Array.isArray(data.states)) ? data.states : [])
    .catch((e) => { console.error(e); return []; });
}

function applyTerritoriesSummary(states) {
  territoriesStateIndex = new Map();
  (states || []).forEach((s) => {
    const key = normalizeTerritoryKey(s.estado || '');
    if (!key) return;
    territoriesStateIndex.set(key, s);
  });
  if (territoriesLayer) territoriesLayer.setStyle(getTerritoriesFeatureStyle);
  renderTerritoriesLegend();
}

function refreshTerritoriesOverlay(form, territoriosChk) {
  if (!territoriosChk || !territoriosChk.checked) { setTerritoriesVisible(false); return; }
  ensureTerritoriesLayer()
    .then(() => fetchTerritoriesSummary(form))
    .then((states) => {
      applyTerritoriesSummary(states);
      setTerritoriesVisible(true);
    });
}

function getIcon(active, estatus) {
  const color = getStatusColor(estatus);
  return {
    path: google.maps.SymbolPath.CIRCLE,
    scale: active ? 10 : 6,
    fillColor: color,
    fillOpacity: 1,
    strokeColor: '#fff',
    strokeWeight: 2
  };
}

// ═══════════════════════════════════════════
//  GOOGLE MAP INIT
// ═══════════════════════════════════════════
window.initMap = function () {
  map = new google.maps.Map(document.getElementById('map'), {
    center: { lat: 23.6345, lng: -102.5528 },
    zoom: 6
  });

  dataLayer = new google.maps.Data({ map });
  infoWindow = new google.maps.InfoWindow();
  directionsService = new google.maps.DirectionsService();
  directionsRenderer = new google.maps.DirectionsRenderer({
    map,
    suppressMarkers: false,
    preserveViewport: true
  });

  dataLayer.setStyle(feature => {
    if (feature.getProperty('editing')) return { visible: false };
    const active = !!feature.getProperty('active');
    const estatus = feature.getProperty('estatus');
    return { icon: getIcon(active, estatus) };
  });

  dataLayer.addListener('click', (e) => activarEscuela(e.feature));

  map.addListener('click', (e) => {
    if (!routePickMode || !routePickVisit) return;
    const lat = e.latLng.lat();
    const lng = e.latLng.lng();
    routePickMode = false;
    const visit = routePickVisit;
    routePickVisit = null;
    startRouteForVisit(visit, { lat, lng });
  });
};

// ═══════════════════════════════════════════
//  InfoWindow
// ═══════════════════════════════════════════
function renderInfo(f) {
  const ok = (f.getProperty('verificada') === 1 || f.getProperty('verificada') === true || f.getProperty('verificada') === 'Sí');
  const badge = ok ? '✔️' : '❌';
  const id              = f.getProperty('id') ?? '';
  const nombre          = f.getProperty('nombre') ?? '';
  const estado          = f.getProperty('estado') ?? '';
  const municipio       = f.getProperty('municipio') ?? '';
  const cct             = f.getProperty('cct') ?? '';
  const tipo            = f.getProperty('tipo') ?? '';
  const user            = f.getProperty('user') ?? '';
  const sector          = f.getProperty('sector') ?? '';
  const turno           = f.getProperty('turno') ?? '';
  const alumnos         = f.getProperty('num_alumnos') ?? '';
  const grupos          = f.getProperty('grupos') ?? '';
  const nombreContacto  = f.getProperty('nombre_contacto') ?? '';
  const telefonoContacto= f.getProperty('telefono_contacto') ?? '';
  const notas           = f.getProperty('notas') ?? '';
  const editorialActual = f.getProperty('editorial_actual') ?? '';
  const ventaMontenegro = f.getProperty('venta_montenegro') ?? '';
  const competencia     = f.getProperty('competencia') ?? '';
  const presupuesto     = f.getProperty('presupuesto') ?? '';
  const estatus         = f.getProperty('estatus') ?? '';
  const estatusTxt      = getEstatusText(estatus);
  const statusColor     = getStatusColor(estatus);
  const canSave    = !!pendingCoords && String(pendingCoords.id) === String(id);
  const inMoveMode = !!originalCoords && f.getProperty('editing') === true;
  const showMoreActions = !!infoMoreActions;
  const toggleArrow = showMoreActions ? '▾' : '▸';

  return `
    <div style="position:relative; font-size:13px; max-width:260px; padding-right:18px;">
      <div style="position:absolute; top:0; right:0; font-size:14px;">${badge}</div>
      <div style="font-weight:700; margin-bottom:6px;">${nombre}</div>
      <div style="display:inline-flex;align-items:center;gap:6px;margin:6px 0 8px 0;">
        <span style="display:inline-block;width:10px;height:10px;border-radius:999px;background:${statusColor};"></span>
        <span style="padding:2px 8px;border-radius:999px;background:rgba(0,0,0,.06);font-weight:600;">${estatusTxt}</span>
      </div>
      <div><b>Estado:</b> ${estado}</div>
      <div><b>Municipio:</b> ${municipio}</div>
      <div><b>CCT:</b> ${cct}</div>
      <div><b>Tipo:</b> ${tipo}</div>
      <div><b>Distribuidor:</b> ${user}</div>
      <div><b>Sector:</b> ${sector}</div>
      <div><b>Turno:</b> ${turno}</div>
      <div><b># Alumnos:</b> ${alumnos}</div>
      <div><b>Grupos:</b> ${grupos}</div>
      <div><b>Contacto:</b> ${nombreContacto}</div>
      <div><b>Teléfono:</b> ${telefonoContacto}</div>
      <div><b>Notas:</b> ${notas}</div>
      <div><b>Tipo Compras:</b> ${editorialActual}</div>
      <div><b>Venta Montenegro:</b> ${ventaMontenegro}</div>
      <div><b>Competencia:</b> ${competencia}</div>
      <div><b>Presupuesto:</b> ${presupuesto}</div>
      <div style="margin-top:10px; display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
        <button type="button" onclick="window._openEditModal()"
          style="background:transparent;color:#8b1d2c;border:0;padding:0;cursor:pointer;font-weight:600;">
          Editar escuela
        </button>
        <button type="button" onclick="window._movePinActive()"
          style="background:transparent;color:#8b1d2c;border:0;padding:0;cursor:pointer;font-weight:600;">
          Mover pin
        </button>
        <button type="button" onclick="window._toggleMoreActions()" title="Mas acciones"
          style="background:transparent;color:#8b1d2c;border:0;padding:0 2px;cursor:pointer;font-size:14px;font-weight:700;">
          ${toggleArrow}
        </button>
      </div>
      <div style="margin-top:6px; display:${showMoreActions ? 'flex' : 'none'}; gap:10px; flex-wrap:wrap; align-items:center;">
        <button type="button" onclick="window._openScheduleModal()"
          style="background:transparent;color:#8b1d2c;border:0;padding:0;cursor:pointer;font-weight:600;">
          Agendar visita
        </button>
        <button type="button" onclick="window._openMaterials()"
          style="background:transparent;color:#8b1d2c;border:0;padding:0;cursor:pointer;font-weight:600;">
          Gestor de materiales
        </button>
      </div>
      <div style="margin-top:6px; display:${inMoveMode ? 'flex' : 'none'}; gap:10px; flex-wrap:wrap; align-items:center;">
        <button type="button" onclick="window._savePinActive()" ${canSave ? '' : 'disabled'}
          style="background:transparent;color:#8b1d2c;border:0;padding:0;cursor:${canSave ? 'pointer' : 'default'};font-weight:700;opacity:${canSave ? '1' : '.5'};">
          Guardar ubicacion
        </button>
        <button type="button" onclick="window._cancelMovePin()"
          style="background:transparent;color:#8b1d2c;border:0;padding:0;cursor:pointer;font-weight:600;">
          Cancelar
        </button>
      </div>
    </div>
  `;
}

window._openMaterials = function () {
  if (!activeFeature) return;
  const id = activeFeature.getProperty('id');
  if (!id) { alert('No hay id de escuela'); return; }
  window.open(materialsUrlTpl.replace('__ID__', encodeURIComponent(id)), '_blank');
};

window._openScheduleModal = function () {
  if (!activeFeature) return;
  const id = activeFeature.getProperty('id');
  const nombre = activeFeature.getProperty('nombre') ?? '';
  if (!id) return;
  scheduleSchoolId = id;
  scheduleSchoolName = nombre;
  const modal = document.getElementById('schedule-modal');
  const nameEl = document.getElementById('schedule-school-name');
  const dtEl = document.getElementById('schedule-datetime');
  const notesEl = document.getElementById('schedule-notes');
  if (nameEl) nameEl.textContent = nombre || '-';
  if (dtEl) dtEl.value = '';
  if (notesEl) notesEl.value = '';
  if (modal) { modal.style.display = 'flex'; document.body.style.overflow = 'hidden'; }
};

window._toggleMoreActions = function () {
  infoMoreActions = !infoMoreActions;
  if (!activeFeature) return;
  const pos = activeFeature.getGeometry().get();
  infoWindow.setContent(renderInfo(activeFeature));
  infoWindow.setPosition(pos);
  infoWindow.open(map);
};

function activarEscuela(feature) {
  if (!feature) return;
  pendingCoords = null;
  originalCoords = null;
  removeEditMarker();
  if (activeFeature) {
    activeFeature.setProperty('active', false);
    activeFeature.setProperty('editing', false);
  }
  activeFeature = feature;
  infoMoreActions = false;
  feature.setProperty('active', true);
  const pos = feature.getGeometry().get();
  map.panTo(pos);
  map.setZoom(Math.max(map.getZoom(), 9));
  infoWindow.setContent(renderInfo(feature));
  infoWindow.setPosition(pos);
  infoWindow.open(map);
}

function enableMovePin(feature) {
  if (!feature) return;
  removeEditMarker();
  const pos = feature.getGeometry().get();
  originalCoords = { lat: pos.lat(), lng: pos.lng() };
  pendingCoords = null;
  feature.setProperty('editing', true);
  editMarker = new google.maps.Marker({
    position: pos,
    map,
    draggable: true,
    title: 'Arrastra para ajustar la ubicación'
  });
  map.panTo(pos);
  map.setZoom(Math.max(map.getZoom(), 16));
  if (infoWindow) infoWindow.close();
  editMarker.addListener('dragend', () => {
    const p = editMarker.getPosition();
    const lat = p.lat();
    const lng = p.lng();
    feature.setGeometry(new google.maps.Data.Point({ lat, lng }));
    pendingCoords = { id: feature.getProperty('id'), lat, lng };
    infoWindow.setContent(renderInfo(feature));
    infoWindow.setPosition({ lat, lng });
    infoWindow.open(map);
  });
}

function removeEditMarker() {
  if (editMarker) { editMarker.setMap(null); editMarker = null; }
}

function saveCoords(id, lat, lng) {
  const headers = { 'Accept': 'application/json' };
  if (csrfToken) headers['X-CSRF-Token'] = csrfToken;
  const formData = new FormData();
  formData.append('id', id);
  formData.append('lat', lat);
  formData.append('lng', lng);
  return fetch(guardarUrl, { method: 'POST', headers, body: formData, credentials: 'same-origin' })
    .then(async (r) => {
      const text = await r.text();
      let j = null;
      try { j = JSON.parse(text); } catch(e) {}
      if (!r.ok) { console.error('Guardar coords HTTP', r.status, j || text); return false; }
      return !!(j && j.ok);
    });
}

window._movePinActive = function () { if (!activeFeature) return; enableMovePin(activeFeature); };

window._savePinActive = async function () {
  if (!activeFeature || !pendingCoords) return;
  const id = activeFeature.getProperty('id');
  if (String(pendingCoords.id) !== String(id)) return;
  const ok = await saveCoords(pendingCoords.id, pendingCoords.lat, pendingCoords.lng);
  if (!ok) { console.warn('No se pudo guardar la ubicación'); return; }
  showSavedToast('✔ Coordenadas guardadas correctamente');
  pendingCoords = null;
  originalCoords = null;
  infoMoreActions = false;
  activeFeature.setProperty('editing', false);
  removeEditMarker();
  const pos = activeFeature.getGeometry().get();
  infoWindow.setContent(renderInfo(activeFeature));
  infoWindow.setPosition(pos);
  infoWindow.open(map);
};

window._cancelMovePin = function () {
  if (!activeFeature) return;
  if (originalCoords) {
    activeFeature.setGeometry(new google.maps.Data.Point({ lat: originalCoords.lat, lng: originalCoords.lng }));
  }
  pendingCoords = null;
  originalCoords = null;
  activeFeature.setProperty('editing', false);
  removeEditMarker();
  const pos = activeFeature.getGeometry().get();
  infoWindow.setContent(renderInfo(activeFeature));
  infoWindow.setPosition(pos);
  infoWindow.open(map);
};

// ═══════════════════════════════════════════
//  Modal editar
// ═══════════════════════════════════════════
function getEditIframeContentHeight(iframe) {
  if (!iframe) return null;
  try {
    const doc = iframe.contentDocument || iframe.contentWindow?.document;
    if (!doc || !doc.body || !doc.documentElement) return null;
    const root = doc.querySelector('.modal-school-edit') || doc.querySelector('#edit-school-modal-form');
    if (root) {
      const rect = root.getBoundingClientRect();
      const styles = iframe.contentWindow?.getComputedStyle(root);
      const marginTop = parseFloat(styles?.marginTop || '0') || 0;
      const marginBottom = parseFloat(styles?.marginBottom || '0') || 0;
      return Math.ceil(rect.height + marginTop + marginBottom + 8);
    }
    return Math.max(doc.body.scrollHeight||0, doc.body.offsetHeight||0, doc.documentElement.scrollHeight||0, doc.documentElement.offsetHeight||0);
  } catch (e) { return null; }
}

function resizeEditModalToContent() {
  const modal = document.getElementById('edit-modal');
  const iframe = document.getElementById('edit-modal-iframe');
  if (!modal || !iframe || modal.style.display !== 'flex') return;
  const minHeight = 260;
  const maxHeight = Math.max(minHeight, Math.floor(window.innerHeight * 0.84));
  const contentHeight = getEditIframeContentHeight(iframe);
  const targetHeight = contentHeight ? Math.min(Math.max(contentHeight + 8, minHeight), maxHeight) : minHeight;
  const currentHeight = parseInt(iframe.style.height || '0', 10) || 0;
  if (Math.abs(currentHeight - targetHeight) < 2) return;
  iframe.style.height = `${targetHeight}px`;
}

function startEditModalAutoResize() {
  stopEditModalAutoResize();
  const passes = [0, 120, 280, 520, 900];
  editModalResizeTimers = passes.map((ms) => setTimeout(resizeEditModalToContent, ms));
}

function stopEditModalAutoResize() {
  if (!editModalResizeTimers.length) return;
  editModalResizeTimers.forEach((id) => clearTimeout(id));
  editModalResizeTimers = [];
}

window._openEditModal = function () {
  if (!activeFeature) return;
  const id = activeFeature.getProperty('id');
  if (!id) return;
  const modal = document.getElementById('edit-modal');
  const iframe = document.getElementById('edit-modal-iframe');
  if (!modal || !iframe) return;
  const base = editUrlTpl.replace('__ID__', encodeURIComponent(id));
  const url = base.includes('?') ? `${base}&layout=ajax` : `${base}?layout=ajax`;
  iframe.style.height = '260px';
  iframe.src = url;
  modal.style.display = 'flex';
  document.body.style.overflow = 'hidden';
  startEditModalAutoResize();
};

function closeEditModal() {
  const modal = document.getElementById('edit-modal');
  const iframe = document.getElementById('edit-modal-iframe');
  if (!modal || !iframe) return;
  iframe.src = 'about:blank';
  iframe.style.height = '420px';
  modal.style.display = 'none';
  document.body.style.overflow = '';
  stopEditModalAutoResize();
}

function openCompleteModal(visitId) {
  completeVisitId = visitId;
  const modal = document.getElementById('complete-modal');
  const notesEl = document.getElementById('complete-notes');
  const fileEl = document.getElementById('complete-evidence');
  if (notesEl) notesEl.value = '';
  if (fileEl) fileEl.value = '';
  if (modal) { modal.style.display = 'flex'; document.body.style.overflow = 'hidden'; }
}

function closeScheduleModal() {
  const modal = document.getElementById('schedule-modal');
  if (modal) modal.style.display = 'none';
  document.body.style.overflow = '';
}

function closeCompleteModal() {
  const modal = document.getElementById('complete-modal');
  if (modal) modal.style.display = 'none';
  document.body.style.overflow = '';
}

// ═══════════════════════════════════════════
//  Utilidades mapa
// ═══════════════════════════════════════════
function clearDataLayer() {
  if (!dataLayer) return;
  dataLayer.forEach(f => dataLayer.remove(f));
  activeFeature = null;
  if (infoWindow) infoWindow.close();
  removeEditMarker();
  pendingCoords = null;
  originalCoords = null;
}

function fitToDataLayer() {
  if (!dataLayer) return;
  const bounds = new google.maps.LatLngBounds();
  let has = false;
  dataLayer.forEach(f => { const p = f.getGeometry().get(); bounds.extend(p); has = true; });
  if (has) map.fitBounds(bounds);
}

function showSavedToast(msg = '✔ Ubicación guardada correctamente') {
  const t = document.getElementById('toast-ok');
  if (!t) return;
  t.textContent = msg;
  t.style.opacity = '1';
  clearTimeout(window.__toastTimer);
  window.__toastTimer = setTimeout(() => { t.style.opacity = '0'; }, 2500);
}

function fetchJsonPost(url, formData) {
  const headers = { 'Accept': 'application/json' };
  if (csrfToken) headers['X-CSRF-Token'] = csrfToken;
  return fetch(url, { method: 'POST', headers, body: formData })
    .then(async (r) => {
      const text = await r.text();
      try { return JSON.parse(text); }
      catch(e){ console.error('Respuesta NO JSON:', text); throw e; }
    });
}

function fetchJsonPostForm(url, formData) {
  const headers = { 'Accept': 'application/json' };
  if (csrfToken) headers['X-CSRF-Token'] = csrfToken;
  return fetch(url, { method: 'POST', headers, body: formData, credentials: 'same-origin' })
    .then(async (r) => {
      const text = await r.text();
      try { return JSON.parse(text); }
      catch(e){ console.error('Respuesta NO JSON:', text); throw e; }
    });
}

function formatDateTime(raw) {
  if (!raw) return '-';
  const d = new Date(raw.replace(' ', 'T'));
  if (Number.isNaN(d.getTime())) return raw;
  return d.toLocaleString();
}

// ═══════════════════════════════════════════
//  Visitas
// ═══════════════════════════════════════════
function renderVisits(rows) {
  const list = document.getElementById('visits-list');
  if (!list) return;
  list.innerHTML = '';
  if (!rows || rows.length === 0) {
    const empty = document.createElement('div');
    empty.className = 'visits-empty';
    empty.textContent = 'Sin visitas.';
    list.appendChild(empty);
    return;
  }
  rows.forEach((row) => {
    const item = document.createElement('div');
    item.className = 'visit-item';
    const title = document.createElement('div');
    title.className = 'visit-title';
    title.textContent = row.school_name || '(Sin escuela)';
    const meta = document.createElement('div');
    meta.className = 'visit-meta';
    meta.textContent = `Fecha: ${formatDateTime(row.scheduled_at)} | Usuario: ${row.user_name || '-'}`;
    const actions = document.createElement('div');
    actions.className = 'visit-actions';
    if (visitsStatus === 'scheduled') {
      const startBtn = document.createElement('button');
      startBtn.className = 'btn-primary-soft';
      startBtn.textContent = 'Iniciar ruta';
      startBtn.addEventListener('click', () => selectStartLocation(row));
      const gmapsBtn = document.createElement('button');
      gmapsBtn.className = 'btn-info-soft';
      gmapsBtn.textContent = 'Abrir en Google Maps';
      gmapsBtn.addEventListener('click', () => openGoogleMapsRoute(row));
      const completeBtn = document.createElement('button');
      completeBtn.className = 'btn-success-soft';
      completeBtn.textContent = 'Completar';
      completeBtn.addEventListener('click', () => openCompleteModal(row.id));
      actions.appendChild(startBtn);
      actions.appendChild(gmapsBtn);
      actions.appendChild(completeBtn);
    } else {
      if (row.evidence_url) {
        const a = document.createElement('a');
        a.href = row.evidence_url;
        a.target = '_blank';
        a.rel = 'noopener';
        a.textContent = 'Ver evidencia';
        a.style.fontSize = '12px';
        actions.appendChild(a);
      }
    }
    item.appendChild(title);
    item.appendChild(meta);
    item.appendChild(actions);
    list.appendChild(item);
  });
}

function loadVisits() {
  const headers = { 'Accept': 'application/json' };
  if (csrfToken) headers['X-CSRF-Token'] = csrfToken;
  const url = `${visitsListUrl}?scope=${encodeURIComponent(visitsScope)}&status=${encodeURIComponent(visitsStatus)}`;
  fetch(url, { headers })
    .then(r => r.json())
    .then(data => {
      if (!data || !data.ok) return;
      const info = document.getElementById('visit-route-info');
      if (info) { info.style.display = 'none'; info.textContent = ''; }
      renderVisits(data.rows || []);
    })
    .catch(console.error);
}

function selectStartLocation(visit) {
  if (!visit || !Number.isFinite(visit.school_lat) || !Number.isFinite(visit.school_lng)) {
    alert('La escuela no tiene coordenadas para ruta.');
    return;
  }
  const useGeo = confirm('Usar ubicacion actual?');
  if (useGeo) {
    if (!navigator.geolocation) { alert('Geolocalizacion no disponible.'); return; }
    navigator.geolocation.getCurrentPosition(
      (pos) => startRouteForVisit(visit, { lat: pos.coords.latitude, lng: pos.coords.longitude }),
      () => alert('No se pudo obtener ubicacion.'),
      { enableHighAccuracy: true, timeout: 10000 }
    );
    return;
  }
  routePickMode = true;
  routePickVisit = visit;
  showSavedToast('Haz clic en el mapa para seleccionar tu ubicacion');
}

function openGoogleMapsRoute(visit) {
  if (!visit || !Number.isFinite(visit.school_lat) || !Number.isFinite(visit.school_lng)) {
    alert('La escuela no tiene coordenadas para ruta.');
    return;
  }
  const origin = visit.start_lat != null && visit.start_lng != null
    ? `${visit.start_lat},${visit.start_lng}` : '';
  const destination = `${visit.school_lat},${visit.school_lng}`;
  const url = origin
    ? `https://www.google.com/maps/dir/?api=1&origin=${encodeURIComponent(origin)}&destination=${encodeURIComponent(destination)}&travelmode=driving`
    : `https://www.google.com/maps/dir/?api=1&destination=${encodeURIComponent(destination)}&travelmode=driving`;
  window.open(url, '_blank');
}

function startRouteForVisit(visit, origin) {
  const formData = new FormData();
  formData.append('visit_id', visit.id);
  formData.append('start_lat', origin.lat);
  formData.append('start_lng', origin.lng);
  fetchJsonPostForm(visitsStartUrl, formData)
    .then((data) => {
      if (!data || !data.ok) { alert('No se pudo iniciar la ruta.'); return; }
      drawRoute(origin, { lat: visit.school_lat, lng: visit.school_lng });
      showSavedToast('Ruta iniciada');
    })
    .catch(console.error);
}

function drawRoute(origin, destination) {
  if (!directionsService || !directionsRenderer) return;
  directionsService.route({
    origin, destination,
    travelMode: google.maps.TravelMode.DRIVING
  }, (result, status) => {
    if (status === 'OK') {
      directionsRenderer.setDirections(result);
      const leg = result.routes?.[0]?.legs?.[0] ?? null;
      const info = document.getElementById('visit-route-info');
      if (leg && info) {
        info.textContent = `Ruta: ${leg.distance?.text || ''} | ${leg.duration?.text || ''}`;
        info.style.display = '';
      }
    } else { console.warn('Directions failed:', status); }
  });
}

// ═══════════════════════════════════════════
//  DOM — DOMContentLoaded
// ═══════════════════════════════════════════
document.addEventListener('DOMContentLoaded', function () {
  const form                    = document.getElementById('filtros-form');
  const mapEl                   = document.getElementById('map');
  const tablaNombresBody        = document.querySelector('#tabla-nombres tbody');
  const modal                   = document.getElementById('edit-modal');
  const filtersBody             = document.getElementById('filters-body');
  const toggleFiltersBtn        = document.getElementById('toggle-filters');
  const mapLayout               = document.getElementById('map-layout');
  const resultsPanel            = document.getElementById('results-panel');
  const toggleResultsFloatingBtn= document.getElementById('toggle-results-floating');
  const scheduleModal           = document.getElementById('schedule-modal');
  const scheduleClose           = document.getElementById('schedule-modal-close');
  const scheduleSave            = document.getElementById('schedule-save');
  const scheduleCancel          = document.getElementById('schedule-cancel');
  const completeModal           = document.getElementById('complete-modal');
  const completeClose           = document.getElementById('complete-modal-close');
  const completeSave            = document.getElementById('complete-save');
  const completeCancel          = document.getElementById('complete-cancel');
  const visitsModal             = document.getElementById('visits-modal');
  const visitsModalClose        = document.getElementById('visits-modal-close');
  const visitsScopeMine         = document.getElementById('visits-scope-mine');
  const visitsScopeAll          = document.getElementById('visits-scope-all');
  const visitsStatusScheduled   = document.getElementById('visits-status-scheduled');
  const visitsStatusCompleted   = document.getElementById('visits-status-completed');
  const visitsRefresh           = document.getElementById('visits-refresh');

  const expandIconUrl   = "<?= $this->Url->build('/img/ins.png',    ['escape' => false]) ?>?v=<?= (string)@filemtime(WWW_ROOT . 'img' . DS . 'ins.png') ?>";
  const collapseIconUrl = "<?= $this->Url->build('/img/desins.png', ['escape' => false]) ?>?v=<?= (string)@filemtime(WWW_ROOT . 'img' . DS . 'desins.png') ?>";

  const btnBuscar      = document.getElementById('btn-buscar');
  const btnLimpiar     = document.getElementById('btn-limpiar');
  const contador       = document.getElementById('contador');
  const territoriosChk = document.getElementById('territorios');
  const estadoSel      = document.getElementById('estado');
  const municipioSel   = document.getElementById('municipio');

  // ── KPI elementos ──
  const kpiCollapseBtn  = document.getElementById('kpi-collapse-btn');
  const kpiBody         = document.getElementById('kpi-body');
  const metaEditTrigger = document.getElementById('meta-edit-trigger');
  const metaInlineForm  = document.getElementById('meta-inline-form');
  const metaInlineInput = document.getElementById('meta-inline-input');
  const metaInlineSave  = document.getElementById('meta-inline-save');
  const metaInlineCancel= document.getElementById('meta-inline-cancel');

  // ── KPI: colapsar/expandir ──
  if (kpiCollapseBtn && kpiBody) {
    kpiCollapseBtn.addEventListener('click', () => {
      const collapsed = kpiBody.style.display === 'none';
      kpiBody.style.display = collapsed ? '' : 'none';
      kpiCollapseBtn.textContent = collapsed ? '▲' : '▼';
      kpiCollapseBtn.title = collapsed ? 'Colapsar' : 'Expandir';
    });
  }

  // ── KPI: editar meta inline ──
  if (metaEditTrigger && metaInlineForm) {
    metaEditTrigger.addEventListener('click', () => {
      metaInlineInput.value = currentMetaValue || '';
      metaInlineForm.classList.toggle('open');
      if (metaInlineForm.classList.contains('open')) metaInlineInput.focus();
    });
  }

  if (metaInlineCancel) {
    metaInlineCancel.addEventListener('click', () => {
      metaInlineForm.classList.remove('open');
    });
  }

  if (metaInlineSave) {
    metaInlineSave.addEventListener('click', () => {
      const valor = parseFloat(metaInlineInput.value);
      if (isNaN(valor) || valor < 0) {
        metaInlineInput.style.borderColor = '#dc3545';
        setTimeout(() => { metaInlineInput.style.borderColor = '#8b1d2c'; }, 1200);
        return;
      }
      metaInlineSave.textContent = '...';
      metaInlineSave.disabled = true;

      guardarMeta(valor)
        .then((data) => {
          if (!data || !data.ok) {
            showSavedToast('✖ No se pudo guardar la meta');
            return;
          }
          metaInlineForm.classList.remove('open');
          // Recalcula KPIs con la nueva meta
          cargarKPIs(new FormData(form));
          showSavedToast('✔ Meta actualizada');
        })
        .catch(console.error)
        .finally(() => {
          metaInlineSave.textContent = 'Guardar';
          metaInlineSave.disabled = false;
        });
    });
  }

  // Enter en input meta
  if (metaInlineInput) {
    metaInlineInput.addEventListener('keydown', (e) => {
      if (e.key === 'Enter') metaInlineSave.click();
      if (e.key === 'Escape') metaInlineCancel.click();
    });
  }

  // ── Tabs ──
  const tabs = document.querySelectorAll('.tab');
  const panels = {
    ubicacion: document.getElementById('panel-ubicacion'),
    escuelas:  document.getElementById('panel-escuelas'),
    comercial: document.getElementById('panel-comercial'),
  };
  tabs.forEach(t => t.addEventListener('click', () => {
    tabs.forEach(x => x.classList.remove('active'));
    t.classList.add('active');
    Object.values(panels).forEach(p => p.classList.add('hidden'));
    panels[t.dataset.tab].classList.remove('hidden');
  }));

  // ── Botón Agenda en nav ──
  const injectVisitsNavButton = () => {
    const navLinks = document.querySelector('.top-nav-links');
    if (!navLinks || document.getElementById('agenda-visitas-nav-btn')) return;
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.id = 'agenda-visitas-nav-btn';
    btn.className = 'agenda-nav-btn';
    btn.textContent = 'Agenda';
    btn.addEventListener('click', () => {
      if (!visitsModal) return;
      visitsModal.style.display = 'flex';
      document.body.style.overflow = 'hidden';
      loadVisits();
    });
    const schoolsLink = Array.from(navLinks.querySelectorAll('a'))
      .find((a) => (a.textContent || '').trim().toLowerCase().includes('escuelas'));
    if (schoolsLink && schoolsLink.nextSibling) {
      navLinks.insertBefore(btn, schoolsLink.nextSibling);
    } else {
      navLinks.appendChild(btn);
    }
  };

  const closeVisitsModal = () => {
    if (!visitsModal) return;
    visitsModal.style.display = 'none';
    document.body.style.overflow = '';
  };

  injectVisitsNavButton();

  // ── Layout helpers ──
  function resizeMap() {
    if (!map || !google?.maps) return;
    const center = map.getCenter();
    google.maps.event.trigger(map, 'resize');
    if (center) map.setCenter(center);
  }

  function syncResultsPanelHeight() {
    if (!mapEl || !resultsPanel || resultsPanel.classList.contains('hidden')) return;
    const mapHeight = Math.round(mapEl.getBoundingClientRect().height);
    if (mapHeight > 0) resultsPanel.style.height = `${mapHeight}px`;
  }

  function setCollapseButtonIcon(button, collapsed, showTitle, hideTitle) {
    if (!button) return;
    button.textContent = '';
    button.title = collapsed ? showTitle : hideTitle;
    const img = document.createElement('img');
    img.src = collapsed ? expandIconUrl : collapseIconUrl;
    img.alt = collapsed ? showTitle : hideTitle;
    img.addEventListener('error', () => { button.textContent = collapsed ? '+' : '-'; });
    button.appendChild(img);
  }

  function setFiltersCollapsed(collapsed) {
    if (!filtersBody || !toggleFiltersBtn) return;
    filtersBody.classList.toggle('hidden', collapsed);
    setCollapseButtonIcon(toggleFiltersBtn, collapsed, 'Mostrar filtros', 'Colapsar filtros');
  }

  function setResultsCollapsed(collapsed) {
    if (!mapLayout || !resultsPanel || !toggleResultsFloatingBtn) return;
    resultsPanel.classList.toggle('hidden', collapsed);
    mapLayout.classList.toggle('results-collapsed', collapsed);
    setCollapseButtonIcon(toggleResultsFloatingBtn, collapsed, 'Mostrar resultados', 'Ocultar resultados');
    if (!collapsed) syncResultsPanelHeight();
    setTimeout(resizeMap, 60);
  }

  // ── Filtros ──
  function actualizarContador() {
    const formData = new FormData(form);
    fetchJsonPost(contarUrl, formData)
      .then(data => { contador.textContent = (data.total ?? 0); })
      .catch(console.error);
  }

  function resetMunicipios() {
    municipioSel.innerHTML = '';
    const opt = document.createElement('option');
    opt.value = '';
    opt.textContent = 'Cualquiera';
    municipioSel.appendChild(opt);
  }

  function cargarMunicipios(estadoId) {
    resetMunicipios();
    if (!estadoId) { actualizarContador(); return; }
    const headers = { 'Accept': 'application/json' };
    if (csrfToken) headers['X-CSRF-Token'] = csrfToken;
    fetch(municipiosUrlTpl.replace('__ID__', encodeURIComponent(estadoId)), { headers })
      .then(r => r.json())
      .then(data => {
        const municipios = data.municipios || {};
        Object.keys(municipios).forEach(id => {
          const opt = document.createElement('option');
          opt.value = id;
          opt.textContent = municipios[id];
          municipioSel.appendChild(opt);
        });
        actualizarContador();
      })
      .catch(console.error);
  }

  estadoSel.addEventListener('change', () => cargarMunicipios(estadoSel.value));
  municipioSel.addEventListener('change', actualizarContador);

  form.addEventListener('change', (e) => {
    if (e.target.id !== 'estado' && e.target.id !== 'municipio') actualizarContador();
    refreshTerritoriesOverlay(form, territoriosChk);
  });

  // ── Buscar ──
  btnBuscar.addEventListener('click', function () {
    const formData = new FormData(form);
    formData.append('offset', 0);

    fetchJsonPost(filtrarUrl, formData)
      .then(data => {
        contador.textContent = (data.total ?? 0);
        tablaNombresBody.innerHTML = '';
        clearDataLayer();

        const rows = data.rows || [];
        rows.forEach(row => {
          const lat = parseFloat(row.lat);
          const lng = parseFloat(row.lng);
          if (!Number.isFinite(lat) || !Number.isFinite(lng)) return;

          const feature = new google.maps.Data.Feature({
            geometry: new google.maps.Data.Point({ lat, lng }),
            properties: {
              id: row.id,
              active: false,
              editing: false,
              nombre: row.nombre ?? '',
              estado: row.estado ?? '',
              municipio: row.municipio ?? '',
              user: row.user ?? '',
              tipo: row.tipo ?? '',
              cct: row.cct ?? '',
              sector: row.sector ?? '',
              turno: row.turno ?? '',
              num_alumnos: row.num_alumnos ?? '',
              grupos: row.grupos ?? '',
              nombre_contacto: row.nombre_contacto ?? '',
              telefono_contacto: row.telefono_contacto ?? '',
              notas: row.notas ?? '',
              editorial_actual: row.editorial_actual ?? '',
              venta_montenegro: row.venta_montenegro ?? '',
              competencia: row.competencia ?? '',
              presupuesto: row.presupuesto ?? '',
              verificada: row.verificada ?? '',
              estatus: row.estatus ?? ''
            }
          });

          dataLayer.add(feature);

          const tr = document.createElement('tr');
          const td = document.createElement('td');
          td.textContent = row.nombre ?? '';
          td.style.cursor = 'pointer';
          td.style.padding = '6px';
          td.addEventListener('click', () => activarEscuela(feature));
          tr.appendChild(td);
          tablaNombresBody.appendChild(tr);
        });

        fitToDataLayer();
        refreshTerritoriesOverlay(form, territoriosChk);

        // ── Actualizar KPIs con los mismos filtros ──
        cargarKPIs(new FormData(form));
      })
      .catch(console.error);
  });

  // ── Limpiar ──
  btnLimpiar.addEventListener('click', function () {
    form.reset();
    resetMunicipios();
    actualizarContador();
    tablaNombresBody.innerHTML = '';
    clearDataLayer();
    refreshTerritoriesOverlay(form, territoriosChk);
    cargarKPIs(new FormData(form));
  });

  // ── Modal editar ──
  if (modal) {
    modal.addEventListener('click', (e) => { if (e.target === modal) closeEditModal(); });
  }
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal && modal.style.display === 'flex') closeEditModal();
  });
  const editIframe = document.getElementById('edit-modal-iframe');
  if (editIframe) editIframe.addEventListener('load', () => startEditModalAutoResize());

  // ── Visitas ──
  function updateVisitsButtons() {
    if (visitsScopeMine)       visitsScopeMine.classList.toggle('btn-active', visitsScope === 'mine');
    if (visitsScopeAll)        visitsScopeAll.classList.toggle('btn-active', visitsScope === 'all');
    if (visitsStatusScheduled) visitsStatusScheduled.classList.toggle('btn-active', visitsStatus === 'scheduled');
    if (visitsStatusCompleted) visitsStatusCompleted.classList.toggle('btn-active', visitsStatus === 'completed');
  }

  if (visitsScopeMine) visitsScopeMine.addEventListener('click', () => { visitsScope = 'mine'; updateVisitsButtons(); loadVisits(); });
  if (visitsScopeAll)  visitsScopeAll.addEventListener('click',  () => { visitsScope = 'all';  updateVisitsButtons(); loadVisits(); });
  if (visitsStatusScheduled) visitsStatusScheduled.addEventListener('click', () => { visitsStatus = 'scheduled'; updateVisitsButtons(); loadVisits(); });
  if (visitsStatusCompleted) visitsStatusCompleted.addEventListener('click', () => { visitsStatus = 'completed'; updateVisitsButtons(); loadVisits(); });
  if (visitsRefresh)   visitsRefresh.addEventListener('click', loadVisits);
  if (visitsModalClose)visitsModalClose.addEventListener('click', closeVisitsModal);
  if (visitsModal)     visitsModal.addEventListener('click', (e) => { if (e.target === visitsModal) closeVisitsModal(); });

  if (scheduleSave) scheduleSave.addEventListener('click', () => {
    const dtEl    = document.getElementById('schedule-datetime');
    const notesEl = document.getElementById('schedule-notes');
    const dt = dtEl ? dtEl.value : '';
    if (!scheduleSchoolId || !dt) { alert('Selecciona fecha y hora.'); return; }
    const formData = new FormData();
    formData.append('school_id', scheduleSchoolId);
    formData.append('scheduled_at', dt);
    if (notesEl && notesEl.value) formData.append('notes', notesEl.value);
    fetchJsonPostForm(visitsScheduleUrl, formData)
      .then((data) => {
        if (!data || !data.ok) { alert('No se pudo agendar.'); return; }
        closeScheduleModal();
        showSavedToast('Visita agendada');
        loadVisits();
      })
      .catch(console.error);
  });

  if (scheduleCancel) scheduleCancel.addEventListener('click', closeScheduleModal);
  if (scheduleClose)  scheduleClose.addEventListener('click',  closeScheduleModal);
  if (scheduleModal)  scheduleModal.addEventListener('click',  (e) => { if (e.target === scheduleModal) closeScheduleModal(); });

  if (completeSave) completeSave.addEventListener('click', () => {
    if (!completeVisitId) return;
    const notesEl = document.getElementById('complete-notes');
    const fileEl  = document.getElementById('complete-evidence');
    const formData = new FormData();
    formData.append('visit_id', completeVisitId);
    if (notesEl && notesEl.value) formData.append('completion_notes', notesEl.value);
    const file = fileEl && fileEl.files ? fileEl.files[0] : null;
    if (file) {
      if (file.size > 10 * 1024 * 1024) { alert('Archivo mayor a 10MB.'); return; }
      formData.append('evidence', file);
    }
    fetchJsonPostForm(visitsCompleteUrl, formData)
      .then((data) => {
        if (!data || !data.ok) { alert('No se pudo completar.'); return; }
        closeCompleteModal();
        showSavedToast('Visita completada');
        loadVisits();
      })
      .catch(console.error);
  });

  if (completeCancel) completeCancel.addEventListener('click', closeCompleteModal);
  if (completeClose)  completeClose.addEventListener('click',  closeCompleteModal);
  if (completeModal)  completeModal.addEventListener('click',  (e) => { if (e.target === completeModal) closeCompleteModal(); });

  if (toggleFiltersBtn) {
    toggleFiltersBtn.addEventListener('click', () => {
      const collapsed = !filtersBody || !filtersBody.classList.contains('hidden');
      setFiltersCollapsed(collapsed);
    });
  }

  if (toggleResultsFloatingBtn) {
    toggleResultsFloatingBtn.addEventListener('click', () => {
      const collapsed = !resultsPanel || !resultsPanel.classList.contains('hidden');
      setResultsCollapsed(collapsed);
    });
  }

  window.addEventListener('resize', () => {
    syncResultsPanelHeight();
    resizeEditModalToContent();
  });

  // ── Inicialización ──
  resetMunicipios();
  actualizarContador();
  setFiltersCollapsed(false);
  setResultsCollapsed(false);
  syncResultsPanelHeight();
  setTimeout(syncResultsPanelHeight, 120);
  updateVisitsButtons();
  loadVisits();
  refreshTerritoriesOverlay(form, territoriosChk);
  cargarKPIs(new FormData(form)); // ← carga KPIs al entrar
});

// ── Mensajes desde iframe ──
window.addEventListener('message', (ev) => {
  if (ev.origin !== window.location.origin || !ev.data) return;

  if (ev.data.type === 'school:modalHeight') {
    const iframe = document.getElementById('edit-modal-iframe');
    const modal  = document.getElementById('edit-modal');
    const reported = Number(ev.data.height || 0);
    if (iframe && modal && modal.style.display === 'flex' && Number.isFinite(reported) && reported > 0) {
      const minHeight = 260;
      const maxHeight = Math.max(minHeight, Math.floor(window.innerHeight * 0.84));
      const targetHeight = Math.min(Math.max(reported, minHeight), maxHeight);
      const currentHeight = parseInt(iframe.style.height || '0', 10) || 0;
      if (Math.abs(currentHeight - targetHeight) >= 2) iframe.style.height = `${targetHeight}px`;
    }
    return;
  }

  if (ev.data.type !== 'school:updated') return;

  closeEditModal();
  showSavedToast('✔ Escuela actualizada correctamente');

  const p = ev.data.payload || {};
  if (activeFeature && p.id && String(activeFeature.getProperty('id')) === String(p.id)) {
    Object.keys(p).forEach((k) => activeFeature.setProperty(k, p[k]));
    const pos = activeFeature.getGeometry().get();
    infoWindow.setContent(renderInfo(activeFeature));
    infoWindow.setPosition(pos);
    infoWindow.open(map);
  }
});
</script>
<!-- ===== CHATBOT WIDGET ===== -->

<script
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCs_TXbIcZnT4GdQl_QnswuGQNUsog2jJI&callback=initMap"
  async
  defer></script>
