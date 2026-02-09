<?= $this->Form->create() ?>

<fieldset>
    <legend>Transferir Escuelas</legend>

    <?= $this->Form->control('user_origen', [
        'label' => 'Usuario origen',
        'options' => $users,
        'empty' => '-- Seleccione usuario --',
        'id' => 'user-origen'
    ]) ?>
    <?= $this->Form->control('user_destino', [
        'label' => 'Usuario destino',
        'options' => $users,
        'empty' => '-- Seleccione usuario --',
        'id' => 'user-destino'
    ]) ?>
    <?= $this->Form->control('estado_id', [
        'label' => 'Estado',
        'options' => ['' => '-- Todos --'],
        'empty' => '-- Todos --',
        'id' => 'estado'
    ]) ?>
    <?= $this->Form->control('municipio_id', [
        'label' => 'Municipio',
        'options' => [],
        'empty' => '-- Todos --',
        'id' => 'municipio'
    ]) ?>
    <?= $this->Form->control('tipo', [
        'label' => 'Tipo',
        'options' => [
            'Preescolar' => 'Preescolar',
            'Primaria' => 'Primaria',
            'Secundaria' => 'Secundaria'
        ],
        'empty' => '-- Todos --'
    ]) ?>
    <?= $this->Form->control('turno', [
        'options' => [
            'Matutino' => 'Matutino',
            'Vespertino' => 'Vespertino',
            'Nocturno' => 'Nocturno'
        ],
        'empty' => '-- Todos --'
    ]) ?>
    <?= $this->Form->control('sector', [
        'options' => [
            'Privado' => 'Privado',
            'Publico' => 'Publico'
        ],
        'empty' => '-- Todos --'
    ]) ?>


<?= $this->Form->button('Transferir') ?>
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
</fieldset>