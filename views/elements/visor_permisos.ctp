<?php

configure::load("app/configuracion"); 
$permisos=configure::read("permiso"); 
$no_e="";

if(!empty($no_editar))
	$no_e='disabled';

if(!empty($permisos)){
	echo "<fieldset><legend>Permisos</legend>";
		foreach($permisos as $p=>$l){
			echo "<br><strong>".$p."</strong>";
			foreach($l as $k=>$s){
				echo $form->input($p."|".$k,array('label'=>$s,'type'=>'checkbox','disabled'=>$no_e,'escape'=>false));
			}

		}
	echo "</fieldset>";
}



?>
