<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\AboutU|null $aboutU
 */
?>

<div class="about-us-public">

<?php if ($aboutU): ?>

    <h1><?= h($aboutU->title) ?></h1>

    <?php if (!empty($aboutU->image)): ?>
        <div class="about-us-image">
            <img
                src="/img/about/<?= h($aboutU->image) ?>"
                alt="<?= h($aboutU->title) ?>"
                style="max-width: 100%; margin-bottom: 20px"
            >
        </div>
    <?php endif; ?>

    <div class="about-us-content">
        <?= nl2br(h($aboutU->content)) ?>
    </div>

<?php else: ?>

    <p>Información no disponible.</p>

<?php endif; ?>

</div>
