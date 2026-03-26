<div class="usuarios index">
<h2><?php __('Usuarios');?></h2>



<fieldset><legend>Filtros</legend>
<?php



echo $form->create('Usuario',array('action'=>'index'));
echo '<div class="container"> <div class="row justify-content-center">';

		echo '<div class="col-lg-6 col-md-6 ">';
			echo "Buscar<br>";
			echo $form->input('nombre',array('label'=>'','placeholder'=>'Nombre'));
			echo $form->input('apellido_p',array('label'=>'','placeholder'=>'Apellido Paterno'));
			echo $form->input('apellido_m',array('label'=>'','placeholder'=>'Apellido Materno'));
			echo $form->input('usern',array('label'=>'','placeholder'=>'Nombre de usuario'));
		echo "</div>";

	echo '<div class="col-lg-6 col-md-6 ">';
		echo $form->input('id_rol',array('label'=>'Tipo de Usuario','type'=>'select','options'=>$roles));
	echo "</div>";



echo '</div></div><br><center>';
	echo $form->submit('Buscar',array( 'class'=>"btn btn-primary py-3 px-4"));
echo "</center>";
	echo $form->end();
?>
</fieldset>
<p>
<?php

echo $paginator->counter(array(
'format' => __('<center><h3>Pagina <strong>%page%</strong> de %pages%</h3></center><br> ', true)
));
?></p>


<table class='tabla_intranet'>
<tr>

	<th><?php echo $paginator->sort('nombre');?></th>
	<th><?php echo $paginator->sort('usern');?></th>
	<th><?php echo $paginator->sort('Rol','id_rol');?></th>
	<th><?php echo $paginator->sort('activo');?></th>
	<th class="actions"><?php __('Acciones');?></th>
</tr>
<?php




$i = 0;
foreach ($usuarios as $usuario):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>

		<td>
			<?php echo $usuario['Usuario']['nombre']." ".$usuario['Usuario']['apellido_p']." ".$usuario['Usuario']['apellido_m'] ?>
		</td>

		<td>
			<?php echo $usuario['Usuario']['usern']; ?>
		</td>

		<td>
			<?php 
				if($usuario['Usuario']['admin']==1)
					echo "ADMINISTRADOR";
				else
					echo $usuario['Rol']['nombre']; 
			?>
		</td>


		<td>
			<?php if($usuario['Usuario']['activo']==1)
					echo "Si";
				else
					echo "No";

			 ?>
		</td>
		
	
		
		<td class="actions">
			<?php


				echo $this->element('setAction',array('parametros'=>array('Ver','iconos/ver.png', array('action'=>'view', $usuario['Usuario']['id']),'Ver')));
				if($user_rol<$usuario['Usuario']['id_rol']  || !empty($is_admin) ){
		        	echo $this->element('setAction',array('parametros'=>array('Modificar','iconos/modificar.png', array('action'=>'edit', $usuario['Usuario']['id']),'Modificar')));
			    	echo $this->element('setAction',array('parametros'=>array('Eliminar','iconos/eliminar.png', array('action'=>'delete', $usuario['Usuario']['id']),'Eliminar','Borrar el Usuario?')));  
				}
			?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>
<?php echo $this->element("botones_paginador"); ?>
<div class="actions">
	<ul>
		<li>
			<?php
				echo $this->element('setAction',array('parametros'=>array('Nuevo Usuario','', array('action'=>'add'),'Nuevo Usuario')));
			?>
		</li>
	</ul>
</div>
