<?php
$data = $args['data'];
?>

<section data-module="hero-center"<?= module_section_attrs($data) ?>>
    <div class="container">
        <?php if ($data['label']) : ?>
            <span class="label"><?= esc_html($data['label']) ?></span>
        <?php endif; ?>
        <h1 class="title"><?= esc_html($data['title']) ?></h1>
        <?php if ($data['text']) : ?>
            <p class="text"><?= nl2br(esc_html($data['text'])) ?></p>
        <?php endif; ?>
        <div class="video">
            <?php if ($data['video_embed']) : ?>
                <?= $data['video_embed'] ?>
            <?php elseif ($data['video_file']) : ?>
                <video src="<?= esc_url($data['video_file']['src']) ?>" controls playsinline preload="metadata"></video>
            <?php else : ?>
                <span class="video-placeholder">
                    <?= get_icon_svg('play') ?>
                    <span>Hier kommt das Werbevideo hin</span>
                </span>
            <?php endif; ?>
        </div>
        <div class="buttons">
            <?php if ($data['button_1_text']) : ?>
                <a class="button button--solid" href="<?= esc_url($data['button_1_link'] ?: '#') ?>"<?= link_target_attrs((string) $data['button_1_link']) ?>><?= get_icon_svg('link') ?><?= esc_html($data['button_1_text']) ?></a>
            <?php endif; ?>
            <?php if ($data['button_2_text']) : ?>
                <a class="button button--outline" href="<?= esc_url($data['button_2_link'] ?: '#') ?>"<?= link_target_attrs((string) $data['button_2_link']) ?>><?= esc_html($data['button_2_text']) ?></a>
            <?php endif; ?>
        </div>
    </div>
</section>
