<?php
$data = $args['data'];
ob_start();
get_template_part('frontend/views/partials/link-pills', null, ['links' => $data['links'] ?? []]);
$aside = trim(ob_get_clean());
$tilts = ['-3deg', '2deg', '-1.5deg', '3deg'];
?>

<section data-module="social-phones"<?= module_section_attrs($data) ?>>
    <div class="container">
        <?php get_template_part('frontend/views/partials/section-head', null, ['data' => $data, 'aside' => $aside]) ?>
        <?php if (!empty($data['items'])) : ?>
            <div class="phones">
                <?php foreach ($data['items'] as $i => $item) : ?>
                    <div class="phone-item">
                        <div class="phone" style="--tilt: <?= $tilts[$i % count($tilts)] ?>">
                            <?php if ($item['media'] && $item['media']['type'] === 'image') : ?>
                                <img src="<?= $item['media']['src'] ?>" alt="<?= esc_attr($item['media']['alt']) ?>">
                            <?php elseif ($item['media']) : ?>
                                <video src="<?= esc_url($item['media']['src']) ?>" autoplay muted loop playsinline></video>
                            <?php else : ?>
                                <?php get_template_part('frontend/views/partials/placeholder', null, ['text' => 'Video folgt']) ?>
                            <?php endif; ?>
                        </div>
                        <?php if ($item['title']) : ?>
                            <span class="phone-title"><?= esc_html($item['title']) ?></span>
                        <?php endif; ?>
                        <?php if ($item['text']) : ?>
                            <span class="phone-text"><?= esc_html($item['text']) ?></span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($data['stats'])) : ?>
            <div class="stats">
                <?php foreach ($data['stats'] as $stat) : ?>
                    <div class="stat">
                        <span class="stat-number"><?= esc_html($stat['number']) ?></span>
                        <span class="stat-text"><?= esc_html($stat['text']) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
