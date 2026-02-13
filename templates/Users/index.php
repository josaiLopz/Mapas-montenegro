<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\User> $users
 * @var array<int|string, string> $roles
 */
?>

<section class="users-index">
    <div class="users-header">
        <div>
            <h1>Usuarios</h1>
            <p>Consulta, filtra y administra los usuarios registrados.</p>
        </div>
        <div>
            <?= $this->Html->link('Agregar usuario', ['action' => 'add'], ['class' => 'button button-primary']) ?>
        </div>
    </div>

    <section class="users-filters-card">
        <?= $this->Form->create(null, ['type' => 'get', 'url' => ['action' => 'index']]) ?>
        <div class="users-filters-grid">
            <?= $this->Form->control('nombre_completo', [
                'label' => 'Nombre completo',
                'value' => $this->request->getQuery('nombre_completo'),
            ]) ?>

            <?= $this->Form->control('email', [
                'label' => 'Email',
                'value' => $this->request->getQuery('email'),
            ]) ?>

            <?= $this->Form->control('role_id', [
                'label' => 'Rol',
                'options' => $roles,
                'empty' => 'Todos',
                'value' => $this->request->getQuery('role_id'),
            ]) ?>

            <?= $this->Form->control('activo', [
                'type' => 'select',
                'label' => 'Estado',
                'options' => [1 => 'Activo', 0 => 'Inactivo'],
                'empty' => 'Todos',
                'value' => $this->request->getQuery('activo'),
            ]) ?>
        </div>

        <div class="users-filter-actions">
            <?= $this->Form->button('Buscar', ['class' => 'button button-primary']) ?>
            <?= $this->Html->link('Limpiar', ['action' => 'index'], ['class' => 'button button-secondary']) ?>
        </div>
        <?= $this->Form->end() ?>
    </section>

    <section class="users-table-card">
        <div class="table-responsive">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre completo</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td data-label="ID"><?= (int)$user->id ?></td>
                                <td data-label="Nombre completo"><?= h(trim((string)$user->name . ' ' . (string)$user->apellido_paterno . ' ' . (string)$user->apellido_materno)) ?></td>
                                <td data-label="Email"><?= h((string)$user->email) ?></td>
                                <td data-label="Rol"><?= h((string)($user->role->name ?? 'Sin rol')) ?></td>
                                <td data-label="Estado">
                                    <span class="status-badge <?= $user->activo ? 'is-active' : 'is-inactive' ?>">
                                        <?= $user->activo ? 'Activo' : 'Inactivo' ?>
                                    </span>
                                </td>
                                <td data-label="Acciones" class="action-cell">
                                        <a
                                            href="#"
                                            class="button button-small button-info button-icon view-user"
                                            data-id="<?= (int)$user->id ?>"
                                            aria-label="Ver usuario"
                                            title="Ver usuario"
                                        >
                                            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                                <path d="M12 5C6.5 5 2.1 8.1 1 12c1.1 3.9 5.5 7 11 7s9.9-3.1 11-7c-1.1-3.9-5.5-7-11-7Zm0 11a4 4 0 1 1 0-8 4 4 0 0 1 0 8Z"></path>
                                            </svg>
                                        </a>
                                        <?= $this->Html->link(
                                            '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="m3 17.2 0 3.8 3.8 0 11.2-11.2-3.8-3.8L3 17.2Zm17.7-10.5a1 1 0 0 0 0-1.4l-2-2a1 1 0 0 0-1.4 0l-1.6 1.6 3.8 3.8 1.2-1.2Z"></path></svg>',
                                            ['action' => 'edit', $user->id],
                                            [
                                                'class' => 'button button-small button-secondary button-icon',
                                                'escape' => false,
                                                'aria-label' => 'Editar usuario',
                                                'title' => 'Editar usuario',
                                            ]
                                        ) ?>
                                        <?= $this->Form->postLink(
                                            '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M18.3 5.7 12 12l6.3 6.3-1.4 1.4L10.6 13.4 4.3 19.7 2.9 18.3 9.2 12 2.9 5.7 4.3 4.3l6.3 6.3 6.3-6.3 1.4 1.4Z"></path></svg>',
                                            ['action' => 'delete', $user->id],
                                            [
                                                'class' => 'button button-small button-danger button-icon',
                                                'escape' => false,
                                                'aria-label' => 'Eliminar usuario',
                                                'title' => 'Eliminar usuario',
                                                'confirm' => 'Â¿Seguro que deseas eliminarlo?',
                                            ]
                                        ) ?>
                                    </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="empty-row">No se encontraron usuarios con los filtros actuales.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="users-pagination">
            <span>
                <?= $this->Paginator->counter('Mostrando {{start}} a {{end}} de {{count}} registros') ?>
            </span>
            <div class="pagination-links">
                <?= $this->Paginator->first('<< Primera') ?>
                <?= $this->Paginator->prev('< Anterior') ?>
                <?= $this->Paginator->numbers() ?>
                <?= $this->Paginator->next('Siguiente >') ?>
                <?= $this->Paginator->last('Ultima >>') ?>
            </div>
        </div>
    </section>
</section>

<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalle de usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body" id="userModalBody">Cargando...</div>
        </div>
    </div>
</div>

<style>
.users-index {
    display: grid;
    gap: 14px;
}

.users-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
    flex-wrap: wrap;
}

.users-header h1 {
    margin: 0;
}

.users-header p {
    margin: 4px 0 0;
    color: #6b6358;
}

.users-filters-card,
.users-table-card {
    background: #ffffff;
    border: 1px solid #e9e0d3;
    border-radius: 12px;
    padding: 14px;
}

.users-filters-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 10px;
}

.users-filter-actions {
    margin-top: 10px;
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.users-table {
    width: 100%;
    border-collapse: collapse;
}

.table-responsive {
    width: 100%;
}

.users-table th,
.users-table td {
    padding: 10px 8px;
    border-bottom: 1px solid #efe5d8;
    vertical-align: middle;
    text-align: left;
    word-break: break-word;
}

.users-table th {
    white-space: nowrap;
    color: #43382b;
    font-weight: 700;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    border-radius: 999px;
    padding: 3px 10px;
    font-size: 1.1rem;
    font-weight: 700;
}

.status-badge.is-active {
    color: #0f5132;
    background: #d1e7dd;
}

.status-badge.is-inactive {
    color: #842029;
    background: #f8d7da;
}

.action-cell {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}

.button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 30px;
    border: 1px solid transparent;
    padding: 7px 11px;
    text-decoration: none;
    font-weight: 600;
    line-height: 1.2;
}

.button-small {
    padding: 5px 9px;
    font-size: 1.2rem;
}

.button-icon {
    width: 24px;
    height: 24px;
    padding: 0;
}

.button-icon svg {
    width: 18px;
    height: 18px;
    fill: currentColor;
}

.button-primary {
    background: #8c1d2f;
    border-color: #8c1d2f;
    color: #ffffff;
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

.button-info {
    color: #053b5e;
    border-color: #b7d7eb;
    background: #e9f4fb;
}

.button-info:hover {
    color: #053b5e;
    background: #d9ecf8;
}

.button-danger {
    color: #ffffff;
    border-color: #bb2d3b;
    background: #dc3545;
}

.button-danger:hover {
    color: #ffffff;
    background: #bb2d3b;
}

.users-pagination {
    margin-top: 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.pagination-links {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.pagination-links ul {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0;
    padding: 0;
    list-style: none;
}

.pagination-links li {
    margin: 0;
    padding: 0;
    list-style: none;
}

.pagination-links .current {
    font-weight: 700;
}

.empty-row {
    text-align: center;
    color: #6c6458;
}

@media (max-width: 960px) {
    .users-table th,
    .users-table td {
        padding: 9px 7px;
        font-size: 0.92rem;
    }

    .users-filter-actions .button {
        min-height: 36px;
    }
}

@media (max-width: 780px) {
    .users-header {
        align-items: stretch;
    }

    .users-header > div {
        width: 100%;
    }

    .users-header .button {
        width: 100%;
    }

    .users-table-card {
        padding: 10px;
    }

    .users-filters-grid {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    }
}

@media (max-width: 640px) {
    .users-filters-card,
    .users-table-card {
        padding: 10px;
    }

    .users-filters-grid {
        grid-template-columns: 1fr;
    }

    .users-table thead {
        display: none;
    }

    .users-table,
    .users-table tbody,
    .users-table tr,
    .users-table td {
        display: block;
        width: 100%;
    }

    .users-table tr {
        border: 1px solid #efe5d8;
        border-radius: 10px;
        padding: 8px 10px;
        margin-bottom: 10px;
        background: #fff;
    }

    .users-table td {
        border-bottom: 0;
        padding: 6px 0;
        display: flex;
        justify-content: space-between;
        gap: 12px;
        align-items: center;
        text-align: right;
    }

    .users-table td::before {
        content: attr(data-label);
        font-weight: 700;
        color: #43382b;
        text-align: left;
    }

    .users-table td[data-label="Acciones"] {
        display: block;
        text-align: left;
        padding-top: 10px;
        border-top: 1px dashed #efe5d8;
        margin-top: 4px;
    }

    .users-table td[data-label="Acciones"]::before {
        display: block;
        margin-bottom: 7px;
    }

    .users-table td.empty-row {
        display: block;
        text-align: center;
    }

    .users-table td.empty-row::before {
        display: none;
    }

    .action-cell {
        justify-content: flex-start;
    }

    .users-pagination {
        flex-direction: column;
        align-items: flex-start;
    }
}

@media (max-width: 420px) {
    .users-index {
        gap: 10px;
    }

    .users-header h1 {
        font-size: 1.45rem;
    }

    .users-header p {
        font-size: 0.88rem;
    }

    .users-filters-card,
    .users-table-card {
        padding: 8px;
        border-radius: 10px;
    }

    .users-table tr {
        padding: 7px 8px;
        margin-bottom: 8px;
    }

    .users-table td {
        padding: 5px 0;
        font-size: 0.86rem;
        gap: 8px;
    }

    .users-table td::before {
        font-size: 0.82rem;
    }

    .status-badge {
        font-size: 0.72rem;
        padding: 2px 8px;
    }

    .users-filter-actions {
        gap: 6px;
    }

    .button {
        font-size: 0.82rem;
        padding: 6px 9px;
        border-radius: 7px;
    }

    .button-icon {
        width: 30px;
        height: 30px;
    }

    .button-icon svg {
        width: 16px;
        height: 16px;
    }

    .action-cell {
        gap: 5px;
    }

    .users-pagination {
        gap: 6px;
    }

    .users-pagination span,
    .pagination-links {
        font-size: 0.82rem;
    }
}

@media (max-width: 340px) {
    .users-header h1 {
        font-size: 1.3rem;
    }

    .users-header p {
        font-size: 0.82rem;
    }

    .users-table td {
        font-size: 0.8rem;
    }

    .users-table td::before {
        font-size: 0.78rem;
    }

    .button {
        font-size: 0.78rem;
    }

    .button-icon {
        width: 28px;
        height: 28px;
    }
}
</style>

<script>
(() => {
    const modalEl = document.getElementById('userModal');
    const modalBodyEl = document.getElementById('userModalBody');

    if (!modalEl || !modalBodyEl) {
        return;
    }

    const openModal = () => {
        if (window.bootstrap && window.bootstrap.Modal) {
            window.bootstrap.Modal.getOrCreateInstance(modalEl).show();
            return;
        }

        modalEl.classList.add('show');
        modalEl.style.display = 'block';
    };

    const bindViewActions = () => {
        const buttons = document.querySelectorAll('.view-user');

        buttons.forEach((button) => {
            button.addEventListener('click', (event) => {
                event.preventDefault();
                const userId = button.dataset.id;

                if (!userId) {
                    return;
                }

                modalBodyEl.textContent = 'Cargando...';

                fetch('/users/view/' + userId, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error('No se pudo cargar el detalle del usuario.');
                        }

                        return response.text();
                    })
                    .then((html) => {
                        modalBodyEl.innerHTML = html;
                        openModal();
                    })
                    .catch(() => {
                        modalBodyEl.innerHTML = '<p>No fue posible cargar la informaci&oacute;n del usuario.</p>';
                        openModal();
                    });
            });
        });
    };

    bindViewActions();
})();
</script>
