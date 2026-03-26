<?php
class Usuario extends AppModel {

	var $name = 'Usuario';
	var $useTable = 'usuarios';

	var $belongsTo = array(
			'Rol' => array('className' => 'Rol',
								'foreignKey' => 'id_rol',
								'dependent' => false,
								'conditions' => '',
								'fields' => '',
								'order' => ''
			)
	);

	function distribuidores(){

		$ses=$_SESSION['Usuario']['rol'];

		if($ses==4){
			$s=$this->findAll(array('Usuario.id'=>$_SESSION['Usuario']['userid']),array(),array('Usuario.apellido_p','Usuario.apellido_m','Usuario.nombre'));
			$res=array();
			foreach($s as $a)
				$res[$a['Usuario']['id']]=$a['Usuario']['apellido_p']." ".$a['Usuario']['apellido_m']." ".$a['Usuario']['nombre'];
	
			return $res;
		}

		if($ses==6 || $ses==5){
		
			$s=$this->findAll(array('Usuario.id'=>$_SESSION['Usuario']['padre']),array(),array('Usuario.apellido_p','Usuario.apellido_m','Usuario.nombre'));
			$res=array();
			foreach($s as $a)
				$res[$a['Usuario']['id']]=$a['Usuario']['apellido_p']." ".$a['Usuario']['apellido_m']." ".$a['Usuario']['nombre'];

			return $res;
		}

		$s=$this->findAll(array('activo'=>1,'id_rol'=>4),array(),array('Usuario.apellido_p','Usuario.apellido_m','Usuario.nombre'));
		$res=array(0=>'Cualquiera',99999=>'Vacante');
		foreach($s as $a)
			$res[$a['Usuario']['id']]=$a['Usuario']['apellido_p']." ".$a['Usuario']['apellido_m']." ".$a['Usuario']['nombre'];

		return $res;
	}

	function beforefind($qry){

	if(stripos($_SERVER["REQUEST_URI"],'/Usuarios')!==false){
		$ses=$_SESSION['Usuario']['rol'];

		if($ses==4){
			$qry['conditions'][]=array("Usuario.padre"=>$_SESSION['Usuario']['userid']);
		}
	}

		return $qry;

	}

}
?>
