<div class="usuarios view">

<?php

	Configure::load('app/app');
	$permisos=Configure::read('permisos');

?>
<h2><?php  __('Usuario');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>



<?php if($usuario['Usuario']['admin']==1){ ?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('<u>Administrador</u>'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
		</dd><br>

<?php } ?>





		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $usuario['Usuario']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Nombre'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $usuario['Usuario']['nombre']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Apellido P'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $usuario['Usuario']['apellido_p']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Apellido M'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $usuario['Usuario']['apellido_m']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Usern'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $usuario['Usuario']['usern']; ?>
			&nbsp;
		</dd>
		
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Activo'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php if($usuario['Usuario']['activo']==1)
					echo "Si";
				else
					echo "No";

			 ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Email'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $usuario['Usuario']['email']; ?>
			&nbsp;
		</dd>



	



		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Permisos'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php 

				if($usuario['Usuario']['admin']==1)
					echo "ADMINISTRADOR";

				else{
				echo $form->create('Rol');
				echo $this->element('visor_permisos',array('no_editar'=>1));
				echo $form->end();
				}

			?>
			&nbsp;
		</dd>



		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Creado'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $usuario['Usuario']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modificado'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $usuario['Usuario']['modified']; ?>
			&nbsp;
		</dd>

		
	</dl>
</div>
<div class="actions">
	<ul>
	<?php if($user_rol<$usuario['Usuario']['id_rol'] || !empty($is_admin)  ){ ?>
		<li>
			<?php echo $this->element('setAction',array('parametros'=>array('Modificar Usuario','', array('action'=>'edit', $usuario['Usuario']['id']),'Modificar'))); ?>
		</li>

		<li>
			<?php echo $this->element('setAction',array('parametros'=>array('Eliminar Usuario','', array('action'=>'delete', $usuario['Usuario']['id']),'Eliminar','Eliminar El Usuario?'))); ?>
		</li>

<?php } ?>
		<li><?php echo $html->link(__('Mostrar Usuarios', true), array('action'=>'index')); ?> </li>

		<li>
			<?php echo $this->element('setAction',array('parametros'=>array('Nuevo Usuario','', array('action'=>'add'),'Nuevo Usuario'))); ?>
		</li>


	</ul>
</div>
