<?php
// -> Kategorien der Projekte: Film, Fotografie, Modeling, Weiteres.
//    Die Beschreibung der Kategorie erscheint als kleiner Untertitel.
register_taxonomy('project_category', ['project'], [
    'labels' => [
        'name' => 'Kategorien',
        'singular_name' => 'Kategorie',
        'menu_name' => 'Kategorien',
        'all_items' => 'Alle Kategorien',
        'edit_item' => 'Kategorie bearbeiten',
        'add_new_item' => 'Neue Kategorie hinzufügen',
        'new_item_name' => 'Name der Kategorie',
        'search_items' => 'Kategorie suchen',
        'not_found' => 'Keine Kategorie gefunden',
    ],
    'hierarchical' => true,
    'public' => false,
    'show_ui' => true,
    'show_admin_column' => true,
    'show_in_nav_menus' => false,
    'show_in_rest' => false,
]);
