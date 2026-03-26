<div class="rols form">
<?php echo $form->create('Rol');?>
	<fieldset>
 		<legend><?php __('Editar Rol');?></legend>
	<?php

		echo $form->input('id');
		echo $form->input('nombre');
		echo $this->element('visor_permisos');
	?>
	</fieldset>
	<?php echo $form->submit('Guardar',array( 'class'=>"btn btn-primary py-3 px-4"));?>
<?php echo $form->end();?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $this->element('setAction',array('parametros'=>array('Eliminar','', array('action'=>'delete', $form->value('Rol.id')),'Eliminar','Eliminar Rol ?'))); ?></li>
		<li><?php echo $html->link('Regresar', array('action'=>'index'));?></li>
	</ul>
</div>
