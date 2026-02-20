<?php
$cakeDescription = 'Mapa Distribuidores Montenegro';
$identity = $this->request->getAttribute('identity');
$user = $identity ? $identity->getOriginalData() : null;

$profileImage = (!empty($user->avatar))
    ? '/img/avatars/' . h($user->avatar)
    : '/img/default-user.png';

$allUsers = $this->get('allUsers') ?? [];
/**
 * ===============================
 *   SISTEMA DINÃMICO DE PERMISOS
 * ===============================
 */

$permissionsMap = [];

if ($user && !empty($user->role->permissions)) {
    foreach ($user->role->permissions as $perm) {
        $key = strtolower($perm->controller . '.' . $perm->action);
        $permissionsMap[$key] = true;
    }
}

/**
 * Helper local para validar permisos
 */
$can = function (string $controller, string $action) use ($permissionsMap): bool {
    $key = strtolower($controller . '.' . $action);
    return isset($permissionsMap[$key]);
};

$roleName = strtolower((string)($user->role->name ?? ''));
$isTicketManagerByRole = str_contains($roleName, 'admin')
    || str_contains($roleName, 'super')
    || str_contains($roleName, 'soporte')
    || str_contains($roleName, 'support');
$canTicketsBase = $isTicketManagerByRole || $can('Tickets', 'manage') || $can('Tickets', 'index');
$canTicketNotifications = $canTicketsBase || $can('Tickets', 'myNotifications');

/**
 * ConstrucciÃ³n dinÃ¡mica del sidebar
 */
$sidebarItems = [];

// AdministraciÃ³n
if ($can('Dashboard', 'index')) {
    $sidebarItems[] = ['Dashboard', ['controller' => 'Dashboard', 'action' => 'index']];
}

if ($can('Users', 'index')) {
    $sidebarItems[] = ['Usuarios', ['controller' => 'Users', 'action' => 'index']];
}

if ($can('Permissions', 'index')) {
    $sidebarItems[] = ['Permisos', ['controller' => 'Permissions', 'action' => 'index']];
}

if ($can('Roles', 'index')) {
    $sidebarItems[] = ['Roles', ['controller' => 'Roles', 'action' => 'index']];
}

if ($can('Schools', 'index')) {
    $sidebarItems[] = ['Schools', ['controller' => 'Schools', 'action' => 'index']];
}

if ($can('Schools', 'transfer')) {
    $sidebarItems[] = ['Transferir escuela', ['controller' => 'Schools', 'action' => 'transfer']];
}

if ($can('Schools', 'asignar')) {
    $sidebarItems[] = ['Asignar escuela', ['controller' => 'Schools', 'action' => 'asignar']];
}

if ($can('Schools', 'filtros')) {
    $sidebarItems[] = ['Filtros Admin', ['controller' => 'Schools', 'action' => 'filtros']];
}
if ($canTicketsBase) {
    $sidebarItems[] = ['Tickets', ['controller' => 'Tickets', 'action' => 'index']];
}

$showSidebar = !empty($sidebarItems);


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
    <script>
      (function () {
        try {
          if (localStorage.getItem('layout.sidebarCollapsed') === '1') {
            document.documentElement.classList.add('sidebar-collapsed');
          }
        } catch (e) {}
      })();
    </script>
    <?= $this->Html->meta('icon', '/img/icon.png') ?>

    <?= $this->Html->css(['normalize.min', 'milligram.min', 'fonts', 'cake']) ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
<style>
:root {
    --bg: #ffffff;
    --ink: #ffffff;
    --muted: #c2b6b6;
    --accent: #ffffff;
    --shadow: 0 10px 30px rgba(255, 255, 255, 0.08);
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

.flash-stack {
    position: fixed;
    top: 84px;
    right: 16px;
    width: min(420px, calc(100vw - 32px));
    z-index: 20000;
}

.flash-stack .message {
    width: auto;
    margin-bottom: 10px;
    box-shadow: 0 10px 22px rgba(0, 0, 0, 0.12);
    cursor: pointer;
    transition: opacity 0.25s ease, transform 0.25s ease;
}

.flash-stack .message.is-closing {
    opacity: 0;
    transform: translateY(-8px);
    pointer-events: none;
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

.ticket-notif-wrap {
    position: relative;
}

.ticket-notif-btn {
    position: relative;
    border: 1px solid #eadfcf;
    background: #fff;
    color: #2f2922;
    border-radius: 999px;
    width: 44px;
    height: 44px;
    cursor: pointer;
    font-size: 1.8rem;
    line-height: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.ticket-notif-btn .bi {
    font-size: 1.7rem;
    line-height: 1;
}

.ticket-notif-count {
    position: absolute;
    top: -5px;
    right: -5px;
    min-width: 20px;
    height: 20px;
    border-radius: 999px;
    background: #aa2334;
    color: #fff;
    font-size: 1rem;
    font-weight: 700;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 0 6px;
}

.ticket-notif-panel {
    position: absolute;
    right: 0;
    top: 54px;
    width: min(380px, 90vw);
    max-height: 420px;
    overflow: auto;
    border-radius: 12px;
    border: 1px solid #e2d9cd;
    background: #fff;
    box-shadow: 0 16px 34px rgba(0, 0, 0, 0.18);
    display: none;
    z-index: 16000;
}

.ticket-notif-panel.open {
    display: block;
}

.ticket-notif-head {
    padding: 10px 12px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
}

.ticket-notif-list {
    display: grid;
}

.ticket-notif-item {
    display: block;
    padding: 10px 12px;
    border-bottom: 1px solid #f0f0f0;
    text-decoration: none;
    color: #1f1b16;
}

.ticket-notif-item:hover {
    background: #faf6f1;
}

.ticket-notif-item small {
    color: #6f6860;
    display: block;
}

.ticket-notif-empty {
    padding: 14px;
    color: #6f6860;
    font-size: 1.3rem;
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

.desktop-sidebar-toggle {
    display: none;
}

@media (min-width: 821px) {
    .app-shell {
        transition: grid-template-columns 0.22s ease;
    }

    .sidebar {
        transition: transform 0.22s ease;
        will-change: transform;
    }

    .desktop-sidebar-toggle {
        display: inline-flex;
        position: fixed;
        top: calc(var(--topbar) + 50vh - 26px);
        left: calc(var(--sidebar) - 12px);
        width: 24px;
        height: 52px;
        border: 0;
        border-radius: 0 10px 10px 0;
        background: #aa2334;
        color: #fff;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 12000;
        box-shadow: 0 10px 24px rgba(0,0,0,0.22);
        font-size: 1.8rem;
        line-height: 1;
        padding: 0;
    }

    body.sidebar-collapsed .app-shell,
    html.sidebar-collapsed .app-shell {
        grid-template-columns: 1fr;
    }

    body.sidebar-collapsed .sidebar,
    html.sidebar-collapsed .sidebar {
        display: none;
    }

    body.sidebar-collapsed .desktop-sidebar-toggle,
    html.sidebar-collapsed .desktop-sidebar-toggle {
        left: 2px;
    }
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

/* =============== âœ… FIX RESPONSIVE (SOLO MÃ“VIL) =============== */
.mobile-ui-toggle,
.mobile-sidebar-overlay {
    display: none;
}
/* ===== CHATBOT GLOBAL ===== */
#chatbot-fab{
  position: fixed;
  right: 18px;
  bottom: 158px;
  width: 50px;
  height: 50px;
  border-radius: 999px;
  border: 0;
  background:rgb(0, 0, 0);
  color: #fff;
  font-size: 22px;
  box-shadow: 0 10px 26px rgba(0,0,0,.25);
  cursor: pointer;
  z-index: 20000;
}

#chatbot-panel{
  position: fixed;
  right: 18px;
  bottom: 86px;
  width: min(380px, calc(100vw - 36px));
  height: min(560px, calc(100vh - 120px));
  background: #fff;
  border: 1px solid #e6e6e6;
  border-radius: 14px;
  box-shadow: 0 18px 45px rgba(0,0,0,.25);
  display: none;
  flex-direction: column;
  overflow: hidden;
  z-index: 20000;
}

#chatbot-head{
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:10px;
  padding:10px 12px;
  border-bottom:1px solid #eee;
  background:#fafafa;
}

#chatbot-head-actions{
  display:flex;
  align-items:center;
  gap:8px;
}

#chatbot-minimize,
#chatbot-close{
  border:0;
  background:#eee;
  border-radius:10px;
  padding:6px 10px;
  cursor:pointer;
}

#chatbot-messages{
  flex:1;
  padding:12px;
  overflow:auto;
  background:#fff;
}

.chatbot-msg{ margin:8px 0; display:flex; }
.chatbot-msg.user{ justify-content:flex-end; }

.chatbot-bubble{
  max-width:85%;
  padding:10px 12px;
  border-radius:12px;
  border:1px solid #eee;
  font-size:13px;
  line-height:1.35;
  white-space:pre-wrap;
}

.chatbot-msg.user .chatbot-bubble{
  background: rgba(170,35,52,.08);
  border-color: rgba(170,35,52,.2);
}

.chatbot-msg.bot .chatbot-bubble{ background:#f8f9fa; }

#chatbot-inputbar{
  display:flex; gap:8px;
  padding:10px 12px;
  border-top:1px solid #eee;
  background:#fafafa;
}

#chatbot-input{
  flex:1;
  border:1px solid #ddd;
  border-radius:10px;
  padding:10px 10px;
  font-size:13px;
}

#chatbot-send{
  border:0;
  border-radius:10px;
  padding:10px 12px;
  background:#aa2334;
  color:#fff;
  cursor:pointer;
}

#chatbot-escalate{
  padding:10px 12px;
  border-top:1px solid #eee;
  background:#fff;
}
#chatbot-messages .chatbot-chips{
    display:flex;
    flex-wrap:wrap;
    gap:8px;
    margin:10px 0 2px;
  }
  
  #chatbot-messages .chatbot-chip{
    appearance:none;
    border:1px solid rgba(170,35,52,.35) !important;
    background: rgba(170,35,52,.08) !important;
    color:#aa2334 !important;
  
    border-radius:999px !important;
    padding:8px 12px !important;
    font-size:12px !important;
    line-height:1 !important;
  
    cursor:pointer;
    box-shadow:none !important;
    text-transform:none !important;
    letter-spacing:0 !important;
    height:auto !important;
  
    transition: transform .08s ease, background .12s ease, border-color .12s ease;
  }
  
  #chatbot-messages .chatbot-chip:hover{
    background: rgba(170,35,52,.14) !important;
    border-color: rgba(170,35,52,.55) !important;
  }
  
  #chatbot-messages .chatbot-chip:active{
    transform: scale(.98);
  }
  
  #chatbot-messages .chatbot-chip:focus{
    outline: none;
    box-shadow: 0 0 0 3px rgba(170,35,52,.18) !important;
  }
/* Solo en pantallas pequeÃ±as */
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

    /* BotÃ³n para abrir/cerrar sidebar (solo mÃ³vil, solo si existe sidebar) */
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
        padding: 14px 16px; /* solo reduce en mÃ³vil */
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
        overflow-x: auto; /* ya lo tenÃ­as, aseguramos ancho completo */
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

    /* Evitar que el contenido quede pegado en mÃ³vil */
    .page-card {
        border-radius: 0;
        padding: 16px;
    }
}
/* =============== FIN FIX RESPONSIVE =============== */

/* BotÃ³n flotante (tu botÃ³n existente) */
.floating-toggle {
    position: fixed;
    bottom: 222px;
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

.floating-toggle .bi {
    font-size: 2rem;
    line-height: 1;
}

.floating-toggle:hover {
    background: #8f1d2b;
    transform: translateY(-2px);
}

/* MODO PANTALLA LIMPIA */
body.clean-view .top-nav { display: none; }
body.clean-view .sidebar { display: none; }
body.clean-view .app-shell { grid-template-columns: 1fr !important; }
body.clean-view .desktop-sidebar-toggle { display: none !important; }
body.clean-view .content { padding: 10px 20px; }
body.clean-view .page-card {
    box-shadow: none;
    border-radius: 0;
    padding: 0;
}
</style>
</head>
<body>

    <!-- Overlay para sidebar en mÃ³vil -->
    <div class="mobile-sidebar-overlay" id="mobileSidebarOverlay"></div>

    <!-- NAV PRINCIPAL -->
    <nav class="top-nav">
        <div class="top-nav-inner">

            <div class="top-nav-title">
                <?= $this->Html->link(' <img class="logo-sim" src="/img/logo.png" alt="Logo">', ['controller' => 'Dashboard', 'action' => 'index'], ['escape' => false]) ?>

                <?php if ($identity && $showSidebar): ?>
                    <button class="mobile-ui-toggle" id="mobileSidebarBtn" type="button" aria-label="Abrir menÃº">
                        â˜°
                    </button>
                <?php endif; ?>
            </div>

            <?php if ($identity): ?>
            <div class="top-nav-links">
                <?= $this->Html->link('Escuelas', ['controller' => 'Schools', 'action' => 'misFiltros']) ?>
                <?php if ($canTicketsBase): ?>
                    <?= $this->Html->link('Tickets', ['controller' => 'Tickets', 'action' => 'index']) ?>
                <?php endif; ?>
                <?= $this->Html->link('Acerca de', ['controller' => 'AboutUs', 'action' => 'publicView']) ?>
                <?= $this->Html->link('Mi perfil', ['controller' => 'Users', 'action' => 'profile']) ?>
                <?= $this->Html->link('Salir', ['controller' => 'Users', 'action' => 'logout']) ?>
            </div>

            <div class="top-nav-right">
                <?php if ($canTicketNotifications): ?>
                    <div class="ticket-notif-wrap">
                        <button type="button" id="ticketNotifBtn" class="ticket-notif-btn" aria-label="Notificaciones de tickets">
                            <i class="bi bi-bell-fill" aria-hidden="true"></i>
                            <span id="ticketNotifCount" class="ticket-notif-count">0</span>
                        </button>
                        <div id="ticketNotifPanel" class="ticket-notif-panel" aria-hidden="true">
                            <div class="ticket-notif-head">
                                <strong>Notificaciones soporte</strong>
                                <button type="button" id="ticketNotifMarkAll" class="button button-outline">Marcar leidas</button>
                            </div>
                            <div id="ticketNotifList" class="ticket-notif-list">
                                <div class="ticket-notif-empty">Sin notificaciones nuevas</div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
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
      <?php if ($identity && $showSidebar): ?>
      <button
          type="button"
          id="desktopSidebarToggle"
          class="desktop-sidebar-toggle"
          aria-label="Ocultar menu lateral"
          aria-expanded="true"
          title="Ocultar menu lateral"><i class="bi bi-caret-left-fill" aria-hidden="true"></i></button>
      <?php endif; ?>
      <?php if ($identity && $showSidebar): ?>
      <aside class="sidebar" id="sidebar">
      
          <h4>AdministraciÃ³n</h4>
      
          <div class="nav-links">
              <?php foreach ($sidebarItems as $item): ?>
                  <?= $this->Html->link($item[0], $item[1]) ?>
              <?php endforeach; ?>
          </div>
      
          <h4>Mi cuenta</h4>
          <div class="nav-links">
              <?= $this->Html->link('Acerca de', ['controller' => 'AboutUs', 'action' => 'index']) ?>
          </div>
      
      </aside>
      <?php endif; ?>
      

        <main class="content1">
            <div class="page-card">
                <div class="flash-stack"><?= $this->Flash->render() ?></div>
                <?= $this->fetch('content') ?>
            </div>
        </main>
    </div>
    <?php else: ?>
    <main class="main">
        <div class="container">
            <div class="flash-stack"><?= $this->Flash->render() ?></div>
            <?= $this->fetch('content') ?>
        </div>
    </main>
    <?php endif; ?>

    <footer></footer>

    <button id="toggle-ui" class="floating-toggle" aria-label="Activar vista limpia" title="Activar vista limpia">
        <i class="bi bi-fullscreen" aria-hidden="true"></i>
    </button>
    <?php if ($identity): // opcional: solo si estÃ¡ logueado ?>
    <button type="button" id="chatbot-fab" title="Ayuda" aria-label="Abrir ayuda" style="padding:0; overflow:hidden;">
        <img src="/img/bot.png" alt="Bot" style="width:70%; height:100%; object-fit:cover;">
    </button>
    
    <div id="chatbot-panel" aria-hidden="true">
      <div id="chatbot-head">
        <div>
          <strong>Ayuda</strong>
          <div style="font-size:12px; opacity:.75;">Asistente del sistema</div>
        </div>
        <div id="chatbot-head-actions">
          <button type="button" id="chatbot-minimize" aria-label="Minimizar">_</button>
          <button type="button" id="chatbot-close" aria-label="Cerrar">X</button>
        </div>
      </div>
    
      <div id="chatbot-messages"></div>
    
      <div id="chatbot-escalate" style="display:none;">
        <div style="font-size:12px; margin-bottom:8px;">
          No pude resolverlo con seguridad. Â¿Quieres contactar a soporte?
        </div>
        <div style="display:flex; gap:8px; flex-wrap:wrap;">
          <a id="chatbot-support-link" class="button" href="<?= $this->Url->build('/tickets/add') ?>">
            Contactar soporte
          </a>
          <button type="button" id="chatbot-copy" class="button button-outline">Copiar conversaciÃ³n</button>
        </div>
      </div>
    
      <div id="chatbot-inputbar">
        <input id="chatbot-input" type="text" placeholder="Escribe tu preguntaâ€¦">
        <button type="button" id="chatbot-send">Enviar</button>
      </div>
    </div>
    <?php endif; ?>
    
<script>
document.addEventListener('DOMContentLoaded', () => {
    (function () {
        const messages = document.querySelectorAll('.flash-stack .message');
        if (!messages.length) return;

        const dismiss = (el) => {
            if (!el || el.dataset.closing === '1') return;
            el.dataset.closing = '1';
            el.classList.add('is-closing');
            window.setTimeout(() => el.remove(), 260);
        };

        messages.forEach((message) => {
            window.setTimeout(() => dismiss(message), 5000);
            message.addEventListener('click', () => dismiss(message));
        });
    })();

    (function(){
        const idleLimitMs = 10 * 60 * 1000;
        const logoutUrl = "<?= $this->Url->build(['controller' => 'Users', 'action' => 'logout']) ?>";
        let idleTimer = null;

        const resetIdleTimer = () => {
            if (idleTimer) {
                clearTimeout(idleTimer);
            }
            idleTimer = setTimeout(() => {
                window.location.href = logoutUrl;
            }, idleLimitMs);
        };

        ['mousemove', 'mousedown', 'keydown', 'scroll', 'touchstart', 'click'].forEach((eventName) => {
            window.addEventListener(eventName, resetIdleTimer, { passive: true });
        });

        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                resetIdleTimer();
            }
        });

        resetIdleTimer();
    })();
    // BotÃ³n de vista limpia (tu lÃ³gica)
    const btn = document.getElementById('toggle-ui');
    if (btn) {
        const icon = btn.querySelector('i');
        btn.addEventListener('click', () => {
            const isClean = document.body.classList.toggle('clean-view');
            if (icon) {
                icon.classList.toggle('bi-fullscreen', !isClean);
                icon.classList.toggle('bi-fullscreen-exit', isClean);
            }
            btn.setAttribute('aria-label', isClean ? 'Salir de vista limpia' : 'Activar vista limpia');
            btn.setAttribute('title', isClean ? 'Salir de vista limpia' : 'Activar vista limpia');
        });
    }

    // Sidebar mÃ³vil (solo si existe)
    const sidebarBtn = document.getElementById('mobileSidebarBtn');
    const overlay = document.getElementById('mobileSidebarOverlay');
    const desktopSidebarToggle = document.getElementById('desktopSidebarToggle');
    const desktopSidebarStorageKey = 'layout.sidebarCollapsed';

    function applyDesktopSidebarState(collapsed) {
        if (window.innerWidth <= 820) {
            document.body.classList.remove('sidebar-collapsed');
            document.documentElement.classList.remove('sidebar-collapsed');
            return;
        }
        document.body.classList.toggle('sidebar-collapsed', collapsed);
        document.documentElement.classList.toggle('sidebar-collapsed', collapsed);
        if (desktopSidebarToggle) {
            const icon = desktopSidebarToggle.querySelector('i');
            if (icon) {
                icon.classList.toggle('bi-caret-right-fill', collapsed);
                icon.classList.toggle('bi-caret-left-fill', !collapsed);
            }
            desktopSidebarToggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
            desktopSidebarToggle.setAttribute('aria-label', collapsed ? 'Mostrar menu lateral' : 'Ocultar menu lateral');
            desktopSidebarToggle.setAttribute('title', collapsed ? 'Mostrar menu lateral' : 'Ocultar menu lateral');
        }
    }

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

        // Si pasas a desktop, asegÃºrate de cerrar estado mÃ³vil
        window.addEventListener('resize', () => {
            if (window.innerWidth > 820) closeSidebar();
        });
    }

    if (desktopSidebarToggle) {
        const savedCollapsed = localStorage.getItem(desktopSidebarStorageKey) === '1';
        applyDesktopSidebarState(savedCollapsed);

        desktopSidebarToggle.addEventListener('click', () => {
            const nowCollapsed = !document.body.classList.contains('sidebar-collapsed');
            applyDesktopSidebarState(nowCollapsed);
            localStorage.setItem(desktopSidebarStorageKey, nowCollapsed ? '1' : '0');
        });

        window.addEventListener('resize', () => {
            const collapsed = localStorage.getItem(desktopSidebarStorageKey) === '1';
            applyDesktopSidebarState(collapsed);
        });
    }
    (function(){
        const btn = document.getElementById('ticketNotifBtn');
        const panel = document.getElementById('ticketNotifPanel');
        const countEl = document.getElementById('ticketNotifCount');
        const listEl = document.getElementById('ticketNotifList');
        const markAllBtn = document.getElementById('ticketNotifMarkAll');
        if (!btn || !panel || !countEl || !listEl) return;

        const csrfToken = "<?= h($this->request->getAttribute('csrfToken') ?? '') ?>";
        const listUrl = "<?= $this->Url->build(['controller' => 'Tickets', 'action' => 'myNotifications']) ?>";
        const markAllUrl = "<?= $this->Url->build(['controller' => 'Tickets', 'action' => 'markNotificationRead']) ?>";

        function escapeHtml(raw) {
            return String(raw ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/\"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function setCount(value){
            const n = Number(value) || 0;
            countEl.textContent = String(n);
            countEl.style.display = n > 0 ? 'inline-flex' : 'none';
        }

        function renderRows(rows){
            if (!Array.isArray(rows) || rows.length === 0) {
                listEl.innerHTML = '<div class="ticket-notif-empty">Sin notificaciones nuevas</div>';
                return;
            }

            listEl.innerHTML = rows.map((row) => {
                const id = Number(row.id) || 0;
                return `
                    <a href="${escapeHtml(row.url || '#')}" class="ticket-notif-item" data-id="${id}">
                        <strong>${escapeHtml(row.title || 'Notificacion')}</strong>
                        <small>${escapeHtml(row.message || '')}</small>
                        <small>${escapeHtml(row.created || '')}</small>
                    </a>
                `;
            }).join('');

            listEl.querySelectorAll('.ticket-notif-item').forEach((link) => {
                link.addEventListener('click', async () => {
                    const id = Number(link.getAttribute('data-id')) || 0;
                    if (!id) return;

                    const headers = { 'Accept': 'application/json' };
                    if (csrfToken) headers['X-CSRF-Token'] = csrfToken;

                    try {
                        await fetch(markAllUrl + '/' + id, {
                            method: 'POST',
                            headers,
                            credentials: 'same-origin',
                        });
                    } catch (_e) {}
                });
            });
        }

        async function fetchNotifications() {
            try {
                const r = await fetch(listUrl, {
                    headers: { 'Accept': 'application/json' },
                    credentials: 'same-origin'
                });
                const t = await r.text();
                const data = JSON.parse(t);
                if (!r.ok || !data || !data.ok) return;
                setCount(data.unread_count || 0);
                renderRows(data.rows || []);
            } catch (_e) {}
        }

        async function markAllRead(){
            const headers = { 'Accept': 'application/json', 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' };
            if (csrfToken) headers['X-CSRF-Token'] = csrfToken;

            try {
                await fetch(markAllUrl, {
                    method: 'POST',
                    headers,
                    credentials: 'same-origin',
                    body: 'all=1',
                });
                fetchNotifications();
            } catch (_e) {}
        }

        btn.addEventListener('click', () => {
            panel.classList.toggle('open');
            panel.setAttribute('aria-hidden', panel.classList.contains('open') ? 'false' : 'true');
            if (panel.classList.contains('open')) {
                fetchNotifications();
            }
        });

        document.addEventListener('click', (e) => {
            if (!panel.contains(e.target) && !btn.contains(e.target)) {
                panel.classList.remove('open');
                panel.setAttribute('aria-hidden', 'true');
            }
        });

        markAllBtn?.addEventListener('click', markAllRead);

        fetchNotifications();
        setInterval(() => {
            if (!document.hidden) {
                fetchNotifications();
            }
        }, 60000);
    })();
    (function(){
        const askUrl = "<?= $this->Url->build('/chatbot/ask', ['escape' => false]) ?>";
        const csrfToken = "<?= h($this->request->getAttribute('csrfToken') ?? '') ?>";
        const storageKey = "chatbot.state.<?= h((string)($user->id ?? $user->email ?? 'user')) ?>";
      
        const fab = document.getElementById('chatbot-fab');
        const panel = document.getElementById('chatbot-panel');
        const closeBtn = document.getElementById('chatbot-close');
        const minimizeBtn = document.getElementById('chatbot-minimize');
        const msgs = document.getElementById('chatbot-messages');
        const input = document.getElementById('chatbot-input');
        const send = document.getElementById('chatbot-send');
      
        const escalateBox = document.getElementById('chatbot-escalate');
        const copyBtn = document.getElementById('chatbot-copy');
        const supportLink = document.getElementById('chatbot-support-link');
      
        if (!fab || !panel || !msgs || !input || !send) return;
      
        // ====== Estado local persistente ======
        const history = []; // {role:'user'|'assistant', content:'...'}
        const messageLog = []; // {role:'user'|'bot', text:'...'}
        let lastChips = [];
        let chatStarted = false;
        let uiState = 'closed';

        function pushHistory(role, content){
          history.push({ role, content });
          if (history.length > 30) history.splice(0, history.length - 30);
        }

        function saveState(){
          sessionStorage.setItem(storageKey, JSON.stringify({
            uiState,
            chatStarted,
            history: history.slice(-30),
            messages: messageLog.slice(-30),
            chips: lastChips,
            escalateVisible: Boolean(escalateBox && escalateBox.style.display !== 'none')
          }));
        }

        function loadState(){
          try {
            const raw = sessionStorage.getItem(storageKey);
            return raw ? JSON.parse(raw) : null;
          } catch (_e) {
            return null;
          }
        }

        function clearState(){
          history.length = 0;
          messageLog.length = 0;
          lastChips = [];
          chatStarted = false;
          msgs.innerHTML = '';
          if (escalateBox) escalateBox.style.display = 'none';
          sessionStorage.removeItem(storageKey);
        }
      
        function getContext(){
          return {
            page: "<?= h($this->getRequest()->getParam('controller') . '/' . $this->getRequest()->getParam('action')) ?>",
            url: window.location.pathname + window.location.search
          };
        }
      
        function addMsg(role, text){
          const cleanText = (text || '').trim();
          if (!cleanText) return;

          const row = document.createElement('div');
          row.className = 'chatbot-msg ' + role;
      
          const b = document.createElement('div');
          b.className = 'chatbot-bubble';
          b.textContent = cleanText;
      
          row.appendChild(b);
          msgs.appendChild(row);
          msgs.scrollTop = msgs.scrollHeight;

          messageLog.push({ role, text: cleanText });
          if (messageLog.length > 30) messageLog.splice(0, messageLog.length - 30);

          pushHistory(role === 'user' ? 'user' : 'assistant', cleanText);
          saveState();
        }
        const addUser = (t)=>addMsg('user', t);
        const addBot  = (t)=>addMsg('bot', t);
      
        // ====== Chips ======
        function renderChips(chips){
          // elimina chips anteriores
          msgs.querySelectorAll('.chatbot-chips').forEach(el => el.remove());
          if (!chips || !chips.length) {
            lastChips = [];
            saveState();
            return;
          }

          lastChips = chips
            .filter(c => c && typeof c.label === 'string' && typeof c.value === 'string')
            .slice(0, 10);
          if (!lastChips.length) {
            saveState();
            return;
          }
      
          const wrap = document.createElement('div');
          wrap.className = 'chatbot-chips';
        
          lastChips.forEach(c => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'chatbot-chip';
            btn.textContent = c.label;
        
            btn.addEventListener('click', () => {
              input.value = c.value;
              onSend();
            });
        
            wrap.appendChild(btn);
          });
        
          msgs.appendChild(wrap);
          msgs.scrollTop = msgs.scrollHeight;
          saveState();
        }
      
        function setUiState(nextState){
          uiState = nextState;
          if (uiState === 'open') {
            panel.style.display = 'flex';
            panel.setAttribute('aria-hidden', 'false');
            fab.style.display = 'none';
            input.focus();
          } else {
            panel.style.display = 'none';
            panel.setAttribute('aria-hidden', 'true');
            fab.style.display = '';
          }
          saveState();
        }

        function openChat(){
          setUiState('open');
      
          if (!chatStarted){
            chatStarted = true;
            addBot("Hola. Soy el asistente del sistema. En que te ayudo hoy?");
            saveState();
          }
        }
        function closeChat(){
          clearState();
          setUiState('closed');
        }

        function minimizeChat(){
          setUiState('minimized');
        }
      
        async function askBot(question){
          const headers = { 'Accept':'application/json', 'Content-Type':'application/json' };
          if (csrfToken) headers['X-CSRF-Token'] = csrfToken;
      
          const r = await fetch(askUrl, {
            method:'POST',
            headers,
            credentials:'same-origin',
            body: JSON.stringify({
              question,
              context: getContext(),
              history
            })
          });
      
          const txt = await r.text();
          let data = null;
          try { data = JSON.parse(txt); } catch(e) { data = { ok:false, answer: txt }; }
      
          if (!r.ok || !data) throw new Error('Respuesta invÃ¡lida');
          return data;
        }
      
        async function onSend(){
          const q = (input.value || '').trim();
          if (!q) return;
      
          input.value = '';
          if (escalateBox) escalateBox.style.display = 'none';
      
          addUser(q);
          const thinkingNode = document.createElement('div');
          thinkingNode.className = 'chatbot-msg bot';
          thinkingNode.innerHTML = '<div class="chatbot-bubble">Estoy revisandoâ€¦</div>';
          msgs.appendChild(thinkingNode);
          msgs.scrollTop = msgs.scrollHeight;
      
          try{
            const data = await askBot(q);
            thinkingNode.remove();
      
            addBot(data.answer || "No pude generar respuesta.");
            renderChips(data.chips || []);
      
            if (data.escalate && escalateBox){
              escalateBox.style.display = '';
              if (data.support_url && supportLink) supportLink.href = data.support_url;
            }
            saveState();
          }catch(e){
            thinkingNode.remove();
            addBot("Tuve un problema para responder. Intenta de nuevo o contacta soporte.");
            renderChips([{label:'Contactar soporte', value:'Necesito soporte'}]);
            if (escalateBox) escalateBox.style.display = '';
            saveState();
          }
        }
      
        function copyConversation(){
          const lines = [];
          msgs.querySelectorAll('.chatbot-msg').forEach(m => {
            const role = m.classList.contains('user') ? 'Usuario' : 'Bot';
            const text = m.querySelector('.chatbot-bubble')?.textContent || '';
            lines.push(role + ': ' + text);
          });
          navigator.clipboard?.writeText(lines.join('\n\n'));
        }

        function restoreState(){
          const saved = loadState();
          if (!saved) return;

          const savedHistory = Array.isArray(saved.history) ? saved.history : [];
          const savedMessages = Array.isArray(saved.messages) ? saved.messages : [];
          const savedChips = Array.isArray(saved.chips) ? saved.chips : [];

          msgs.innerHTML = '';
          history.length = 0;
          messageLog.length = 0;

          savedMessages.forEach((m) => {
            if (!m || (m.role !== 'user' && m.role !== 'bot') || typeof m.text !== 'string') return;
            const text = m.text.trim();
            if (!text) return;

            const row = document.createElement('div');
            row.className = 'chatbot-msg ' + m.role;
            const b = document.createElement('div');
            b.className = 'chatbot-bubble';
            b.textContent = text;
            row.appendChild(b);
            msgs.appendChild(row);
            messageLog.push({ role: m.role, text });
          });

          if (savedHistory.length) {
            savedHistory.forEach((m) => {
              if (!m || (m.role !== 'user' && m.role !== 'assistant') || typeof m.content !== 'string') return;
              const content = m.content.trim();
              if (!content) return;
              history.push({ role: m.role, content });
            });
          } else {
            messageLog.forEach((m) => {
              history.push({ role: m.role === 'user' ? 'user' : 'assistant', content: m.text });
            });
          }

          chatStarted = Boolean(saved.chatStarted || messageLog.length > 0);
          uiState = saved.uiState === 'open' || saved.uiState === 'minimized' ? saved.uiState : 'closed';
          renderChips(savedChips);
          if (escalateBox) escalateBox.style.display = saved.escalateVisible ? '' : 'none';
          msgs.scrollTop = msgs.scrollHeight;
          setUiState(uiState);
        }

        function bindLogoutCleanup(){
          const clear = () => sessionStorage.removeItem(storageKey);

          document.querySelectorAll('a[href]').forEach((a) => {
            const href = (a.getAttribute('href') || '').toLowerCase();
            if (href.includes('/users/logout')) a.addEventListener('click', clear);
          });

          document.querySelectorAll('form[action]').forEach((f) => {
            const action = (f.getAttribute('action') || '').toLowerCase();
            if (action.includes('/users/logout')) f.addEventListener('submit', clear);
          });
        }
      
        restoreState();
        bindLogoutCleanup();

        fab.addEventListener('click', openChat);
        minimizeBtn?.addEventListener('click', minimizeChat);
        closeBtn?.addEventListener('click', closeChat);
        send.addEventListener('click', onSend);
        input.addEventListener('keydown', (e)=>{ if (e.key === 'Enter') onSend(); });
        copyBtn?.addEventListener('click', copyConversation);
      })();
      });
</script>
</body>
</html>
