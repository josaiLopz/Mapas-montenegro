<?php

$ver=0;

if(!empty($is_admin))
	$ver=1;

else{
	configure::load("app/configuracion"); 
	$secciones=configure::read("permiso"); 

	if(empty($parametros[2]['controller']))
		$parametros[2]['controller']=$this->name;



	if(empty($secciones[$parametros[2]['controller']][$parametros[2]['action']]))
		$ver=1;

	else{
		$s=$parametros[2]['controller']."|".$parametros[2]['action'];
		if(array_search($s,$c_permisos)!==false)
			$ver=1;
	}

}

		if($ver==1){
		$ll=$parametros[0];
		if(!empty($parametros[1]))
			$ll=$html->image($parametros[1]);


		if(!empty($parametros[4]))
			echo $html->link($ll, $parametros[2], null, sprintf(__($parametros[4], true)),array('escape'=>false,'title'=>$parametros[3])); 

		else
			echo $html->link($ll,$parametros[2],array('escape'=>false,'title'=>$parametros[3]));
		}




?>
