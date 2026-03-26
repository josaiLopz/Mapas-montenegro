<?php



//Secciones que no requiere session
//$no_session[Controlador][Accion]=1
//$no_session[Controlador][*] -> que ninguna accion de este controlador requiere session

$config['no_session']['Usuarios']['intranet']=1;
$config['no_session']['Usuarios']['login']=1;
$config['no_session']['Usuarios']['recuperar_pass']=1;
$config['no_session']['Bases']['importar_distribuidores']=1;


//$config['no_session']['Rols']['*']=1;





// $menu[]=array(Controladro,Nombre,url)
//$config['menu'][] =array('Escuelas','Capturar Escuelas',array('controller'=>'Escuelas','action'=>'index'));
//$config['menu'][] =array('Bases','Rutas',array('controller'=>'Bases','action'=>'rutas'));
//$config['menu'][] =array('Rutas','Mis Rutas',array('controller'=>'Rutas','action'=>'index'));
$config['menu'][] =array('Bases','Escuelas',array('controller'=>'Bases','action'=>'index'));
//$config['menu'][] =array('Territorios','Territorios',array('controller'=>'Territorios','action'=>'index'));
$config['menu'][] =array('Escuelas','Acerca de',array('controller'=>'Escuelas','action'=>'acerca_de'));
$config['menu'][] =array('Usuarios','Usuarios',array('controller'=>'Usuarios','action'=>'index'));
$config['menu'][] =array('Rols','Roles',array('controller'=>'Rols','action'=>'index'));
$config['menu'][] =array('Todos','Mi Cuenta',array('controller'=>'Usuarios','action'=>'mi_cuenta'));
$config['menu'][] =array('Todos','Salir',array('controller'=>'Usuarios','action'=>'logout'));



//Secciones que requieren permiso
//	$permiso[Controlador][accion]=Titulo

/*
$config['permiso']['Escuelas']['index'] ="Index Escuelas";
$config['permiso']['Escuelas']['add'] ="Agregar/Editar informacion de escuela";
*/

$config['permiso']['Bases']['index'] ="Escuelas";
$config['permiso']['Bases']['add'] ="Agregar/Editar informacion de escuela";
//$config['permiso']['Bases']['rutas'] ="Ver Rutas";

//$config['permiso']['Rutas']['index'] ="Mis rutas";
//$config['permiso']['Rutas']['add'] ="Agregar nuevas rutas";
//$config['permiso']['Rutas']['delete'] ="Eliminar Rutas";

/*
$config['permiso']['Territorios']['index'] ="Index Terriotorios";
$config['permiso']['Territorios']['add'] ="Agregar Terriotorios";
$config['permiso']['Territorios']['edit'] ="Editar Terriotorios";
$config['permiso']['Territorios']['delete'] ="Eliminar Terriotorios";
*/

	$config['permiso']['Escuelas']['acerca_de'] ="Acerca de";

	$config['permiso']['Usuarios']['index'] ="Index Usuario";
	$config['permiso']['Usuarios']['view'] ="Ver Usuario";
	$config['permiso']['Usuarios']['add'] ="Agregar Usuario";
	$config['permiso']['Usuarios']['edit'] ="Editar Usuario";
	$config['permiso']['Usuarios']['delete'] ="Eliminar Usuario";

	$config['permiso']['Rols']['index'] ="Index Rol";
	$config['permiso']['Rols']['view'] ="Ver Rol";
	$config['permiso']['Rols']['add'] ="Agregar Roles";
	$config['permiso']['Rols']['edit'] ="Editar Roles";
	$config['permiso']['Rols']['delete'] ="Eliminar Roles";


?>
