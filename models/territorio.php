<?php
class Territorio extends AppModel {

	var $name = 'Territorio';
	var $useTable = 'territorios';

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
