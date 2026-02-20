<?php
/** @var \App\Model\Entity\Ticket $ticket */
$this->assign('title', 'Detalle de ticket');
$this->Html->css('tickets', ['block' => true]);

$statusClassMap = [
    'nuevo' => 'badge-status-nuevo',
    'en_proceso' => 'badge-status-en-proceso',
    'esperando_usuario' => 'badge-status-esperando-usuario',
    'resuelto' => 'badge-status-resuelto',
    'cerrado' => 'badge-status-cerrado',
];
$priorityClassMap = [
    'baja' => 'badge-priority-baja',
    'media' => 'badge-priority-media',
    'alta' => 'badge-priority-alta',
    'critica' => 'badge-priority-critica',
];
$typeClassMap = [
    'error' => 'badge-type-error',
    'mejora' => 'badge-type-mejora',
    'soporte' => 'badge-type-soporte',
];

$statusClass = $statusClassMap[$ticket->status] ?? 'badge-status-nuevo';
$priorityClass = $priorityClassMap[$ticket->priority] ?? 'badge-priority-media';
$typeClass = $typeClassMap[$ticket->type] ?? 'badge-type-soporte';
$updateTypeClassMap = [
    'created' => 'badge-type-soporte',
    'comment' => 'badge-type-mejora',
    'status_change' => 'badge-type-error',
];
?>

<div class="tickets-shell ticket-view">
    <section class="tickets-card">
    <div class="ticket-detail-head">
        <div>
            <h2 class="tickets-title"><?= h($ticket->folio ?: ('#' . $ticket->id)) ?> - <?= h($ticket->title) ?></h2>
            <div class="ticket-meta">
                <span class="badge <?= h($typeClass) ?>"><?= h($typeLabels[$ticket->type] ?? $ticket->type) ?></span>
                <span class="badge <?= h($priorityClass) ?>"><?= h($priorityLabels[$ticket->priority] ?? $ticket->priority) ?></span>
                <span class="badge <?= h($statusClass) ?>"><?= h($statusLabels[$ticket->status] ?? $ticket->status) ?></span>
            </div>
            <p class="tickets-subtitle">Solicitante: <?= h($ticket->requester->name ?? $ticket->requester->email ?? 'N/A') ?> | Asignado: <?= h($ticket->assignee->name ?? 'Sin asignar') ?></p>
        </div>
        <div>
            <?= $this->Html->link('Volver', ['action' => 'index'], ['class' => 'button button-outline']) ?>
        </div>
    </div>
    </section>

    <div class="ticket-view-grid">
        <div class="ticket-view-main">
            <section class="ticket-block">
                <h4>Descripcion</h4>
                <p><?= nl2br(h($ticket->description)) ?></p>
            </section>

            <?php if ($canChangeStatus): ?>
            <section class="ticket-block">
                <h4>Cambiar estatus</h4>
                <?= $this->Form->create(null, ['url' => ['action' => 'updateStatus', $ticket->id]]) ?>
                <div class="ticket-grid">
                    <?= $this->Form->control('status', [
                        'label' => 'Nuevo estatus',
                        'type' => 'select',
                        'options' => $statusOptions,
                        'empty' => false,
                        'value' => $ticket->status,
                    ]) ?>

                    <?php if ($canManage): ?>
                        <?= $this->Form->control('assigned_to', [
                            'label' => 'Asignar a',
                            'type' => 'select',
                            'options' => $assignees,
                            'empty' => 'Sin asignar',
                            'value' => $ticket->assigned_to,
                        ]) ?>
                    <?php endif; ?>

                    <div class="field-full">
                        <?= $this->Form->control('status_comment', [
                            'label' => 'Comentario del cambio',
                            'type' => 'textarea',
                            'rows' => 3,
                        ]) ?>
                    </div>
                </div>
                <button class="button" type="submit">Actualizar estatus</button>
                <?= $this->Form->end() ?>
            </section>
            <?php endif; ?>

            <section class="ticket-block">
                <h4>Agregar seguimiento</h4>
                <?= $this->Form->create(null, ['type' => 'file', 'url' => ['action' => 'addUpdate', $ticket->id]]) ?>
                <?= $this->Form->control('message', [
                    'label' => 'Comentario',
                    'type' => 'textarea',
                    'rows' => 4,
                    'placeholder' => 'Describe avance, respuesta o informacion adicional.',
                ]) ?>
                <?= $this->Form->control('attachments[]', [
                    'label' => 'Adjuntar archivos',
                    'type' => 'file',
                    'multiple' => true,
                    'accept' => '.jpg,.jpeg,.png,.gif,.webp,.pdf,.doc,.docx,.xls,.xlsx,.csv',
                ]) ?>
                <button class="button" type="submit">Guardar seguimiento</button>
                <?= $this->Form->end() ?>
            </section>
        </div>

        <aside class="ticket-view-side">
            <section class="ticket-block">
                <h4>Historial</h4>
                <?php if (!empty($ticket->ticket_updates)): ?>
                    <div class="timeline">
                        <?php foreach ($ticket->ticket_updates as $item): ?>
                            <?php $updateTypeClass = $updateTypeClassMap[$item->update_type] ?? 'badge-type-soporte'; ?>
                            <article class="timeline-item">
                                <header class="timeline-header">
                                    <strong><?= h($item->creator->name ?? $item->creator->email ?? 'Usuario') ?></strong>
                                    <span class="tickets-muted"><?= h($item->created?->format('Y-m-d H:i')) ?></span>
                                </header>
                                <div class="timeline-meta">
                                    <span class="badge <?= h($updateTypeClass) ?>"><?= h($item->update_type) ?></span>
                                </div>
                                <?php if (!empty($item->status_from) || !empty($item->status_to)): ?>
                                    <p>
                                        Cambio de estatus:
                                        <strong><?= h($statusLabels[$item->status_from] ?? $item->status_from ?: 'N/A') ?></strong>
                                        ->
                                        <strong><?= h($statusLabels[$item->status_to] ?? $item->status_to ?: 'N/A') ?></strong>
                                    </p>
                                <?php endif; ?>
                                <?php if (!empty($item->message)): ?>
                                    <p><?= nl2br(h($item->message)) ?></p>
                                <?php endif; ?>

                                <?php if (!empty($item->ticket_attachments)): ?>
                                    <ul class="attach-list">
                                        <?php foreach ($item->ticket_attachments as $file): ?>
                                            <li>
                                                <?= $this->Html->link(
                                                    h($file->original_name),
                                                    ['action' => 'downloadAttachment', $file->id]
                                                ) ?>
                                                (<?= h($file->extension) ?>, <?= h(number_format(((int)$file->file_size) / 1024, 1)) ?> KB)
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="tickets-muted">No hay seguimiento registrado todavia.</p>
                <?php endif; ?>
            </section>
        </aside>
    </div>
</div>
