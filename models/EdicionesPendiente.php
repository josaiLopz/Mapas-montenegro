<?php
class EdicionesPendiente extends AppModel {
    public $useTable = 'ediciones_pendientes';

    public $belongsTo = array(
        'Base' => array(
            'className' => 'Base',
            'foreignKey' => 'base_id'
        )
    );
}
?>
