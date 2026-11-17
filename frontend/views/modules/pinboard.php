<?php
$data = $args['data'];
$tilts = ['5deg', '-6deg', '4deg', '-3deg'];
$photo = 0;
?>

<section data-module="pinboard"<?= module_section_attrs($data) ?>>
    <div class="container">
        <div class="head-box">
            <h2 class="heading"><?= esc_html($data['title']) ?></h2>
            <?php if ($data['subtitle']) : ?>
                <span class="subtitle"><?= esc_html($data['subtitle']) ?></span>
            <?php endif; ?>
        </div>
        <div class="board">
            <?php foreach ($data['items'] ?? [] as $item) : ?>
                <?php if ($item['kind'] === 'foto') : ?>
                    <figure class="pin-photo tape--<?= $photo % 2 ? 'pink' : 'gruen' ?>" style="--tilt: <?= $tilts[$photo++ % count($tilts)] ?>">
                        <?php if ($item['image']) : ?>
                            <img src="<?= $item['image']['src'] ?>" alt="<?= esc_attr($item['image']['alt']) ?>">
                        <?php else : ?>
                            <?php get_template_part('frontend/views/partials/placeholder') ?>
                        <?php endif; ?>
                        <?php if ($item['text']) : ?>
                            <figcaption><?= esc_html($item['text']) ?></figcaption>
                        <?php endif; ?>
                    </figure>
                <?php else : ?>
                    <div class="pin-dot dot--<?= esc_attr($item['color'] ?: 'pink') ?>"><?= nl2br(esc_html($item['text'])) ?></div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
