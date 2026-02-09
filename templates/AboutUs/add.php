<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\AboutU $aboutU
 */
?>

<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Acciones') ?></h4>
            <?= $this->Html->link(__('Listar About Us'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>

    <div class="column column-80">
        <div class="aboutUs form content">

            <!-- ✅ FORMULARIO CORRECTO -->
            <?= $this->Form->create($aboutU, ['type' => 'file']) ?>

            <fieldset>
                <legend><?= __('Agregar About Us') ?></legend>

                <?= $this->Form->control('title', [
                    'label' => 'Título'
                ]) ?>

                <?= $this->Form->control('content', [
                    'type' => 'textarea',
                    'label' => 'Contenido'
                ]) ?>

                <!-- ✅ CAMPO DE ARCHIVO -->
                <?= $this->Form->control('image_file', [
                    'type' => 'file',
                    'label' => 'Imagen'
                ]) ?>

                <?= $this->Form->control('active', [
                    'type' => 'checkbox',
                    'label' => 'Activo'
                ]) ?>

            </fieldset>

            <?= $this->Form->button(__('Guardar')) ?>
            <?= $this->Form->end() ?>

        </div>
    </div>
</div>
