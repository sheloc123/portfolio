<?php
$data = $args['data'];
$tilts = ['-3deg', '2deg', '-1.5deg', '3deg'];
?>

<section data-module="events"<?= module_section_attrs($data) ?>>
    <div class="container">
        <?php get_template_part('frontend/views/partials/section-head', null, ['data' => $data]) ?>
        <?php if ($data['upcoming']) : ?>
            <div class="group">
                <span class="label"><?= esc_html($data['upcoming_label']) ?></span>
                <?php foreach ($data['upcoming'] as $event) : ?>
                    <div class="event">
                        <span class="event-date"><?= esc_html($event['day']) ?><small><?= esc_html($event['year']) ?></small></span>
                        <span class="event-info">
                            <span class="event-title"><?= esc_html($event['title']) ?></span>
                            <span class="event-meta"><?= esc_html(implode(' · ', array_filter([$event['place'], $event['kind']]))) ?></span>
                        </span>
                        <?php if ($event['link']) : ?>
                            <a class="button button--solid" href="<?= esc_url($event['link']) ?>"<?= link_target_attrs($event['link']) ?>><?= esc_html($event['link_text']) ?></a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php if ($data['past']) : ?>
            <div class="group">
                <span class="label"><?= esc_html($data['past_label']) ?></span>
                <div class="polaroid-grid">
                    <?php foreach ($data['past'] as $i => $event) : ?>
                        <figure class="polaroid" style="--tilt: <?= $tilts[$i % count($tilts)] ?>">
                            <?php if ($event['image']) : ?>
                                <img src="<?= $event['image']['src'] ?>" alt="<?= esc_attr($event['image']['alt']) ?>">
                            <?php else : ?>
                                <?php get_template_part('frontend/views/partials/placeholder') ?>
                            <?php endif; ?>
                            <figcaption><?= esc_html($event['title'] . ', ' . $event['year']) ?></figcaption>
                        </figure>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
