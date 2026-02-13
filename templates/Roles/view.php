<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Role $role
 */

$permissionItems = [];
if (!empty($role->permissions)) {
    foreach ($role->permissions as $permission) {
        $permissionItems[] = [
            'controller' => (string)($permission->controller ?? ''),
            'action' => (string)($permission->action ?? ''),
            'description' => (string)($permission->description ?? ''),
        ];
    }
}
?>

<section class="role-view-page">
    <header class="role-view-header">
        <h2>Detalle del rol</h2>
        <p>Consulta la configuracion y permisos asignados.</p>
    </header>

    <article class="role-view-card">
        <div class="role-meta-grid">
            <div class="meta-item">
                <dt>ID</dt>
                <dd><?= (int)$role->id ?></dd>
            </div>
            <div class="meta-item">
                <dt>Nombre</dt>
                <dd><?= h((string)$role->name) ?></dd>
            </div>
            <div class="meta-item meta-full">
                <dt>Descripcion</dt>
                <dd><?= h((string)($role->description ?? 'Sin descripcion')) ?></dd>
            </div>
        </div>

        <section class="role-permissions-panel">
            <h3>Permisos asignados (<?= count($permissionItems) ?>)</h3>

            <?php if (!empty($permissionItems)): ?>
                <div class="permissions-table-wrap">
                    <table class="permissions-table">
                        <thead>
                            <tr>
                                <th>Controlador</th>
                                <th>Accion</th>
                                <th>Descripcion</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($permissionItems as $item): ?>
                                <tr>
                                    <td data-label="Controlador"><?= h($item['controller'] !== '' ? $item['controller'] : 'N/A') ?></td>
                                    <td data-label="Accion"><?= h($item['action'] !== '' ? $item['action'] : 'N/A') ?></td>
                                    <td data-label="Descripcion"><?= h($item['description'] !== '' ? $item['description'] : 'Sin descripcion') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="empty-text">Este rol no tiene permisos asignados.</p>
            <?php endif; ?>
        </section>

        <div class="role-view-actions">
            <?= $this->Html->link('Editar', ['action' => 'edit', $role->id], ['class' => 'button button-primary']) ?>
            <?= $this->Html->link('Volver', ['action' => 'index'], ['class' => 'button button-secondary']) ?>
        </div>
    </article>
</section>

<style>
.role-view-page {
    max-width: 980px;
    margin: 0 auto;
    display: grid;
    gap: 14px;
}

.role-view-header h2 {
    margin: 0;
    color: #2f251a;
}

.role-view-header p {
    margin: 4px 0 0;
    color: #6c6458;
}

.role-view-card {
    background: #ffffff;
    border: 1px solid #e9e0d3;
    border-radius: 12px;
    padding: 16px;
}

.role-meta-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 10px;
}

.meta-item {
    border: 1px solid #ece3d6;
    border-radius: 10px;
    background: #fffaf3;
    padding: 10px 12px;
}

.meta-item dt {
    margin: 0;
    color: #786f63;
    font-size: 1.2rem;
}

.meta-item dd {
    margin: 4px 0 0;
    color: #30261b;
    font-weight: 600;
    word-break: break-word;
}

.meta-full {
    grid-column: 1 / -1;
}

.role-permissions-panel {
    margin-top: 14px;
    padding-top: 14px;
    border-top: 1px solid #ece3d6;
}

.role-permissions-panel h3 {
    margin: 0 0 10px;
    color: #30251a;
}

.permissions-table-wrap {
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
    text-align: left;
    vertical-align: top;
    word-break: break-word;
}

.permissions-table th {
    color: #43382b;
    font-weight: 700;
}

.empty-text {
    margin: 0;
    color: #756c61;
}

.role-view-actions {
    margin-top: 14px;
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    border: 1px solid transparent;
    padding: 8px 12px;
    text-decoration: none;
    font-weight: 600;
    line-height: 1.2;
}

.button-primary {
    color: #ffffff;
    background: #8c1d2f;
    border-color: #8c1d2f;
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

@media (max-width: 760px) {
    .role-view-card {
        padding: 12px;
    }

    .role-meta-grid {
        grid-template-columns: 1fr;
    }

    .meta-full {
        grid-column: auto;
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
        text-align: right;
    }

    .permissions-table td::before {
        content: attr(data-label);
        font-weight: 700;
        color: #43382b;
        text-align: left;
    }

    .role-view-actions .button {
        width: 100%;
    }
}
</style>
