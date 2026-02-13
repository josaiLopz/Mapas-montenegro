<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\AboutU $aboutU
 */
?>

<section class="about-form-page">
    <header class="about-form-header">
        <h2>Editar About Us</h2>
        <p>Actualiza titulo, contenido, imagen y estado de publicacion.</p>
    </header>

    <article class="about-form-card">
        <?= $this->Form->create($aboutU, ['type' => 'file']) ?>

        <div class="about-form-grid">
            <?= $this->Form->control('title', ['label' => 'Titulo']) ?>

            <div class="about-form-full">
                <?= $this->Form->control('content', ['type' => 'textarea', 'label' => 'Contenido']) ?>
            </div>

            <div class="about-form-full">
                <?php if (!empty($aboutU->image)): ?>
                    <div class="about-current-image">
                        <label>Imagen actual</label>
                        <img src="/img/about/<?= h($aboutU->image) ?>" alt="Imagen actual" class="about-image-preview">
                    </div>
                <?php endif; ?>

                <?= $this->Form->control('image_file', ['type' => 'file', 'label' => 'Cambiar imagen']) ?>
            </div>

            <?= $this->Form->control('active', ['type' => 'checkbox', 'label' => 'Activo']) ?>
        </div>

        <div class="about-form-actions">
            <?= $this->Form->button('Guardar cambios', ['class' => 'button button-primary']) ?>
            <?= $this->Html->link('Cancelar', ['action' => 'index'], ['class' => 'button button-secondary']) ?>
        </div>

        <?= $this->Form->end() ?>
    </article>
</section>

<style>
.about-form-page {
    max-width: 900px;
    margin: 0 auto;
    display: grid;
    gap: 14px;
}

.about-form-header h2 {
    margin: 0;
    color: #2f251a;
}

.about-form-header p {
    margin: 4px 0 0;
    color: #6c6458;
}

.about-form-card {
    background: #ffffff;
    border: 1px solid #e9e0d3;
    border-radius: 12px;
    padding: 16px;
}

.about-form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px;
}

.about-form-full {
    grid-column: 1 / -1;
}

.about-form-grid .input,
.about-form-grid .textarea,
.about-form-grid .file,
.about-form-grid .checkbox {
    margin-bottom: 0;
}

.about-current-image {
    margin-bottom: 10px;
}

.about-current-image label {
    display: block;
    margin-bottom: 6px;
    font-weight: 700;
}

.about-image-preview {
    width: auto;
    max-width: 240px;
    height: auto;
    border-radius: 8px;
    border: 1px solid #ddd1c0;
    background: #fff;
}

.about-form-actions {
    margin-top: 14px;
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
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
}

.button-primary {
    color: #fff;
    background: #8c1d2f;
    border-color: #8c1d2f;
}

.button-secondary {
    color: #4b3e31;
    background: #fff8ee;
    border-color: #dbc9b4;
}

@media (max-width: 760px) {
    .about-form-card {
        padding: 12px;
    }

    .about-form-grid {
        grid-template-columns: 1fr;
    }

    .about-form-full {
        grid-column: auto;
    }

    .about-form-actions .button {
        width: 100%;
    }
}
</style>

