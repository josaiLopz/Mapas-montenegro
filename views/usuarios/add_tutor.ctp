<?php ?>
<div class="usuarios form">

<?php echo $form->create('Usuario',array('action'=>'add_tutor'));?>
	<fieldset>
 		<legend><?php __('Nuevo Tutor');?></legend>
	<?php
		echo $form->input('nombre',array('label'=>'Nombre<br>','size'=>'30'));
		echo "<br/>";
		echo $form->input('apellido_p',array('label'=>'Apellido Paterno<br>','size'=>'30'));
		echo "<br/>";
		echo $form->input('apellido_m',array('label'=>'Apellido Materno<br>','size'=>'30'));
		echo "<br/>";
		echo $form->input('usern',array('label'=>'Nombre de Usuario<br>','size'=>'30'));
		echo "<br/>";
		echo $form->input('pssword',array('label'=>'Password<br>','size'=>'30','type'=>'password'));
		echo "<br/>";
		echo $form->input('email',array('label'=>'Email<br>','size'=>'30'));
		echo "<br/>";
		$sexos=array(1=>'Masculino',2=>'Femenino');
		echo $form->input('sexo',array('label'=>'Sexo<br>','type'=>$select,'options'=>$sexos));
		echo "<br/>";
		
	?>
	</fieldset>
	<?php echo $form->submit('Guardar',array( 'class'=>"btn btn-primary py-3 px-4"));?>
<?php echo $form->end();?>
</div>
