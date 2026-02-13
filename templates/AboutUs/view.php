<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\AboutU $aboutU
 */
?>

<section class="about-view-page">
    <header class="about-view-header">
        <h2>Detalle About Us</h2>
        <p>Consulta el contenido completo y su estado de publicacion.</p>
    </header>

    <article class="about-view-card">
        <div class="about-view-actions">
            <?= $this->Html->link('Editar', ['action' => 'edit', $aboutU->id], ['class' => 'button button-primary']) ?>
            <?= $this->Html->link('Volver', ['action' => 'index'], ['class' => 'button button-secondary']) ?>
            <?= $this->Form->postLink(
                'Eliminar',
                ['action' => 'delete', $aboutU->id],
                ['class' => 'button button-danger', 'confirm' => __('Are you sure you want to delete # {0}?', $aboutU->id)]
            ) ?>
        </div>

        <dl class="about-view-grid">
            <div class="about-item">
                <dt>ID</dt>
                <dd><?= (int)$aboutU->id ?></dd>
            </div>
            <div class="about-item">
                <dt>Titulo</dt>
                <dd><?= h((string)$aboutU->title) ?></dd>
            </div>
            <div class="about-item">
                <dt>Activo</dt>
                <dd>
                    <span class="status-badge <?= $aboutU->active ? 'is-active' : 'is-inactive' ?>">
                        <?= $aboutU->active ? 'Si' : 'No' ?>
                    </span>
                </dd>
            </div>
            <div class="about-item">
                <dt>Creado</dt>
                <dd><?= h((string)($aboutU->created ?? '-')) ?></dd>
            </div>
            <div class="about-item">
                <dt>Actualizado</dt>
                <dd><?= h((string)($aboutU->updated ?? '-')) ?></dd>
            </div>
            <div class="about-item about-item-full">
                <dt>Imagen</dt>
                <dd>
                    <?php if (!empty($aboutU->image)): ?>
                        <img
                            src="/img/about/<?= h($aboutU->image) ?>"
                            alt="<?= h((string)$aboutU->title) ?>"
                            class="about-image-preview"
                        >
                    <?php else: ?>
                        <span class="empty-text">Sin imagen</span>
                    <?php endif; ?>
                </dd>
            </div>
            <div class="about-item about-item-full">
                <dt>Contenido</dt>
                <dd><?= $this->Text->autoParagraph(h((string)$aboutU->content)); ?></dd>
            </div>
        </dl>
    </article>
</section>

<style>
.about-view-page {
    max-width: 980px;
    margin: 0 auto;
    display: grid;
    gap: 14px;
}

.about-view-header h2 {
    margin: 0;
    color: #2f251a;
}

.about-view-header p {
    margin: 4px 0 0;
    color: #6c6458;
}

.about-view-card {
    background: #ffffff;
    border: 1px solid #e9e0d3;
    border-radius: 12px;
    padding: 16px;
}

.about-view-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 14px;
}

.about-view-grid {
    margin: 0;
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 10px;
}

.about-item {
    margin: 0;
    border: 1px solid #ece3d6;
    border-radius: 10px;
    padding: 10px 12px;
    background: #fffaf3;
}

.about-item dt {
    margin: 0;
    font-size: 1.2rem;
    color: #786f63;
}

.about-item dd {
    margin: 4px 0 0;
    color: #30261b;
    word-break: break-word;
}

.about-item-full {
    grid-column: 1 / -1;
}

.about-image-preview {
    width: auto;
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    border: 1px solid #e1d5c4;
}

.empty-text {
    color: #756c61;
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

.button-secondary {
    color: #4b3e31;
    border-color: #dbc9b4;
    background: #fff8ee;
}

.button-danger {
    color: #ffffff;
    border-color: #bb2d3b;
    background: #dc3545;
}

@media (max-width: 760px) {
    .about-view-card {
        padding: 12px;
    }

    .about-view-grid {
        grid-template-columns: 1fr;
    }

    .about-item-full {
        grid-column: auto;
    }

    .about-view-actions .button {
        width: 100%;
    }
}
</style>
