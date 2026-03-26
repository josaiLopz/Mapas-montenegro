<div class="historial view">
    <h2>Detalles del Cambio #<?php echo $registro['id']; ?></h2>
    
    <dl>
        <dt>Escuela Original</dt>
        <dd>
            <?php 
            echo $this->Html->link(
                $registro['id_original'], 
                array('action' => 'edit', $registro['id_original'])
            ); 
            ?>
        </dd>
        
        <dt>Nombre</dt>
        <dd><?php echo utf8_encode($registro['nombre']); ?></dd>
        
        <dt>CCT</dt>
        <dd><?php echo $registro['cct']; ?></dd>
        
        <dt>Tipo</dt>
        <dd><?php echo $registro['tipo']; ?></dd>
        
        <dt>Sector</dt>
        <dd><?php echo utf8_encode($registro['sector']); ?></dd>
        
        <dt>Modificado por</dt>
        <dd>
            <?php 
            echo $usuario['Usuario']['nombre'].' '.
                 $usuario['Usuario']['apellido_p'].' '.
                 $usuario['Usuario']['apellido_m']; 
            ?>
        </dd>
        
        <dt>Fecha</dt>
        <dd><?php echo $registro['fecha_modificacion']; ?></dd>
    </dl>
    
    <div class="actions">
        <?php echo $this->Html->link('Volver al Historial', array('action' => 'historial')); ?>
    </div>
</div>