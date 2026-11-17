<?php
$menu = get_menu_by_slug('main');
$logo = get_globals_logo();
$button = get_globals_header_button();
?>

<header data-layout="header">
    <a class="logo-link" href="<?= home_url('/') ?>">
        <?php if ($logo) : ?>
            <img class="logo" src="<?= $logo['src'] ?>" alt="<?= $logo['alt'] ?>">
        <?php else : ?>
            <span class="logo-text"><?php bloginfo('name') ?></span>
        <?php endif; ?>
    </a>
    <nav class="nav-container" aria-label="Hauptnavigation">
        <ul class="nav">
            <?php foreach ($menu as $item) : ?>
                <li class="nav-item">
                    <a target="<?= $item['target'] ?>" href="<?= $item['url'] ?>">
                        <?= $item['title'] ?>
                    </a>
                </li>
            <?php endforeach; ?>
            <?php if ($button) : ?>
                <li class="nav-item">
                    <a class="button button--pink" href="<?= esc_url($button['link']) ?>"><?= esc_html($button['text']) ?></a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
