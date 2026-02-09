<?php
// templates/Schools/edit_modal.php
?>

<h4 style="margin:10px 0;">Editar escuela</h4>

<?= $this->Form->create($school, [
  'id' => 'edit-school-modal-form',
  'url' => ['action' => 'updateModal', $school->id],
]) ?>

<?= $this->Form->control('nombre') ?>
<?= $this->Form->control('estado_id') ?>
<?= $this->Form->control('municipio_id') ?>
<?= $this->Form->control('user_id') ?>
<?= $this->Form->control('correo_contacto') ?>
                <!-- Estatus -->
                <?= $this->Form->control('estatus', [
                    'type' => 'select',
                    'options' => ['noAtendida' => 'No atendida', 'escuelaPromocion'  => 'Escuela en promoción', 'ventaConfirmada'  => 'Venta confirmada', 'prohibicion' => 'Prohibicion', 'ventaMarcas', 'Venta otras marcas'],
                    'empty' => '-- Seleccione estatus --'
                ]) ?>

<?= $this->Form->control('cct') ?>
<?= $this->Form->control('tipo') ?>
<?= $this->Form->control('sector') ?>
<?= $this->Form->control('turno') ?>
<?= $this->Form->control('num_alumnos') ?>
<?= $this->Form->control('grupos') ?>
<?= $this->Form->control('nombre_contacto') ?>
<?= $this->Form->control('telefono_contacto') ?>
<?= $this->Form->control('notas') ?>
<?= $this->Form->control('editorial_actual') ?>
<?= $this->Form->control('venta_montenegro') ?>
<?= $this->Form->control('competencia') ?>
<?= $this->Form->control('presupuesto') ?>
<?= $this->Form->control('verificada') ?>

<div style="margin-top:12px; display:flex; gap:8px;">
  <button type="submit" class="btn btn-success">Guardar</button>
</div>
<?= $this->Html->link(
  'Gestionar productos',
  '/schools/' . (int)$school->id . '/materials-manager',
  ['class'=>'btn btn-danger', 'target'=>'_blank']
) ?>

<?= $this->Form->end() ?>

<script>
(function(){
  const form = document.getElementById('edit-school-modal-form');
  if (!form) return;

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
    let j = null; try { j = JSON.parse(text); } catch(e){}

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
      window.parent.postMessage({ type:'school:requested', payload:j }, window.location.origin);
      return;
    }

    // avisar al padre para cerrar modal + toast
    window.parent.postMessage({ type:'school:updated', payload:j.school }, window.location.origin);
  });
})();
</script>
