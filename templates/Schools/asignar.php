<?= $this->Form->create(null, ['id' => 'asignar-form']) ?>

<fieldset>
    <legend>Asignar Escuelas Disponibles</legend>

    <?= $this->Form->control('user_destino', [
        'label' => 'Asignar a usuario',
        'options' => $users,
        'empty' => '-- Seleccione usuario --',
        'required' => true
    ]) ?>

    <?= $this->Form->control('estado_id', [
        'label' => 'Estado',
        'options' => ['' => '-- Todos --'],
        'id' => 'estado'
    ]) ?>

    <?= $this->Form->control('municipio_id', [
        'label' => 'Municipio',
        'options' => ['' => '-- Todos --'],
        'id' => 'municipio'
    ]) ?>

    <?= $this->Form->control('tipo', [
        'options' => [
            'Preescolar' => 'Preescolar',
            'Primaria' => 'Primaria',
            'Secundaria' => 'Secundaria'
        ],
        'empty' => '-- Todos --',
        'id' => 'tipo'
    ]) ?>

    <?= $this->Form->control('turno', [
        'options' => [
            'Matutino' => 'Matutino',
            'Vespertino' => 'Vespertino',
            'Nocturno' => 'Nocturno'
        ],
        'empty' => '-- Todos --',
        'id' => 'turno'
    ]) ?>

    <?= $this->Form->control('sector', [
        'options' => [
            'Publico' => 'Publico',
            'Privado' => 'Privado'
        ],
        'empty' => '-- Todos --',
        'id' => 'sector'
    ]) ?>

    <div class="alert alert-info mt-3">
        <strong>Escuelas a asignar:</strong>
        <span id="contador">0</span>
    </div>

    <button type="button" id="btn-confirmar" class="btn btn-primary mt-3">
        Asignar Escuelas
    </button>

</fieldset>

<?= $this->Form->end() ?>
<div id="modal-confirmacion" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.5);">
  <div style="background:#fff; width:400px; margin:10% auto; padding:20px; border-radius:6px;">
    <h4>Confirmar asignación</h4>
    <p>
      Se asignarán <strong><span id="modal-total"></span></strong> escuelas
      al usuario seleccionado.
    </p>

    <div style="text-align:right;">
      <button id="cancelar" class="btn btn-secondary">Cancelar</button>
      <button id="confirmar" class="btn btn-danger">Confirmar</button>
    </div>
  </div>
</div>
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

  // 🔹 cargar estados disponibles
  fetch(estadosUrl)
    .then(r => r.json())
    .then(data => {
      Object.entries(data.estados || {}).forEach(([id, nombre]) => {
        estado.insertAdjacentHTML('beforeend', `<option value="${id}">${nombre}</option>`);
      });
    });

  // 🔹 cargar municipios al cambiar estado
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

  // 🔹 recalcular contador al cambiar cualquier filtro
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

  // 🔹 abrir modal
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
    modal.style.display = 'block';
  });

  cancelar.addEventListener('click', () => modal.style.display = 'none');
  confirmar.addEventListener('click', () => form.submit());

});
</script>
