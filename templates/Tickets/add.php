<?php
/** @var \App\Model\Entity\Ticket $ticket */
$this->assign('title', 'Nueva incidencia');
$this->Html->css('tickets', ['block' => true]);
?>

<div class="tickets-shell tickets-add">
    <div class="tickets-head">
        <div>
            <h2 class="tickets-title">Registrar incidencia</h2>
            <p class="tickets-subtitle">Reporta errores del sistema o solicitudes de mejora con evidencia.</p>
        </div>
    </div>

    <section class="tickets-card">
    <?= $this->Form->create($ticket, ['type' => 'file']) ?>
    <div class="ticket-grid">
        <?= $this->Form->control('title', ['label' => 'Titulo']) ?>
        <?= $this->Form->control('type', [
            'label' => 'Tipo',
            'type' => 'select',
            'options' => $typeLabels,
            'empty' => false,
        ]) ?>

        <?= $this->Form->control('priority', [
            'label' => 'Prioridad',
            'type' => 'select',
            'options' => $priorityLabels,
            'empty' => false,
        ]) ?>

        <?php if (!empty($assignees) && $canManage): ?>
            <?= $this->Form->control('assigned_to', [
                'label' => 'Asignar a',
                'type' => 'select',
                'options' => $assignees,
                'empty' => 'Sin asignar',
            ]) ?>
        <?php endif; ?>

        <div class="field-full">
            <?= $this->Form->control('description', [
                'label' => 'Descripcion del problema o mejora',
                'type' => 'textarea',
                'rows' => 8,
            ]) ?>
        </div>

        <div class="field-full">
            <?= $this->Form->control('attachments[]', [
                'label' => 'Adjuntos (imagenes, pdf, excel, word, csv)',
                'type' => 'file',
                'multiple' => true,
                'accept' => '.jpg,.jpeg,.png,.gif,.webp,.pdf,.doc,.docx,.xls,.xlsx,.csv',
            ]) ?>
            <small class="tickets-help-note">Tamano maximo por archivo: 15MB. Recomendado: capturas claras + descripcion corta del error.</small>
        </div>
    </div>

    <div class="ticket-actions">
        <button type="submit" class="button button-primary">Crear ticket</button>
        <?= $this->Html->link('Cancelar', ['action' => 'index'], ['class' => 'button button-outline']) ?>
    </div>
    <?= $this->Form->end() ?>
    </section>
</div>
