<?php
$data = $args['data'];
$tilts = ['-2deg', '1.5deg', '-1deg', '2deg'];
?>

<section data-module="projects"<?= module_section_attrs($data) ?>>
    <div class="container">
        <div class="intro-box">
            <h1 class="title"><?= esc_html($data['title']) ?></h1>
            <?php if ($data['text']) : ?>
                <p class="text"><?= nl2br(esc_html($data['text'])) ?></p>
            <?php endif; ?>
            <?php if (count($data['groups']) > 1) : ?>
                <nav class="chips" aria-label="Kategorien">
                    <?php foreach ($data['groups'] as $group) : ?>
                        <a class="chip" href="#<?= esc_attr($group['id']) ?>"><?= esc_html($group['title']) ?></a>
                    <?php endforeach; ?>
                </nav>
            <?php endif; ?>
        </div>
        <?php foreach ($data['groups'] as $group) : ?>
            <div class="group" id="<?= esc_attr($group['id']) ?>">
                <div class="group-head">
                    <h2 class="heading"><?= esc_html($group['title']) ?></h2>
                    <?php if ($group['sub']) : ?>
                        <span class="subtitle"><?= esc_html($group['sub']) ?></span>
                    <?php endif; ?>
                </div>
                <div class="project-grid">
                    <?php foreach ($group['items'] as $i => $item) : ?>
                        <?php
                        // -> mit zwei Links wird die Karte selbst kein Link, sondern hat zwei Buttons
                        $two_links = $item['link'] && $item['link_2'];
                        $tag = $item['link'] && !$two_links ? 'a' : 'div';
                        ?>
                        <<?= $tag ?> class="project" style="--tilt: <?= $tilts[$i % count($tilts)] ?>"<?= $tag === 'a' ? ' href="' . esc_url($item['link']) . '"' . link_target_attrs($item['link']) : '' ?>>
                            <?php if ($item['image']) : ?>
                                <img src="<?= $item['image']['src'] ?>" alt="<?= esc_attr($item['image']['alt']) ?>">
                            <?php else : ?>
                                <?php get_template_part('frontend/views/partials/placeholder') ?>
                            <?php endif; ?>
                            <?php if ($item['status']) : ?>
                                <span class="status"><?= esc_html($item['status']) ?></span>
                            <?php endif; ?>
                            <span class="project-title"><?= esc_html($item['title']) ?></span>
                            <?php if ($item['meta']) : ?>
                                <span class="project-meta"><?= esc_html($item['meta']) ?></span>
                            <?php endif; ?>
                            <?php if ($two_links) : ?>
                                <span class="project-links">
                                    <a href="<?= esc_url($item['link']) ?>"<?= link_target_attrs($item['link']) ?>><?= esc_html($item['link_text']) ?></a>
                                    <a href="<?= esc_url($item['link_2']) ?>"<?= link_target_attrs($item['link_2']) ?>><?= esc_html($item['link_2_text']) ?></a>
                                </span>
                            <?php endif; ?>
                        </<?= $tag ?>>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
