<?php ?>
<div class="usuarios form">


<?php echo $form->create('Usuario');?>
	<fieldset>
 		<legend><?php __('Editar Usuario '.$this->data['Usuario']['usern']);?></legend>
	<?php
		echo $form->input('id');

		echo $form->input('nombre',array('label'=>'Nombre<br>','size'=>'30'));
		echo "<br>";
		echo $form->input('apellido_p',array('label'=>'Apellido Paterno<br>','size'=>'30'));
		echo "<br>";
		echo $form->input('apellido_m',array('label'=>'Apellido Materno<br>','size'=>'30'));
		echo "<br>";
		echo $form->input('pssword',array('label'=>'Password<br>','size'=>'30'));
		echo "<br>";
		echo $form->input('email',array('label'=>'Email<br>','size'=>'30'));
		echo "<br/>";
		$sexos=array(1=>'Masculino',2=>'Femenino');
		echo $form->input('sexo',array('label'=>'Sexo<br>','type'=>$select,'options'=>$sexos));
		echo "<br/>";
		echo $form->input('id_rol',array('label'=>'Rol<br>','type'=>'select','options'=>$roles));
		echo "<br/>";
		echo $form->input('activo',array('type'=>'checkbox'));
		echo "<br/>";



	?>
	</fieldset>
	<?php echo $form->submit('Guardar',array( 'class'=>"btn btn-primary py-3 px-4"));?>
<?php echo $form->end();?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Mostrar Usuarios', true), array('action'=>'index'));?></li>
	</ul>
</div>
<script>cambio_subir()</script>
