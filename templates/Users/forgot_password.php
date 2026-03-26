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

.forgot-password-link {
    text-align: center;
    margin-top: 16px;
}

.forgot-password-link a {
    color: #555;
    font-size: 0.9em;
    text-decoration: none;
}
.forgot-password-link a:hover { text-decoration: underline; }
</style>

<div class="login-page">
    <div class="login-card">
        <h2 class="login-title">Recuperar Contraseña</h2>
        <p style="text-align:center; margin-top:-10px; margin-bottom:20px; color:#666;">Ingresa tu email y te enviaremos un enlace para restablecer tu contraseña.</p>

        <?= $this->Form->create() ?>
        <?= $this->Form->control('email', ['label' => 'Correo electrónico', 'required' => true, 'type' => 'email']) ?>
        <?= $this->Form->button('Enviar enlace') ?>
        <?= $this->Form->end() ?>

        <div class="forgot-password-link">
            <?= $this->Html->link('Volver a Iniciar Sesión', ['action' => 'login']) ?>
        </div>
    </div>
</div>