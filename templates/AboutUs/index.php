<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\AboutU> $aboutUs
 */
?>

<section class="about-index">
    <div class="about-header">
        <div>
            <h1>About Us</h1>
            <p>Administra el contenido institucional que se muestra en la seccion publica.</p>
        </div>
        <div>
            <?= $this->Html->link('Nuevo registro', ['action' => 'add'], ['class' => 'button button-primary']) ?>
        </div>
    </div>

    <section class="about-table-card">
        <div class="table-responsive">
            <table class="about-table">
                <thead>
                    <tr>
                        <th><?= $this->Paginator->sort('id') ?></th>
                        <th><?= $this->Paginator->sort('title', 'Titulo') ?></th>
                        <th><?= $this->Paginator->sort('image', 'Imagen') ?></th>
                        <th><?= $this->Paginator->sort('active', 'Activo') ?></th>
                        <th><?= $this->Paginator->sort('updated', 'Actualizado') ?></th>
                        <th class="actions-col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($aboutUs)): ?>
                        <?php foreach ($aboutUs as $aboutU): ?>
                            <tr>
                                <td><?= (int)$aboutU->id ?></td>
                                <td><?= h((string)$aboutU->title) ?></td>
                                <td><?= h((string)($aboutU->image ?? '-')) ?></td>
                                <td>
                                    <span class="status-badge <?= $aboutU->active ? 'is-active' : 'is-inactive' ?>">
                                        <?= $aboutU->active ? 'Si' : 'No' ?>
                                    </span>
                                </td>
                                <td><?= h((string)($aboutU->updated ?? '-')) ?></td>
                                <td class="actions-col">
                                    <div class="action-links">
                                        <?= $this->Html->link(
                                            '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M12 5C6.5 5 2.1 8.1 1 12c1.1 3.9 5.5 7 11 7s9.9-3.1 11-7c-1.1-3.9-5.5-7-11-7Zm0 11a4 4 0 1 1 0-8 4 4 0 0 1 0 8Z"></path></svg>',
                                            ['action' => 'view', $aboutU->id],
                                            [
                                                'class' => 'button button-small button-info button-icon',
                                                'escape' => false,
                                                'title' => 'Ver registro',
                                                'aria-label' => 'Ver registro',
                                            ]
                                        ) ?>
                                        <?= $this->Html->link(
                                            '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="m3 17.2 0 3.8 3.8 0 11.2-11.2-3.8-3.8L3 17.2Zm17.7-10.5a1 1 0 0 0 0-1.4l-2-2a1 1 0 0 0-1.4 0l-1.6 1.6 3.8 3.8 1.2-1.2Z"></path></svg>',
                                            ['action' => 'edit', $aboutU->id],
                                            [
                                                'class' => 'button button-small button-secondary button-icon',
                                                'escape' => false,
                                                'title' => 'Editar registro',
                                                'aria-label' => 'Editar registro',
                                            ]
                                        ) ?>
                                        <?= $this->Form->postLink(
                                            '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M18.3 5.7 12 12l6.3 6.3-1.4 1.4L10.6 13.4 4.3 19.7 2.9 18.3 9.2 12 2.9 5.7 4.3 4.3l6.3 6.3 6.3-6.3 1.4 1.4Z"></path></svg>',
                                            ['action' => 'delete', $aboutU->id],
                                            [
                                                'method' => 'delete',
                                                'class' => 'button button-small button-danger button-icon',
                                                'escape' => false,
                                                'title' => 'Eliminar registro',
                                                'aria-label' => 'Eliminar registro',
                                                'confirm' => __('Are you sure you want to delete # {0}?', $aboutU->id),
                                            ]
                                        ) ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="empty-row">No hay registros de About Us.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="about-pagination">
            <span><?= $this->Paginator->counter('Mostrando {{start}} a {{end}} de {{count}} registros') ?></span>
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

<style>
.about-index {
    display: grid;
    gap: 14px;
}

.about-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
    flex-wrap: wrap;
}

.about-header h1 {
    margin: 0;
}

.about-header p {
    margin: 4px 0 0;
    color: #6b6358;
}

.about-table-card {
    background: #ffffff;
    border: 1px solid #e9e0d3;
    border-radius: 12px;
    padding: 14px;
}

.table-responsive {
    width: 100%;
    overflow-x: auto;
}

.about-table {
    width: 100%;
    border-collapse: collapse;
}

.about-table th,
.about-table td {
    padding: 10px 8px;
    border-bottom: 1px solid #efe5d8;
    text-align: left;
    vertical-align: middle;
}

.about-table th {
    white-space: nowrap;
    color: #43382b;
    font-weight: 700;
}

.actions-col {
    min-width: 120px;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    border-radius: 999px;
    padding: 3px 9px;
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

.action-links {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}

.button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
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
    width: 16px;
    height: 16px;
    fill: currentColor;
}

.button-primary {
    background: #8c1d2f;
    border-color: #8c1d2f;
    color: #ffffff;
}

.button-secondary {
    color: #4b3e31;
    border-color: #dbc9b4;
    background: #fff8ee;
}

.button-info {
    color: #053b5e;
    border-color: #b7d7eb;
    background: #e9f4fb;
}

.button-danger {
    color: #ffffff;
    border-color: #bb2d3b;
    background: #dc3545;
}

.about-pagination {
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

.empty-row {
    text-align: center;
    color: #6c6458;
}

@media (max-width: 780px) {
    .about-header {
        align-items: stretch;
    }

    .about-header > div {
        width: 100%;
    }

    .about-header .button {
        width: 100%;
    }
}
</style>
