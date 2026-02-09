<?php
$csrfToken = h($this->request->getAttribute('csrfToken') ?? '');
$updateUrl = $this->Url->build(
    ['controller' => 'SchoolMaterials', 'action' => 'updateCell'],
    ['escape' => false]
);
?>

<style>
  .wrap{ background:#fff; border:1px solid #e6e6e6; border-radius:14px; padding:14px; }
  .topbar{ display:flex; justify-content:space-between; align-items:center; gap:10px; margin-bottom:12px; }
  h3{ margin:0; color:#8b1d2c; font-weight:800; }

  .search{ width:min(860px,100%); padding:10px 12px; border:1px solid #ddd; border-radius:10px; }
  .tablebox{ margin-top:12px; border-radius:12px; overflow:auto; border:1px solid #eee; }

  table{ width:100%; border-collapse:collapse; min-width:900px; }
  thead th{
    background:#f6c9cf; color:#6b1a24; text-align:center;
    padding:12px 10px; border:1px solid #e6aab2; font-weight:800;
  }
  tbody td{ border:1px solid #eee; padding:10px; vertical-align:middle; }
  tbody tr:nth-child(even){ background:#fafafa; }

  .lvl{ width:140px; text-align:center; }
  .mat{ min-width:420px; }
  .num{ width:180px; text-align:center; }

  .cell-input{
    width:140px; text-align:center; padding:7px 9px;
    border:1px solid #ddd; border-radius:8px; outline:none;
  }
  .saving{ opacity:.65; }
  .ok{ box-shadow:0 0 0 2px rgba(25,135,84,.22); }
  .bad{ box-shadow:0 0 0 2px rgba(220,53,69,.22); }

  .toast{
    position:fixed; bottom:20px; right:20px;
    background:#198754; color:#fff; padding:12px 16px;
    border-radius:10px; box-shadow:0 6px 18px rgba(0,0,0,.2);
    font-size:14px; opacity:0; pointer-events:none; transition:opacity .25s ease;
    z-index:9999;
  }
</style>

<div class="wrap">
  <div class="topbar">
    <h3>Materiales Mapa para la Base — <?= h($school->nombre) ?></h3>
    <div style="display:flex; gap:8px;">
    </div>
  </div>

  <input id="q" class="search" placeholder="Buscar en la tabla..." />

  <div class="tablebox">
    <table id="tbl">
      <thead>
        <tr>
          <th class="lvl">Nivel</th>
          <th class="mat">Material</th>
          <th class="num">Proyección Venta</th>
          <th class="num">Cierre 2026</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $r): ?>
          <tr data-row-id="<?= (int)$r->id ?>">
            <td class="lvl"><?= h($r->material->nivel ?? '—') ?></td>
            <td class="mat"><?= h($r->material->nombre ?? '—') ?></td>

            <td class="num">
              <input class="cell-input"
                     inputmode="decimal"
                     data-field="proyeccion_venta"
                     value="<?= h(number_format((float)$r->proyeccion_venta, 2, '.', '')) ?>">
            </td>

            <td class="num">
              <input class="cell-input"
                     inputmode="decimal"
                     data-field="cierre_2026"
                     value="<?= h(number_format((float)$r->cierre_2026, 2, '.', '')) ?>">
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<div id="toast" class="toast">✔ Guardado</div>

<script>
const csrfToken = "<?= $csrfToken ?>";
const updateUrl  = "<?= $updateUrl ?>";

const toast = document.getElementById('toast');

function showToast(msg, ok=true){
  toast.textContent = msg;
  toast.style.background = ok ? '#198754' : '#dc3545';
  toast.style.opacity = '1';
  clearTimeout(window.__t);
  window.__t = setTimeout(()=> toast.style.opacity='0', 1400);
}

// Buscador
document.getElementById('q').addEventListener('input', (e)=>{
  const q = e.target.value.toLowerCase();
  document.querySelectorAll('#tbl tbody tr').forEach(tr=>{
    tr.style.display = tr.innerText.toLowerCase().includes(q) ? '' : 'none';
  });
});

// Autosave con debounce por celda
const timers = new Map();

async function postUpdate(id, field, value){
  const headers = { 'Accept': 'application/json' };
  if (csrfToken) headers['X-CSRF-Token'] = csrfToken;

  const fd = new FormData();
  fd.append('id', id);
  fd.append('field', field);
  fd.append('value', value);

  const r = await fetch(updateUrl, {
    method:'POST',
    headers,
    body: fd,
    credentials:'same-origin'
  });

  const txt = await r.text();
  let j = null; try { j = JSON.parse(txt); } catch(e) {}

  if (!r.ok || !j || !j.ok) {
    throw new Error((j && j.msg) ? j.msg : 'Error guardando');
  }
  return true;
}

function queueSave(inputEl){
  const tr = inputEl.closest('tr');
  const rowId = tr.dataset.rowId;
  const field = inputEl.dataset.field;

  // normaliza (por si escriben 1,23)
  const value = (inputEl.value || '').trim();

  inputEl.classList.remove('ok','bad');
  inputEl.classList.add('saving');

  const key = rowId + '|' + field;

  if (timers.has(key)) clearTimeout(timers.get(key));

  timers.set(key, setTimeout(async () => {
    try {
      await postUpdate(rowId, field, value);
      inputEl.classList.remove('saving');
      inputEl.classList.add('ok');
      showToast('✔ Guardado');
      setTimeout(()=> inputEl.classList.remove('ok'), 650);
    } catch (err) {
      inputEl.classList.remove('saving');
      inputEl.classList.add('bad');
      showToast('✖ ' + err.message, false);
      console.error('Autosave error:', err);
    }
  }, 400));
}

document.querySelectorAll('.cell-input').forEach(inp=>{
  inp.addEventListener('input', ()=> queueSave(inp));
  inp.addEventListener('blur', ()=> queueSave(inp));
});
</script>
