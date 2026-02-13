<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Permission $permission
 */
?>

<section class="permission-form-page">
    <header class="permission-form-header">
        <h2>Editar permiso</h2>
        <p>Actualiza el controlador, accion y descripcion del permiso.</p>
    </header>

    <article class="permission-form-card">
        <?= $this->Form->create($permission, ['class' => 'permission-form']) ?>

        <div class="permission-form-grid">
            <?= $this->Form->control('controller', [
                'label' => 'Controlador',
                'required' => true,
                'placeholder' => 'Users, Roles, Permissions...',
            ]) ?>

            <?= $this->Form->control('action', [
                'label' => 'Accion',
                'required' => true,
                'placeholder' => 'index, add, edit, delete...',
            ]) ?>

            <div class="field-full">
                <?= $this->Form->control('description', [
                    'label' => 'Descripcion',
                    'required' => true,
                ]) ?>
            </div>
        </div>

        <div class="permission-form-actions">
            <?= $this->Form->button('Guardar cambios', ['class' => 'button button-primary']) ?>
            <?= $this->Html->link('Cancelar', ['action' => 'index'], ['class' => 'button button-secondary']) ?>
        </div>

        <?= $this->Form->end() ?>
    </article>
</section>

<style>
.permission-form-page {
    max-width: 860px;
    margin: 0 auto;
    display: grid;
    gap: 14px;
}

.permission-form-header h2 {
    margin: 0;
    color: #2f251a;
}

.permission-form-header p {
    margin: 4px 0 0;
    color: #6c6458;
}

.permission-form-card {
    background: #ffffff;
    border: 1px solid #e9e0d3;
    border-radius: 12px;
    padding: 16px;
}

.permission-form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px;
}

.permission-form-grid .field-full {
    grid-column: 1 / -1;
}

.permission-form-grid .input,
.permission-form-grid .textarea {
    margin-bottom: 0;
}

.permission-form-actions {
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
    .permission-form-card {
        padding: 12px;
    }

    .permission-form-grid {
        grid-template-columns: 1fr;
    }

    .permission-form-grid .field-full {
        grid-column: auto;
    }

    .permission-form-actions .button {
        width: 100%;
    }
}
</style>
