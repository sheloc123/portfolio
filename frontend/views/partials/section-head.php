<?php
// -> Überschrift eines Abschnitts: kleiner Titel, grosser Titel, Text, rechts optional Buttons
$data = $args['data'];
$aside = $args['aside'] ?? '';
$tag = $args['tag'] ?? 'h2';
if (empty($data['label']) && empty($data['title']) && empty($data['text']) && !$aside) {
    return;
}
?>
<div class="section-head">
    <div class="section-head-text">
        <?php if (!empty($data['label'])) : ?>
            <span class="label"><?= esc_html($data['label']) ?></span>
        <?php endif; ?>
        <?php if (!empty($data['title'])) : ?>
            <<?= $tag ?> class="heading"><?= esc_html($data['title']) ?></<?= $tag ?>>
        <?php endif; ?>
        <?php if (!empty($data['text'])) : ?>
            <p class="lead"><?= nl2br(esc_html($data['text'])) ?></p>
        <?php endif; ?>
    </div>
    <?php if ($aside) : ?>
        <div class="section-head-aside"><?= $aside ?></div>
    <?php endif; ?>
</div>
