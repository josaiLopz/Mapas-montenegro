<div class="rols form">
<?php echo $form->create('Rol');?>
	<fieldset>
 		<legend><?php __('Agregar Rol');?></legend>
	<?php
		echo $form->input('nombre');
		echo $this->element('visor_permisos');
	?>
	</fieldset>
	<?php echo $form->submit('Guardar',array( 'class'=>"btn btn-primary py-3 px-4"));?>
<?php echo $form->end();?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link('Regresar', array('action'=>'index'));?></li>
	</ul>
</div>
