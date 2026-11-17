<?php
$data = $args['data'];
$paragraphs = array_filter(array_map('trim', preg_split("/\R\s*\R/", (string) $data['text'])));
$chips = array_filter(array_map('trim', preg_split("/\R/", (string) $data['chips'])));
$side = $data['image_side'] ?: 'links';
?>

<section data-module="page-intro"<?= module_section_attrs($data) ?>>
    <div class="container layout--<?= esc_attr($side) ?>">
        <figure class="polaroid shape--<?= esc_attr($data['image_shape'] ?: 'hoch') ?>">
            <?php if ($data['image']) : ?>
                <img src="<?= $data['image']['src'] ?>" alt="<?= esc_attr($data['image']['alt']) ?>">
            <?php else : ?>
                <?php get_template_part('frontend/views/partials/placeholder', null, ['text' => 'Foto folgt']) ?>
            <?php endif; ?>
            <?php if ($data['image_caption']) : ?>
                <figcaption><?= esc_html($data['image_caption']) ?></figcaption>
            <?php endif; ?>
        </figure>
        <div class="box">
            <?php if ($data['label']) : ?>
                <span class="label"><?= esc_html($data['label']) ?></span>
            <?php endif; ?>
            <h1 class="title">
                <?= esc_html($data['title']) ?>
                <?php if ($data['title_accent']) : ?>
                    <span class="accent"><?= esc_html($data['title_accent']) ?></span>
                <?php endif; ?>
            </h1>
            <?php foreach ($paragraphs as $paragraph) : ?>
                <p class="text"><?= nl2br(esc_html($paragraph)) ?></p>
            <?php endforeach; ?>
            <?php if ($chips) : ?>
                <div class="chips">
                    <?php foreach ($chips as $chip) : ?>
                        <span class="chip"><?= esc_html($chip) ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php if ($data['button_1_text'] || $data['button_2_text']) : ?>
                <div class="buttons">
                    <?php if ($data['button_1_text']) : ?>
                        <a class="button button--solid" href="<?= esc_url($data['button_1_link'] ?: '#') ?>"<?= link_target_attrs((string) $data['button_1_link']) ?>><?= esc_html($data['button_1_text']) ?></a>
                    <?php endif; ?>
                    <?php if ($data['button_2_text']) : ?>
                        <a class="button button--outline" href="<?= esc_url($data['button_2_link'] ?: '#') ?>"<?= link_target_attrs((string) $data['button_2_link']) ?>><?= esc_html($data['button_2_text']) ?></a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
