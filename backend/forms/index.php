<?php
/**
 * Kontaktformular auf der Seite Zusammenarbeit.
 * Die Anfrage wird als Mail an die Adresse unter Globales > Zusammenarbeit geschickt.
 * Danach geht es zurück zur Seite, mit ?anfrage=ok oder ?anfrage=fehler.
 */

function handle_kontakt_anfrage(): void {
    $back = wp_get_referer() ?: home_url('/');
    $back = remove_query_arg('anfrage', strtok($back, '#'));
    $fail = function () use ($back) {
        wp_safe_redirect(add_query_arg('anfrage', 'fehler', $back) . '#kontakt');
        exit;
    };

    if (!isset($_POST['kontakt_nonce']) || !wp_verify_nonce($_POST['kontakt_nonce'], 'kontakt_anfrage')) {
        $fail();
    }
    // -> Spam: das unsichtbare Feld wurde ausgefüllt, so tun als ob alles geklappt hat
    if (!empty($_POST['website'])) {
        wp_safe_redirect(add_query_arg('anfrage', 'ok', $back) . '#kontakt');
        exit;
    }

    $name = sanitize_text_field(wp_unslash($_POST['name'] ?? ''));
    $firma = sanitize_text_field(wp_unslash($_POST['firma'] ?? ''));
    $mail = sanitize_email(wp_unslash($_POST['mail'] ?? ''));
    $anliegen = sanitize_text_field(wp_unslash($_POST['anliegen'] ?? ''));
    $nachricht = sanitize_textarea_field(wp_unslash($_POST['nachricht'] ?? ''));
    if (!$name || !is_email($mail) || !$nachricht) {
        $fail();
    }

    $to = carbon_get_theme_option('contact_email') ?: get_option('admin_email');
    $subject = 'Neue Anfrage über die Webseite' . ($anliegen ? ': ' . $anliegen : '');
    $body = "Worum geht es: $anliegen\nName: $name\nFirma: $firma\nMail: $mail\n\nNachricht:\n$nachricht\n";
    $sent = wp_mail($to, $subject, $body, ['Reply-To: ' . $name . ' <' . $mail . '>']);

    wp_safe_redirect(add_query_arg('anfrage', $sent ? 'ok' : 'fehler', $back) . '#kontakt');
    exit;
}

add_action('admin_post_nopriv_kontakt_anfrage', 'handle_kontakt_anfrage');
add_action('admin_post_kontakt_anfrage', 'handle_kontakt_anfrage');
