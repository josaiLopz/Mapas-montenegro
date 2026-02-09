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

            <?= $this->Form->postLink(
                __('Eliminar'),
                ['action' => 'delete', $aboutU->id],
                [
                    'confirm' => __('¿Seguro que deseas eliminar este registro?'),
                    'class' => 'side-nav-item'
                ]
            ) ?>

            <?= $this->Html->link(
                __('Listar About Us'),
                ['action' => 'index'],
                ['class' => 'side-nav-item']
            ) ?>
        </div>
    </aside>

    <div class="column column-80">
        <div class="aboutUs form content">

            <!-- ✅ FORMULARIO -->
            <?= $this->Form->create($aboutU, ['type' => 'file']) ?>

            <fieldset>
                <legend><?= __('Editar About Us') ?></legend>

                <?= $this->Form->control('title', [
                    'label' => 'Título'
                ]) ?>

                <?= $this->Form->control('content', [
                    'type' => 'textarea',
                    'label' => 'Contenido'
                ]) ?>

                <!-- ✅ PREVISUALIZACIÓN -->
                <?php if (!empty($aboutU->image)): ?>
                    <div style="margin-bottom: 15px">
                        <label>Imagen actual</label><br>
                        <img
                            src="/img/about/<?= h($aboutU->image) ?>"
                            style="max-width: 250px; border: 1px solid #ccc; padding: 5px"
                        >
                    </div>
                <?php endif; ?>

                <!-- ✅ NUEVA IMAGEN -->
                <?= $this->Form->control('image_file', [
                    'type' => 'file',
                    'label' => 'Cambiar imagen'
                ]) ?>

                <?= $this->Form->control('active', [
                    'type' => 'checkbox',
                    'label' => 'Activo'
                ]) ?>

            </fieldset>

            <?= $this->Form->button(__('Guardar cambios')) ?>
            <?= $this->Form->end() ?>

        </div>
    </div>
</div>
