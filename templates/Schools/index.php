<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\School> $schools
 */
?>

<section class="schools-index">
    <div class="schools-header">
        <div>
            <h1>Escuelas</h1>
            <p>Listado general de escuelas con informacion de ubicacion, contacto y seguimiento comercial.</p>
        </div>
        <div class="schools-actions">
            <?= $this->Html->link('Buscar/Filtros', ['action' => 'filtros'], ['class' => 'button button-info']) ?>
            <?= $this->Html->link('Importar CSV', ['action' => 'import'], ['class' => 'button button-secondary']) ?>
            <?= $this->Html->link('Descargar plantilla CSV', ['action' => 'downloadTemplate'], ['class' => 'button button-outline']) ?>
            <?= $this->Html->link('Nueva escuela', ['action' => 'add'], ['class' => 'button button-primary']) ?>
        </div>
    </div>

    <section class="schools-table-card">
        <div class="table-responsive">
            <table class="schools-table">
                <thead>
                    <tr>
                        <th><?= $this->Paginator->sort('id') ?></th>
                        <th><?= $this->Paginator->sort('nombre') ?></th>
                        <th>Estado</th>
                        <th>Municipio</th>
                        <th>Usuario asignado</th>
                        <th><?= $this->Paginator->sort('tipo') ?></th>
                        <th><?= $this->Paginator->sort('sector') ?></th>
                        <th><?= $this->Paginator->sort('turno') ?></th>
                        <th><?= $this->Paginator->sort('num_alumnos') ?></th>
                        <th><?= $this->Paginator->sort('cct') ?></th>
                        <th><?= $this->Paginator->sort('lat') ?></th>
                        <th><?= $this->Paginator->sort('lng') ?></th>
                        <th><?= $this->Paginator->sort('grupos') ?></th>
                        <th><?= $this->Paginator->sort('nombre_contacto') ?></th>
                        <th><?= $this->Paginator->sort('telefono_contacto') ?></th>
                        <th><?= $this->Paginator->sort('correo_contacto') ?></th>
                        <th><?= $this->Paginator->sort('presupuesto') ?></th>
                        <th><?= $this->Paginator->sort('notas') ?></th>
                        <th><?= $this->Paginator->sort('estatus') ?></th>
                        <th><?= $this->Paginator->sort('verificada') ?></th>
                        <th><?= $this->Paginator->sort('editorial_actual') ?></th>
                        <th><?= $this->Paginator->sort('venta_montenegro') ?></th>
                        <th><?= $this->Paginator->sort('competencia') ?></th>
                        <th><?= $this->Paginator->sort('fecha_decision') ?></th>
                        <th><?= $this->Paginator->sort('created') ?></th>
                        <th class="actions-col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($schools)): ?>
                        <?php foreach ($schools as $school): ?>
                            <tr>
                                <td><?= (int)$school->id ?></td>
                                <td><?= h((string)$school->nombre) ?></td>
                                <td><?= h((string)($school->estado->nombre ?? '-')) ?></td>
                                <td><?= h((string)($school->municipio->nombre ?? '-')) ?></td>
                                <td>
                                    <?= $school->has('user') ? h((string)$school->user->name) : '-' ?><br>
                                    <small><?= h((string)($school->user->email ?? '')) ?></small>
                                </td>
                                <td><?= h((string)$school->tipo) ?></td>
                                <td><?= h((string)$school->sector) ?></td>
                                <td><?= h((string)$school->turno) ?></td>
                                <td><?= h((string)($school->num_alumnos ?? '-')) ?></td>
                                <td><?= h((string)$school->cct) ?></td>
                                <td><?= h((string)($school->lat ?? '-')) ?></td>
                                <td><?= h((string)($school->lng ?? '-')) ?></td>
                                <td><?= h((string)($school->grupos ?? '-')) ?></td>
                                <td><?= h((string)($school->nombre_contacto ?? '-')) ?></td>
                                <td><?= h((string)($school->telefono_contacto ?? '-')) ?></td>
                                <td><?= h((string)($school->correo_contacto ?? '-')) ?></td>
                                <td><?= h((string)($school->presupuesto ?? '-')) ?></td>
                                <td><?= h((string)($school->notas ?? '-')) ?></td>
                                <td><?= h(ucfirst((string)$school->estatus)) ?></td>
                                <td>
                                    <span class="status-badge <?= $school->verificada ? 'is-active' : 'is-inactive' ?>">
                                        <?= $school->verificada ? 'Si' : 'No' ?>
                                    </span>
                                </td>
                                <td><?= h((string)$school->editorial_actual) ?></td>
                                <td>
                                    <span class="status-badge <?= $school->venta_montenegro ? 'is-active' : 'is-inactive' ?>">
                                        <?= $school->venta_montenegro ? 'Si' : 'No' ?>
                                    </span>
                                </td>
                                <td><?= h((string)$school->competencia) ?></td>
                                <td><?= h((string)($school->fecha_decision ?? '-')) ?></td>
                                <td><?= h((string)$school->created) ?></td>
                                <td class="actions-col">
                                    <div class="action-links">
                                        <?= $this->Html->link(
                                            '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M12 5C6.5 5 2.1 8.1 1 12c1.1 3.9 5.5 7 11 7s9.9-3.1 11-7c-1.1-3.9-5.5-7-11-7Zm0 11a4 4 0 1 1 0-8 4 4 0 0 1 0 8Z"></path></svg>',
                                            ['action' => 'view', $school->id],
                                            [
                                                'class' => 'button button-small button-info button-icon',
                                                'escape' => false,
                                                'aria-label' => 'Ver escuela',
                                                'title' => 'Ver escuela',
                                            ]
                                        ) ?>
                                        <?= $this->Html->link(
                                            '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="m3 17.2 0 3.8 3.8 0 11.2-11.2-3.8-3.8L3 17.2Zm17.7-10.5a1 1 0 0 0 0-1.4l-2-2a1 1 0 0 0-1.4 0l-1.6 1.6 3.8 3.8 1.2-1.2Z"></path></svg>',
                                            ['action' => 'edit', $school->id],
                                            [
                                                'class' => 'button button-small button-secondary button-icon',
                                                'escape' => false,
                                                'aria-label' => 'Editar escuela',
                                                'title' => 'Editar escuela',
                                            ]
                                        ) ?>
                                        <?= $this->Form->postLink(
                                            '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M18.3 5.7 12 12l6.3 6.3-1.4 1.4L10.6 13.4 4.3 19.7 2.9 18.3 9.2 12 2.9 5.7 4.3 4.3l6.3 6.3 6.3-6.3 1.4 1.4Z"></path></svg>',
                                            ['action' => 'delete', $school->id],
                                            [
                                                'class' => 'button button-small button-danger button-icon',
                                                'escape' => false,
                                                'aria-label' => 'Eliminar escuela',
                                                'title' => 'Eliminar escuela',
                                                'confirm' => __('Are you sure you want to delete # {0}?', $school->id),
                                            ]
                                        ) ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="26" class="empty-row">No hay escuelas registradas.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="schools-pagination">
            <span><?= $this->Paginator->counter('Mostrando {{start}} a {{end}} de {{count}} registros') ?></span>
            <div class="pagination-links">
                <?= $this->Paginator->first('<< Primera') ?>
                <?= $this->Paginator->prev('< Anterior') ?>
                <?= $this->Paginator->numbers() ?>
                <?= $this->Paginator->next('Siguiente >') ?>
                <?= $this->Paginator->last('Ultima >>') ?>
            </div>
        </div>
    </section>
</section>

<style>
.schools-index {
    display: grid;
    gap: 14px;
    min-width: 0;
}

.schools-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
    flex-wrap: wrap;
}

.schools-header h1 {
    margin: 0;
}

.schools-header p {
    margin: 4px 0 0;
    color: #6b6358;
}

.schools-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.schools-table-card {
    background: #ffffff;
    border: 1px solid #e9e0d3;
    border-radius: 12px;
    padding: 14px;
    min-width: 0;
    overflow: hidden;
}

.table-responsive {
    display: block;
    width: 100%;
    max-width: 100%;
    overflow-x: auto;
    overflow-y: hidden;
    -webkit-overflow-scrolling: touch;
    overscroll-behavior-x: contain;
}

.schools-table {
    width: max-content;
    min-width: 1800px;
    border-collapse: collapse;
}

.schools-table th,
.schools-table td {
    padding: 9px 8px;
    border-bottom: 1px solid #efe5d8;
    text-align: left;
    vertical-align: top;
    white-space: nowrap;
}

.schools-table th {
    position: sticky;
    top: 0;
    z-index: 2;
    background: #fffaf3;
    color: #43382b;
    font-weight: 700;
}

.schools-table td small {
    color: #71695f;
}

.actions-col {
    min-width: 185px;
    position: sticky;
    right: 0;
    z-index: 1;
    background: #ffffff;
    box-shadow: -10px 0 12px -12px rgba(35, 27, 19, 0.35);
}

.schools-table th.actions-col {
    z-index: 4;
    background: #fffaf3;
}

.action-links {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    border-radius: 999px;
    padding: 3px 9px;
    font-size: 1.1rem;
    font-weight: 700;
}

.status-badge.is-active {
    color: #0f5132;
    background: #d1e7dd;
}

.status-badge.is-inactive {
    color: #842029;
    background: #f8d7da;
}

.button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    border: 1px solid transparent;
    padding: 7px 11px;
    text-decoration: none;
    font-weight: 600;
    line-height: 1.2;
}

.button-small {
    padding: 5px 9px;
    font-size: 1.2rem;
}

.button-icon {
    width: 24px;
    height: 24px;
    padding: 0;
}

.button-icon svg {
    width: 16px;
    height: 16px;
    fill: currentColor;
}

.button-primary {
    background: #8c1d2f;
    border-color: #8c1d2f;
    color: #ffffff;
}

.button-primary:hover {
    color: #ffffff;
    background: #741727;
    border-color: #741727;
}

.button-secondary {
    color: #4b3e31;
    border-color: #dbc9b4;
    background: #fff8ee;
}

.button-secondary:hover {
    color: #4b3e31;
    background: #f9ebda;
}

.button-outline {
    color: #4b3e31;
    border-color: #dbc9b4;
    background: transparent;
}

.button-outline:hover {
    color: #4b3e31;
    background: #f9ebda;
}

.button-info {
    color: #053b5e;
    border-color: #b7d7eb;
    background: #e9f4fb;
}

.button-info:hover {
    color: #053b5e;
    background: #d9ecf8;
}

.button-danger {
    color: #ffffff;
    border-color: #bb2d3b;
    background: #dc3545;
}

.button-danger:hover {
    color: #ffffff;
    background: #bb2d3b;
}

.schools-pagination {
    margin-top: 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.pagination-links {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.pagination-links ul {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0;
    padding: 0;
    list-style: none;
}

.pagination-links li {
    margin: 0;
    padding: 0;
    list-style: none;
}

.pagination-links .current {
    font-weight: 700;
}

.empty-row {
    text-align: center;
    color: #6c6458;
}

@media (max-width: 960px) {
    .schools-actions .button {
        min-height: 36px;
    }
}

@media (max-width: 780px) {
    .schools-header {
        align-items: stretch;
    }

    .schools-header > div {
        width: 100%;
    }

    .schools-actions {
        width: 100%;
    }

    .schools-actions .button {
        width: 100%;
    }
}
</style>
