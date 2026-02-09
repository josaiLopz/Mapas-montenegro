<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\School $school
 * @var string[]|\Cake\Collection\CollectionInterface $users
 * @var string[]|\Cake\Collection\CollectionInterface $estados
 * @var string[]|\Cake\Collection\CollectionInterface $municipios
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $school->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $school->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Schools'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>

    <div class="column column-80">
        <div class="schools form content">
            <?= $this->Form->create($school) ?>
            <fieldset>
                <legend><?= __('Edit School') ?></legend>

                <!-- Información general -->
                <?= $this->Form->control('nombre') ?>
                <?= $this->Form->control('cct', ['label' => 'CCT', 'placeholder' => 'Ej. 14DJN0001X']) ?>
                <?= $this->Form->control('tipo') ?>
                <?= $this->Form->control('sector') ?>
                <?= $this->Form->control('turno') ?>
                <?= $this->Form->control('num_alumnos') ?>

                <!-- Estado y Municipio -->
                <?= $this->Form->control('estado_id', [
                    'label' => 'Estado',
                    'options' => $estados,
                    'empty' => '-- Seleccione estado --'
                ]) ?>
                <?= $this->Form->control('municipio_id', [
                    'label' => 'Municipio',
                    'options' => $municipios,
                    'empty' => '-- Seleccione municipio --'
                ]) ?>

                <!-- Usuario / Distribuidor -->
                <?= $this->Form->control('user_id', [
                    'label' => 'users',
                    'type' => 'select',
                    'options' => $users,
                    'empty' => '-- Seleccione distribuidor --'
                ]) ?>

                <!-- Coordenadas y grupos -->
                <?= $this->Form->control('lat') ?>
                <?= $this->Form->control('lng') ?>
                <?= $this->Form->control('grupos') ?>

                <!-- Contacto -->
                <?= $this->Form->control('nombre_contacto') ?>
                <?= $this->Form->control('telefono_contacto') ?>
                <?= $this->Form->control('correo_contacto') ?>

                <!-- Presupuesto y notas -->
                <?= $this->Form->control('presupuesto') ?>
                <?= $this->Form->control('notas', ['type' => 'textarea']) ?>

                <!-- Estatus -->
                <?= $this->Form->control('estatus', [
                    'type' => 'select',
                    'options' => ['noAtendida' => 'No atendida', 'escuelaPromocion'  => 'Escuela en promoción', 'ventaConfirmada'  => 'Venta confirmada', 'prohibicion' => 'Prohibicion', 'ventaMarcas', 'Venta otras marcas'],
                    'empty' => '-- Seleccione estatus --'
                ]) ?>


                <!-- Booleanos -->
                <?= $this->Form->control('verificada', [
                    'type' => 'select',
                    'options' => [1 => 'Sí', 0 => 'No']
                ]) ?>
                <?= $this->Form->control('venta_montenegro', [
                    'type' => 'select',
                    'options' => [1 => 'Sí', 0 => 'No']
                ]) ?>

                <!-- Editorial, competencia y fecha -->
                <?= $this->Form->control('editorial_actual') ?>
                <?= $this->Form->control('competencia') ?>
                <?= $this->Form->control('fecha_decision', ['empty' => true]) ?>

            </fieldset>

            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
