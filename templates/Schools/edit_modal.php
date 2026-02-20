<?php
/**
 * @var \App\Model\Entity\School $school
 * @var iterable $users
 * @var iterable $estados
 * @var iterable $municipios
 */

$statusOptions = [
    'noAtendida' => 'No atendida',
    'escuelaPromocion' => 'Escuela en promocion',
    'ventaConfirmada' => 'Venta confirmada',
    'prohibicion' => 'Prohibicion',
    'ventaMarcas' => 'Venta otras marcas',
];
?>

<section class="modal-school-edit">
  <div class="modal-topbar">
    <div class="modal-tabs">
      <button type="button" class="modal-tab active" data-tab="ubicacion">Ubicacion</button>
      <button type="button" class="modal-tab" data-tab="escuelas">Escuelas</button>
      <button type="button" class="modal-tab" data-tab="comercial">Comercial</button>
    </div>
  </div>

  <?= $this->Form->create($school, [
    'id' => 'edit-school-modal-form',
    'class' => 'modal-school-form',
    'url' => ['action' => 'updateModal', $school->id],
  ]) ?>

  <div class="modal-panel">
    <div class="modal-grid modal-tab-panel active" id="panel-ubicacion">
      <?= $this->Form->control('estado_id', [
        'label' => 'Estado',
        'options' => $estados,
        'empty' => '-- Seleccione estado --',
      ]) ?>

      <?= $this->Form->control('municipio_id', [
        'label' => 'Municipio',
        'options' => $municipios,
        'empty' => '-- Seleccione municipio --',
      ]) ?>

      <?= $this->Form->control('user_id', [
        'label' => 'Distribuidor',
        'options' => $users,
        'empty' => '-- Seleccione usuario --',
      ]) ?>
    </div>

    <div class="modal-grid modal-tab-panel" id="panel-escuelas">
      <?= $this->Form->control('nombre') ?>
      <?= $this->Form->control('cct') ?>
      <?= $this->Form->control('tipo') ?>
      <?= $this->Form->control('sector') ?>
      <?= $this->Form->control('turno') ?>
      <?= $this->Form->control('num_alumnos') ?>
      <?= $this->Form->control('grupos') ?>
    </div>

    <div class="modal-grid modal-tab-panel" id="panel-comercial">
      <?= $this->Form->control('correo_contacto') ?>
      <?= $this->Form->control('nombre_contacto') ?>
      <?= $this->Form->control('telefono_contacto') ?>

      <?= $this->Form->control('estatus', [
        'type' => 'select',
        'options' => $statusOptions,
        'empty' => '-- Seleccione estatus --',
      ]) ?>

      <?= $this->Form->control('editorial_actual') ?>
      <?= $this->Form->control('competencia') ?>
      <?= $this->Form->control('presupuesto') ?>

      <?= $this->Form->control('venta_montenegro', [
        'type' => 'select',
        'options' => [1 => 'Si', 0 => 'No'],
      ]) ?>

      <?= $this->Form->control('verificada', [
        'type' => 'select',
        'options' => [1 => 'Si', 0 => 'No'],
      ]) ?>

      <div class="field-full">
        <?= $this->Form->control('notas', ['type' => 'textarea']) ?>
      </div>
    </div>
  </div>

  <div class="modal-actions">
    <button type="submit" class="button button-primary">Guardar</button>
  </div>

  <div class="modal-secondary-action">
    <?= $this->Html->link(
      'Gestion de Materiales',
      '/schools/' . (int)$school->id . '/materials-manager',
      ['class' => 'materials-link', 'target' => '_blank', 'rel' => 'noopener noreferrer']
    ) ?>
  </div>

  <?= $this->Form->end() ?>
</section>

<style>

.modal-school-edit {
  background: #e9e1e1;
  border: 1px solid #d8c8c8;
  border-radius: 16px;
}

.modal-topbar {
  margin-bottom: 10px;
}

.modal-tabs {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
}

.modal-tab {
  border: 1px solid transparent;
  border-radius: 999px;
  background: transparent;
  color: #a52030;
  padding: 8px 16px;
  font-size: 0.8rem;
  font-weight: 600;
  cursor: pointer;
}

.modal-tab.active {
  background: #b21f34;
  color: #fff;
}

.modal-panel {
  border: 1px solid #d9c9c9;
  border-radius: 14px;
  padding: 14px;
  background: #e9e1e1;
}

.modal-tab-panel {
  display: none;
}

.modal-tab-panel.active {
  display: grid;
}

.modal-school-form {
  margin: 0;
}

.modal-grid {
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: 10px;
}

.field-full {
  grid-column: 1 / -1;
}

.modal-grid .input,
.modal-grid .select,
.modal-grid .textarea,
.modal-grid .email,
.modal-grid .number,
.modal-grid .text {
  margin: 0;
  display: grid;
  gap: 6px;
}

.modal-grid label {
  margin: 0;
  font-weight: 500;
  color: #5d5757;
  font-size: 0.8rem;
  line-height: 1.2;
}

.modal-grid input[type="text"],
.modal-grid input[type="email"],
.modal-grid input[type="number"],
.modal-grid select,
.modal-grid textarea {
  width: 100%;
  min-height: 38px;
  border: 1px solid #9f9f9f;
  border-radius: 3px;
  background: #f2f2f2;
  color: #111;
  padding: 8px 10px;
  margin: 0;
  box-sizing: border-box;
}

.modal-grid textarea {
  min-height: 96px;
  resize: vertical;
}

.modal-grid input:focus,
.modal-grid select:focus,
.modal-grid textarea:focus {
  border-color: #8c1d2f;
  box-shadow: 0 0 0 3px rgba(140, 29, 47, 0.15);
  outline: none;
}

.modal-actions {
  margin-top: 14px;
  display: flex;
  justify-content: center;
}

.button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 0;
  border: 1px solid transparent;
  min-width: 118px;
  padding: 12px 14px;
  text-decoration: none;
  font-weight: 600;
  font-size: 1.1rem;
}

.button-primary {
  color: #fff;
  background: #b21f34;
  border-color: #b21f34;
}

.modal-secondary-action {
  margin-top: 8px;
}

.materials-link {
  color: #a52030;
  text-decoration: none;
  font-size: 1.1rem;
}

.materials-link:hover {
  text-decoration: underline;
}

@media (max-width: 900px) {
  .modal-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}

@media (max-width: 760px) {
  .modal-grid {
    grid-template-columns: 1fr;
  }

  .field-full {
    grid-column: auto;
  }
}
</style>

<script>
(function(){
  const form = document.getElementById('edit-school-modal-form');
  if (!form) return;
  let lastHeight = 0;

  const fitFrameToContent = () => {
    if (!window.frameElement) return;
    const root = document.querySelector('.modal-school-edit') || form;
    const rect = root.getBoundingClientRect();
    const styles = window.getComputedStyle(root);
    const marginTop = parseFloat(styles.marginTop || '0') || 0;
    const marginBottom = parseFloat(styles.marginBottom || '0') || 0;
    const height = Math.ceil(rect.height + marginTop + marginBottom + 6);
    if (Math.abs(lastHeight - height) < 2) return;
    lastHeight = height;
    window.frameElement.style.height = `${height}px`;
    if (window.parent && window.parent !== window) {
      window.parent.postMessage({ type: 'school:modalHeight', height }, window.location.origin);
    }
  };

  const tabs = document.querySelectorAll('.modal-tab');
  const panels = {
    ubicacion: document.getElementById('panel-ubicacion'),
    escuelas: document.getElementById('panel-escuelas'),
    comercial: document.getElementById('panel-comercial')
  };

  tabs.forEach((tab) => {
    tab.addEventListener('click', () => {
      tabs.forEach((item) => item.classList.remove('active'));
      tab.classList.add('active');
      Object.values(panels).forEach((panel) => panel && panel.classList.remove('active'));
      const target = panels[tab.dataset.tab];
      if (target) target.classList.add('active');
      fitFrameToContent();
    });
  });

  window.addEventListener('load', fitFrameToContent);
  window.addEventListener('resize', fitFrameToContent);
  setTimeout(fitFrameToContent, 0);

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const fd = new FormData(form);
    const csrf = fd.get('_csrfToken');

    const r = await fetch(form.action, {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        ...(csrf ? {'X-CSRF-Token': csrf} : {})
      },
      body: fd,
      credentials: 'same-origin'
    });

    const text = await r.text();
    let j = null;
    try { j = JSON.parse(text); } catch(_e) {}

    if (!r.ok || !j || !j.ok) {
      alert((j && j.message) ? j.message : 'No se pudo actualizar');
      console.error('updateModal error', r.status, j || text);
      return;
    }

    if (j.requested === false) {
      alert(j.message || 'Sin cambios para enviar');
      return;
    }

    if (j.requested) {
      window.parent.postMessage({ type: 'school:requested', payload: j }, window.location.origin);
      return;
    }

    window.parent.postMessage({ type: 'school:updated', payload: j.school }, window.location.origin);
  });
})();
</script>
