<?php
/** @var \Cake\Collection\CollectionInterface|iterable $tickets */
$this->assign('title', 'Tickets de soporte');
$this->Html->css('tickets', ['block' => true]);

$statusLabels = $statusLabels ?? [];
$typeLabels = $typeLabels ?? [];
$priorityLabels = $priorityLabels ?? [];

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

$ticketRows = is_iterable($tickets) ? iterator_to_array($tickets) : [];
$totalPage = count($ticketRows);
$openCount = 0;
$resolvedCount = 0;
$criticalCount = 0;
foreach ($ticketRows as $row) {
    if (in_array((string)$row->status, ['nuevo', 'en_proceso', 'esperando_usuario'], true)) {
        $openCount++;
    }
    if (in_array((string)$row->status, ['resuelto', 'cerrado'], true)) {
        $resolvedCount++;
    }
    if ((string)$row->priority === 'critica') {
        $criticalCount++;
    }
}
?>

<div class="tickets-shell tickets-index">
    <div class="tickets-head">
        <div>
            <h2 class="tickets-title">Tickets de soporte</h2>
            <p class="tickets-subtitle">Monitorea incidencias, seguimiento y tiempos de respuesta.</p>
        </div>
        <div class="tickets-head-actions">
            <?= $this->Html->link('Nueva incidencia', ['action' => 'add'], ['class' => 'button button-primary']) ?>
        </div>
    </div>

    <section class="tickets-stats">
        <article class="tickets-stat-card">
            <span class="tickets-stat-label">Tickets en pagina</span>
            <strong class="tickets-stat-value"><?= h((string)$totalPage) ?></strong>
        </article>
        <article class="tickets-stat-card">
            <span class="tickets-stat-label">Abiertos</span>
            <strong class="tickets-stat-value"><?= h((string)$openCount) ?></strong>
        </article>
        <article class="tickets-stat-card">
            <span class="tickets-stat-label">Resueltos / cerrados</span>
            <strong class="tickets-stat-value"><?= h((string)$resolvedCount) ?></strong>
        </article>
        <article class="tickets-stat-card">
            <span class="tickets-stat-label">Criticos</span>
            <strong class="tickets-stat-value"><?= h((string)$criticalCount) ?></strong>
        </article>
    </section>

    <section class="tickets-card">
    <?= $this->Form->create(null, ['type' => 'get', 'class' => 'tickets-filters']) ?>
    <?= $this->Form->control('scope', [
        'label' => 'Vista',
        'type' => 'select',
        'options' => $canManage ? ['mine' => 'Mis tickets', 'all' => 'Todos'] : ['mine' => 'Mis tickets'],
        'value' => $scope,
    ]) ?>
    <?= $this->Form->control('status', [
        'label' => 'Estatus',
        'type' => 'select',
        'empty' => 'Todos',
        'options' => $statusLabels,
        'value' => $status,
    ]) ?>
    <?= $this->Form->control('type', [
        'label' => 'Tipo',
        'type' => 'select',
        'empty' => 'Todos',
        'options' => $typeLabels,
        'value' => $type,
    ]) ?>
    <div class="filter-actions">
        <button class="button" type="submit">Filtrar</button>
    </div>
    <?= $this->Form->end() ?>
    </section>

    <section class="tickets-card">
    <div class="tickets-table-wrap">
        <table class="tickets-table">
            <thead>
            <tr>
                <th>Folio</th>
                <th>Titulo</th>
                <th>Tipo</th>
                <th>Prioridad</th>
                <th>Estatus</th>
                <th>Solicitante</th>
                <th>Asignado</th>
                <th>Fecha</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($ticketRows as $ticket): ?>
                <?php
                    $statusClass = $statusClassMap[$ticket->status] ?? 'badge-status-nuevo';
                    $priorityClass = $priorityClassMap[$ticket->priority] ?? 'badge-priority-media';
                    $typeClass = $typeClassMap[$ticket->type] ?? 'badge-type-soporte';
                ?>
                <tr>
                    <td><?= h($ticket->folio ?: ('#' . $ticket->id)) ?></td>
                    <td><?= h($ticket->title) ?></td>
                    <td><span class="badge <?= h($typeClass) ?>"><?= h($typeLabels[$ticket->type] ?? $ticket->type) ?></span></td>
                    <td><span class="badge <?= h($priorityClass) ?>"><?= h($priorityLabels[$ticket->priority] ?? $ticket->priority) ?></span></td>
                    <td><span class="badge <?= h($statusClass) ?>"><?= h($statusLabels[$ticket->status] ?? $ticket->status) ?></span></td>
                    <td><?= h($ticket->requester->name ?? $ticket->requester->email ?? 'N/A') ?></td>
                    <td class="tickets-muted"><?= h($ticket->assignee->name ?? 'Sin asignar') ?></td>
                    <td class="tickets-muted"><?= h($ticket->created?->format('Y-m-d H:i')) ?></td>
                    <td><?= $this->Html->link('Ver', ['action' => 'view', $ticket->id]) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (count($tickets) === 0): ?>
                <tr>
                    <td colspan="9" class="tickets-muted">No se encontraron tickets con los filtros actuales.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    </section>

    <div class="tickets-card">
        <?= $this->Paginator->numbers() ?>
    </div>
</div>
