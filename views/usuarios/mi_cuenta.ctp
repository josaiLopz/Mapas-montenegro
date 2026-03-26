<?php ?>
<h2>Mi Cuenta</h2>

<script>
function leer_correo(a){

if(document.getElementById('leer_mail').checked)
	muestra(a);
else
	oculta(a);

}

</scripT>


<?php 
echo "<fieldset><legend>".$this->data['Usuario']['usern']."</legend>";


	echo $form->create('Usuario',array('action'=>'mi_cuenta'));
	echo $form->input('id');
	echo $form->input('nombre',array('label'=>'Nombre<bR> '));
	echo $form->input('apellido_p',array('label'=>'<bR>Apellido Paterno<bR>'));
	echo $form->input('apellido_m',array('label'=>'<bR>Apellido Materno<bR>'));
	echo $form->input('email',array('label'=>'<bR>Email<bR>'));
	echo "<fieldset><legend>Llenar solo en caso de cambiar password</legend>";

	echo '<bR>Password<bR>';
	echo "<div style='position:relative'>
		<div class='ver_passm'><a href='javascript:ver_pass()'><img src='/img/iconos/see.png' id='icono_see'></a></div>";
		echo $form->input('pssword',array('label'=>false, 'type'=>'password','id'=>'campo_pass','style'=>'width:100%'));
	echo "</div>";

	echo "<bR>Re-Password<bR>";
	echo "<div style='position:relative'>
		<div class='ver_passm'><a href='javascript:ver_pass2()'><img src='/img/iconos/see.png' id='icono_see2'></a></div>";
		echo $form->input('pssword2',array('label'=>false,'type'=>'password','id'=>'campo_repass','style'=>'width:100%'));
	echo "</div>";
	
	
	echo "</fieldset>";
	echo "<br>";
	echo "<input  type='button' class='btn btn-primary py-3 px-4' value='Guardar' onclick='enviar_formulario()' >";
	echo $form->end();
	echo "</fieldset>";
?>

<script>

	var el_pass=0
	var el_pass2=0
	
	function ver_pass(){
		if(el_pass==0){
			$("#icono_see").attr("src","/img/iconos/nsee.png");
			$("#campo_pass").attr("type","text");
			el_pass=1
		}

		else {
			$("#icono_see").attr("src","/img/iconos/see.png");
			$("#campo_pass").attr("type","password");
			el_pass=0
		}
	}


	function ver_pass2(){
		if(el_pass2==0){
			$("#icono_see2").attr("src","/img/iconos/nsee.png");
			$("#campo_repass").attr("type","text");
			el_pass2=1
		}

		else {
			$("#icono_see2").attr("src","/img/iconos/see.png");
			$("#campo_repass").attr("type","password");
			el_pass2=0
		}
	}

function enviar_formulario(){
pas=document.getElementById('campo_pass').value;



if(pas.length<1){
	document.getElementById('UsuarioMiCuentaForm').submit();
}

else if(pas.length<8)
	alert('El tamaño del Password debe de ser de al menos 8 caracteres');

else{

	pas2=document.getElementById('campo_repass').value

	if(pas!=pas2)
		alert('El Password y el Re-Password no coinciden')
	else
	document.getElementById('UsuarioMiCuentaForm').submit();


 }
}


</script>


<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Regresar', true), array('action'=>'index')); ?> </li>
	
	</ul>
</div>
