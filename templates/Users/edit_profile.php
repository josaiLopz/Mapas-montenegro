<article class="edit-profile-card">

    <?= $this->Form->create($user, ['type' => 'file', 'class' => 'edit-profile-form']) ?>
    
    <div class="dashboard-layout">
    
        <!-- COLUMNA IZQUIERDA -->
        <aside class="profile-sidebar">
    
            <div class="avatar-card">
                <img
                    src="/img/avatars/<?= h($user->avatar ?? 'default.png') ?>"
                    alt="Avatar"
                    class="current-avatar"
                >
    
                <h3><?= h($user->name ?? 'Usuario') ?></h3>
    
                <?= $this->Form->control('avatar', [
                    'type' => 'file',
                    'label' => 'Cambiar foto'
                ]) ?>
            </div>
    
        </aside>
    
    
        <!-- COLUMNA DERECHA -->
        <section class="profile-content">
    
            <div class="content-card">
                <h3>Información</h3>
    
                <div class="profile-grid">
                    <?= $this->Form->control('name', ['label' => 'Nombre']) ?>
                    <?= $this->Form->control('apellido_paterno', ['label' => 'Apellido paterno']) ?>
                    <?= $this->Form->control('apellido_materno', ['label' => 'Apellido materno']) ?>
                    <?= $this->Form->control('email', ['label' => 'Correo electronico']) ?>
                </div>
            </div>
    
    
            <div class="content-card">
                <h3>Seguridad</h3>
    
                <div class="password-group">
                    <?= $this->Form->control('new_password', [
                        'type' => 'password',
                        'label' => 'Nueva contrasena',
                        'id' => 'new-password'
                    ]) ?>
                    <button type="button" class="toggle-password" data-target="new-password">👁</button>
                </div>
    
                <div class="password-group">
                    <?= $this->Form->control('confirm_password', [
                        'type' => 'password',
                        'label' => 'Confirmar contrasena',
                        'id' => 'confirm-password'
                    ]) ?>
                    <button type="button" class="toggle-password" data-target="confirm-password">👁</button>
                </div>
    
            </div>
    
            <div class="actions-row">
                <?= $this->Form->button('Guardar cambios', ['class' => 'save-profile-btn']) ?>
            </div>
    
        </section>
    
    </div>
    
    <?= $this->Form->end() ?>
    </article>
    

<style>
.edit-profile-page {
    max-width: 760px;
    margin: 0 auto;
    display: grid;
    gap: 14px;
}

.edit-profile-header h2 {
    margin: 0;
    color: #2f271d;
    font-size: 2rem;
}

.edit-profile-header p {
    margin: 4px 0 0;
    color: #6b6358;
}

.edit-profile-card {
    background: linear-gradient(180deg, #ffffff 0%,rgb(250, 250, 250) 100%);
    border: 1px solidrgb(201, 201, 201);
    border-radius: 14px;
    padding: 20px;
    box-shadow: 0 8px 24px rgba(44, 34, 21, 0.08);
}

.current-avatar-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 14px;
    padding-bottom: 14px;
    border-bottom: 1px solidrgb(248, 183, 183);
}

.current-avatar {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #ffffff;
    box-shadow: 0 3px 12px rgba(35, 26, 16, 0.16);
}

.current-avatar-label {
    font-size: 1.3rem;
    color: #6b6358;
    font-weight: 600;
}

.edit-profile-form .input {
    margin-bottom: 12px;
}

.edit-profile-form {
    width: 100%;
    max-width: 760px;
    margin: 0 auto;
}

.profile-grid,
.password-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px;
}

.profile-grid .input,
.password-grid .input {
    margin-bottom: 0;
}

.field-full {
    grid-column: 1 / -1;
}

.edit-profile-form label {
    display: block;
    margin-bottom: 5px;
    color: #3a2f23;
    font-weight: 600;
}

.edit-profile-form input[type="text"],
.edit-profile-form input[type="email"],
.edit-profile-form input[type="password"],
.edit-profile-form input[type="file"] {
    width: 100%;
    border: 1px solid #d8cab8;
    border-radius: 10px;
    background: #ffffff;
    padding: 10px 12px;
    color: #2f271d;
    transition: border-color 0.16s ease, box-shadow 0.16s ease;
}

.edit-profile-form input[type="password"] {
    padding-right: 46px;
}

.edit-profile-form input[type="file"] {
    padding: 8px;
}

.edit-profile-form input:focus {
    outline: none;
    border-color: #b58d65;
    box-shadow: 0 0 0 3px rgba(181, 141, 101, 0.18);
}

.password-group {
    position: relative;
}

.password-group input[type="password"],
.password-group input[type="text"] {
    padding-right: 45px;
}

.toggle-password {
    position: absolute;
    right: 10px;
    top: 34px;
    border: none;
    width: 28px;
    height: 28px;
    padding: 0;
    border-radius: 6px;
    background: #f6ece0;
    cursor: pointer;
    font-size: 16px;
    line-height: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #6b6358;
}

.password-section {
    margin-top: 10px;
    padding-top: 12px;
    border-top: 1px solid #eadfce;
}

.password-section h3 {
    margin: 0 0 8px;
    color: #3a2f23;
}

.toggle-password:hover {
    color: #8b1e2e;
    background: #efdfcd;
}


.actions-row {
    margin-top: 14px;
    padding-top: 12px;
    border-top: 1px solid #eadfce;
}

.save-profile-btn {
    border: 1px solid #8b1e2e;
    background: #8b1e2e;
    color: #ffffff;
    border-radius: 10px;
    padding: 10px 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.16s ease;
}

.save-profile-btn:hover {
    background: #721624;
    border-color: #721624;
    transform: translateY(-1px);
}

@media (max-width: 640px) {
    .edit-profile-card {
        padding: 14px;
    }

    .profile-grid,
    .password-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    
        document.querySelectorAll('.toggle-password').forEach(function (btn) {
    
            btn.addEventListener('click', function () {
    
                var input = document.getElementById(btn.dataset.target);
                if (!input) return;
    
                if (input.type === 'password') {
                    input.type = 'text';
                    btn.textContent = '🙈';
                } else {
                    input.type = 'password';
                    btn.textContent = '👁';
                }
    
            });
    
        });
    
    });
    </script>
    
