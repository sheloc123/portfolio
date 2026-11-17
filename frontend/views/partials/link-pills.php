<?php $links = $args['links'] ?? []; ?>
<?php if ($links) : ?>
    <div class="pills">
        <?php foreach ($links as $item) : ?>
            <a class="pill" href="<?= esc_url($item['link'] ?: '#') ?>"<?= link_target_attrs((string) $item['link']) ?>>
                <?= get_icon_svg($item['icon'] ?: 'link') ?><?= esc_html($item['text']) ?>
            </a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
