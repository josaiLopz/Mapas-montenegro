<style>
.login-page {
    min-height: 70vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 24px;
}

.login-card {
    width: 100%;
    max-width: 420px;
    background: #ffffff;
    border-radius: 18px;
    box-shadow: 0 18px 40px rgba(0,0,0,0.12);
    padding: 28px;
}

.login-title {
    text-align: center;
    margin-bottom: 18px;
}

.login-card .button {
    width: 100%;
}
</style>

<div class="login-page">
    <div class="login-card">
        <h2 class="login-title">Restablecer Contraseña</h2>
        <p style="text-align:center; margin-top:-10px; margin-bottom:20px; color:#666;">
            Ingresa tu nueva contraseña.
        </p>

        <?php
        // Asumimos que el controlador pasa la variable $user
        ?>
        <?= $this->Form->create($user) ?>
        <?= $this->Form->control('password', ['type' => 'password', 'label' => 'Nueva Contraseña', 'required' => true, 'value' => '']) ?>
        <?= $this->Form->control('password_confirm', ['type' => 'password', 'label' => 'Confirmar Nueva Contraseña', 'required' => true]) ?>
        <?= $this->Form->button('Guardar Contraseña') ?>
        <?= $this->Form->end() ?>
    </div>
</div>
