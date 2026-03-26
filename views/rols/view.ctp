<div class="rols view">
<h2><?php  __('Rol');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $rol['Rol']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Nombre'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $rol['Rol']['nombre']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Creado'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $rol['Rol']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modificado'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $rol['Rol']['modified']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Permisos'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php 


				echo $form->create('Rol');
				echo $this->element('visor_permisos',array('no_editar'=>1));
				echo $form->end();
				

			?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $this->element('setAction',array('parametros'=>array('Modificar  Rol','', array('action'=>'edit', $rol['Rol']['id']),'Modificar'))); ?> </li>
		<li><?php echo $this->element('setAction',array('parametros'=>array('Eliminar Rol','', array('action'=>'delete', $rol['Rol']['id']),'Eliminar','Eliminar rol'))); ?> </li>
		<li><?php echo $html->link(__('Regresar', true), array('action'=>'index')); ?> </li>
		<li><?php echo $this->element('setAction',array('parametros'=>array('Nuevo Rol','', array('action'=>'add'),'Agregar'))); ?> </li>
	</ul>
</div>
