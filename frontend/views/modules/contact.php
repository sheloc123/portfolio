<?php
$data = $args['data'];
$options = array_filter(array_map('trim', preg_split("/\R/", (string) $data['options'])));
$status = sanitize_key($_GET['anfrage'] ?? '');
?>

<section data-module="contact"<?= module_section_attrs($data) ?>>
    <div class="container">
        <div class="contact-text">
            <h2 class="heading"><?= esc_html($data['title']) ?></h2>
            <?php if ($data['text']) : ?>
                <p class="lead"><?= nl2br(esc_html($data['text'])) ?></p>
            <?php endif; ?>
            <?php if ($data['show_media_kit']) : ?>
                <a class="button button--solid" href="<?= esc_url($data['media_kit'] ?: '#') ?>"<?= link_target_attrs((string) $data['media_kit']) ?>><?= get_icon_svg('link') ?>Media Kit ansehen</a>
            <?php endif; ?>
            <?php if ($data['email']) : ?>
                <a class="email" href="mailto:<?= esc_attr(antispambot($data['email'])) ?>"><?= esc_html(antispambot($data['email'])) ?></a>
            <?php endif; ?>
        </div>
        <form class="form" method="post" action="<?= esc_url(admin_url('admin-post.php')) ?>">
            <?php if ($status === 'ok') : ?>
                <p class="message message--ok" role="status"><?= esc_html($data['success_text']) ?></p>
            <?php elseif ($status === 'fehler') : ?>
                <p class="message message--error" role="alert">Das hat leider nicht geklappt. Bitte fülle Name, Mail und Nachricht aus und versuche es nochmals.</p>
            <?php endif; ?>
            <input type="hidden" name="action" value="kontakt_anfrage">
            <?php wp_nonce_field('kontakt_anfrage', 'kontakt_nonce'); ?>
            <?php // -> unsichtbares Feld gegen Spam: Menschen lassen es leer ?>
            <label class="honey" aria-hidden="true">Webseite <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
            <?php if ($options) : ?>
                <label class="field">Worum geht es?
                    <select name="anliegen">
                        <?php foreach ($options as $option) : ?>
                            <option><?= esc_html($option) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
            <?php endif; ?>
            <div class="row">
                <label class="field">Name
                    <input type="text" name="name" required autocomplete="name">
                </label>
                <label class="field">Firma
                    <input type="text" name="firma" autocomplete="organization">
                </label>
            </div>
            <label class="field">Mail
                <input type="email" name="mail" required autocomplete="email">
            </label>
            <label class="field">Nachricht
                <textarea name="nachricht" rows="5" required></textarea>
            </label>
            <button class="button button--solid" type="submit">Senden</button>
        </form>
    </div>
</section>
