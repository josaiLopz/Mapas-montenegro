<?= $this->Form->create(null, ['id' => 'transfer-form']) ?>

<section class="transfer-page">
  <header class="transfer-header">
    <h2>Transferir escuelas</h2>
    <p>Mueve escuelas de un usuario origen a otro usuario destino usando filtros.</p>
  </header>

  <article class="transfer-card">
    <div class="transfer-grid">
      <?= $this->Form->control('user_origen', [
          'label' => 'Usuario origen',
          'options' => $users,
          'empty' => '-- Seleccione usuario --',
          'id' => 'user-origen',
      ]) ?>

      <?= $this->Form->control('user_destino', [
          'label' => 'Usuario destino',
          'options' => $users,
          'empty' => '-- Seleccione usuario --',
          'id' => 'user-destino',
      ]) ?>

      <?= $this->Form->control('estado_id', [
          'label' => 'Estado',
          'options' => ['' => '-- Todos --'],
          'empty' => '-- Todos --',
          'id' => 'estado',
      ]) ?>

      <?= $this->Form->control('municipio_id', [
          'label' => 'Municipio',
          'options' => [],
          'empty' => '-- Todos --',
          'id' => 'municipio',
      ]) ?>

      <?= $this->Form->control('tipo', [
          'label' => 'Tipo',
          'options' => [
              'Preescolar' => 'Preescolar',
              'Primaria' => 'Primaria',
              'Secundaria' => 'Secundaria',
          ],
          'empty' => '-- Todos --',
      ]) ?>

      <?= $this->Form->control('turno', [
          'options' => [
              'Matutino' => 'Matutino',
              'Vespertino' => 'Vespertino',
              'Nocturno' => 'Nocturno',
          ],
          'empty' => '-- Todos --',
      ]) ?>

      <?= $this->Form->control('sector', [
          'options' => [
              'Privado' => 'Privado',
              'Publico' => 'Publico',
          ],
          'empty' => '-- Todos --',
      ]) ?>
    </div>

    <div class="transfer-actions">
      <?= $this->Form->button('Transferir', ['class' => 'button button-primary']) ?>
      <?= $this->Html->link('Volver', ['action' => 'index'], ['class' => 'button button-secondary']) ?>
    </div>
  </article>
</section>

<?= $this->Form->end() ?>

<?php
$estadosUrlBase = $this->Url->build([
    'controller' => 'Schools',
    'action' => 'estadosPorUsuario',
    '__ID__'
], ['escape' => false]);

$municipiosUrlBase = $this->Url->build([
    'controller' => 'Schools',
    'action' => 'municipiosPorEstado',
    '__ID__'
], ['escape' => false]);
?>

<style>
.transfer-page {
  display: grid;
  gap: 14px;
}

.transfer-header h2 {
  margin: 0;
  color: #2f251a;
}

.transfer-header p {
  margin: 4px 0 0;
  color: #6d655a;
}

.transfer-card {
  background: #fff;
  border: 1px solid #e9dfd2;
  border-radius: 12px;
  padding: 14px;
}

.transfer-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 10px;
}

.transfer-actions {
  margin-top: 12px;
  display: flex;
  gap: 8px;
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
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

  const estadosUrlBase = "<?= $estadosUrlBase ?>";
  const municipiosUrlBase = "<?= $municipiosUrlBase ?>";

  const userOrigen = document.getElementById('user-origen');
  const estado = document.getElementById('estado');
  const municipio = document.getElementById('municipio');

  const urlEstados = (id) => estadosUrlBase.replace('__ID__', encodeURIComponent(id));
  const urlMunicipios = (id) => municipiosUrlBase.replace('__ID__', encodeURIComponent(id));

  userOrigen.addEventListener('change', function () {
    estado.innerHTML = '<option value="">-- Todos --</option>';
    municipio.innerHTML = '<option value="">-- Todos --</option>';
    if (!this.value) return;

    fetch(urlEstados(this.value), { headers: { 'Accept': 'application/json' } })
      .then(r => r.json())
      .then(data => {
        Object.entries(data.estados || {}).forEach(([id, nombre]) => {
          estado.insertAdjacentHTML('beforeend', `<option value="${id}">${nombre}</option>`);
        });
      })
      .catch(err => console.error('Error estados:', err));
  });

  estado.addEventListener('change', function () {
    municipio.innerHTML = '<option value="">-- Todos --</option>';
    if (!this.value) return;

    fetch(urlMunicipios(this.value), { headers: { 'Accept': 'application/json' } })
      .then(r => r.json())
      .then(data => {
        Object.entries(data.municipios || {}).forEach(([id, nombre]) => {
          municipio.insertAdjacentHTML('beforeend', `<option value="${id}">${nombre}</option>`);
        });
      })
      .catch(err => console.error('Error municipios:', err));
  });

});
</script>
