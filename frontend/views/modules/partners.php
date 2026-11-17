<?php
$data = $args['data'];
?>

<section data-module="partners"<?= module_section_attrs($data) ?>>
    <div class="container">
        <?php get_template_part('frontend/views/partials/section-head', null, ['data' => $data]) ?>
        <div class="partner-grid">
            <?php foreach ($data['items'] as $item) : ?>
                <?php $tag = $item['link'] ? 'a' : 'div'; ?>
                <<?= $tag ?> class="partner"<?= $item['link'] ? ' href="' . esc_url($item['link']) . '"' . link_target_attrs($item['link']) : '' ?>>
                    <span class="partner-logo">
                        <?php if ($item['logo']) : ?>
                            <img src="<?= $item['logo']['src'] ?>" alt="<?= esc_attr($item['title']) ?>">
                        <?php else : ?>
                            Logo folgt
                        <?php endif; ?>
                    </span>
                    <span class="partner-name"><?= esc_html($item['title']) ?></span>
                    <?php if ($item['service']) : ?>
                        <span class="partner-service"><?= esc_html($item['service']) ?></span>
                    <?php endif; ?>
                </<?= $tag ?>>
            <?php endforeach; ?>
        </div>
    </div>
</section>
