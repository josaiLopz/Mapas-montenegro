<?php
$this->assign('title', 'Tickets de soporte');

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
?>

<style>

/* ====== Layout ====== */
.tickets-shell { padding: 1.5rem; }
.tickets-card {
    background:#fff;
    border-radius:14px;
    padding:1.25rem;
    margin-bottom:1.5rem;
    box-shadow:0 4px 16px rgba(0,0,0,0.05);
}

/* ====== Header ====== */
.tickets-head {
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
    gap:1rem;
    margin-bottom:1.5rem;
}
.tickets-title { margin:0; font-size:1.8rem; }
.tickets-subtitle { margin:.25rem 0 0 0; color:#6b7280; }

/* ====== Stats ====== */
.tickets-stats {
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:1rem;
    margin-bottom:1.5rem;
}
.tickets-stat-card {
    padding:1rem;
    border-radius:12px;
    background:#fff;
    box-shadow:0 2px 8px rgba(0,0,0,.04);
}
.tickets-stat-label { font-size:.85rem; color:#6b7280; }
.tickets-stat-value { font-size:1.5rem; font-weight:600; }

/* ====== Filters ====== */
.tickets-filters {
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:1rem;
    align-items:end;
}
.tickets-filters select,
.tickets-filters input {
    width:100%;
}
.filter-actions { display:flex; }
.filter-actions .button { width:100%; }

/* ====== Table Desktop ====== */
.tickets-table-wrap { width:100%; overflow-x:auto; }
.tickets-table {
    width:100%;
    border-collapse:collapse;
}
.tickets-table th,
.tickets-table td {
    padding:.75rem 1rem;
    border-bottom:1px solid #eee;
}
.tickets-table th {
    background:#f9fafb;
    font-size:.8rem;
    text-transform:uppercase;
    color:#6b7280;
}
.tickets-table tbody tr:hover { background:#f9fafb; }

/* ====== Badges ====== */
.badge {
    display:inline-block;
    padding:.3rem .6rem;
    border-radius:999px;
    font-size:.75rem;
    font-weight:500;
}
.badge-status-nuevo { background:#e0f2fe; color:#0369a1; }
.badge-status-en-proceso { background:#fef3c7; color:#92400e; }
.badge-status-esperando-usuario { background:#ede9fe; color:#5b21b6; }
.badge-status-resuelto { background:#dcfce7; color:#166534; }
.badge-status-cerrado { background:#e5e7eb; color:#374151; }

.badge-priority-baja { background:#e0f2fe; color:#0369a1; }
.badge-priority-media { background:#fef9c3; color:#854d0e; }
.badge-priority-alta { background:#fed7aa; color:#9a3412; }
.badge-priority-critica { background:#fee2e2; color:#991b1b; }

.badge-type-error { background:#fee2e2; color:#991b1b; }
.badge-type-mejora { background:#e0f2fe; color:#075985; }
.badge-type-soporte { background:#dcfce7; color:#166534; }

/* ============================
   MOBILE
============================ */

@media (max-width: 992px) {
    .tickets-stats { grid-template-columns:repeat(2,1fr); }
    .tickets-filters { grid-template-columns:repeat(2,1fr); }
}

@media (max-width: 768px) {

    .tickets-stats { grid-template-columns:1fr; }
    .tickets-filters { grid-template-columns:1fr; }

    .tickets-table thead { display:none; }

    .tickets-table,
    .tickets-table tbody,
    .tickets-table tr,
    .tickets-table td {
        display:block;
        width:100%;
    }

    .tickets-table tr {
        background:#fff;
        border-radius:14px;
        padding:1rem;
        margin-bottom:1rem;
        box-shadow:0 4px 14px rgba(0,0,0,0.05);
        border:1px solid #f1f1f1;
    }

    .tickets-table td {
        border:none;
        padding:.4rem 0;
        display:flex;
        justify-content:space-between;
        font-size:.9rem;
    }

    .tickets-table td::before {
        content: attr(data-label);
        font-weight:600;
        color:#6b7280;
    }
}

</style>

<div class="tickets-shell">

    <!-- HEADER -->
    <div class="tickets-head">
        <div>
            <h2 class="tickets-title">Tickets de soporte</h2>
            <p class="tickets-subtitle">Monitorea incidencias y seguimiento.</p>
        </div>
        <div>
            <?= $this->Html->link('Nueva incidencia', ['action'=>'add'], ['class'=>'button button-primary']) ?>
        </div>
    </div>

    <!-- STATS -->
    <section class="tickets-stats">
        <div class="tickets-stat-card">
            <div class="tickets-stat-label">En página</div>
            <div class="tickets-stat-value"><?= $totalPage ?></div>
        </div>
    </section>

    <!-- FILTROS -->
    <section class="tickets-card">
        <?= $this->Form->create(null,['type'=>'get','class'=>'tickets-filters']) ?>
        <?= $this->Form->control('scope',['label'=>'Vista','type'=>'select','options'=>$canManage ? ['mine'=>'Mis tickets','all'=>'Todos'] : ['mine'=>'Mis tickets'],'value'=>$scope]) ?>
        <?= $this->Form->control('status',['label'=>'Estatus','type'=>'select','empty'=>'Todos','options'=>$statusLabels,'value'=>$status]) ?>
        <?= $this->Form->control('type',['label'=>'Tipo','type'=>'select','empty'=>'Todos','options'=>$typeLabels,'value'=>$type]) ?>
        <div class="filter-actions">
            <button class="button" type="submit">Filtrar</button>
        </div>
        <?= $this->Form->end() ?>
    </section>

    <!-- TABLA -->
    <section class="tickets-card">
        <div class="tickets-table-wrap">
            <table class="tickets-table">
                <thead>
                <tr>
                    <th>Folio</th>
                    <th>Título</th>
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
                        <td data-label="Folio"><?= h($ticket->folio ?: '#'.$ticket->id) ?></td>
                        <td data-label="Título"><?= h($ticket->title) ?></td>
                        <td data-label="Tipo"><span class="badge <?= $typeClass ?>"><?= h($typeLabels[$ticket->type] ?? $ticket->type) ?></span></td>
                        <td data-label="Prioridad"><span class="badge <?= $priorityClass ?>"><?= h($priorityLabels[$ticket->priority] ?? $ticket->priority) ?></span></td>
                        <td data-label="Estatus"><span class="badge <?= $statusClass ?>"><?= h($statusLabels[$ticket->status] ?? $ticket->status) ?></span></td>
                        <td data-label="Solicitante"><?= h($ticket->requester->name ?? 'N/A') ?></td>
                        <td data-label="Asignado"><?= h($ticket->assignee->name ?? 'Sin asignar') ?></td>
                        <td data-label="Fecha"><?= h($ticket->created?->format('Y-m-d H:i')) ?></td>
                        <td data-label="">
                            <?= $this->Html->link('Ver',['action'=>'view',$ticket->id]) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

</div>