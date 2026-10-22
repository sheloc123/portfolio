<?php
$menu = get_menu_by_slug( slug: 'legals');
$socials = get_global_socials();
?>

<footer data-layout="footer">
    <div class = "socials">
        <?php foreach ($socials as $item) : ?>
            <a target="_blank" href="<?= $item['link'] ?>" aria-label="Link zu <?= $item ['title'] ?>">
                <img class="social-icon" src="<?= $item ['icon']['src'] ?>" alt="<?= $item ['icon']['src'] ?>">
            </a>
        <?php endforeach; ?>
    </div>
    <nav class="nav-container">
        <ul class="nav">
            <?php foreach ($menu as $item) : ?>
                <li class="nav-item">
                    <a target="<?= $item['target'] ?>" href="<?= $item['url'] ?>">
                        <?= $item['title'] ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
</footer>
