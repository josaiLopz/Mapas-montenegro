<?php
/* SVN FILE: $Id: app_controller.php 6296 2008-01-01 22:18:17Z phpnut $ */
/**
 * Short description for file.
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework <http://www.cakephp.org/>
 * Copyright 2005-2008, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2005-2008, Cake Software Foundation, Inc.
 * @link				http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package			cake
 * @subpackage		cake.app
 * @since			CakePHP(tm) v 0.2.9
 * @version			$Revision: 6296 $
 * @modifiedby		$LastChangedBy: phpnut $
 * @lastmodified	$Date: 2008-01-01 16:18:17 -0600 (Tue, 01 Jan 2008) $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 * Short description for class.
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		cake
 * @subpackage	cake.app
 */
class AppController extends Controller {
   var $helpers = array('Javascript', 'Form', 'Html', 'Session', 'text');
   var $is_admin=0;
   var $mis_permisos="";



	function beforeFilter(){
		$this->set('titulo_pag',"Mapa Distribuidores Montenegro");
		configure::load("app/configuracion"); 
		$no_session=configure::read("no_session"); 

		if(!empty($no_session[$this->name]['*'])){
			$this->set('is_admin',0);
			$this->set('mis_permisos',"");
			$this->set('c_permisos',array());

		}

		else if(!empty($no_session[$this->name][$this->action])){
			$this->set('is_admin',0);
			$this->set('mis_permisos',"");
			$this->set('c_permisos',array());
		}


		else{
			$ses=$this->existe_session();
			if(!$ses){
				 $this->Session->setFlash("<label class='error'>Se necesita Usuario y Contrase&ntilde;a.</label>");
				 $this->redirect(array('controller'=>'Usuarios','action'=>'intranet'));
				die;
			}

		




		$is_admin=$this->Session->read('Usuario.useradmin');
		$mis_permisos=$this->Session->read('Usuario.permisos');

		$c_permisos=explode("@",$mis_permisos);


		$ver=0;

			if(!empty($is_admin))
				$ver=1;

			else{

				$secciones=configure::read("permiso"); 
				if(empty($secciones[$this->name][$this->action]))
					$ver=1;

				
				else{
					$s=$this->name."|".$this->action;
					if(array_search($s,$c_permisos)!==false)
							$ver=1;
				}

			}



			if($ver==0){
				 $this->Session->setFlash("<label class='error'>No se tiene permios para la seccion ".$this->name."/".$this->action."</label>");
				 $this->redirect(array('controller'=>'Intranet','action'=>'inicio'));
				die;

			}

		$this->set('is_admin',$is_admin);
		$this->set('mis_permisos',$mis_permisos);
		$this->set('c_permisos',$c_permisos);
		$this->set('login',$this->Session->read('Usuario.Intranet'));
		$this->set('user_rol',$this->Session->read('Usuario.rol'));
		}
		
	}

	function existe_session(){
		$ses=$this->Session->read('Usuario.Intranet');

		return $ses;
	}


	function reestablecer_permisos($per1){
		$per=explode("@",$per1['Rol']['permisos']);


		if(!empty($per)){
			foreach($per as $l){
				if(!empty($l))
					$this->data['Rol'][$l]=1;
			}

		}


	}



  function id_activo(){
		return $this->Session->read('Usuario.userid');

}



function  dame_usuario_modifica(){
	$id_a=$this->id_activo();

	$usuario=$this->Session->read('Usuario.username').":".$this->Session->read('Usuario.usernom')." ".$this->Session->read('Usuario.userfirst')." (".$id_a.")";
	return $usuario;

}



 public function escribe_log($mensaje){

	configure::load("app/app"); 
	$dir=configure::read("log.usuarios"); 


$usuario=$this->dame_usuario_modifica();
$datos=date('Y-m-d H:i:s')." ".$mensaje." del usuario ".$usuario."\n";

@file_put_contents($dir  ,$datos,FILE_APPEND);

}


	function authgMail($from, $namefrom, $to, $nameto, $subject, $message,$user='noreply@opengate.com.mx', $pass='8C4621')
	{


		$from='noreply@opengate.com.mx';
		$user='noreply@opengate.com.mx';
	        $pass='8C462122';
		/*  your configuration here  */
		$smtpServer = "tls://smtp.gmail.com"; //does not accept STARTTLS
		$port = "465"; // try 587 if this fails
		$timeout = "45"; //typical timeout. try 45 for slow servers
		$username = $user; //your gmail account
		$password = $pass; //the pass for your gmail
		$localhost = "opengate.com.mx"; //$_SERVER['REMOTE_ADDR']; //requires a real ip
		$newLine = "\r\n"; //var just for newlines

		/*  you shouldn't need to mod anything else */
		//connect to the host and port
		$smtpConnect = fsockopen($smtpServer, $port, $errno, $errstr, $timeout);
		//echo $errstr." - ".$errno;
		$smtpResponse = fgets($smtpConnect, 4096);
		if(empty($smtpConnect))
		{
   			$output = "Failed to connect: $smtpResponse";
		   //echo $output;
   			return $output;
		}
		else
		{
   			$logArray['connection'] = "Connected to: $smtpResponse";
		   //echo "connection accepted<br>".$smtpResponse."<p />Continuing<p />";
		}

		//you have to say HELO again after TLS is started
   		fputs($smtpConnect, "HELO $localhost". $newLine);
   		$smtpResponse = fgets($smtpConnect, 4096);
   		$logArray['heloresponse2'] = "$smtpResponse";
  
		//request for auth login
		fputs($smtpConnect,"AUTH LOGIN" . $newLine);
		$smtpResponse = fgets($smtpConnect, 4096);
		$logArray['authrequest'] = "$smtpResponse";

		//send the username
		fputs($smtpConnect, base64_encode($username) . $newLine);
		$smtpResponse = fgets($smtpConnect, 4096);
		$logArray['authusername'] = "$smtpResponse";

		//send the password
		fputs($smtpConnect, base64_encode($password) . $newLine);
		$smtpResponse = fgets($smtpConnect, 4096);
		$logArray['authpassword'] = "$smtpResponse";

		//email from
		fputs($smtpConnect, "MAIL FROM: <$from>" . $newLine);
		$smtpResponse = fgets($smtpConnect, 4096);
		$logArray['mailfromresponse'] = "$smtpResponse";

		//email to
		fputs($smtpConnect, "RCPT TO: <$to>" . $newLine);
		$smtpResponse = fgets($smtpConnect, 4096);
		$logArray['mailtoresponse'] = "$smtpResponse";

		//the email
		fputs($smtpConnect, "DATA" . $newLine);
		$smtpResponse = fgets($smtpConnect, 4096);
		$logArray['data1response'] = "$smtpResponse";

		//construct headers
		$headers = "MIME-Version: 1.0" . $newLine;
		$headers .= "Content-type: text/html; charset=iso-8859-1" . $newLine;
		$headers .= "To: $nameto <$to>" . $newLine;
		$headers .= "From: $namefrom <$from>" . $newLine;

		//observe the . after the newline, it signals the end of message
		fputs($smtpConnect, "To: $to\r\nFrom: $from\r\nSubject: $subject\r\n$headers\r\n\r\n$message\r\n.\r\n");
		$smtpResponse = fgets($smtpConnect, 4096);
		$logArray['data2response'] = "$smtpResponse";

		// say goodbye
		fputs($smtpConnect,"QUIT" . $newLine);
		$smtpResponse = fgets($smtpConnect, 4096);
		$logArray['quitresponse'] = "$smtpResponse";
		$logArray['quitcode'] = substr($smtpResponse,0,3);
		fclose($smtpConnect);
		//a return value of 221 in $retVal["quitcode"] is a success
		return($logArray);
	}


}
?>
