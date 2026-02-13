<?php
$permissionsByController = [];

if (!empty($user->role) && !empty($user->role->permissions)) {
    foreach ($user->role->permissions as $permission) {
        $controller = (string)($permission->controller ?? '');
        $action = (string)($permission->action ?? '');

        if ($controller === '' || $action === '') {
            continue;
        }

        if (!isset($permissionsByController[$controller])) {
            $permissionsByController[$controller] = [];
        }

        if (!in_array($action, $permissionsByController[$controller], true)) {
            $permissionsByController[$controller][] = $action;
        }
    }
}

$moduleLabels = [
    'Schools' => 'Escuelas',
    'Users' => 'Usuarios',
    'Dashboard' => 'Dashboard',
    'Roles' => 'Roles',
    'Permissions' => 'Permisos',
    'Chatbot' => 'Chatbot',
    'Materials' => 'Materiales',
];

$humanize = static function (string $value): string {
    $value = preg_replace('/(?<!^)[A-Z]/', ' $0', $value) ?? $value;
    $value = str_replace(['_', '-'], ' ', $value);
    $value = trim($value);

    return $value === '' ? '' : ucfirst(strtolower($value));
};

$hasAnyAction = static function (array $actions, array $targets): bool {
    foreach ($targets as $target) {
        if (in_array($target, $actions, true)) {
            return true;
        }
    }

    return false;
};

$buildPermissionSummary = static function (string $controller, array $actions) use ($hasAnyAction): string {
    $actions = array_values(array_unique(array_map('strtolower', $actions)));

    if (in_array('*', $actions, true)) {
        return 'Tienes acceso completo para administrar esta seccion.';
    }

    if ($controller === 'Schools') {
        if ($hasAnyAction($actions, ['delete'])) {
            return 'Puedes administrar escuelas por completo, incluyendo eliminar registros.';
        }
        if ($hasAnyAction($actions, ['edit', 'update', 'add', 'create'])) {
            return 'Puedes gestionar escuelas: crear y editar informacion importante.';
        }
        if ($hasAnyAction($actions, ['assign', 'verify'])) {
            return 'Puedes gestionar tareas clave de escuelas, como asignar y verificar.';
        }

        return 'Puedes consultar y filtrar el listado de escuelas.';
    }

    if ($controller === 'Users') {
        if ($hasAnyAction($actions, ['delete'])) {
            return 'Puedes administrar usuarios por completo.';
        }
        if ($hasAnyAction($actions, ['edit', 'update', 'add', 'create'])) {
            return 'Puedes crear y editar usuarios.';
        }

        return 'Puedes consultar la informacion de usuarios.';
    }

    if ($controller === 'Dashboard') {
        return 'Puedes ver reportes y metricas generales.';
    }

    if ($controller === 'Chatbot') {
        return 'Puedes usar y gestionar funciones principales del chatbot.';
    }

    if ($controller === 'AboutUs' || $controller === 'Pages') {
        return 'Puedes actualizar el contenido informativo de esta seccion.';
    }

    if ($hasAnyAction($actions, ['edit', 'update', 'add', 'create', 'delete'])) {
        return 'Puedes administrar la informacion principal de esta seccion.';
    }

    if ($hasAnyAction($actions, ['index', 'view'])) {
        return 'Puedes consultar la informacion disponible en esta seccion.';
    }

    return 'Tienes acceso a funciones basicas de esta seccion.';
};
?>

<section class="profile-page">
    <div class="profile-header">
        <h2>Mi Perfil</h2>
        <p>Consulta y administra tu informaci&oacute;n personal.</p>
    </div>

    <article class="profile-card">
        <div class="profile-main">
            <div class="avatar-wrap">
                <img
                    src="/img/avatars/<?= h($user->avatar ?? 'default.png') ?>"
                    alt="Avatar de <?= h($user->name ?? 'usuario') ?>"
                    class="avatar"
                >
            </div>

            <div class="profile-info">
                <dl class="info-grid">
                    <div class="info-item">
                        <dt>Nombre</dt>
                        <dd><?= h($user->name) ?></dd>
                    </div>
                    <div class="info-item">
                        <dt>Email</dt>
                        <dd><?= h($user->email) ?></dd>
                    </div>
                    <div class="info-item">
                        <dt>Apellido paterno</dt>
                        <dd><?= h($user->apellido_paterno) ?></dd>
                    </div>
                    <div class="info-item">
                        <dt>Apellido materno</dt>
                        <dd><?= h($user->apellido_materno) ?></dd>
                    </div>
                    <?php if (!empty($user->role->name)): ?>
                        <div class="info-item">
                            <dt>Rol</dt>
                            <dd><?= h($user->role->name) ?></dd>
                        </div>
                    <?php endif; ?>
                </dl>
            </div>
        </div>

        <div class="profile-actions">
            <?= $this->Html->link('Editar perfil', ['action' => 'editProfile'], ['class' => 'button button-primary']) ?>
            <?= $this->Html->link('Cambiar contrase&ntilde;a', ['action' => 'changePassword'], ['class' => 'button button-secondary', 'escape' => false]) ?>
        </div>

        <section class="permissions-panel">
            <h3>Permisos asignados</h3>

            <?php if (!empty($permissionsByController)): ?>
                <div class="permissions-grid">
                    <?php foreach ($permissionsByController as $controller => $actions): ?>
                        <?php $moduleName = $moduleLabels[$controller] ?? $humanize((string)$controller); ?>
                        <?php $summary = $buildPermissionSummary((string)$controller, $actions); ?>
                        <article class="permission-card permission-card-simple">
                            <h4><?= h($moduleName) ?></h4>
                            <p><?= h($summary) ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="permissions-empty">Este perfil no tiene permisos configurados.</p>
            <?php endif; ?>
        </section>
    </article>
</section>

<style>
.profile-page {
    max-width: 940px;
    margin: 0 auto;
    display: grid;
    gap: 14px;
}

.profile-header h2 {
    margin: 0;
    font-size: 2.1rem;
    color: #272018;
}

.profile-header p {
    margin: 5px 0 0;
    color: #6b6358;
}

.profile-card {
    background: linear-gradient(180deg,rgb(255, 255, 255) 0%,rgb(255, 255, 255) 100%);
    border: 1px solidrgb(201, 201, 201);
    border-radius: 14px;
    padding: 18px;
    box-shadow: 0 8px 24px rgba(44, 34, 21, 0.07);
}

.profile-main {
    display: grid;
    grid-template-columns: 150px 1fr;
    gap: 16px;
    align-items: start;
}

.avatar-wrap {
    display: flex;
    justify-content: center;
}

.avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #ffffff;
    box-shadow: 0 4px 16px rgba(35, 26, 16, 0.16);
}

.info-grid {
    margin: 0;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 10px;
}

.info-item {
    margin: 0;
    background: #ffffff;
    border: 1px solid #efe3d4;
    border-radius: 10px;
    padding: 10px 12px;
}

.info-item dt {
    margin: 0;
    font-size: 1.2rem;
    color: #7a7165;
}

.info-item dd {
    margin: 4px 0 0;
    font-weight: 600;
    color: #2f271d;
    line-height: 1.35;
    word-break: break-word;
}

.profile-actions {
    margin-top: 16px;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    padding-top: 14px;
    border-top: 1px solid #eadfce;
}

.profile-actions .button {
    border-radius: 10px;
    padding: 10px 16px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.18s ease;
}

.profile-actions .button-primary {
    color: #ffffff;
    background: #8b1e2e;
    border: 1px solid #8b1e2e;
}

.profile-actions .button-primary:hover {
    background: #721624;
    border-color: #721624;
    transform: translateY(-1px);
}

.profile-actions .button-secondary {
    color: #4d3f31;
    background: #fff8ef;
    border: 1px solid #dcc8b1;
}

.profile-actions .button-secondary:hover {
    background: #f8ecdd;
    transform: translateY(-1px);
}

.permissions-panel {
    margin-top: 16px;
    padding-top: 14px;
    border-top: 1px solid #eadfce;
}

.permissions-panel h3 {
    margin: 0 0 10px;
    color: #2f271d;
    font-size: 1.6rem;
}

.permissions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
    gap: 10px;
}

.permission-card {
    background: #ffffff;
    border: 1px solid #efe3d4;
    border-radius: 10px;
    padding: 10px 12px;
}

.permission-card h4 {
    margin: 0 0 8px;
    color: #3a2f23;
    font-size: 1.4rem;
}

.permission-card-simple p {
    margin: 0;
    color: #5f5345;
    line-height: 1.45;
}

.permissions-empty {
    margin: 0;
    color: #6b6358;
}

@media (max-width: 700px) {
    .profile-main {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .avatar-wrap {
        justify-content: flex-start;
    }

    .profile-card {
        padding: 14px;
    }
}
</style>
