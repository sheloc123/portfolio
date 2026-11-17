<?php
$data = $args['data'];
?>

<section data-module="offers"<?= module_section_attrs($data) ?>>
    <div class="container">
        <?php get_template_part('frontend/views/partials/section-head', null, ['data' => $data]) ?>
        <div class="offer-grid">
            <?php foreach ($data['items'] ?? [] as $item) : ?>
                <div class="offer">
                    <span class="offer-title"><?= esc_html($item['title']) ?></span>
                    <?php if ($item['text']) : ?>
                        <p class="offer-text"><?= nl2br(esc_html($item['text'])) ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
