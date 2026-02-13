<?= $this->Form->create(null, ['id' => 'asignar-form', 'class' => 'assign-form']) ?>

<section class="assign-page">
  <header class="assign-header">
    <h2>Asignar escuelas disponibles</h2>
    <p>Selecciona filtros para calcular y asignar escuelas sin distribuidor.</p>
  </header>

  <article class="assign-card">
    <div class="assign-grid">
      <?= $this->Form->control('user_destino', [
          'label' => 'Asignar a usuario',
          'options' => $users,
          'empty' => '-- Seleccione usuario --',
          'required' => true,
      ]) ?>

      <?= $this->Form->control('estado_id', [
          'label' => 'Estado',
          'options' => ['' => '-- Todos --'],
          'id' => 'estado',
      ]) ?>

      <?= $this->Form->control('municipio_id', [
          'label' => 'Municipio',
          'options' => ['' => '-- Todos --'],
          'id' => 'municipio',
      ]) ?>

      <?= $this->Form->control('tipo', [
          'options' => [
              'Preescolar' => 'Preescolar',
              'Primaria' => 'Primaria',
              'Secundaria' => 'Secundaria',
          ],
          'empty' => '-- Todos --',
          'id' => 'tipo',
      ]) ?>

      <?= $this->Form->control('turno', [
          'options' => [
              'Matutino' => 'Matutino',
              'Vespertino' => 'Vespertino',
              'Nocturno' => 'Nocturno',
          ],
          'empty' => '-- Todos --',
          'id' => 'turno',
      ]) ?>

      <?= $this->Form->control('sector', [
          'options' => [
              'Publico' => 'Publico',
              'Privado' => 'Privado',
          ],
          'empty' => '-- Todos --',
          'id' => 'sector',
      ]) ?>
    </div>

    <div class="assign-counter">
      <strong>Escuelas a asignar:</strong>
      <span id="contador">0</span>
    </div>

    <div class="assign-actions">
      <button type="button" id="btn-confirmar" class="button button-primary">Asignar escuelas</button>
      <?= $this->Html->link('Volver', ['action' => 'index'], ['class' => 'button button-secondary']) ?>
    </div>
  </article>
</section>

<?= $this->Form->end() ?>

<div id="modal-confirmacion" class="modal-overlay" style="display:none;">
  <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="modal-title">
    <h4 id="modal-title">Confirmar asignacion</h4>
    <p>Se asignaran <strong><span id="modal-total"></span></strong> escuelas al usuario seleccionado.</p>
    <div class="modal-actions">
      <button id="cancelar" class="button button-secondary" type="button">Cancelar</button>
      <button id="confirmar" class="button button-danger" type="button">Confirmar</button>
    </div>
  </div>
</div>

<style>
.assign-page {
  display: grid;
  gap: 14px;
}

.assign-header h2 {
  margin: 0;
  color: #2f251a;
}

.assign-header p {
  margin: 4px 0 0;
  color: #6d655a;
}

.assign-card {
  background: #fff;
  border: 1px solid #e9dfd2;
  border-radius: 12px;
  padding: 14px;
}

.assign-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 10px;
}

.assign-counter {
  margin-top: 12px;
  padding: 10px 12px;
  border: 1px solid #d8e6f1;
  border-radius: 10px;
  background: #f2f8fd;
}

.assign-counter span {
  margin-left: 8px;
  font-size: 1.8rem;
  color: #053b5e;
}

.assign-actions {
  margin-top: 12px;
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
}

.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.5);
  z-index: 3000;
  display: none;
  align-items: center;
  justify-content: center;
  padding: 16px;
}

.modal-card {
  width: min(440px, 100%);
  background: #fff;
  border-radius: 12px;
  border: 1px solid #e9dfd2;
  padding: 16px;
}

.modal-card h4 {
  margin: 0 0 8px;
}

.modal-card p {
  margin: 0;
}

.modal-actions {
  margin-top: 14px;
  display: flex;
  gap: 8px;
  justify-content: flex-end;
  flex-wrap: wrap;
}

.button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 8px;
  border: 1px solid transparent;
  padding: 8px 12px;
  text-decoration: none;
  font-weight: 600;
}

.button-primary { color:#fff; background:#8c1d2f; border-color:#8c1d2f; }
.button-secondary { color:#4b3e31; background:#fff8ee; border-color:#dbc9b4; }
.button-danger { color:#fff; background:#dc3545; border-color:#bb2d3b; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

  const estado = document.getElementById('estado');
  const municipio = document.getElementById('municipio');
  const tipo = document.getElementById('tipo');
  const turno = document.getElementById('turno');
  const sector = document.getElementById('sector');
  const contador = document.getElementById('contador');
  const form = document.getElementById('asignar-form');

  const btnConfirmar = document.getElementById('btn-confirmar');
  const modal = document.getElementById('modal-confirmacion');
  const modalTotal = document.getElementById('modal-total');

  const cancelar = document.getElementById('cancelar');
  const confirmar = document.getElementById('confirmar');

  const estadosUrl = "<?= $this->Url->build(['action' => 'estadosDisponibles']) ?>";
  const municipiosUrlBase = "<?= $this->Url->build(['action' => 'municipiosDisponibles', '__ID__'], ['escape' => false]) ?>";
  const contarUrl = "<?= $this->Url->build(['action' => 'contarDisponibles']) ?>";

  const urlMunicipios = id => municipiosUrlBase.replace('__ID__', id);

  fetch(estadosUrl)
    .then(r => r.json())
    .then(data => {
      Object.entries(data.estados || {}).forEach(([id, nombre]) => {
        estado.insertAdjacentHTML('beforeend', `<option value="${id}">${nombre}</option>`);
      });
    });

  estado.addEventListener('change', function () {
    municipio.innerHTML = '<option value="">-- Todos --</option>';
    if (!this.value) {
      actualizarContador();
      return;
    }

    fetch(urlMunicipios(this.value))
      .then(r => r.json())
      .then(data => {
        Object.entries(data.municipios || {}).forEach(([id, nombre]) => {
          municipio.insertAdjacentHTML('beforeend', `<option value="${id}">${nombre}</option>`);
        });
      });

    actualizarContador();
  });

  [municipio, tipo, turno, sector].forEach(el => {
    el.addEventListener('change', actualizarContador);
  });

  function actualizarContador() {
    const data = new FormData(form);

    fetch(contarUrl, {
      method: 'POST',
      body: data
    })
    .then(r => r.json())
    .then(data => {
      contador.textContent = data.total ?? 0;
    });
  }

  btnConfirmar.addEventListener('click', function () {
    const total = parseInt(contador.textContent, 10);

    if (!form.user_destino.value) {
      alert('Selecciona un usuario destino');
      return;
    }

    if (total === 0) {
      alert('No hay escuelas para asignar');
      return;
    }

    modalTotal.textContent = total;
    modal.style.display = 'flex';
  });

  cancelar.addEventListener('click', () => modal.style.display = 'none');
  confirmar.addEventListener('click', () => form.submit());

});
</script>
