<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var array<int|string, string> $roles
 */
?>

<section class="user-form-page">
    <header class="user-form-header">
        <h2>Editar usuario</h2>
        <p>Actualiza los datos del usuario seleccionado.</p>
    </header>

    <article class="user-form-card">
        <?= $this->Form->create($user, ['class' => 'user-form']) ?>

        <div class="user-form-grid">
            <?= $this->Form->control('name', [
                'label' => 'Nombre',
                'required' => true,
            ]) ?>

            <?= $this->Form->control('apellido_paterno', [
                'label' => 'Apellido paterno',
                'required' => true,
            ]) ?>

            <?= $this->Form->control('apellido_materno', [
                'label' => 'Apellido materno',
                'required' => true,
            ]) ?>

            <?= $this->Form->control('email', [
                'label' => 'Email',
                'required' => true,
            ]) ?>
            <?= $this->Form->control('usern', [
                'label' => 'Nombre de usuario',
                'required' => true,
            ]) ?>

            <?= $this->Form->control('role_id', [
                'label' => 'Rol',
                'options' => $roles,
                'required' => true,
            ]) ?>
        </div>

        <div class="user-status-wrap">
            <?= $this->Form->control('activo', [
                'type' => 'checkbox',
                'label' => 'Usuario activo',
                'hiddenField' => true,
            ]) ?>
        </div>

        <div class="user-form-actions">
            <?= $this->Form->button('Actualizar', ['class' => 'button button-primary']) ?>
            <?= $this->Html->link('Cancelar', ['action' => 'index'], ['class' => 'button button-secondary']) ?>
        </div>

        <?= $this->Form->end() ?>
    </article>
</section>

<style>
.user-form-page {
    max-width: 920px;
    margin: 0 auto;
    display: grid;
    gap: 14px;
}

.user-form-header h2 {
    margin: 0;
    color: #2f251a;
}

.user-form-header p {
    margin: 4px 0 0;
    color: #6c6458;
}

.user-form-card {
    background: #ffffff;
    border: 1px solid #e9e0d3;
    border-radius: 12px;
    padding: 16px;
}

.user-form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px;
}

.user-form-grid .input,
.user-form-grid .select,
.user-form-grid .password,
.user-form-grid .email {
    margin-bottom: 0;
}

.user-status-wrap {
    margin-top: 14px;
    padding: 10px 12px;
    border: 1px dashed #e9e0d3;
    border-radius: 10px;
    background: #fffaf3;
}

.user-status-wrap .checkbox {
    margin: 0;
}

.user-status-wrap label {
    margin: 0;
    font-weight: 600;
}

.user-form-actions {
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
    .user-form-card {
        padding: 12px;
    }

    .user-form-grid {
        grid-template-columns: 1fr;
    }

    .user-form-actions .button {
        width: 100%;
    }
}
</style>
