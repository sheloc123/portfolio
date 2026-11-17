<?php
$data = $args['data'];
?>

<section data-module="spotify-episodes"<?= module_section_attrs($data) ?>>
    <div class="container container--narrow">
        <?php get_template_part('frontend/views/partials/section-head', null, ['data' => $data]) ?>
        <div class="player">
            <iframe title="Lebenslänglich? auf Spotify" src="https://open.spotify.com/embed/show/<?= esc_attr($data['show_id']) ?>?utm_source=generator&amp;theme=0" width="100%" height="352" frameborder="0" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>
        </div>
        <?php if ($data['more_text']) : ?>
            <a class="more-link" href="<?= esc_url($data['more_link'] ?: '#') ?>"<?= link_target_attrs((string) $data['more_link']) ?>><?= esc_html($data['more_text']) ?></a>
        <?php endif; ?>
    </div>
</section>
