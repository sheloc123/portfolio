<?php
/**
 * Einmalige Einrichtung: legt die Seiten Über mich, Podcast, Projekte und
 * Zusammenarbeit mit ihren Modulen an, füllt Platzhalter ein und verlinkt
 * Navigation, Kacheln und Buttons. Läuft automatisch ein einziges Mal, sobald
 * jemand mit Adminrechten das WordPress Backend öffnet.
 *
 * Bestehende Seiten und Inhalte werden nie überschrieben: gibt es eine Seite
 * schon und hat sie Module, bleibt sie, wie sie ist.
 */

const THEME_SETUP_VERSION = 1;

add_action('admin_init', function () {
    // -> mit ?theme_setup=1 in der Adresse kann die Einrichtung nochmals gestartet werden
    $again = isset($_GET['theme_setup']);
    if ((!$again && (int) get_option('theme_setup_version') >= THEME_SETUP_VERSION) || !current_user_can('manage_options')) {
        return;
    }
    update_option('theme_setup_version', THEME_SETUP_VERSION);
    theme_setup_run();
}, 20);

function theme_setup_run(): void {
    $pages = [];
    foreach (theme_setup_pages() as $slug => $page) {
        $pages[$slug] = theme_setup_page($slug, $page['title'], $page['modules']);
    }
    theme_setup_content();
    theme_setup_links($pages);
}

// -> Seite anlegen (falls nötig) und Module einfüllen (falls noch keine da sind)
function theme_setup_page(string $slug, string $title, array $modules): int {
    $page = get_page_by_path($slug);
    $id = $page ? $page->ID : wp_insert_post([
        'post_type' => 'page',
        'post_status' => 'publish',
        'post_title' => $title,
        'post_name' => $slug,
    ]);
    if ($id && !is_wp_error($id) && empty(carbon_get_post_meta($id, 'modules'))) {
        carbon_set_post_meta($id, 'modules', $modules);
    }
    return (int) $id;
}

// -> Beitrag eines Inhaltstyps anlegen, falls es den Titel noch nicht gibt
function theme_setup_post(string $type, string $title, array $meta, int $order = 0, array $terms = []): void {
    $existing = get_posts(['post_type' => $type, 'title' => $title, 'post_status' => 'any', 'numberposts' => 1]);
    if ($existing && !str_starts_with($title, '[')) {
        return;
    }
    $id = wp_insert_post([
        'post_type' => $type,
        'post_status' => 'publish',
        'post_title' => $title,
        'menu_order' => $order,
    ]);
    if (!$id || is_wp_error($id)) {
        return;
    }
    foreach ($meta as $key => $value) {
        carbon_set_post_meta($id, $key, $value);
    }
    if ($terms) {
        wp_set_object_terms($id, $terms, 'project_category');
    }
}

function theme_setup_pages(): array {
    $instagram = 'https://www.instagram.com/sheryn.locher/';
    $tiktok = 'https://www.tiktok.com/@sheryn.joy';
    $youtube = 'http://www.youtube.com/@Lebensl%C3%A4nglichPodcast';
    $spotify = 'https://open.spotify.com/show/0ci3VzojV1j9065WeBU0vW';

    return [
        'ueber-mich' => [
            'title' => 'Über mich',
            'modules' => [
                [
                    '_type' => 'page_intro',
                    'background' => 'notizpapier',
                    'label' => 'Über mich',
                    'title' => 'Hi, ich bin',
                    'title_accent' => 'Sheryn',
                    'text' => "Ich bin Podcasterin, Moderatorin und Creatorin. Mit meinem True Crime Podcast «Lebenslänglich?» erzähle ich Schweizer Kriminalfälle und frage nach, was hinter den Schlagzeilen steckt.\n\nAuf meinen eigenen Kanälen zeige ich, was mich sonst bewegt: die Berge, meine Ziele und den ganz normalen Alltag. Daneben studiere ich an der FH Graubünden, mache Filme und stehe vor und hinter der Kamera.",
                    'chips' => "Chur\nFH Graubünden\nLocher Creations",
                    'image_caption' => 'das bin ich',
                    'image_side' => 'links',
                    'image_shape' => 'hoch',
                ],
                [
                    '_type' => 'social_phones',
                    'background' => 'hellgruen',
                    'label' => 'Mein Social Media',
                    'title' => 'Komm mit',
                    'text' => 'Auf Instagram, TikTok und YouTube nehme ich euch mit auf Wanderungen, teile Motivation für den Alltag und erzähle von meinem Plan A.',
                    'links' => [
                        ['_type' => '_', 'icon' => 'instagram', 'text' => 'Instagram', 'link' => $instagram],
                        ['_type' => '_', 'icon' => 'tiktok', 'text' => 'TikTok', 'link' => $tiktok],
                        ['_type' => '_', 'icon' => 'youtube', 'text' => 'YouTube', 'link' => $youtube],
                    ],
                    'items' => [
                        ['_type' => '_', 'title' => 'Wandervlogs', 'text' => 'Unterwegs in den Bergen, vom Start bis zum Gipfel.'],
                        ['_type' => '_', 'title' => 'Motivation', 'text' => 'Kleine Schubser für Ziele, Alltag und schwierige Tage.'],
                        ['_type' => '_', 'title' => 'Plan A', 'text' => '[Ein Satz, worum es bei Plan A geht]'],
                    ],
                    'stats' => [
                        ['_type' => '_', 'number' => '[X]', 'text' => 'Follower auf Instagram'],
                        ['_type' => '_', 'number' => '[X]', 'text' => 'Follower auf TikTok'],
                        ['_type' => '_', 'number' => '[X]', 'text' => 'Aufrufe pro Monat'],
                    ],
                ],
                [
                    '_type' => 'pinboard',
                    'background' => 'notizpapier',
                    'title' => 'Ein paar Dinge über mich',
                    'subtitle' => 'meine kleine Pinnwand',
                    'items' => [
                        ['_type' => '_', 'kind' => 'punkt', 'color' => 'pink', 'text' => '[Fun Fact über dich]'],
                        ['_type' => '_', 'kind' => 'foto', 'color' => 'pink', 'text' => '[kleine Notiz]'],
                        ['_type' => '_', 'kind' => 'punkt', 'color' => 'gruen', 'text' => '[Lieblingsort in den Bergen]'],
                        ['_type' => '_', 'kind' => 'foto', 'color' => 'pink', 'text' => '[kleine Notiz]'],
                        ['_type' => '_', 'kind' => 'punkt', 'color' => 'beige', 'text' => '[Was dich antreibt]'],
                        ['_type' => '_', 'kind' => 'punkt', 'color' => 'rosa', 'text' => '[Etwas, das niemand erwartet]'],
                    ],
                ],
                [
                    '_type' => 'polaroids',
                    'background' => 'hellgruen',
                    'title' => 'Abseits vom Mikrofon',
                    'text' => 'Reisen, Ausflüge und kleine Momente, die es nie in eine Folge schaffen.',
                    'items' => [
                        ['_type' => '_', 'caption' => '[Ort]'],
                        ['_type' => '_', 'caption' => '[Ort]'],
                        ['_type' => '_', 'caption' => '[Ort]'],
                        ['_type' => '_', 'caption' => '[Ort]'],
                    ],
                ],
            ],
        ],
        'podcast' => [
            'title' => 'Podcast',
            'modules' => [
                [
                    '_type' => 'page_intro',
                    'background' => 'wellen-pink',
                    'label' => 'Der Podcast',
                    'title' => 'Lebenslänglich?',
                    'text' => 'Der Schweizer True Crime Podcast von Sheryn Locher. Echte Fälle, sorgfältig recherchiert, mit Gästen und Fachleuten. Zu hören auf Spotify, zu sehen auf Instagram und TikTok.',
                    'chips' => "90+ Folgen\nSchweizer Fälle\n[X] Hörer:innen pro Monat",
                    'button_1_text' => 'Auf Spotify hören',
                    'button_1_link' => $spotify,
                    'button_2_text' => 'Podcast auf Social Media',
                    'button_2_link' => '#social',
                    'image_caption' => 'jetzt reinhören',
                    'image_side' => 'rechts',
                    'image_shape' => 'quadrat',
                ],
                [
                    '_type' => 'spotify_episodes',
                    'background' => 'rosa',
                    'anchor' => 'folgen',
                    'title' => 'Folgen',
                    'text' => 'Die neuste Folge steht automatisch zuoberst.',
                    'more_text' => 'Mehr True Crime: meine Doku über Benno',
                    'more_link' => '/projekte/#film',
                ],
                [
                    '_type' => 'social_phones',
                    'background' => 'oliv',
                    'anchor' => 'social',
                    'label' => 'Hörbar und sichtbar',
                    'title' => 'Lebenslänglich? auf Social Media',
                    'text' => 'Zu jeder Folge produziere ich selbst Clips, Reels und Hintergründe für Instagram und TikTok. So wird aus dem Podcast eine Community.',
                    'links' => [
                        ['_type' => '_', 'icon' => 'instagram', 'text' => 'Instagram', 'link' => $instagram],
                        ['_type' => '_', 'icon' => 'tiktok', 'text' => 'TikTok', 'link' => $tiktok],
                    ],
                    'items' => [
                        ['_type' => '_', 'title' => 'Clips', 'text' => 'Die stärksten Momente aus jeder Folge.'],
                        ['_type' => '_', 'title' => 'Fälle kurz erklärt', 'text' => 'Hintergründe und Fakten in einer Minute.'],
                        ['_type' => '_', 'title' => 'Hinter den Kulissen', 'text' => 'Recherche, Aufnahme und Gäste im Studio.'],
                    ],
                ],
                [
                    '_type' => 'events',
                    'background' => 'pink',
                    'anchor' => 'auftritte',
                    'title' => 'Auftritte',
                    'upcoming_label' => 'Demnächst',
                    'past_label' => 'Rückblick',
                    'past_count' => '4',
                ],
                [
                    '_type' => 'merch',
                    'background' => 'rosa',
                    'anchor' => 'merch',
                    'title' => 'Merch',
                    'button_text' => 'Zum Shop',
                    'button_link' => '#',
                ],
                [
                    '_type' => 'nominations',
                    'background' => 'oliv',
                    'title' => 'Nominierungen',
                ],
                [
                    '_type' => 'press',
                    'background' => 'pink',
                    'title' => 'In den Medien',
                ],
            ],
        ],
        'projekte' => [
            'title' => 'Projekte',
            'modules' => [
                [
                    '_type' => 'projects',
                    'background' => 'picknick',
                    'title' => 'Projekte',
                    'text' => 'Alles ausserhalb vom Podcast: Filme, Fotografie, Modeling und eigene Ideen.',
                ],
            ],
        ],
        'zusammenarbeit' => [
            'title' => 'Zusammenarbeit',
            'modules' => [
                [
                    '_type' => 'hero_center',
                    'background' => 'streifen',
                    'label' => 'Für Brands und Veranstalter',
                    'title' => 'Lass uns zusammenarbeiten',
                    'text' => 'Podcastwerbung, Social Media, Moderation oder ein Shooting: Ich bringe deine Marke zu einer Community, die mir vertraut.',
                    'button_1_text' => 'Media Kit ansehen',
                    'button_2_text' => 'Anfrage senden',
                    'button_2_link' => '#kontakt',
                ],
                [
                    '_type' => 'partners',
                    'background' => 'pink',
                    'title' => 'Bisherige Partner',
                ],
                [
                    '_type' => 'offers',
                    'background' => 'streifen',
                    'title' => 'Was ich anbiete',
                    'items' => [
                        ['_type' => '_', 'title' => 'Podcastwerbung', 'text' => 'Persönlich eingesprochene Spots in Lebenslänglich?'],
                        ['_type' => '_', 'title' => 'Social Media', 'text' => 'Reels, Stories und Posts für deine Marke.'],
                        ['_type' => '_', 'title' => 'Moderation', 'text' => 'Events, Liveshows und Paneldiskussionen.'],
                        ['_type' => '_', 'title' => 'Vorträge', 'text' => 'True Crime und Podcasting, zum Beispiel an Schulen.'],
                        ['_type' => '_', 'title' => 'Shootings', 'text' => 'Vor und hinter der Kamera, für Brands und Agenturen.'],
                    ],
                ],
                [
                    '_type' => 'contact',
                    'background' => 'pink',
                    'anchor' => 'kontakt',
                    'title' => 'Anfrage senden',
                    'text' => 'Erzähl mir kurz von deiner Idee. Alle Zahlen und Angebote findest du in meinem Media Kit.',
                    'show_media_kit' => true,
                    'options' => "Werbepartnerschaft\nModeration\nVortrag\nMedienanfrage\nSonstiges",
                    'success_text' => 'Danke für deine Anfrage! Ich melde mich so schnell wie möglich.',
                ],
            ],
        ],
    ];
}

// -> Platzhalter für Projekte, Auftritte, Merch, Nominierungen, Medienberichte und Partner
function theme_setup_content(): void {
    $categories = [
        'Film' => ['film', 'Dokumentarfilme und Kurzfilm', 1],
        'Fotografie' => ['fotografie', 'hinter der Kamera', 2],
        'Modeling' => ['modeling', 'vor der Kamera', 3],
        'Weiteres' => ['weiteres', 'Vorträge und eigene Ideen', 4],
    ];
    foreach ($categories as $name => [$slug, $description, $order]) {
        $term = term_exists($slug, 'project_category') ?: wp_insert_term($name, 'project_category', ['slug' => $slug, 'description' => $description]);
        if (!is_wp_error($term)) {
            carbon_set_term_meta((int) $term['term_id'], 'category_order', (string) $order);
        }
    }

    $projects = [
        'film' => [
            ['Benno', 'True Crime Doku, auf Spotify', ''],
            ['Bienen', 'Dokumentarfilm, auf YouTube', ''],
            ['Zoo', 'Dokumentarfilm', 'bald online'],
            ['Blind', 'Dokumentarfilm', 'in Produktion'],
            ['Spielfilm Auslandsemester', 'Kurzer Spielfilm, auf Vimeo', ''],
        ],
        'fotografie' => [
            ['Polizei', 'Fotoshooting', ''],
            ['Solothurn', 'Fotoshooting', ''],
            ['Shooting mit Fabienne', 'Hinter der Kamera', ''],
            ['Natur', 'Naturfotografie', ''],
            ['Ferien', 'Reisefotografie', ''],
        ],
        'modeling' => [
            ['Shooting Auslandsemester', 'Vor der Kamera', ''],
            ['Shooting mit Fabienne', 'Vor der Kamera', ''],
            ['Probeshooting mit Carla', 'Vor und hinter der Kamera', ''],
            ['Podcast Cover', 'Coverbild Lebenslänglich?', ''],
            ['Merch Shooting', 'Für den Podcast Merch', ''],
        ],
        'weiteres' => [
            ['Vortrag am BBZ', 'True Crime und Podcasting an einer Schule', ''],
            ['Startup Idee', 'Webseite und Imagevideo', ''],
        ],
    ];
    foreach ($projects as $category => $items) {
        foreach ($items as $i => [$title, $meta, $status]) {
            // -> gleicher Titel in zwei Kategorien (Shooting mit Fabienne) ist gewollt
            $exists = get_posts(['post_type' => 'project', 'title' => $title, 'post_status' => 'any', 'numberposts' => 1,
                'tax_query' => [['taxonomy' => 'project_category', 'field' => 'slug', 'terms' => $category]]]);
            if ($exists) {
                continue;
            }
            $id = wp_insert_post(['post_type' => 'project', 'post_status' => 'publish', 'post_title' => $title, 'menu_order' => $i]);
            if ($id && !is_wp_error($id)) {
                carbon_set_post_meta($id, 'project_meta', $meta);
                carbon_set_post_meta($id, 'project_status', $status);
                wp_set_object_terms($id, $category, 'project_category');
            }
        }
    }

    if (!get_posts(['post_type' => 'auftritt', 'post_status' => 'any', 'numberposts' => 1])) {
        $soon = fn(int $days) => date('Y-m-d', strtotime(current_time('Y-m-d') . " +$days days"));
        theme_setup_post('auftritt', '[Titel des Auftritts]', ['auftritt_date' => $soon(30), 'auftritt_place' => '[Ort]', 'auftritt_kind' => 'Liveshow', 'auftritt_link' => '#', 'auftritt_link_text' => 'Tickets']);
        theme_setup_post('auftritt', '[Titel des Auftritts]', ['auftritt_date' => $soon(75), 'auftritt_place' => '[Ort]', 'auftritt_kind' => 'Gastauftritt', 'auftritt_link' => '#', 'auftritt_link_text' => 'Mehr Infos']);
        foreach (['2025-11-08', '2025-09-20', '2025-05-17', '2024-12-06'] as $date) {
            theme_setup_post('auftritt', '[Event]', ['auftritt_date' => $date, 'auftritt_place' => '[Ort]', 'auftritt_kind' => 'Liveshow']);
        }
    }

    if (!get_posts(['post_type' => 'merch', 'post_status' => 'any', 'numberposts' => 1])) {
        foreach (['Streifenshirt', 'Tennissocken', 'Tasse', 'Cap'] as $i => $title) {
            theme_setup_post('merch', $title, ['merch_price' => '[Preis]'], $i);
        }
    }

    if (!get_posts(['post_type' => 'nominierung', 'post_status' => 'any', 'numberposts' => 1])) {
        for ($i = 0; $i < 3; $i++) {
            theme_setup_post('nominierung', '[Preis]', ['nominierung_category' => '[Kategorie]', 'nominierung_year' => '[Jahr]', 'nominierung_status' => 'nominiert'], $i);
        }
    }

    if (!get_posts(['post_type' => 'medienbericht', 'post_status' => 'any', 'numberposts' => 1])) {
        foreach (['2025-10-01', '2025-06-01', '2025-02-01'] as $date) {
            theme_setup_post('medienbericht', '[Titel des Artikels]', ['medienbericht_medium' => '[Medium]', 'medienbericht_date' => $date, 'medienbericht_link' => '#', 'medienbericht_link_text' => 'Artikel lesen']);
        }
    }

    if (!get_posts(['post_type' => 'partner', 'post_status' => 'any', 'numberposts' => 1])) {
        foreach (['Podcastwerbung', 'Social Media', 'Podcastwerbung', 'Moderation', 'Shooting'] as $i => $service) {
            theme_setup_post('partner', '[Firma]', ['partner_service' => $service], $i);
        }
    }
}

// -> Navigation, Kacheln der Startseite und Buttons verlinken, wo noch # steht
function theme_setup_links(array $pages): void {
    $urls = array_map(fn($id) => $id ? get_permalink($id) : '', $pages);
    $by_title = [
        'Über mich' => $urls['ueber-mich'],
        'Podcast' => $urls['podcast'],
        'Projekte' => $urls['projekte'],
        'Zusammenarbeit' => $urls['zusammenarbeit'],
    ];

    $locations = get_nav_menu_locations();
    if (!empty($locations['main'])) {
        foreach (wp_get_nav_menu_items($locations['main']) ?: [] as $item) {
            if ($item->type === 'custom' && in_array($item->url, ['#', ''], true) && !empty($by_title[$item->title])) {
                update_post_meta($item->ID, '_menu_item_url', esc_url_raw($by_title[$item->title]));
            }
        }
    }

    $by_style = [
        'blumenkaro' => $urls['ueber-mich'],
        'wellen-pink' => $urls['podcast'],
        'picknick' => $urls['projekte'],
        'streifen' => $urls['zusammenarbeit'],
    ];
    $front_id = (int) get_option('page_on_front');
    if ($front_id) {
        $modules = carbon_get_post_meta($front_id, 'modules');
        $changed = false;
        foreach ($modules ?: [] as $m => $module) {
            if ($module['_type'] !== 'tiles') {
                continue;
            }
            foreach ($module['items'] ?? [] as $t => $tile) {
                if (in_array($tile['link'], ['#', ''], true) && !empty($by_style[$tile['style']])) {
                    $modules[$m]['items'][$t]['link'] = $by_style[$tile['style']];
                    $changed = true;
                }
            }
        }
        if ($changed) {
            carbon_set_post_meta($front_id, 'modules', $modules);
        }
    }

    $kontakt = $urls['zusammenarbeit'] ? $urls['zusammenarbeit'] . '#kontakt' : '';
    foreach (['header_button_link', 'footer_button_link'] as $option) {
        if ($kontakt && in_array((string) carbon_get_theme_option($option), ['#', ''], true)) {
            carbon_set_theme_option($option, $kontakt);
        }
    }
}
