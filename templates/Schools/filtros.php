<?php
// templates/Schools/filtros.php
?>

<?= $this->Form->create(null, ['id' => 'filtros-form']) ?>
<?= $this->Form->hidden('mode', ['value' => $mode ?? 'admin']) ?>

<style>
  .wrap{ background:#fff; border:1px solid #e6e6e6; border-radius:14px; padding:14px; position:relative; }
  .topbar{ display:flex; justify-content:space-between; align-items:center; margin-bottom:10px; }
  .tabs{ display:flex; gap:14px; align-items:center; }
  .tab{ padding:8px 14px; border-radius:999px; cursor:pointer; color:#8b1d2c; }
  .tab.active{ background:#8b1d2c; color:#fff; }
  .actions button, .actions a{ margin-left:8px; }

  .panel{ border:1px solid #eee; border-radius:12px; padding:14px; background:#fafafa; position:relative; }
  .grid{ display:grid; grid-template-columns: repeat(12, 1fr); gap:12px; }
  .col-3{ grid-column: span 3; }
  .col-4{ grid-column: span 4; }
  .col-6{ grid-column: span 6; }
  .col-12{ grid-column: span 12; }
  @media(max-width: 1100px){ .col-3,.col-4,.col-6{ grid-column: span 12; } }

  .hidden{ display:none; }
  .corner-btn{
    position:absolute;
    top:8px;
    right:8px;
    width:28px;
    height:28px;
    border-radius:8px;
    border:1px solid #e6e6e6;
    background:#f8f9fa;
    color:#8b1d2c;
    font-size:14px;
    line-height:1;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    cursor:pointer;
    z-index:3;
  }
  .corner-btn.map-corner{
    top:12px;
    right:12px;
    width:34px;
    height:34px;
    border-radius:10px;
    font-size:16px;
    z-index:999; /* encima del mapa y controles */
  }
  
  /* deja espacio a los controles de Google (si estorba) */
  @media (max-width: 768px){
    .corner-btn.map-corner{
      top:12px;
      right:12px;
    }
  }
  .map-layout{
    display:grid;
    grid-template-columns: 1fr 420px;
    gap:12px;
    margin-top:14px;
  }
  .map-layout.results-collapsed{
    grid-template-columns: 1fr;
  }
  .visits-panel{
    margin-top:12px;
    border-top:1px dashed #e6e6e6;
    padding-top:10px;
  }
  .visits-toolbar{
    display:flex;
    gap:8px;
    align-items:center;
    justify-content:space-between;
    margin-bottom:8px;
  }
  .visits-toolbar .btn-group{
    display:flex;
    gap:6px;
    flex-wrap:wrap;
  }
  .visit-item{
    border:1px solid #eee;
    border-radius:10px;
    padding:8px;
    margin-bottom:8px;
    background:#fafafa;
  }
  .visit-title{ font-weight:700; font-size:13px; }
  .visit-meta{ font-size:12px; color:#555; margin:4px 0 6px 0; }
  .visit-actions{ display:flex; gap:6px; flex-wrap:wrap; }
  .visit-actions button{
    border:0;
    border-radius:8px;
    padding:5px 8px;
    font-size:12px;
    cursor:pointer;
  }
  .btn-soft{ background:#e9ecef; color:#333; }
  .btn-primary-soft{ background:#8b1d2c; color:#fff; }
  .btn-success-soft{ background:#198754; color:#fff; }
  .btn-info-soft{ background:#0d6efd; color:#fff; }
  .btn-warning-soft{ background:#fd7e14; color:#fff; }
  .btn-active{ background:#8b1d2c !important; color:#fff !important; }
  .visits-empty{ font-size:12px; color:#666; }
  .route-info{
    font-size:12px;
    color:#333;
    background:#f1f3f5;
    border:1px solid #e6e6e6;
    border-radius:8px;
    padding:6px 8px;
    margin-bottom:8px;
  }
  .modal-backdrop{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.45);
    display:none;
    align-items:center;
    justify-content:center;
    z-index:10000;
  }
  .modal-card{
    background:#fff;
    width:min(520px, 92vw);
    border-radius:12px;
    box-shadow:0 10px 30px rgba(0,0,0,.25);
    overflow:hidden;
  }
  .modal-head{
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:10px 14px;
    border-bottom:1px solid #eee;
  }
  .modal-body{ padding:12px 14px; }
  .modal-actions{
    display:flex;
    gap:8px;
    justify-content:flex-end;
    padding:10px 14px;
    border-top:1px solid #eee;
  }
  .modal-actions button{
    border:0;
    border-radius:8px;
    padding:6px 10px;
    cursor:pointer;
  }

  /* Responsive: sin mover ni ocultar elementos */
  @media (max-width: 1100px){
    .topbar{ flex-wrap:wrap; gap:8px; }
    .tabs{ flex-wrap:wrap; gap:8px; }
    .actions{ display:flex; flex-wrap:wrap; gap:8px; }
    .actions button, .actions a{ margin-left:0; }
  }
  @media (max-width: 1000px){
    .map-layout{ grid-template-columns: 1fr; }
    #results-panel{ width:100%; }
  }
  @media (max-width: 768px){
    #map{ height:60vh; min-height:360px; }
    #tabla-nombres{ display:block; overflow-x:auto; }
    #tabla-nombres th, #tabla-nombres td{ white-space:nowrap; }
  }
</style>

<div class="wrap">
  <button type="button" id="toggle-filters" class="corner-btn" title="Colapsar filtros">-</button>
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
            'label'=>'Editorial actual',
            'id'=>'editorial_actual'
          ]) ?>
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
  <div id="map-wrap" style="position:relative;">
    <div id="map" style="height:760px; border:1px solid #e6e6e6; border-radius:12px;"></div>
  </div>

  <div id="results-panel" style="border:1px solid #e6e6e6; border-radius:12px; padding:12px; background:#fff; position:relative;">
    <button type="button" id="toggle-results-floating" class="corner-btn map-corner" title="Ocultar resultados">-</button>

    <h5 style="margin:0 0 10px 0;">Resultados</h5>
    <table id="tabla-nombres" style="width:100%; border-collapse:collapse;" border="1">
      <thead>
        <tr><th>Escuela</th></tr>
      </thead>
      <tbody></tbody>
    </table>

    <div id="visits-panel" class="visits-panel">
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
  <div style="background:#fff; width:min(980px, 92vw); height:min(760px, 92vh); border-radius:12px; box-shadow:0 10px 30px rgba(0,0,0,.25); overflow:hidden; display:flex; flex-direction:column;">
    <div style="display:flex; align-items:center; justify-content:space-between; padding:10px 14px; border-bottom:1px solid #eee;">
      <strong>Editar escuela</strong>
      <button type="button" id="edit-modal-close" style="border:0; background:#eee; border-radius:8px; padding:6px 10px; cursor:pointer;">Cerrar</button>
    </div>
    <iframe id="edit-modal-iframe" title="Editar escuela" style="width:100%; height:100%; border:0;"></iframe>
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
let activeFeature = null;
let infoWindow;
let directionsService;
let directionsRenderer;

let routePickMode = false;
let routePickVisit = null;

// Marker draggable (solo en modo mover)
let editMarker = null;

// coords pendientes y coords originales (para guardar/cancelar)
let pendingCoords = null;     // { id, lat, lng }
let originalCoords = null;    // { lat, lng }

// CSRF global (para todos los fetch)
const csrfToken = "<?= h($this->request->getAttribute('csrfToken') ?? '') ?>";

// URLs
const filtrarUrl  = "<?= $this->Url->build(['controller'=>'Schools','action'=>'filtrarSchools'], ['escape'=>false]) ?>";
const contarUrl   = "<?= $this->Url->build(['controller'=>'Schools','action'=>'contarFiltrado'], ['escape'=>false]) ?>";
const guardarUrl  = "<?= $this->Url->build(['controller'=>'Schools','action'=>'guardarCoordenadas'], ['escape'=>false]) ?>";
const editUrlTpl  = "<?= $this->Url->build(['controller'=>'Schools','action'=>'editModal','__ID__'], ['escape'=>false]) ?>";
const municipiosUrlTpl = "<?= $this->Url->build(['controller'=>'Schools','action'=>'municipiosPorEstado','__ID__'], ['escape' => false]) ?>";
const materialsUrlTpl = "<?= $this->Url->build('/schools/__ID__/materials-manager', ['escape'=>false]) ?>";
const visitsScheduleUrl = "<?= $this->Url->build(['controller'=>'Visits','action'=>'schedule'], ['escape'=>false]) ?>";
const visitsListUrl = "<?= $this->Url->build(['controller'=>'Visits','action'=>'listVisits'], ['escape'=>false]) ?>";
const visitsStartUrl = "<?= $this->Url->build(['controller'=>'Visits','action'=>'startRoute'], ['escape'=>false]) ?>";
const visitsCompleteUrl = "<?= $this->Url->build(['controller'=>'Visits','action'=>'complete'], ['escape'=>false]) ?>";

let visitsScope = 'mine';
let visitsStatus = 'scheduled';
let scheduleSchoolId = null;
let scheduleSchoolName = '';
let completeVisitId = null;

// ===== Colores por estatus (GLOBAL) =====
function getStatusColor(estatus) {
  switch (String(estatus)) {
    case 'noAtendida':        return '#6c757d'; // gris
    case 'escuelaPromocion':  return '#0dcaf0'; // cyan
    case 'ventaConfirmada':   return '#198754'; // verde
    case 'prohibicion':       return '#dc3545'; // rojo
    case 'ventaMarcas':       return '#fd7e14'; // naranja
    default:                  return '#1976d2'; // azul
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

// ====== GOOGLE MAP INIT ======
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

  // estilo dinámico por feature
  dataLayer.setStyle(feature => {
    if (feature.getProperty('editing')) {
      return { visible: false };
    }
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

// ===== InfoWindow =====
function renderInfo(f) {
  const ok = (f.getProperty('verificada') === 1 || f.getProperty('verificada') === true || f.getProperty('verificada') === 'Sí');
  const badge = ok ? '✔️' : '❌';

  const id = f.getProperty('id') ?? '';

  const nombre = f.getProperty('nombre') ?? '';
  const estado = f.getProperty('estado') ?? '';
  const municipio = f.getProperty('municipio') ?? '';
  const cct = f.getProperty('cct') ?? '';
  const tipo = f.getProperty('tipo') ?? '';
  const user = f.getProperty('user') ?? '';
  const sector = f.getProperty('sector') ?? '';
  const turno = f.getProperty('turno') ?? '';
  const alumnos = f.getProperty('num_alumnos') ?? '';
  const grupos = f.getProperty('grupos') ?? '';
  const nombreContacto = f.getProperty('nombre_contacto') ?? '';
  const telefonoContacto = f.getProperty('telefono_contacto') ?? '';
  const notas = f.getProperty('notas') ?? '';
  const editorialActual = f.getProperty('editorial_actual') ?? '';
  const ventaMontenegro = f.getProperty('venta_montenegro') ?? '';
  const competencia = f.getProperty('competencia') ?? '';
  const presupuesto = f.getProperty('presupuesto') ?? '';

  const estatus = f.getProperty('estatus') ?? '';
  const estatusTxt = getEstatusText(estatus);
  const statusColor = getStatusColor(estatus);

  const canSave = !!pendingCoords && String(pendingCoords.id) === String(id);

  return `
    <div style="position:relative; font-size:13px; max-width:260px; padding-right:18px;">
      <div style="position:absolute; top:0; right:0; font-size:14px;">${badge}</div>
      <div style="font-weight:700; margin-bottom:6px;">${nombre}</div>

      <div style="display:inline-flex;align-items:center;gap:6px;margin:6px 0 8px 0;">
        <span style="display:inline-block;width:10px;height:10px;border-radius:999px;background:${statusColor};"></span>
        <span style="padding:2px 8px;border-radius:999px;background:rgba(0,0,0,.06);font-weight:600;">
          ${estatusTxt}
        </span>
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
      <div><b>Editorial actual:</b> ${editorialActual}</div>
      <div><b>Venta Montenegro:</b> ${ventaMontenegro}</div>
      <div><b>Competencia:</b> ${competencia}</div>
      <div><b>Presupuesto:</b> ${presupuesto}</div>

      <div style="margin-top:10px; display:flex; gap:8px; flex-wrap:wrap;">
        <button type="button"
          onclick="window._openScheduleModal()"
          style="background:#fd7e14;color:#fff;border:0;border-radius:8px;padding:6px 10px;cursor:pointer;">
          Agendar visita
        </button>

        <button type="button"
          onclick="window._movePinActive()"
          style="background:#8b1d2c;color:#fff;border:0;border-radius:8px;padding:6px 10px;cursor:pointer;">
          Mover pin
        </button>

        <button type="button"
          onclick="window._openEditModal()"
          style="background:#0d6efd;color:#fff;border:0;border-radius:8px;padding:6px 10px;cursor:pointer;">
          Editar escuela
        </button>
<button type="button"
  onclick="window._openMaterials()"
  style="background:#8b1d2c;color:#fff;border:0;border-radius:8px;padding:6px 10px;cursor:pointer;">
  Gestor de materiales
</button>

        <button type="button"
          onclick="window._savePinActive()"
          ${canSave ? '' : 'disabled'}
          style="background:#198754;color:#fff;border:0;border-radius:8px;padding:6px 10px;cursor:pointer; opacity:${canSave ? '1' : '.5'};">
          Guardar ubicación
        </button>

        <button type="button"
          onclick="window._cancelMovePin()"
          style="background:#6c757d;color:#fff;border:0;border-radius:8px;padding:6px 10px;cursor:pointer;">
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

  const url = materialsUrlTpl.replace('__ID__', encodeURIComponent(id));
  window.open(url, '_blank');
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

  if (modal) {
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
  }
};
function activarEscuela(feature) {
  if (!feature) return;

  // Limpia edición pendiente si cambias de escuela
  pendingCoords = null;
  originalCoords = null;
  removeEditMarker();

  if (activeFeature) {
    activeFeature.setProperty('active', false);
    activeFeature.setProperty('editing', false);
  }

  activeFeature = feature;
  feature.setProperty('active', true);

  const pos = feature.getGeometry().get();
  map.panTo(pos);
  map.setZoom(Math.max(map.getZoom(), 9));

  infoWindow.setContent(renderInfo(feature));
  infoWindow.setPosition(pos);
  infoWindow.open(map);
}

// ======= MOVER PIN (NO GUARDA HASTA BOTÓN) =======
function enableMovePin(feature) {
  if (!feature) return;

  removeEditMarker();

  const pos = feature.getGeometry().get();
  originalCoords = { lat: pos.lat(), lng: pos.lng() };
  pendingCoords = null;

  // ocultamos punto del DataLayer para no ver doble
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

    // actualiza el DataLayer (solo visual)
    feature.setGeometry(new google.maps.Data.Point({ lat, lng }));

    // guarda coords en “pendiente”
    pendingCoords = { id: feature.getProperty('id'), lat, lng };

    // reabre el popup para habilitar Guardar
    infoWindow.setContent(renderInfo(feature));
    infoWindow.setPosition({ lat, lng });
    infoWindow.open(map);
  });
}

function removeEditMarker() {
  if (editMarker) {
    editMarker.setMap(null);
    editMarker = null;
  }
}

// POST al endpoint guardarCoordenadas
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

      if (!r.ok) {
        console.error('Guardar coords HTTP', r.status, j || text);
        return false;
      }
      return !!(j && j.ok);
    });
}

// Handlers globales para botones del popup
window._movePinActive = function () {
  if (!activeFeature) return;
  enableMovePin(activeFeature);
};

window._savePinActive = async function () {
  if (!activeFeature) return;
  if (!pendingCoords) return;

  const id = activeFeature.getProperty('id');
  if (String(pendingCoords.id) !== String(id)) return;

  const ok = await saveCoords(pendingCoords.id, pendingCoords.lat, pendingCoords.lng);
  if (!ok) {
    console.warn('No se pudo guardar la ubicación');
    return;
  }

  showSavedToast('✔ Coordenadas guardadas correctamente');

  // fin edición
  pendingCoords = null;
  originalCoords = null;
  activeFeature.setProperty('editing', false);
  removeEditMarker();

  const pos = activeFeature.getGeometry().get();
  infoWindow.setContent(renderInfo(activeFeature));
  infoWindow.setPosition(pos);
  infoWindow.open(map);
};

window._cancelMovePin = function () {
  if (!activeFeature) return;

  // volver a coords originales si existían
  if (originalCoords) {
    activeFeature.setGeometry(new google.maps.Data.Point({
      lat: originalCoords.lat,
      lng: originalCoords.lng
    }));
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

// ===== Modal editar =====
window._openEditModal = function () {
  if (!activeFeature) return;
  const id = activeFeature.getProperty('id');
  if (!id) return;

  const modal = document.getElementById('edit-modal');
  const iframe = document.getElementById('edit-modal-iframe');
  if (!modal || !iframe) return;

  const base = editUrlTpl.replace('__ID__', encodeURIComponent(id));
  const url = base.includes('?') ? `${base}&layout=ajax` : `${base}?layout=ajax`;

  iframe.src = url;
  modal.style.display = 'flex';
  document.body.style.overflow = 'hidden';
};


function closeEditModal() {
  const modal = document.getElementById('edit-modal');
  const iframe = document.getElementById('edit-modal-iframe');
  if (!modal || !iframe) return;

  iframe.src = 'about:blank';
  modal.style.display = 'none';
  document.body.style.overflow = '';
}

function openCompleteModal(visitId) {
  completeVisitId = visitId;
  const modal = document.getElementById('complete-modal');
  const notesEl = document.getElementById('complete-notes');
  const fileEl = document.getElementById('complete-evidence');
  if (notesEl) notesEl.value = '';
  if (fileEl) fileEl.value = '';
  if (modal) {
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
  }
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

// ===== utilidades =====
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

  dataLayer.forEach(f => {
    const p = f.getGeometry().get();
    bounds.extend(p);
    has = true;
  });

  if (has) map.fitBounds(bounds);
}

function showSavedToast(msg = '✔ Ubicación guardada correctamente') {
  const t = document.getElementById('toast-ok');
  if (!t) return;

  t.textContent = msg;
  t.style.opacity = '1';

  clearTimeout(window.__toastTimer);
  window.__toastTimer = setTimeout(() => {
    t.style.opacity = '0';
  }, 2500);
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
      if (info) {
        info.style.display = 'none';
        info.textContent = '';
      }
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
    if (!navigator.geolocation) {
      alert('Geolocalizacion no disponible.');
      return;
    }
    navigator.geolocation.getCurrentPosition((pos) => {
      startRouteForVisit(visit, { lat: pos.coords.latitude, lng: pos.coords.longitude });
    }, () => {
      alert('No se pudo obtener ubicacion.');
    }, { enableHighAccuracy: true, timeout: 10000 });
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
    ? `${visit.start_lat},${visit.start_lng}`
    : '';
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
      if (!data || !data.ok) {
        alert('No se pudo iniciar la ruta.');
        return;
      }
      drawRoute(origin, { lat: visit.school_lat, lng: visit.school_lng });
      showSavedToast('Ruta iniciada');
    })
    .catch(console.error);
}

function drawRoute(origin, destination) {
  if (!directionsService || !directionsRenderer) return;
  directionsService.route({
    origin,
    destination,
    travelMode: google.maps.TravelMode.DRIVING
  }, (result, status) => {
    if (status === 'OK') {
      directionsRenderer.setDirections(result);
      const leg = result.routes && result.routes[0] && result.routes[0].legs
        ? result.routes[0].legs[0]
        : null;
      const info = document.getElementById('visit-route-info');
      if (leg && info) {
        const dist = leg.distance ? leg.distance.text : '';
        const dur = leg.duration ? leg.duration.text : '';
        info.textContent = `Ruta: ${dist} | ${dur}`;
        info.style.display = '';
      }
    } else {
      console.warn('Directions failed:', status);
    }
  });
}

// ===== DOM =====
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('filtros-form');
  const tablaNombresBody = document.querySelector('#tabla-nombres tbody');
  const modal = document.getElementById('edit-modal');
  const modalClose = document.getElementById('edit-modal-close');
  const filtersBody = document.getElementById('filters-body');
  const toggleFiltersBtn = document.getElementById('toggle-filters');
  const mapLayout = document.getElementById('map-layout');
  const resultsPanel = document.getElementById('results-panel');
  const toggleResultsFloatingBtn = document.getElementById('toggle-results-floating');
  const scheduleModal = document.getElementById('schedule-modal');
  const scheduleClose = document.getElementById('schedule-modal-close');
  const scheduleSave = document.getElementById('schedule-save');
  const scheduleCancel = document.getElementById('schedule-cancel');
  const completeModal = document.getElementById('complete-modal');
  const completeClose = document.getElementById('complete-modal-close');
  const completeSave = document.getElementById('complete-save');
  const completeCancel = document.getElementById('complete-cancel');
  const visitsScopeMine = document.getElementById('visits-scope-mine');
  const visitsScopeAll = document.getElementById('visits-scope-all');
  const visitsStatusScheduled = document.getElementById('visits-status-scheduled');
  const visitsStatusCompleted = document.getElementById('visits-status-completed');
  const visitsRefresh = document.getElementById('visits-refresh');


  const btnBuscar   = document.getElementById('btn-buscar');
  const btnLimpiar  = document.getElementById('btn-limpiar');
  const contador    = document.getElementById('contador');

  const estadoSel   = document.getElementById('estado');
  const municipioSel= document.getElementById('municipio');

  // Tabs
  const tabs = document.querySelectorAll('.tab');
  const panels = {
    ubicacion: document.getElementById('panel-ubicacion'),
    escuelas: document.getElementById('panel-escuelas'),
    comercial: document.getElementById('panel-comercial'),
  };

  tabs.forEach(t => t.addEventListener('click', () => {
    tabs.forEach(x => x.classList.remove('active'));
    t.classList.add('active');
    Object.values(panels).forEach(p => p.classList.add('hidden'));
    panels[t.dataset.tab].classList.remove('hidden');
  }));

  function resizeMap() {
    if (!map || !google?.maps) return;
    const center = map.getCenter();
    google.maps.event.trigger(map, 'resize');
    if (center) map.setCenter(center);
  }

  function setFiltersCollapsed(collapsed) {
    if (!filtersBody || !toggleFiltersBtn) return;
    filtersBody.classList.toggle('hidden', collapsed);
    toggleFiltersBtn.textContent = collapsed ? '+' : '-';
    toggleFiltersBtn.title = collapsed ? 'Mostrar filtros' : 'Colapsar filtros';
  }

  function setResultsCollapsed(collapsed) {
    if (!mapLayout || !resultsPanel || !toggleResultsFloatingBtn) return;
  
    resultsPanel.classList.toggle('hidden', collapsed);
    mapLayout.classList.toggle('results-collapsed', collapsed);
  
    toggleResultsFloatingBtn.textContent = collapsed ? '+' : '-';
    toggleResultsFloatingBtn.title = collapsed ? 'Mostrar resultados' : 'Ocultar resultados';
  
    setTimeout(resizeMap, 60);
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

  // Eventos filtros
  estadoSel.addEventListener('change', () => cargarMunicipios(estadoSel.value));
  municipioSel.addEventListener('change', actualizarContador);

  form.addEventListener('change', (e) => {
    if (e.target.id !== 'estado' && e.target.id !== 'municipio') {
      actualizarContador();
    }
  });

  // Buscar (tabla + mapa)
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
      })
      .catch(console.error);
  });

  btnLimpiar.addEventListener('click', function(){
    form.reset();
    resetMunicipios();
    actualizarContador();
    tablaNombresBody.innerHTML = '';
    clearDataLayer();
  });

  if (modalClose) modalClose.addEventListener('click', closeEditModal);

  if (modal) {
    modal.addEventListener('click', (e) => {
      if (e.target === modal) closeEditModal();
    });
  }

  function updateVisitsButtons() {
    if (visitsScopeMine) visitsScopeMine.classList.toggle('btn-active', visitsScope === 'mine');
    if (visitsScopeAll) visitsScopeAll.classList.toggle('btn-active', visitsScope === 'all');
    if (visitsStatusScheduled) visitsStatusScheduled.classList.toggle('btn-active', visitsStatus === 'scheduled');
    if (visitsStatusCompleted) visitsStatusCompleted.classList.toggle('btn-active', visitsStatus === 'completed');
  }

  if (visitsScopeMine) visitsScopeMine.addEventListener('click', () => {
    visitsScope = 'mine';
    updateVisitsButtons();
    loadVisits();
  });
  if (visitsScopeAll) visitsScopeAll.addEventListener('click', () => {
    visitsScope = 'all';
    updateVisitsButtons();
    loadVisits();
  });
  if (visitsStatusScheduled) visitsStatusScheduled.addEventListener('click', () => {
    visitsStatus = 'scheduled';
    updateVisitsButtons();
    loadVisits();
  });
  if (visitsStatusCompleted) visitsStatusCompleted.addEventListener('click', () => {
    visitsStatus = 'completed';
    updateVisitsButtons();
    loadVisits();
  });
  if (visitsRefresh) visitsRefresh.addEventListener('click', loadVisits);

  if (scheduleSave) scheduleSave.addEventListener('click', () => {
    const dtEl = document.getElementById('schedule-datetime');
    const notesEl = document.getElementById('schedule-notes');
    const dt = dtEl ? dtEl.value : '';
    if (!scheduleSchoolId || !dt) {
      alert('Selecciona fecha y hora.');
      return;
    }
    const formData = new FormData();
    formData.append('school_id', scheduleSchoolId);
    formData.append('scheduled_at', dt);
    if (notesEl && notesEl.value) formData.append('notes', notesEl.value);

    fetchJsonPostForm(visitsScheduleUrl, formData)
      .then((data) => {
        if (!data || !data.ok) {
          alert('No se pudo agendar.');
          return;
        }
        closeScheduleModal();
        showSavedToast('Visita agendada');
        loadVisits();
      })
      .catch(console.error);
  });

  if (scheduleCancel) scheduleCancel.addEventListener('click', closeScheduleModal);
  if (scheduleClose) scheduleClose.addEventListener('click', closeScheduleModal);
  if (scheduleModal) scheduleModal.addEventListener('click', (e) => {
    if (e.target === scheduleModal) closeScheduleModal();
  });

  if (completeSave) completeSave.addEventListener('click', () => {
    if (!completeVisitId) return;
    const notesEl = document.getElementById('complete-notes');
    const fileEl = document.getElementById('complete-evidence');
    const formData = new FormData();
    formData.append('visit_id', completeVisitId);
    if (notesEl && notesEl.value) formData.append('completion_notes', notesEl.value);

    const file = fileEl && fileEl.files ? fileEl.files[0] : null;
    if (file) {
      if (file.size > 10 * 1024 * 1024) {
        alert('Archivo mayor a 10MB.');
        return;
      }
      formData.append('evidence', file);
    }

    fetchJsonPostForm(visitsCompleteUrl, formData)
      .then((data) => {
        if (!data || !data.ok) {
          alert('No se pudo completar.');
          return;
        }
        closeCompleteModal();
        showSavedToast('Visita completada');
        loadVisits();
      })
      .catch(console.error);
  });
  if (completeCancel) completeCancel.addEventListener('click', closeCompleteModal);
  if (completeClose) completeClose.addEventListener('click', closeCompleteModal);
  if (completeModal) completeModal.addEventListener('click', (e) => {
    if (e.target === completeModal) closeCompleteModal();
  });

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

  // Inicial
  resetMunicipios();
  actualizarContador();
  setFiltersCollapsed(false);
  setResultsCollapsed(false);
  updateVisitsButtons();
  loadVisits();
});

// recibe mensaje del iframe cuando se guarda la escuela
window.addEventListener('message', (ev) => {
  if (ev.origin !== window.location.origin) return;
  if (!ev.data || ev.data.type !== 'school:updated') return;

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


<script
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCs_TXbIcZnT4GdQl_QnswuGQNUsog2jJI&callback=initMap"
  async
  defer></script>
