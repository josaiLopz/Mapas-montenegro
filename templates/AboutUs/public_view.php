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
                class="about-us-image__img"
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

<style>
.about-us-public {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 clamp(16px, 4vw, 42px);
    box-sizing: border-box;
}

.about-us-public h1 {
    margin-bottom: 18px;
}

.about-us-image__img {
    display: block;
    width: auto;
    max-width: 100%;
    height: auto;
    margin-bottom: 20px;
    border-radius: 8px;
}

.about-us-content {
    line-height: 1.65;
}
</style>
