<?php
$data = $args['data'];
?>

<section data-module="hero-front">
    <h1 class="name" aria-label="<?= esc_attr($data['line_1'] . ' ' . $data['line_2']) ?>">
        <span><?= esc_html($data['line_1']) ?></span>
        <span><?= esc_html($data['line_2']) ?></span>
    </h1>
    <?php if ($data['image']) : ?>
        <img class="portrait" src="<?= $data['image']['src'] ?>" alt="<?= esc_attr($data['image']['alt']) ?>">
    <?php endif; ?>
    <?php if ($data['subtitle']) : ?>
        <p class="subtitle"><?= esc_html($data['subtitle']) ?></p>
    <?php endif; ?>
    <?php if ($data['flower_link']) : ?>
        <a class="flower" href="<?= esc_url($data['flower_link']) ?>">
            <svg class="flower-shape" aria-hidden="true" viewBox="0 0 100 100">
                <circle cx="50" cy="26" r="24"/><circle cx="72.8" cy="42.6" r="24"/><circle cx="64.1" cy="69.4" r="24"/>
                <circle cx="35.9" cy="69.4" r="24"/><circle cx="27.2" cy="42.6" r="24"/><circle cx="50" cy="50" r="26"/>
            </svg>
            <span class="flower-text">
                <strong><?= esc_html($data['flower_title']) ?></strong>
                <span><?= esc_html($data['flower_text']) ?></span>
            </span>
        </a>
    <?php endif; ?>
</section>
