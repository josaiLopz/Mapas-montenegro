<?php
 Configure::load('app/app');
 $title = Configure::read('Title');
 echo '<div class="contact-form bg-secondary" style="padding: 30px;">';
?> 


<?php echo $this->element('sha1'); ?>
<script type="text/javascript">
    $(document).ready(function() {
        $('#Login').bind('submit', function() {
            var pssword = $(this).find('#UsuarioPssword');
            pssword.val(sha1Hash(pssword.val()));
        });
    });
</script>
  
<?php


    if(Configure::read() > 0):
	    Debugger::checkSessionKey();
	endif;
	if(!isset($_SESSION['RADIO']['Intranet'])):
		$_SESSION['RADIO']['Intranet'] = false;
	endif;
?>

<?php if($_SESSION['RADIO']['Intranet'] == false){ 
	?>

<?php echo $form->create('Usuario', array('action' => 'login', 'id'=>'Login')); ?>
            <p><h1><?php //echo $title['intranet']; ?></h1></p>
<div class='intranet'>

		<?php echo $form->input('Usuario.usern', array('type' => 'text','class'=>'form-control border-0 p-4','label'=>false, 'placeholder'=>'Usuario')); ?>
            <br class="clear"/>
		<div style='position:relative'>
		<div class='ver_pass'><a href='javascript:ver_pass()'><img src='/img/iconos/see.png' id='icono_see'></a></div>
		<?php echo $form->input('Usuario.pssword', array('type' => 'password','class'=>'form-control border-0 p-4','label'=>false, 'placeholder'=>'Contraseña')); ?>

		</div>
		<br class="clear"/>
</div>
<div class="container">
<div class="row justify-content-center">
	<div class="col-lg-12 col-md-12 ">
		<?php echo $form->submit('Enviar',array( 'class'=>"btn btn-primary py-3 px-4"));?>
	</div>
	
	
</div>
</div>
<?php echo $form->end();?>



<?php  if ($session->check("Message.flash")): 
	    echo "<br><div class='error'>";
             $session->flash();
	echo "</div>";
             endif; 

?>

<?php echo $this->element('recuperar_pass',array('ruta'=>'../usuarios/recuperar_pass')); ?>
<? }else{ ?>


	<center>

	<h1 >
		<?php echo $title['bienvenido']; ?>
	</h1>
	</center>
	<bR><bR>
	
<?php } ?>
</div>

<script>
	var el_pass=0
	
	function ver_pass(){
		if(el_pass==0){
			$("#icono_see").attr("src","/img/iconos/nsee.png");
			$("#UsuarioPssword").attr("type","text");
			el_pass=1
		}

		else {
			$("#icono_see").attr("src","/img/iconos/see.png");
			$("#UsuarioPssword").attr("type","password");
			el_pass=0
		}
	}
</script>






