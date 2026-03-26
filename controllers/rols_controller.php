<?php
class RolsController extends AppController {

	var $name = 'Rols';
	var $helpers = array('Html', 'Form');

	function index() {
		$this->Rol->recursive = 0;
		$this->set('rols', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Rol', true));
		}
		$rol=$this->Rol->read(null, $id);
		$this->set('rol', $rol);
		$this->reestablecer_permisos($rol);

	}

	function establece_permisos(){
		$this->data['Rol']['permisos']="";
		foreach($this->data['Rol'] as $l=>$m){
			if(!empty($m) && $l!='nombre' && $l!='id')
				$this->data['Rol']['permisos'].=$l."@";
		}

	}

	function add() {
		if (!empty($this->data)) {
			$this->establece_permisos();
			$this->Rol->create();
			if ($this->Rol->save($this->data)) {
				$this->Session->setFlash(__('Rol saved.', true));

				$this->escribe_log('Rol '.$this->data['Rol']['nombre'].' ('.$this->Rol->id.') agregado');
				$this->redirect(array('action'=>'index'));
			}
			
		}
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Rol', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			$this->establece_permisos();


			if ($this->Rol->save($this->data)) {
				$this->Session->setFlash(__('The Rol has been saved.', true));
				$this->escribe_log('Rol '.$this->data['Rol']['nombre'].' ('.$this->Rol->id.') editado');
				$this->redirect(array('action'=>'index'));
				
			} 
		}
		if (empty($this->data)) {
			$this->data = $this->Rol->read(null, $id);
		}

		$this->reestablecer_permisos($this->data);
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Rol', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Rol->del($id)) {
			$this->Session->setFlash(__('Rol deleted', true));
			$this->escribe_log('Rol  ('.$id.') eliminado');

		}
			$this->redirect(array('action'=>'index'));
	}

}
?>
