<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Role> $roles
 */
?>

<section class="roles-index">
    <div class="roles-header">
        <div>
            <h1>Roles</h1>
            <p>Administra roles y permisos asignados en el sistema.</p>
        </div>
        <div>
            <?= $this->Html->link('Agregar rol', ['action' => 'add'], ['class' => 'button button-primary']) ?>
        </div>
    </div>

    <section class="roles-table-card">
        <div class="table-responsive">
            <table class="roles-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Permisos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($roles)): ?>
                        <?php foreach ($roles as $role): ?>
                            <?php $permissionList = collection($role->permissions)->extract('description')->filter()->toList(); ?>
                            <?php $visiblePermissions = array_slice($permissionList, 0, 10); ?>
                            <?php $hiddenPermissionsCount = max(count($permissionList) - 10, 0); ?>
                            <tr>
                                <td data-label="ID"><?= (int)$role->id ?></td>
                                <td data-label="Nombre"><?= h((string)$role->name) ?></td>
                                <td data-label="Permisos">
                                    <?php if (!empty($permissionList)): ?>
                                        <div class="permissions-wrap">
                                            <?php foreach ($visiblePermissions as $permissionText): ?>
                                                <span class="permission-chip"><?= h((string)$permissionText) ?></span>
                                            <?php endforeach; ?>
                                            <?php if ($hiddenPermissionsCount > 0): ?>
                                                <span class="permission-chip permission-chip-more">+<?= (int)$hiddenPermissionsCount ?> mas</span>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="permissions-empty">Sin permisos</span>
                                    <?php endif; ?>
                                </td>
                                <td data-label="Acciones" class="action-cell">
                                    <?= $this->Html->link(
                                        '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M12 5C6.5 5 2.1 8.1 1 12c1.1 3.9 5.5 7 11 7s9.9-3.1 11-7c-1.1-3.9-5.5-7-11-7Zm0 11a4 4 0 1 1 0-8 4 4 0 0 1 0 8Z"></path></svg>',
                                        ['action' => 'view', $role->id],
                                        [
                                            'class' => 'button button-small button-info button-icon',
                                            'escape' => false,
                                            'aria-label' => 'Ver rol',
                                            'title' => 'Ver rol',
                                        ]
                                    ) ?>
                                    <?= $this->Html->link(
                                        '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="m3 17.2 0 3.8 3.8 0 11.2-11.2-3.8-3.8L3 17.2Zm17.7-10.5a1 1 0 0 0 0-1.4l-2-2a1 1 0 0 0-1.4 0l-1.6 1.6 3.8 3.8 1.2-1.2Z"></path></svg>',
                                        ['action' => 'edit', $role->id],
                                        [
                                            'class' => 'button button-small button-secondary button-icon',
                                            'escape' => false,
                                            'aria-label' => 'Editar rol',
                                            'title' => 'Editar rol',
                                        ]
                                    ) ?>
                                    <?= $this->Form->postLink(
                                        '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M18.3 5.7 12 12l6.3 6.3-1.4 1.4L10.6 13.4 4.3 19.7 2.9 18.3 9.2 12 2.9 5.7 4.3 4.3l6.3 6.3 6.3-6.3 1.4 1.4Z"></path></svg>',
                                        ['action' => 'delete', $role->id],
                                        [
                                            'class' => 'button button-small button-danger button-icon',
                                            'escape' => false,
                                            'aria-label' => 'Eliminar rol',
                                            'title' => 'Eliminar rol',
                                            'confirm' => '¿Estas seguro de que quieres eliminar este rol?',
                                        ]
                                    ) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="empty-row">No hay roles registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="roles-pagination">
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
.roles-index {
    display: grid;
    gap: 14px;
}

.roles-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
    flex-wrap: wrap;
}

.roles-header h1 {
    margin: 0;
}

.roles-header p {
    margin: 4px 0 0;
    color: #6b6358;
}

.roles-table-card {
    background: #ffffff;
    border: 1px solid #e9e0d3;
    border-radius: 12px;
    padding: 14px;
}

.table-responsive {
    width: 100%;
}

.roles-table {
    width: 100%;
    border-collapse: collapse;
}

.roles-table th,
.roles-table td {
    padding: 10px 8px;
    border-bottom: 1px solid #efe5d8;
    vertical-align: middle;
    text-align: left;
    word-break: break-word;
}

.roles-table th {
    white-space: nowrap;
    color: #43382b;
    font-weight: 700;
}

.permissions-wrap {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}

.permission-chip {
    display: inline-flex;
    align-items: center;
    border-radius: 999px;
    padding: 3px 9px;
    font-size: 1.1rem;
    color: #4e4337;
    background: #ffffff;
    border: 1px solid #eadbc8;
}

.permission-chip-more {
    background: #f4efe8;
    border-color: #ddd1c3;
    color: #5e5449;
    font-weight: 700;
}

.permissions-empty {
    color: #766d62;
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

.roles-pagination {
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
    .roles-header {
        align-items: stretch;
    }

    .roles-header > div {
        width: 100%;
    }

    .roles-header .button {
        width: 100%;
    }
}

@media (max-width: 640px) {
    .roles-table-card {
        padding: 10px;
    }

    .roles-table thead {
        display: none;
    }

    .roles-table,
    .roles-table tbody,
    .roles-table tr,
    .roles-table td {
        display: block;
        width: 100%;
    }

    .roles-table tr {
        border: 1px solid #efe5d8;
        border-radius: 10px;
        padding: 8px 10px;
        margin-bottom: 10px;
        background: #fff;
    }

    .roles-table td {
        border-bottom: 0;
        padding: 6px 0;
        display: flex;
        justify-content: space-between;
        gap: 12px;
        align-items: center;
        text-align: right;
    }

    .roles-table td::before {
        content: attr(data-label);
        font-weight: 700;
        color: #43382b;
        text-align: left;
    }

    .roles-table td[data-label="Permisos"] {
        display: block;
        text-align: left;
    }

    .roles-table td[data-label="Permisos"]::before {
        display: block;
        margin-bottom: 7px;
    }

    .roles-table td[data-label="Acciones"] {
        display: block;
        text-align: left;
        padding-top: 10px;
        border-top: 1px dashed #efe5d8;
        margin-top: 4px;
    }

    .roles-table td[data-label="Acciones"]::before {
        display: block;
        margin-bottom: 7px;
    }

    .roles-table td.empty-row {
        display: block;
        text-align: center;
    }

    .roles-table td.empty-row::before {
        display: none;
    }

    .action-cell {
        justify-content: flex-start;
    }

    .roles-pagination {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>
