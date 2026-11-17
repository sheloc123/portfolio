<?php
$data = $args['data'];
?>

<section data-module="nominations"<?= module_section_attrs($data) ?>>
    <div class="container container--narrow">
        <?php get_template_part('frontend/views/partials/section-head', null, ['data' => $data]) ?>
        <div class="badges">
            <?php foreach ($data['items'] as $item) : ?>
                <?php $tag = $item['link'] ? 'a' : 'div'; ?>
                <<?= $tag ?> class="badge status--<?= esc_attr($item['status']) ?>"<?= $item['link'] ? ' href="' . esc_url($item['link']) . '"' . link_target_attrs($item['link']) : '' ?>>
                    <span class="badge-title"><?= esc_html($item['title']) ?></span>
                    <span class="badge-meta"><?= esc_html(implode(' · ', array_filter([$item['category'], $item['year']]))) ?></span>
                    <span class="badge-status"><?= esc_html($item['status']) ?></span>
                </<?= $tag ?>>
            <?php endforeach; ?>
        </div>
    </div>
</section>
