<?php
/**
 * Funktionen in dieser Datei können überall im Projekt verwendet werden.
 * Wenn eine neue globale Funktion benötigt wird, bitte hier ergänzen.
 *
 * Diese globalen Funktionen sind nicht Wordpress-spezifisch.
 */

// -> PHP-Daten in der Browser-Konsole anzeigen
function js_log(mixed ...$content): void
{
    foreach ($content as $c) {
        echo '<script>console.log(' . json_encode($c) . ')</script>';
    }
}

// -> Details einer Mediendatei anhand der ID laden
function get_media_by_id(int|string $id, string $size = 'm'): false|array
{
    $id = (int) $id;
    $data = [
        'title' => get_the_title($id),
        'alt' => get_post_meta($id, '_wp_attachment_image_alt', true) ?: get_the_title($id),
    ];
    $image = wp_get_attachment_image_src($id, $size);
    if ($image) {
        $data['src'] = $image[0];
        $data['type'] = 'image';
    } else {
        $data['src'] = wp_get_attachment_url($id);
        $data['type'] = get_post_mime_type($id);
    }
    return $data;
}

// -> Ein Menu anhand des Keys laden
function get_menu_by_slug(string $slug): array
{
    $theme_locations = get_nav_menu_locations();
    if (!array_key_exists($slug, $theme_locations)) {
        return [];
    }
    $menu_obj = get_term($theme_locations[$slug], 'nav_menu');
    $menu = wp_get_nav_menu_items($menu_obj->name) ?: [];
    $items = [];
    foreach ($menu as $item) {
        $items[] = [
            'ID' => (int) $item->ID,
            'title' => $item->title,
            'url' => $item->url,
            'target' => $item->target,
        ];
    }
    return $items;
}

// -> Kleine Icons für Social Media Buttons (Strichzeichnungen)
function get_icon_svg(string $name): string
{
    $paths = [
        'instagram' => '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="0.8" fill="currentColor"/>',
        'tiktok' => '<path d="M14 3v11.5a3.5 3.5 0 1 1-3.5-3.5"/><path d="M14 3c.5 3 2.5 4.5 5 4.5"/>',
        'youtube' => '<rect x="2.5" y="6" width="19" height="12" rx="4"/><path d="M10 9.5 L15 12 L10 14.5 Z"/>',
        'spotify' => '<circle cx="12" cy="12" r="9"/><path d="M7.5 9.5c3-1 6.5-.7 9 .8"/><path d="M8 12.5c2.5-.7 5.2-.4 7.3.8"/><path d="M8.7 15.3c1.9-.5 3.8-.3 5.4.6"/>',
        'linkedin' => '<rect x="3" y="3" width="18" height="18" rx="3"/><path d="M8 10v7"/><path d="M8 7v.01"/><path d="M12 17v-4a2 2 0 0 1 4 0v4"/><path d="M12 10v7"/>',
        'link' => '<path d="M14 3h7v7"/><path d="M21 3l-9 9"/><path d="M19 14v5a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h5"/>',
        'play' => '<circle cx="12" cy="12" r="10" fill="currentColor" stroke="none"/><path d="M10 8 L16.5 12 L10 16 Z" fill="var(--icon-play, #0d0d0d)" stroke="none"/>',
    ];
    $path = $paths[$name] ?? $paths['link'];
    return '<svg class="icon" aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' . $path . '</svg>';
}

// -> Ist ein Link extern? Dann in neuem Tab öffnen
function link_target_attrs(string $link): string
{
    $host = parse_url($link, PHP_URL_HOST);
    if ($host && $host !== parse_url(home_url(), PHP_URL_HOST)) {
        return ' target="_blank" rel="noopener"';
    }
    return '';
}

// -> CSS Klasse und Sprungmarke für einen Modul Abschnitt
function module_section_attrs(array $data): string
{
    $background = $data['background'] ?? '' ?: 'beige';
    $attrs = ' class="bg bg--' . esc_attr($background) . '"';
    if (!empty($data['anchor'])) {
        $attrs .= ' id="' . esc_attr(ltrim($data['anchor'], '#')) . '"';
    }
    return $attrs;
}
