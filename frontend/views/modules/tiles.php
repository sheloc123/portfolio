<?php
$data = $args['data'];
$items = $data['items'] ?? [];
?>

<section data-module="tiles">
    <div class="grid">
        <?php foreach ($items as $item) : ?>
            <a class="tile pattern--<?= esc_attr($item['style'] ?: 'picknick') ?>" href="<?= esc_url($item['link']) ?>">
                <span class="box">
                    <?php if ($item['label']) : ?>
                        <span class="label"><?= esc_html($item['label']) ?></span>
                    <?php endif; ?>
                    <span class="title"><?= esc_html($item['title']) ?></span>
                    <?php if ($item['text']) : ?>
                        <span class="text"><?= esc_html($item['text']) ?></span>
                    <?php endif; ?>
                </span>
            </a>
        <?php endforeach; ?>
    </div>
</section>
