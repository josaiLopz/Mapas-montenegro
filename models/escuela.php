<?php
class Escuela extends AppModel {

	var $name = 'Escuela';
	var $useTable = 'escuelas';

	var $belongsTo = array(
			'Usuario' => array('className' => 'Usuario',
								'foreignKey' => 'id_distribuidor',
								'dependent' => false,
								'conditions' => '',
								'fields' => '',
								'order' => ''
			)
	);


}
?>
