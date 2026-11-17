<?php
$data = $args['data'];
?>

<section data-module="press"<?= module_section_attrs($data) ?>>
    <div class="container">
        <?php get_template_part('frontend/views/partials/section-head', null, ['data' => $data]) ?>
        <div class="cards">
            <?php foreach ($data['items'] as $item) : ?>
                <article class="card">
                    <?php if ($item['logo']) : ?>
                        <img class="logo" src="<?= $item['logo']['src'] ?>" alt="<?= esc_attr($item['medium']) ?>">
                    <?php endif; ?>
                    <span class="card-meta"><?= esc_html(implode(' · ', array_filter([$item['medium'], $item['date']]))) ?></span>
                    <span class="card-title"><?= esc_html($item['title']) ?></span>
                    <?php if ($item['link']) : ?>
                        <a class="card-link" href="<?= esc_url($item['link']) ?>"<?= link_target_attrs($item['link']) ?>><?= esc_html($item['link_text']) ?></a>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
