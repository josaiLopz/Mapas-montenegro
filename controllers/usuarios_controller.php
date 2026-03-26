<?php
class UsuariosController extends AppController {

	var $name = 'Usuarios';
	var $helpers = array('Html', 'Form');
	var $uses=array('Usuario','Rol');



	function intranet(){

	}

 public function login()
  	  {
		$this->Session->write('Usuario.Intranet', false);
		$userExists = true;
		$user = $this->data["Usuario"]["usern"];
		$password = $this->data["Usuario"]["pssword"];
       	$conditions = array("Usuario.usern" => $user);
//, "Usuario.pssword" => $password
		$userData = $this->Usuario->find($conditions);
		if($userData) {



			if($userData['Usuario']['pssword']!=$password){
					$this->Session->setFlash("<label class='error'>Contrase&ntilde;a Incorrecta.</label>");
			}

			else if($userData['Usuario']['activo']!=1){
					$this->Session->setFlash("<label class='error'>Esta Cuenta esta Desactivada.</label>");
					
			}
			else{
					$this->Session->write('Usuario.padre', $userData['Usuario']['padre']);
					$this->Session->write('Usuario.username', $userData['Usuario']['usern']);
					$this->Session->write('Usuario.userid', $userData['Usuario']['id']);
					$this->Session->write('Usuario.usernom', $userData['Usuario']['nombre']);
					$this->Session->write('Usuario.userfirst', $userData['Usuario']['apellido_p']);
					$this->Session->write('Usuario.useradmin', $userData['Usuario']['admin']);
					$this->Session->write('Usuario.permisos', $userData['Rol']['permisos']);
					$this->Session->write('Usuario.rol', $userData['Rol']['id']);
					$this->Session->write('Usuario.Intranet', '1');


				$this->escribe_log('Entra al Sistema ');
				$this->redirect(array('controller'=>'Usuarios','action'=>'mi_cuenta'));
				
			}
		}else{
			 $this->Session->setFlash("<label class='error'>Nombre de Usuario Incorrecto.</label>");
		 	}
		$this->redirect(array('controller'=>'Usuarios','action'=>'intranet'));
	}

	public function logout(){
		$this->escribe_log('Sale del Sistema ');

			$this->Session->del('Usuario.username');
			$this->Session->del('Usuario.userid');
			$this->Session->del('Usuario.usernom');
			$this->Session->del('Usuario.userfirst');
			$this->Session->del('Usuario.useradmin');
			$this->Session->del('Usuario.permisos');
			$this->Session->del('Usuario.Intranet');
			$this->Session->del('Usuario.rol');
			$this->Session->del('Usuario.Intranet');

		
		$this->redirect(array('controller' => 'intranet', 'action' => 'index'));
	}

	function filtros(){
		$cond=array();

		if(!empty($this->data))
		$this->Session->write('Usuario.filtros',$this->data);

		$filtros=$this->Session->read('Usuario.filtros');
		$this->data=$filtros;


		if(!empty($filtros['Usuario']['id_rol'])){
			$cond[]=array('Usuario.id_rol'=>$filtros['Usuario']['id_rol']);
		}

		if(!empty($filtros['Usuario']['status'])){
			if($filtros['Usuario']['status']==4)
				$cond[]=array('Usuario.status_revision'=>1);
			else
				$cond[]=array('Usuario.status'=>$filtros['Usuario']['status']);
		}

		if(!empty($filtros['Usuario']['nombre'])){
			$txt=$filtros['Usuario']['nombre'];
			$cad="Usuario.nombre like '%".$txt."%'";
			$cond[]=$cad;
		}

		if(!empty($filtros['Usuario']['apellido_p'])){
			$txt=$filtros['Usuario']['apellido_p'];
			$cad="Usuario.apellido_p like '%".$txt."%'";
			$cond[]=$cad;
		}

		if(!empty($filtros['Usuario']['apellido_m'])){
			$txt=$filtros['Usuario']['apellido_m'];
			$cad="Usuario.apellido_m like '%".$txt."%'";
			$cond[]=$cad;
		}

		if(!empty($filtros['Usuario']['usern'])){
			$txt=$filtros['Usuario']['usern'];
			$cad="Usuario.usern like '%".$txt."%'";
			$cond[]=$cad;
		}


		return $cond;
	}
	function index() {
		$cond=$this->filtros();

		$this->paginate['conditions']=$cond;
		$this->paginate['order']='Usuario.modified desc';

		$this->Usuario->recursive = 0;
		$this->set('usuarios', $this->paginate());
		$rols=$this->Rol->listado(1);
		$this->set('roles',$rols);
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Usuario.', true));
			$this->redirect(array('action'=>'index'));
		}
		$rol=$this->Usuario->read(null, $id);
		$this->set('usuario', $rol);
		$this->reestablecer_permisos($rol);
	}

	function add() {
		if (!empty($this->data)) {
			if(empty($this->data['Usuario']['usern'])){
					$this->Session->setFlash(__('Nombre De Usuario Necesario', true));
			}

			else if(empty($this->data['Usuario']['pssword'])){
					$this->Session->setFlash(__('Password Necesario', true));
			}


			else{

				$us=$this->Usuario->find(array('Usuario.usern'=>$this->data['Usuario']['usern']));
				if($us)
					$this->Session->setFlash(__('El nombre de usuario '.$this->data['Usuario']['usern']. ' ya existe, intente con otro', true));
				else{

				$this->data['Usuario']['pssword']=sha1($this->data['Usuario']['pssword']);

				$ses=$_SESSION['Usuario']['rol'];
				if($ses==4){
					$this->data['Usuario']['padre']=$this->id_activo();
				}
				

				$this->Usuario->create();
				if ($this->Usuario->save($this->data)) {

					$this->escribe_log('Usuarios '.$this->data['Usuario']['usern'].' ('.$this->Usuario->id.') creado ');
	
					$this->Session->setFlash(__('El usuario ha sido guardado', true));
				$this->redirect(array('action'=>'index'));
				} else {
					$this->Session->setFlash(__('The Usuario could not be saved. Please, try again.', true));
				}
				}
			}

	
		}

		$this->set('roles',$this->Rol->listado());
	}


	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Usuario', true));
			$this->redirect(array('action'=>'index'));
		}

		if (!empty($this->data)) {
			$usu=$this->Usuario->read(null,$id);
			if($this->data['Usuario']['pssword']==""){
				$this->data['Usuario']['pssword']=$usu['Usuario']['pssword'];
			}

			else{
				$this->data['Usuario']['pssword']=sha1($this->data['Usuario']['pssword']);
			}


			if ($this->Usuario->save($this->data)) {
				$this->escribe_log('Usuarios '.$usu['Usuario']['usern'].' ('.$this->Usuario->id.') editado ');
				$this->Session->setFlash(__('El Usuario ha sido Guardado', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Usuario could not be saved. Please, try again.', true));
			}
		}

		if (empty($this->data)) {

			$this->data = $this->Usuario->read(null, $id);
			$this->data['Usuario']['pssword']="";

			$is_admin=$this->Session->read('Usuario.useradmin');
			if($this->Session->read('Usuario.rol')<$this->data['Usuario']['id_rol'] || !empty($is_admin) ){}
			else{
				$this->Session->setFlash(__('No tienes permiso para editar este usuario', true));
				$this->redirect(array('action'=>'index'));
			}
			
				

			
		}
		$this->set('roles',$this->Rol->listado());
	}

	function delete($id = null) {
		$usu=$this->Usuario->read(null,$id);

		$is_admin=$this->Session->read('Usuario.useradmin');
		if($this->Session->read('Usuario.rol')<$usu['Usuario']['id_rol'] || !empty($is_admin) ){}
		else{
			$this->Session->setFlash(__('No tienes permiso para eliminar este usuario', true));
			$this->redirect(array('action'=>'index'));
		}


		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Usuario', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Usuario->del($id)) {
			$this->escribe_log('Usuarios '.$usu['Usuario']['usern'].' ('.$usu['Usuario']['id'].') eliminado ');
			$this->Session->setFlash(__('Usuario borrado', true));
			$this->redirect(array('action'=>'index'));
		}
	}


	function recuperar_pass(){
		$valor=$this->data['Usuario']['valor'];
		$usu=$this->Usuario->find(array('OR'=>array('Usuario.usern'=>$valor,'Usuario.email'=>$valor)));
		if($usu){
			$pass=$this->crear_pass();
			$this->Usuario->id=$usu['Usuario']['id'];
			$this->Usuario->savefield('pssword',sha1($pass));
			$url_radio="http://".$_SERVER['SERVER_NAME']."/";
			$cad="<h2>Recuperacion de Password</h2><bR> Tu cuenta del Sistema <a href='".$url_radio."'>".$url_radio."</a> ha solicitado un nuevo Password";
		$cad.="<br>Tus nuevos datos son:<bR>Usuario.- ".$usu['Usuario']['usern']."<br>Password.- ".$pass;

				$this->authgMail('','', $usu['Usuario']['email'], $usu['Usuario']['nombre'], 'Recuperacion Password', $cad);
			$this->Session->setFlash("<label class='error'>Un nuevo Password se ha enviado a ".$usu['Usuario']['email']."</label>");
			$this->redirect(array('action'=>'intranet'));			
		}
		else{
			$this->Session->setFlash("<label class='error'>No se encontro un Usuario con esas caracteristicas</label>");
			$this->redirect(array('action'=>'intranet'));			
		}

	}

	function crear_pass(){
	 $length=8;
	 $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
         $code = "";
         $clen = strlen($chars) - 1;  //a variable with the fixed length of chars correct for the fence post issue
         while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0,$clen)];  //mt_rand's range is inclusive - this is why we need 0 to n-1
        }
        return $code;
	}

	function mi_cuenta(){

		if(!empty($this->data)){
			if($this->data['Usuario']['pssword']!="")
				$this->data['Usuario']['pssword']=sha1($this->data['Usuario']['pssword']);
			else{
				$dd=array();
				foreach($this->data['Usuario'] as $ll=>$d){
					if($ll!='pssword')
						$dd[$ll]=$d;
				}
				$this->data['Usuario']=$dd;

			}
			$this->Session->setFlash("<label class='error'>Cambios guardados.</label>");
			$usu=$this->Usuario->read(null,$this->id_activo());
			$this->data['Usuario']['id']=$usu['Usuario']['id'];
			$this->Usuario->save($this->data);
			$this->escribe_log('Usuarios '.$usu['Usuario']['usern'].' ('.$usu['Usuario']['id'].') editado mi cuenta ');
			$this->redirect(array('action'=>'mi_cuenta'));	

		}

		$id_usu=$this->id_activo();
		$this->data=$this->Usuario->read(null,$id_usu);
		$this->data['Usuario']['pssword']='';

	}


	function logs($nom=null){

		if(!empty($nom))
			$this->Session->write('Radio.ver_log',$nom);

		$nom=$this->Session->read('Radio.ver_log');
		if(empty($nom)){
			$this->Session->write('Radio.ver_log','usuarios');
			$nom='log';
		}
		

		
		$usu=$this->Usuario->findAll(array(),'Usuario.usern');
		$this->set(compact('usu'));
	
		if(!empty($this->params['named']['nombre']))
			$this->set('usuario',$this->params['named']['nombre']);

		if(!empty($this->data['Usuario']['buscar']))
			$this->set('buscar',$this->data['Usuario']['buscar']);
		

		$this->set('nom_archivo',$nom);
	}


}
?>
