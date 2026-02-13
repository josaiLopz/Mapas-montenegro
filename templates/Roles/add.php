<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Role $role
 * @var array<string, array> $permissionsGrouped
 */

$selectedPermissionIds = $role->permissions
    ? collection($role->permissions)->extract('id')->toList()
    : [];
?>

<section class="role-form-page">
    <header class="role-form-header">
        <h2>Agregar rol</h2>
        <p>Crea un rol y selecciona los permisos que tendra.</p>
    </header>

    <article class="role-form-card">
        <?= $this->Form->create($role, ['class' => 'role-form']) ?>

        <div class="role-form-grid">
            <?= $this->Form->control('name', [
                'label' => 'Nombre del rol',
                'required' => true,
            ]) ?>

            <div class="field-full">
                <?= $this->Form->control('description', [
                    'label' => 'Descripcion del rol',
                    'required' => true,
                ]) ?>
            </div>
        </div>

        <section class="permissions-panel">
            <h3>Permisos</h3>

            <?php if (!empty($permissionsGrouped)): ?>
                <div class="permissions-groups">
                    <?php foreach ($permissionsGrouped as $controller => $permissions): ?>
                        <article class="permission-group">
                            <h4><?= h((string)$controller) ?></h4>
                            <div class="permission-items">
                                <?php foreach ($permissions as $permission): ?>
                                    <label class="permission-item">
                                        <?= $this->Form->checkbox('permissions._ids[]', [
                                            'value' => $permission->id,
                                            'checked' => in_array($permission->id, $selectedPermissionIds, true),
                                            'hiddenField' => false,
                                        ]) ?>
                                        <span>
                                            <strong><?= h((string)$permission->action) ?></strong>
                                            <small><?= h((string)$permission->description) ?></small>
                                        </span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="permissions-empty">No hay permisos disponibles para asignar.</p>
            <?php endif; ?>
        </section>

        <div class="role-form-actions">
            <?= $this->Form->button('Guardar rol', ['class' => 'button button-primary']) ?>
            <?= $this->Html->link('Cancelar', ['action' => 'index'], ['class' => 'button button-secondary']) ?>
        </div>

        <?= $this->Form->end() ?>
    </article>
</section>

<style>
.role-form-page {
    max-width: 980px;
    margin: 0 auto;
    display: grid;
    gap: 14px;
}

.role-form-header h2 {
    margin: 0;
    color: #2f251a;
}

.role-form-header p {
    margin: 4px 0 0;
    color: #6c6458;
}

.role-form-card {
    background: #ffffff;
    border: 1px solid #e9e0d3;
    border-radius: 12px;
    padding: 16px;
}

.role-form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px;
}

.field-full {
    grid-column: 1 / -1;
}

.role-form-grid .input,
.role-form-grid .textarea {
    margin-bottom: 0;
}

.permissions-panel {
    margin-top: 14px;
    padding-top: 14px;
    border-top: 1px solid #ece3d6;
}

.permissions-panel h3 {
    margin: 0 0 10px;
    color: #30251a;
}

.permissions-groups {
    display: grid;
    gap: 10px;
}

.permission-group {
    border: 1px solid #ece3d6;
    border-radius: 10px;
    background: #fffcf8;
    padding: 10px;
}

.permission-group h4 {
    margin: 0 0 8px;
    color: #423527;
}

.permission-items {
    display: grid;
    gap: 8px;
}

.permission-item {
    display: grid;
    grid-template-columns: 18px 1fr;
    gap: 8px;
    align-items: start;
    margin: 0;
}

.permission-item strong {
    display: block;
    line-height: 1.2;
}

.permission-item small {
    display: block;
    color: #6f665c;
    line-height: 1.35;
}

.permissions-empty {
    margin: 0;
    color: #756c61;
}

.role-form-actions {
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
    .role-form-card {
        padding: 12px;
    }

    .role-form-grid {
        grid-template-columns: 1fr;
    }

    .field-full {
        grid-column: auto;
    }

    .role-form-actions .button {
        width: 100%;
    }
}
</style>
