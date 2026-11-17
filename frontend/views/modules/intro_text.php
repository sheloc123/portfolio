<?php
$data = $args['data'];
?>

<section data-module="intro-text">
    <div class="inner">
        <div class="box">
            <p class="text"><?= esc_html($data['text']) ?></p>
        </div>
        <?php if ($data['image_left']) : ?>
            <figure class="polaroid polaroid--left">
                <img src="<?= $data['image_left']['src'] ?>" alt="<?= esc_attr($data['image_left']['alt']) ?>">
                <?php if (!empty($data['caption_left'])) : ?>
                    <figcaption><?= esc_html($data['caption_left']) ?></figcaption>
                <?php endif; ?>
            </figure>
        <?php endif; ?>
        <?php if ($data['image_right']) : ?>
            <figure class="polaroid polaroid--right">
                <img src="<?= $data['image_right']['src'] ?>" alt="<?= esc_attr($data['image_right']['alt']) ?>">
                <?php if (!empty($data['caption_right'])) : ?>
                    <figcaption><?= esc_html($data['caption_right']) ?></figcaption>
                <?php endif; ?>
            </figure>
        <?php endif; ?>
    </div>
</section>
