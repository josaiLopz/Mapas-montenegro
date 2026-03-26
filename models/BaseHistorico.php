<?php
class BaseHistorico extends AppModel {
    var $name = 'BaseHistorico';
    var $useTable = 'bases_historial'; // Nombre de la tabla en la base de datos
    
    // Opcional: relaciones si las necesitas
    var $belongsTo = array(
        'Usuario' => array(
            'className' => 'Usuario',
            'foreignKey' => 'usuario_modificacion'
        )
    );
}
?>