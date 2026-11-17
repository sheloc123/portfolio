<?php
$menu = get_menu_by_slug('legals');
$socials = get_global_socials();
$footer = get_globals_footer();
?>

<footer data-layout="footer">
    <div class="stripes" aria-hidden="true"></div>
    <div class="container">
        <div class="cta">
            <h2 class="cta-title"><?= esc_html($footer['title']) ?></h2>
            <?php if ($footer['button_text'] && $footer['button_link']) : ?>
                <a class="button button--pink" href="<?= esc_url($footer['button_link']) ?>"><?= esc_html($footer['button_text']) ?></a>
            <?php endif; ?>
        </div>
        <div class="info">
            <div class="about">
                <a class="name" href="<?= home_url('/') ?>"><?= esc_html($footer['name']) ?></a>
                <?php if ($footer['tagline']) : ?>
                    <p class="tagline"><?= esc_html($footer['tagline']) ?></p>
                <?php endif; ?>
            </div>
            <div class="socials">
                <?php foreach ($socials as $item) : ?>
                    <a target="_blank" rel="noopener" href="<?= esc_url($item['link']) ?>" aria-label="<?= esc_attr($item['title']) ?>">
                        <?php if ($item['icon']) : ?>
                            <img class="social-icon" src="<?= $item['icon']['src'] ?>" alt="">
                        <?php else : ?>
                            <?= esc_html($item['title']) ?>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="bottom">
            <span>© <?= date('Y') ?> <?= esc_html($footer['name']) ?></span>
            <ul class="nav">
                <?php foreach ($menu as $item) : ?>
                    <li class="nav-item">
                        <a target="<?= $item['target'] ?>" href="<?= $item['url'] ?>"><?= $item['title'] ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</footer>
