<?php
class Ruta extends AppModel {

	var $name = 'Ruta';
	var $useTable = 'rutas';

	var $belongsTo = array(
			'Usuario' => array('className' => 'Usuario',
								'foreignKey' => 'id_usuario',
								'dependent' => false,
								'conditions' => '',
								'fields' => '',
								'order' => ''
			)
	);


}
?>
