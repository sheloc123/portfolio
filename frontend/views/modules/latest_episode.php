<?php
$data = $args['data'];
$tag = $data['link'] ? 'a' : 'div';
?>

<section data-module="latest-episode">
    <<?= $tag ?> class="bar"<?= $data['link'] ? ' href="' . esc_url($data['link']) . '" target="_blank" rel="noopener"' : '' ?>>
        <svg class="play" aria-hidden="true" viewBox="0 0 44 44"><circle cx="22" cy="22" r="22"/><path d="M18 14 L31 22 L18 30 Z"/></svg>
        <span class="info">
            <span class="label"><?= esc_html($data['label']) ?></span>
            <span class="title"><?= esc_html($data['title']) ?></span>
        </span>
        <?php if ($data['link']) : ?>
            <span class="link-text"><?= esc_html($data['link_text']) ?></span>
        <?php endif; ?>
    </<?= $tag ?>>
</section>
