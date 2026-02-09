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

.logo-sim {
    width: 20%%;
    height: 96px;

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
        <img class="logo-sim" src="/img/logo.png" alt="Logo">
        <h2 class="login-title">Iniciar sesión</h2>

        <?= $this->Form->create() ?>
        <?= $this->Form->control('email') ?>
        <?= $this->Form->control('password', ['type' => 'password']) ?>
        <?= $this->Form->button('Entrar') ?>
        <?= $this->Form->end() ?>
    </div>
</div>
