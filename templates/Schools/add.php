<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\School $school
 * @var \Cake\Collection\CollectionInterface|string[] $users
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Schools'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="schools form content">
            <?= $this->Form->create($school) ?>
            <fieldset>
                <legend><?= __('Add School') ?></legend>
                <?php
                    echo $this->Form->control('nombre');
                    echo $this->Form->control('estado_id', [
                        'label' => 'Estado',
                        'options' => $estados,
                        'empty' => '-- Seleccione estado --'
                    ]);
                   echo $this->Form->control('municipio_id', [
    'label' => 'Municipio',
    'options' => $municipios,
    'empty' => '-- Seleccione municipio --'
]);
                    echo $this->Form->control('user_id', [
                        'label' => 'Usuario asignado',
                        'options' => $users,
                        'empty' => '-- Selecciona un usuario --'
                    ]);
                    echo $this->Form->control('cct', [
                        'label' => 'CCT',
                        'placeholder' => 'Ej. 14DJN0001X'
                    ]);
                    echo $this->Form->control('tipo');
                    echo $this->Form->control('sector');
                    echo $this->Form->control('turno');
                    echo $this->Form->control('num_alumnos');
                    echo $this->Form->control('estatus', [
                        'type' => 'select',
                        'options' => [
                            'noAtendida' => 'No atendida',
                            'escuelaPromocion'  => 'Escuela en promoción',
                            'ventaConfirmada'  => 'Venta confirmada',
                            'prohibicion' => 'Prohibicion',
                            'ventaMarcas' => 'Venta otras marcas'
                        ],
                        'empty' => 'Seleccione estatus'
                    ]);
                    echo $this->Form->control('lat');
                    echo $this->Form->control('lng');
                    echo $this->Form->control('grupos');
                    echo $this->Form->control('nombre_contacto');
                    echo $this->Form->control('telefono_contacto');
                    echo $this->Form->control('correo_contacto');
                    echo $this->Form->control('presupuesto');
                    echo $this->Form->control('notas', ['type' => 'textarea']);
                    echo $this->Form->control('verificada');
                    echo $this->Form->control('editorial_actual');
                    echo $this->Form->control('venta_montenegro');
                    echo $this->Form->control('competencia');
                    echo $this->Form->control('fecha_decision', ['empty' => true]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
