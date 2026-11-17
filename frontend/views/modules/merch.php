<?php
$data = $args['data'];
$tilts = ['-2deg', '1.5deg', '-1deg', '2deg'];
$aside = $data['button_text']
    ? '<a class="button button--solid" href="' . esc_url($data['button_link'] ?: '#') . '"' . link_target_attrs((string) $data['button_link']) . '>' . esc_html($data['button_text']) . '</a>'
    : '';
?>

<section data-module="merch"<?= module_section_attrs($data) ?>>
    <div class="container">
        <?php get_template_part('frontend/views/partials/section-head', null, ['data' => $data, 'aside' => $aside]) ?>
        <div class="product-grid">
            <?php foreach ($data['items'] as $i => $item) : ?>
                <?php $tag = $item['link'] ? 'a' : 'div'; ?>
                <<?= $tag ?> class="product" style="--tilt: <?= $tilts[$i % count($tilts)] ?>"<?= $item['link'] ? ' href="' . esc_url($item['link']) . '"' . link_target_attrs($item['link']) : '' ?>>
                    <?php if ($item['image']) : ?>
                        <img src="<?= $item['image']['src'] ?>" alt="<?= esc_attr($item['image']['alt']) ?>">
                    <?php else : ?>
                        <?php get_template_part('frontend/views/partials/placeholder') ?>
                    <?php endif; ?>
                    <span class="product-title"><?= esc_html($item['title']) ?></span>
                    <?php if ($item['price']) : ?>
                        <span class="product-price"><?= esc_html($item['price']) ?></span>
                    <?php endif; ?>
                </<?= $tag ?>>
            <?php endforeach; ?>
        </div>
    </div>
</section>
