<?php
/**
 * In diesem Ordner werden Custom Post Types verwaltet.
 * Für jeden Custom Post Type gibt es eine eigene Datei, die hier geladen wird.
 * Der Dateiname entspricht dem Slug des Post Typs.
 *
 * Dokumentation: https://developer.wordpress.org/reference/functions/register_post_type/
 */

/**
 * Registriert einen einfachen Inhaltstyp mit deutschen Beschriftungen.
 * Diese Inhalte haben keine eigene Seite, sie erscheinen in den Modulen.
 * Die Reihenfolge kann unter "Attribute > Reihenfolge" angepasst werden.
 */
function register_simple_post_type(string $slug, string $singular, string $plural, string $icon, int $position, bool $neuer = false): void {
    $neu = $neuer ? 'Neuer' : 'Neues';
    register_post_type($slug, [
        'labels' => [
            'name' => $plural,
            'singular_name' => $singular,
            'menu_name' => $plural,
            'all_items' => 'Alle ' . $plural,
            'add_new' => $neu . ' ' . $singular,
            'add_new_item' => $neu . ' ' . $singular . ' hinzufügen',
            'new_item' => $neu . ' ' . $singular,
            'edit_item' => $singular . ' bearbeiten',
            'search_items' => $singular . ' suchen',
            'not_found' => 'Nichts gefunden',
            'not_found_in_trash' => 'Nichts im Papierkorb gefunden',
        ],
        'supports' => ['title', 'page-attributes'],
        'hierarchical' => false,
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => $position,
        'menu_icon' => $icon,
        'show_in_admin_bar' => false,
        'show_in_nav_menus' => false,
        'has_archive' => false,
        'exclude_from_search' => true,
        'publicly_queryable' => false,
        'show_in_rest' => false,
    ]);
}

add_action('init', function () {
    require_once __DIR__ . '/_project.php';
    require_once __DIR__ . '/_auftritt.php';
    require_once __DIR__ . '/_merch.php';
    require_once __DIR__ . '/_nominierung.php';
    require_once __DIR__ . '/_medienbericht.php';
    require_once __DIR__ . '/_partner.php';
}, 0);
