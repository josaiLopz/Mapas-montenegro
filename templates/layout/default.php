<?php
$cakeDescription = 'Mapa Distribuidores Montenegro';
$identity = $this->request->getAttribute('identity');
$user = $identity ? $identity->getOriginalData() : null;

$profileImage = (!empty($user->avatar))
    ? '/img/avatars/' . h($user->avatar)
    : '/img/default-user.png';

$allUsers = $this->get('allUsers') ?? [];
$roleName = $user && !empty($user->role) ? (string)$user->role->name : '';
$showSidebar = in_array(strtolower($roleName), ['administrador', 'atencion a cliente'], true);

$dias = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];
$meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
$fechaNav = $dias[(int)date('w')] . ' ' . date('j') . ' de ' . $meses[(int)date('n') - 1] . ', ' . date('Y');
?>

<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon', '/img/icon.png') ?>

    <?= $this->Html->css(['normalize.min', 'milligram.min', 'fonts', 'cake']) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
<style>
:root {
    --bg: #f5f3ef;
    --panel: #ffffff;
    --ink: #1f1b16;
    --muted: #6f6860;
    --accent: #c16f3e;
    --shadow: 0 10px 30px rgba(0,0,0,0.08);
    --radius: 16px;
    --sidebar: 280px;
    --topbar: 92px;
}

html, body {
    width: 100%;
    margin: 0;
    padding: 0;
    font-family: "Raleway", "Arial", sans-serif;
}

body {
    min-height: 100vh;
}

/* Forzar ancho completo en todo el sistema */
.container, .top-nav{
    max-width: 100% !important;
    width: 100% !important;
    padding-left: 0;
    padding-right: 0;
}

.top-nav {
    min-height: 36px;
    background: linear-gradient(130deg, #ffffff 0%, #ffffff 60%, #ffffff 100%);
    border-bottom: 0.5px solid #eadfcf;
    position: sticky;
    top: 0;
    z-index: 10;
    display: flex;
    align-items: center;
    margin: 0;
    padding: 0;
    top: 0;
}

.top-nav-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
}

.top-nav-title img{
    max-width:260px;
    max-height:60px;
    letter-spacing: 0.04em;
    text-transform: uppercase;
}

.top-nav-links {
    display: flex;
    align-items: center;
    gap: 12px;
    font-family: "Raleway", "Arial", sans-serif;
}

.top-nav-links a {
    color: #000000;
    font-weight: 600;
    font-size: 1.6rem;
    padding: 26px;
    background: rgba(255, 255, 255, 0.6);
    border: 0px solid #eadfcf;
}

.top-nav-links a:hover {
    background: #aa2334;
    border-color: #ffffff;
    color: #ffffff;
}

.top-nav-right {
    display: flex;
    align-items: center;
    gap: 14px;
}

.nav-date {
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--muted);
    white-space: nowrap;
}

.app-shell {
    display: grid;
    grid-template-columns: var(--sidebar) 1fr;
    min-height: calc(100vh - var(--topbar));
}

.app-shell.no-sidebar {
    grid-template-columns: 1fr;
}

.sidebar {
    background: #12100e;
    color: #f7efe6;
    padding: 28px 24px;
    position: sticky;
    top: var(--topbar);
    height: calc(100vh - var(--topbar));
    overflow: auto;
}

.sidebar h4 {
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.18em;
    color: #c8b9a6;
    margin: 6px 0 14px;
}

.sidebar .nav-links {
    display: grid;
    gap: 10px;
}

.sidebar .nav-links a {
    display: block;
    padding: 10px 12px;
    border-radius: 10px;
    color: #f7efe6;
    background: rgba(255,255,255,0.02);
    border: 1px solid rgba(255,255,255,0.06);
    font-family: "Raleway", "Arial", sans-serif;
    font-weight: 500;
}

.sidebar .nav-links a:hover {
    border-color: rgba(255,255,255,0.22);
    background: rgba(255,255,255,0.08);
}

.users-strip {
    background: transparent;
    border-radius: 0;
    box-shadow: none;
    padding: 0;
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 0;
    overflow-x: auto;
}

.users-strip h3 {
    margin: 0;
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.2em;
    color: var(--muted);
}

.users-list {
    display: flex;
    gap: 10px;
}

.user-chip {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #f8f4ee;
    border: 1px solid #ede4d7;
    border-radius: 999px;
    padding: 6px 12px 6px 6px;
    min-width: 180px;
    font-family: "Raleway", "Arial", sans-serif;
}

.user-chip img {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #fff;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.user-chip strong {
    display: block;
    font-size: 0.92rem;
}

.user-chip span {
    display: block;
    font-size: 0.78rem;
    color: var(--muted);
}

.main {
    padding: 0;
}

.page-card {
    background: var(--panel);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 24px;
}

.user-self {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 18px;
    padding-top: 18px;
    border-top: 1px solid rgba(255,255,255,0.12);
    font-family: "Raleway", "Arial", sans-serif;
}

.user-self img {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    object-fit: cover;
}

.button.button-outline {
    border-color: #f2e1cf;
    color: #f2e1cf;
}

@media (max-width: 1024px) {
    :root { --sidebar: 220px; }
}

/* =============== ✅ FIX RESPONSIVE (SOLO MÓVIL) =============== */
.mobile-ui-toggle,
.mobile-sidebar-overlay {
    display: none;
}

/* Solo en pantallas pequeñas */
@media (max-width: 820px) {
    :root { --topbar: 72px; }

    /* Barra superior: permitir scroll horizontal de links en vez de romper */
    .top-nav-inner {
        flex-direction: column;
        align-items: stretch;
        gap: 8px;
        padding: 8px 10px;
    }

    .top-nav-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    /* Botón para abrir/cerrar sidebar (solo móvil, solo si existe sidebar) */
    .mobile-ui-toggle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 0;
        background: #aa2334;
        color: #fff;
        border-radius: 10px;
        padding: 10px 12px;
        font-size: 1.8rem;
        line-height: 1;
        cursor: pointer;
        box-shadow: 0 10px 24px rgba(0,0,0,0.18);
        flex: 0 0 auto;
    }

    /* Links superiores accesibles: scroll horizontal */
    .top-nav-links {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        padding-bottom: 6px;
        gap: 8px;
        flex-wrap: nowrap;
    }
    .top-nav-links a {
        white-space: nowrap;
        padding: 14px 16px; /* solo reduce en móvil */
        font-size: 1.4rem;
        border-radius: 12px;
    }

    /* Parte derecha (usuarios + fecha) en columna y sin desbordar */
    .top-nav-right {
        width: 100%;
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }

    .users-strip {
        width: 100%;
        overflow-x: auto; /* ya lo tenías, aseguramos ancho completo */
        -webkit-overflow-scrolling: touch;
        padding-bottom: 6px;
    }

    .nav-date {
        width: 100%;
        white-space: normal;
        line-height: 1.2;
    }

    /* App shell en una sola columna */
    .app-shell {
        grid-template-columns: 1fr;
    }

    /* Sidebar como drawer (accesible sin romper layout) */
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: min(88vw, 320px);
        max-width: 320px;
        transform: translateX(-105%);
        transition: transform 0.25s ease;
        z-index: 10001;
        padding-top: calc(var(--topbar) + 14px);
    }

    body.sidebar-open .sidebar {
        transform: translateX(0);
    }

    /* Overlay para cerrar tocando fuera */
    .mobile-sidebar-overlay {
        display: block;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.35);
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.25s ease;
        z-index: 10000;
    }

    body.sidebar-open .mobile-sidebar-overlay {
        opacity: 1;
        pointer-events: auto;
    }

    /* Evitar que el contenido quede pegado en móvil */
    .page-card {
        border-radius: 0;
        padding: 16px;
    }
}
/* =============== FIN FIX RESPONSIVE =============== */

/* Botón flotante (tu botón existente) */
.floating-toggle {
    position: fixed;
    bottom: 122px;
    right: 22px;
    z-index: 9999;
    background: #aa2334;
    color: #fff;
    border: none;
    border-radius: 999px;
    padding: 12px 20px;
    font-size: 1.9rem;
    font-weight: 600;
    cursor: pointer;
    box-shadow: 0 10px 30px rgba(0,0,0,0.25);
    transition: all 0.25s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
}

.floating-toggle:hover {
    background: #8f1d2b;
    transform: translateY(-2px);
}

/* MODO PANTALLA LIMPIA */
body.clean-view .top-nav { display: none; }
body.clean-view .sidebar { display: none; }
body.clean-view .app-shell { grid-template-columns: 1fr !important; }
body.clean-view .content { padding: 10px 20px; }
body.clean-view .page-card {
    box-shadow: none;
    border-radius: 0;
    padding: 0;
}
</style>
</head>
<body>

    <!-- Overlay para sidebar en móvil -->
    <div class="mobile-sidebar-overlay" id="mobileSidebarOverlay"></div>

    <!-- NAV PRINCIPAL -->
    <nav class="top-nav">
        <div class="top-nav-inner">

            <div class="top-nav-title">
                <?= $this->Html->link(' <img class="logo-sim" src="/img/logo.png" alt="Logo">', ['controller' => 'Dashboard', 'action' => 'index'], ['escape' => false]) ?>

                <?php if ($identity && $showSidebar): ?>
                    <button class="mobile-ui-toggle" id="mobileSidebarBtn" type="button" aria-label="Abrir menú">
                        ☰
                    </button>
                <?php endif; ?>
            </div>

            <?php if ($identity): ?>
            <div class="top-nav-links">
                <?= $this->Html->link('Escuelas', ['controller' => 'Schools', 'action' => 'misFiltros']) ?>
                <?= $this->Html->link('Acerca de', ['controller' => 'AboutUs', 'action' => 'publicView']) ?>
                <?= $this->Html->link('Mi perfil', ['controller' => 'Users', 'action' => 'profile']) ?>
                <?= $this->Html->link('Salir', ['controller' => 'Users', 'action' => 'logout']) ?>
            </div>

            <div class="top-nav-right">
                <div class="users-strip">
                    <h3>Usuarios</h3>
                    <div class="users-list">
                        <?php foreach ($allUsers as $item): ?>
                            <?php
                                $avatar = !empty($item->avatar)
                                    ? '/img/avatars/' . h($item->avatar)
                                    : '/img/default-user.png';
                                $nombre = trim(h(($item->first_name ?? '') . ' ' . ($item->last_name ?? '')));
                                $correo = h($item->email ?? '');
                            ?>
                            <div class="user-chip">
                                <img src="<?= $avatar ?>" alt="Avatar">
                                <div>
                                    <strong><?= $nombre !== '' ? $nombre : $correo ?></strong>
                                    <span><?= $correo ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <?php if (empty($allUsers)): ?>
                            <div class="user-chip">
                                <img src="<?= $profileImage ?>" alt="Avatar">
                                <div>
                                    <strong><?= h($user->email) ?></strong>
                                    <span>Usuario actual</span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="nav-date"><?= h($fechaNav) ?></div>
            </div>
            <?php endif; ?>

        </div>
    </nav>

    <?php if ($identity): ?>
    <div class="app-shell<?= $showSidebar ? '' : ' no-sidebar' ?>">
        <?php if ($showSidebar): ?>
        <aside class="sidebar" id="sidebar">
            <h4>Administración</h4>
            <div class="nav-links">
                <?= $this->Html->link('Dashboard', ['controller' => 'Dashboard', 'action' => 'index']) ?>
                <?= $this->Html->link('Usuarios', ['controller' => 'Users', 'action' => 'index']) ?>
                <?= $this->Html->link('Permisos', ['controller' => 'Permissions', 'action' => 'index']) ?>
                <?= $this->Html->link('Roles', ['controller' => 'Roles', 'action' => 'index']) ?>
                <?= $this->Html->link('Schools', ['controller' => 'Schools', 'action' => 'index']) ?>
                <?= $this->Html->link('Transferir escuela', ['controller' => 'Schools', 'action' => 'transfer']) ?>
                <?= $this->Html->link('Asignar escuela', ['controller' => 'Schools', 'action' => 'asignar']) ?>
                <?= $this->Html->link('Filtros Admin', ['controller' => 'Schools', 'action' => 'filtros']) ?>
            </div>

            <h4>Mi cuenta</h4>
            <div class="nav-links">
                <?= $this->Html->link('Acerca de', ['controller' => 'AboutUs', 'action' => 'index']) ?>
            </div>

            <div class="user-self">
                <img src="<?= $profileImage ?>" alt="Avatar">
                <div>
                    <strong><?= h($user->email) ?></strong>
                    <?= $this->Form->postLink(
                        'Salir',
                        ['controller' => 'Users', 'action' => 'logout'],
                        ['confirm' => 'Â¿Cerrar sesiÃ³n?', 'class' => 'button button-outline']
                    ) ?>
                </div>
            </div>
        </aside>
        <?php endif; ?>

        <main class="content1">
            <div class="page-card">
                <?= $this->Flash->render() ?>
                <?= $this->fetch('content') ?>
            </div>
        </main>
    </div>
    <?php else: ?>
    <main class="main">
        <div class="container">
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </div>
    </main>
    <?php endif; ?>

    <footer></footer>

    <button id="toggle-ui" class="floating-toggle">⛶</button>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Botón de vista limpia (tu lógica)
    const btn = document.getElementById('toggle-ui');
    if (btn) {
        btn.addEventListener('click', () => {
            document.body.classList.toggle('clean-view');
            btn.textContent = '⛶';
        });
    }

    // Sidebar móvil (solo si existe)
    const sidebarBtn = document.getElementById('mobileSidebarBtn');
    const overlay = document.getElementById('mobileSidebarOverlay');

    function openSidebar() {
        document.body.classList.add('sidebar-open');
    }
    function closeSidebar() {
        document.body.classList.remove('sidebar-open');
    }

    if (sidebarBtn && overlay) {
        sidebarBtn.addEventListener('click', () => {
            document.body.classList.toggle('sidebar-open');
        });

        overlay.addEventListener('click', closeSidebar);

        // Cerrar con Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeSidebar();
        });

        // Si pasas a desktop, asegúrate de cerrar estado móvil
        window.addEventListener('resize', () => {
            if (window.innerWidth > 820) closeSidebar();
        });
    }
});
</script>

</body>
</html>
