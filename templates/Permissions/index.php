<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Permission> $permissions
 */
?>

<section class="permissions-index">
    <div class="permissions-header">
        <div>
            <h1>Permisos</h1>
            <p>Administra los permisos por controlador y accion del sistema.</p>
        </div>
        <div>
            <?= $this->Html->link('Agregar permiso', ['action' => 'add'], ['class' => 'button button-primary']) ?>
        </div>
    </div>

    <section class="permissions-table-card">
        <div class="table-responsive">
            <table class="permissions-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Controlador</th>
                        <th>Accion</th>
                        <th>Descripcion</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($permissions)): ?>
                        <?php foreach ($permissions as $permission): ?>
                            <tr>
                                <td data-label="ID"><?= (int)$permission->id ?></td>
                                <td data-label="Controlador"><?= h((string)$permission->controller) ?></td>
                                <td data-label="Accion"><?= h((string)$permission->action) ?></td>
                                <td data-label="Descripcion"><?= h((string)$permission->description) ?></td>
                                <td data-label="Acciones" class="action-cell">
                                    <?= $this->Html->link(
                                        '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M12 5C6.5 5 2.1 8.1 1 12c1.1 3.9 5.5 7 11 7s9.9-3.1 11-7c-1.1-3.9-5.5-7-11-7Zm0 11a4 4 0 1 1 0-8 4 4 0 0 1 0 8Z"></path></svg>',
                                        ['action' => 'view', $permission->id],
                                        [
                                            'class' => 'button button-small button-info button-icon',
                                            'escape' => false,
                                            'aria-label' => 'Ver permiso',
                                            'title' => 'Ver permiso',
                                        ]
                                    ) ?>
                                    <?= $this->Html->link(
                                        '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="m3 17.2 0 3.8 3.8 0 11.2-11.2-3.8-3.8L3 17.2Zm17.7-10.5a1 1 0 0 0 0-1.4l-2-2a1 1 0 0 0-1.4 0l-1.6 1.6 3.8 3.8 1.2-1.2Z"></path></svg>',
                                        ['action' => 'edit', $permission->id],
                                        [
                                            'class' => 'button button-small button-secondary button-icon',
                                            'escape' => false,
                                            'aria-label' => 'Editar permiso',
                                            'title' => 'Editar permiso',
                                        ]
                                    ) ?>
                                    <?= $this->Form->postLink(
                                        '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M18.3 5.7 12 12l6.3 6.3-1.4 1.4L10.6 13.4 4.3 19.7 2.9 18.3 9.2 12 2.9 5.7 4.3 4.3l6.3 6.3 6.3-6.3 1.4 1.4Z"></path></svg>',
                                        ['action' => 'delete', $permission->id],
                                        [
                                            'class' => 'button button-small button-danger button-icon',
                                            'escape' => false,
                                            'aria-label' => 'Eliminar permiso',
                                            'title' => 'Eliminar permiso',
                                            'confirm' => '¿Estas seguro de que quieres eliminar este permiso?',
                                        ]
                                    ) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="empty-row">No hay permisos registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="permissions-pagination">
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
.permissions-index {
    display: grid;
    gap: 14px;
}

.permissions-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
    flex-wrap: wrap;
}

.permissions-header h1 {
    margin: 0;
}

.permissions-header p {
    margin: 4px 0 0;
    color: #6b6358;
}

.permissions-table-card {
    background: #ffffff;
    border: 1px solid #e9e0d3;
    border-radius: 12px;
    padding: 14px;
}

.table-responsive {
    width: 100%;
}

.permissions-table {
    width: 100%;
    border-collapse: collapse;
}

.permissions-table th,
.permissions-table td {
    padding: 10px 8px;
    border-bottom: 1px solid #efe5d8;
    vertical-align: middle;
    text-align: left;
    word-break: break-word;
}

.permissions-table th {
    white-space: nowrap;
    color: #43382b;
    font-weight: 700;
}

.action-cell {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
    align-items: center;
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

.permissions-pagination {
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

@media (max-width: 780px) {
    .permissions-header {
        align-items: stretch;
    }

    .permissions-header > div {
        width: 100%;
    }

    .permissions-header .button {
        width: 100%;
    }
}

@media (max-width: 640px) {
    .permissions-table-card {
        padding: 10px;
    }

    .permissions-table thead {
        display: none;
    }

    .permissions-table,
    .permissions-table tbody,
    .permissions-table tr,
    .permissions-table td {
        display: block;
        width: 100%;
    }

    .permissions-table tr {
        border: 1px solid #efe5d8;
        border-radius: 10px;
        padding: 8px 10px;
        margin-bottom: 10px;
        background: #fff;
    }

    .permissions-table td {
        border-bottom: 0;
        padding: 6px 0;
        display: flex;
        justify-content: space-between;
        gap: 12px;
        align-items: center;
        text-align: right;
    }

    .permissions-table td::before {
        content: attr(data-label);
        font-weight: 700;
        color: #43382b;
        text-align: left;
    }

    .permissions-table td[data-label="Acciones"] {
        display: block;
        text-align: left;
        padding-top: 10px;
        border-top: 1px dashed #efe5d8;
        margin-top: 4px;
    }

    .permissions-table td[data-label="Acciones"]::before {
        display: block;
        margin-bottom: 7px;
    }

    .permissions-table td.empty-row {
        display: block;
        text-align: center;
    }

    .permissions-table td.empty-row::before {
        display: none;
    }

    .action-cell {
        justify-content: flex-start;
    }

    .permissions-pagination {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>
