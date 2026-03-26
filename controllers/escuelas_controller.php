<?php
class EscuelasController extends AppController {

	var $name = 'Escuelas';
	var $helpers = array('Html', 'Form');
	var $uses=array('Escuela');

	function index(){
		
	}

	function acerca_de(){
		
	}

	function edit($id_google,$nombre,$lat,$lng){

		$distribuidores=$this->Escuela->Usuario->distribuidores();
		$this->set("distribuidores",$distribuidores);
		$this->set('id_google',$id_google);

		$this->data=$this->Escuela->find(array('Escuela.id_maps'=>$id_google));
		$this->data['Escuela']['nombre']=$nombre;
		$this->data['Escuela']['lat']=$lat;
		$this->data['Escuela']['lng']=$lng;

		$this->layout='ajax';
	}

	function guardar(){
		$datos=array();
		$datos['Escuela']=$this->params['form'];
		$datos['Escuela']['id']=trim($datos['Escuela']['id']);
		
		if(empty($datos['Escuela']['alumnos']))
		$datos['Escuela']['alumnos']=0;

		if(empty($datos['Escuela']['id'])){
			$datos['Escuela'] = array_reverse($datos['Escuela']);
			array_pop($datos['Escuela']);
			$this->Escuela->create();
		}

	
		$this->Escuela->save($datos);
		die;
	}

	function cargar(){
		$id_maps=$this->params['form']['id_maps'];
		$this->set("datos",$this->Escuela->find(array('Escuela.id_maps'=>$id_maps)));
		$this->layout='ajax';
	}
}
?>
