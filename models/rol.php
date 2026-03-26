<?php
class Rol extends AppModel {

	var $name = 'Rol';
	var $useTable = 'rols';


	function listado($todos){

		$ses=$_SESSION['Usuario']['rol'];
		if($ses==4){
			$r=$this->findAll(array('Rol.id'=>'>4'),array(),'Rol.nombre');
		}

		if($ses==3){
			$r=$this->findAll(array('Rol.id'=>'>3'),array(),'Rol.nombre');
		}

		else
			$r=$this->findAll(array(),array(),'Rol.nombre');

		$res=array();
		if(!empty($todos))
			$res[0]='Todos';
			if(!empty($r)){
				foreach($r as $s)
					$res[$s['Rol']['id']]=$s['Rol']['nombre'];
			}

		return $res;
	}

}
?>
