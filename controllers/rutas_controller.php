<?php
class RutasController extends AppController {

	var $name = 'Rutas';
	var $helpers = array('Html', 'Form');
	var $uses=array('Ruta','Base','Escuela');

	function index(){
		$distribuidores=$this->Escuela->Usuario->distribuidores();
		$distribuidores[0]='Cualquiera';
		$this->set("distribuidores",$distribuidores);

		$this->paginate['conditions']=array('Ruta.id_usuario'=>$this->id_activo());
		$this->paginate['order']=array('Ruta.created');

		$rutas=$this->paginate();

		$rutas_ids=array();
		foreach($rutas as $r){
			$ids=explode(",",$r['Ruta']['ids']);
			$rutas_ids[$r['Ruta']['id']]=$this->Base->findAll(array('Base.id'=>$ids));
		}
		
		$this->set('rutas_ids',$rutas_ids);
		$this->set('rutas',$rutas);

	}

	function delete($id = null) {

		if ($this->Ruta->del($id)) {
			$this->Session->setFlash(__('Ruta eliminada', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Erro al eliminar ruta', true));
		$this->redirect(array('action'=>'index'));
	}



}
?>
