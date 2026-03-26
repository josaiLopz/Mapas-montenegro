<?php
class TerritoriosController extends AppController {

	var $name = 'Territorios';
	var $helpers = array('Html', 'Form');
	var $uses=array('Territorio');

	function index(){
		$distribuidores=$this->Territorio->Usuario->distribuidores();
		$this->set("distribuidores",$distribuidores);
	}

	function guardar(){
		$datos['Territorio']=$this->params['form'];

		$datos['Territorio']['id']=trim($datos['Territorio']['id']);
		if(empty($datos['Territorio']['id'])){
			$datos['Territorio'] = array_reverse($datos['Territorio']);
			array_pop($datos['Territorio']);
			$this->Territorio->create();
		}


		$this->Territorio->save($datos);
		die;
	}

	function cargar(){
		$this->set("territorios",$this->Territorio->findAll(array(),array(),array('Usuario.nombre','Usuario.apellido_p','Usuario.apellido_m')));
		$this->layout='ajax';
	}

	function eliminar(){
		$id=$this->params['form']['id'];
		$this->Territorio->del($id);
		die;
	}

}
?>
