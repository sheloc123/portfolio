<?php
$data = $args['data'];
$tilts = ['-4deg', '3deg', '-2deg', '4deg'];
?>

<section data-module="polaroids"<?= module_section_attrs($data) ?>>
    <div class="container">
        <?php get_template_part('frontend/views/partials/section-head', null, ['data' => $data]) ?>
        <div class="polaroid-grid">
            <?php foreach ($data['items'] ?? [] as $i => $item) : ?>
                <figure class="polaroid" style="--tilt: <?= $tilts[$i % count($tilts)] ?>">
                    <?php if ($item['image']) : ?>
                        <img src="<?= $item['image']['src'] ?>" alt="<?= esc_attr($item['image']['alt']) ?>">
                    <?php else : ?>
                        <?php get_template_part('frontend/views/partials/placeholder') ?>
                    <?php endif; ?>
                    <?php if ($item['caption']) : ?>
                        <figcaption><?= esc_html($item['caption']) ?></figcaption>
                    <?php endif; ?>
                </figure>
            <?php endforeach; ?>
        </div>
    </div>
</section>
