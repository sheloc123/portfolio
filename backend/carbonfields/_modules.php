<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field;

// -> Hintergründe, die bei fast allen Modulen gewählt werden können
function module_backgrounds(): array {
    return [
        'notizpapier' => 'Notizpapier mit Blumen (Über mich)',
        'hellgruen' => 'Hellgrün',
        'wellen-pink' => 'Pinke Wellen (Podcast)',
        'pink' => 'Pink',
        'rosa' => 'Hellpink',
        'oliv' => 'Olivgrün',
        'streifen' => 'Grüne Streifen (Zusammenarbeit)',
        'picknick' => 'Picknickdecke (Projekte)',
        'beige' => 'Beige',
        'schwarz' => 'Schwarz',
    ];
}

// -> Felder, die jedes Seitenmodul hat: Hintergrund und Sprungmarke
function module_common_fields(string $background = 'beige'): array {
    return [
        Field::make('select', 'background', 'Hintergrund')
            ->set_options(module_backgrounds())
            ->set_default_value($background)
            ->set_width(50),
        Field::make('text', 'anchor', 'Sprungmarke (optional)')
            ->set_help_text('Zum Beispiel kontakt, dann führt der Link /zusammenarbeit/#kontakt direkt hierhin')
            ->set_width(50),
    ];
}

// -> Überschrift eines Abschnitts: kleiner Titel, grosser Titel, Text
function module_head_fields(string $title = ''): array {
    return [
        Field::make('text', 'label', 'Kleiner Titel (optional)')->set_width(50),
        Field::make('text', 'title', 'Titel')->set_default_value($title)->set_width(50),
        Field::make('textarea', 'text', 'Text (optional)')->set_rows(3),
    ];
}

// -> Button mit Text und Link
function module_button_fields(string $name, string $label, string $text = ''): array {
    return [
        Field::make('text', $name . '_text', $label . ' Text')->set_default_value($text)->set_width(50),
        Field::make('text', $name . '_link', $label . ' Link')->set_width(50),
    ];
}

// -> Runde Social Media Buttons mit Icon
function module_link_pills_field(): Field\Field {
    return Field::make('complex', 'links', 'Social Media Buttons')
        ->add_fields([
            Field::make('select', 'icon', 'Icon')
                ->set_options([
                    'instagram' => 'Instagram',
                    'tiktok' => 'TikTok',
                    'youtube' => 'YouTube',
                    'spotify' => 'Spotify',
                    'linkedin' => 'LinkedIn',
                    'link' => 'Link',
                ])
                ->set_width(33),
            Field::make('text', 'text', 'Text')->set_width(33),
            Field::make('text', 'link', 'Link')->set_width(33),
        ])
        ->set_header_template('<% if (text) { %> <%- text %><% } %>')
        ->set_layout('tabbed-horizontal');
}

// -> Konfiguration der verschiedenen Module
Container::make('post_meta', 'Module')
    ->where('post_type', '=', 'page')
    ->add_fields([
        Field::make('complex', 'modules', 'Module')
            // -> example
            ->add_fields('example', 'Beispiel', [
                Field::make('text', 'title', 'Titel')->set_required(),
                Field::make('image', 'image', 'Bild')->set_required(),
            ])
            // -> hero_front: Name gross, freigestelltes Foto davor, Blümchen mit Link
            ->add_fields('hero_front', 'Hero Startseite', [
                Field::make('text', 'line_1', 'Name Zeile 1')->set_default_value('SHERYN'),
                Field::make('text', 'line_2', 'Name Zeile 2')->set_default_value('LOCHER'),
                Field::make('image', 'image', 'Freigestelltes Foto (PNG ohne Hintergrund)'),
                Field::make('text', 'subtitle', 'Untertitel')
                    ->set_default_value('Podcasterin, Moderatorin und Creatorin'),
                Field::make('text', 'flower_title', 'Blümchen Titel')->set_default_value('Lebenslänglich?'),
                Field::make('text', 'flower_text', 'Blümchen Text')->set_default_value('Jetzt reinhören'),
                Field::make('text', 'flower_link', 'Blümchen Link'),
            ])
            // -> intro_text: Textkasten auf schwarzweissen Wellen, zwei Polaroids unten
            ->add_fields('intro_text', 'Intro Text mit Polaroids', [
                Field::make('textarea', 'text', 'Text')->set_required(),
                Field::make('image', 'image_left', 'Polaroid links'),
                Field::make('text', 'caption_left', 'Notiz unter Polaroid links')
                    ->set_help_text('Ganz kurz, zum Beispiel ein Stichwort'),
                Field::make('image', 'image_right', 'Polaroid rechts'),
                Field::make('text', 'caption_right', 'Notiz unter Polaroid rechts'),
            ])
            // -> latest_episode: Balken mit der neusten Podcastfolge
            ->add_fields('latest_episode', 'Neuste Folge', [
                Field::make('html', 'latest_episode_info', 'Info')
                    ->set_html('<p>Die neuste Folge kommt automatisch von Spotify (Einstellungen unter Globales &gt; Podcast). Die Felder Folge und Link werden nur gebraucht, falls Spotify einmal nicht erreichbar ist.</p>'),
                Field::make('text', 'label', 'Kleiner Titel')->set_default_value('Neuste Folge'),
                Field::make('text', 'title', 'Folge (Ersatz)'),
                Field::make('text', 'link', 'Link zur Folge (Ersatz)'),
                Field::make('text', 'link_text', 'Link Text')->set_default_value('Jetzt hören'),
            ])
            // -> tiles: grosse Kacheln, jede im Muster ihrer Seite
            ->add_fields('tiles', 'Kacheln', [
                Field::make('complex', 'items', 'Kacheln')
                    ->add_fields([
                        Field::make('select', 'style', 'Muster')
                            ->set_options([
                                'blumenkaro' => 'Blumenkaro (Über mich)',
                                'wellen-pink' => 'Pinke Wellen (Podcast)',
                                'picknick' => 'Picknickdecke (Projekte)',
                                'streifen' => 'Grüne Streifen (Zusammenarbeit)',
                            ]),
                        Field::make('text', 'label', 'Kleiner Titel'),
                        Field::make('text', 'title', 'Titel')->set_required(),
                        Field::make('textarea', 'text', 'Text')->set_rows(2),
                        Field::make('text', 'link', 'Link')->set_required(),
                    ])
                    ->set_header_template('<% if (title) { %> <%- title %><% } %>')
                    ->set_layout('tabbed-horizontal'),
            ])
            // -> page_intro: Kopf einer Seite mit Text, Schildern, Buttons und Polaroid
            ->add_fields('page_intro', 'Seitenkopf mit Polaroid', array_merge(
                module_common_fields('notizpapier'),
                [
                    Field::make('text', 'label', 'Kleiner Titel')->set_width(33),
                    Field::make('text', 'title', 'Titel')->set_required()->set_width(33),
                    Field::make('text', 'title_accent', 'Titel Zusatz in Schreibschrift (optional)')
                        ->set_help_text('Zum Beispiel Sheryn, steht direkt nach dem Titel')
                        ->set_width(33),
                    Field::make('textarea', 'text', 'Text')
                        ->set_help_text('Eine leere Zeile macht einen neuen Abschnitt'),
                    Field::make('textarea', 'chips', 'Schilder (optional)')
                        ->set_rows(3)
                        ->set_help_text('Ein Schild pro Zeile, zum Beispiel Chur'),
                ],
                module_button_fields('button_1', 'Button 1'),
                module_button_fields('button_2', 'Button 2'),
                [
                    Field::make('image', 'image', 'Foto im Polaroid')->set_width(33),
                    Field::make('text', 'image_caption', 'Notiz unter dem Foto')->set_width(33),
                    Field::make('select', 'image_side', 'Polaroid steht')
                        ->set_options(['links' => 'links', 'rechts' => 'rechts'])
                        ->set_width(33),
                    Field::make('select', 'image_shape', 'Form des Fotos')
                        ->set_options(['hoch' => 'Hochformat', 'quadrat' => 'Quadrat']),
                ]
            ))
            // -> social_phones: Social Media Abschnitt mit Handys, Buttons und Zahlen
            ->add_fields('social_phones', 'Social Media mit Handys', array_merge(
                module_common_fields('hellgruen'),
                module_head_fields('Komm mit'),
                [
                    module_link_pills_field(),
                    Field::make('complex', 'items', 'Handys')
                        ->add_fields([
                            Field::make('file', 'media', 'Video oder Bild (Hochformat)')
                                ->set_type(['video', 'image']),
                            Field::make('text', 'title', 'Titel'),
                            Field::make('text', 'text', 'Text'),
                        ])
                        ->set_header_template('<% if (title) { %> <%- title %><% } %>')
                        ->set_layout('tabbed-horizontal'),
                    Field::make('complex', 'stats', 'Zahlen (optional)')
                        ->add_fields([
                            Field::make('text', 'number', 'Zahl')->set_width(50),
                            Field::make('text', 'text', 'Text')->set_width(50),
                        ])
                        ->set_header_template('<% if (text) { %> <%- text %><% } %>')
                        ->set_layout('tabbed-horizontal'),
                ]
            ))
            // -> pinboard: runde Punkte mit kurzen Sätzen und Polaroids mit Klebeband
            ->add_fields('pinboard', 'Pinnwand', array_merge(
                module_common_fields('notizpapier'),
                [
                    Field::make('text', 'title', 'Titel')->set_default_value('Ein paar Dinge über mich')->set_width(50),
                    Field::make('text', 'subtitle', 'Untertitel in Schreibschrift')->set_width(50),
                    Field::make('complex', 'items', 'Pinnwand')
                        ->add_fields([
                            Field::make('select', 'kind', 'Art')
                                ->set_options(['punkt' => 'Runder Punkt mit Text', 'foto' => 'Polaroid mit Klebeband'])
                                ->set_width(33),
                            Field::make('select', 'color', 'Farbe')
                                ->set_options(['pink' => 'Pink', 'gruen' => 'Dunkelgrün', 'beige' => 'Beige', 'rosa' => 'Hellpink'])
                                ->set_width(33),
                            Field::make('textarea', 'text', 'Text oder Notiz')->set_rows(2)->set_width(33),
                            Field::make('image', 'image', 'Foto (nur bei Polaroid)'),
                        ])
                        ->set_header_template('<% if (text) { %> <%- text %><% } %>')
                        ->set_layout('tabbed-horizontal'),
                ]
            ))
            // -> polaroids: Fotos als Polaroids mit kurzer Notiz
            ->add_fields('polaroids', 'Polaroids', array_merge(
                module_common_fields('hellgruen'),
                module_head_fields('Abseits vom Mikrofon'),
                [
                    Field::make('complex', 'items', 'Polaroids')
                        ->add_fields([
                            Field::make('image', 'image', 'Foto')->set_width(50),
                            Field::make('text', 'caption', 'Notiz')->set_width(50),
                        ])
                        ->set_header_template('<% if (caption) { %> <%- caption %><% } %>')
                        ->set_layout('tabbed-horizontal'),
                ]
            ))
            // -> spotify_episodes: alle Folgen als Spotify Player
            ->add_fields('spotify_episodes', 'Podcast Folgen (Spotify)', array_merge(
                module_common_fields('rosa'),
                module_head_fields('Folgen'),
                [
                    Field::make('html', 'spotify_info', 'Info')
                        ->set_html('<p>Der Spotify Player zeigt automatisch alle Folgen, die neuste zuoberst. Der Podcast wird unter Globales &gt; Podcast &gt; Show ID eingestellt.</p>'),
                ],
                module_button_fields('more', 'Link unter dem Player')
            ))
            // -> events: Auftritte, kommende und vergangene automatisch getrennt
            ->add_fields('events', 'Auftritte', array_merge(
                module_common_fields('pink'),
                module_head_fields('Auftritte'),
                [
                    Field::make('html', 'events_info', 'Info')
                        ->set_html('<p>Die Auftritte kommen aus dem Menü links «Auftritte». Kommende stehen unter Demnächst, vergangene mit Foto im Rückblick.</p>'),
                    Field::make('text', 'upcoming_label', 'Titel Demnächst')->set_default_value('Demnächst')->set_width(33),
                    Field::make('text', 'past_label', 'Titel Rückblick')->set_default_value('Rückblick')->set_width(33),
                    Field::make('text', 'past_count', 'Wie viele im Rückblick')
                        ->set_attribute('type', 'number')
                        ->set_default_value('4')
                        ->set_width(33),
                ]
            ))
            // -> merch: Produkte aus dem Menü Merch
            ->add_fields('merch', 'Merch', array_merge(
                module_common_fields('rosa'),
                module_head_fields('Merch'),
                module_button_fields('button', 'Button', 'Zum Shop')
            ))
            // -> nominations: Nominierungen aus dem Menü Nominierungen
            ->add_fields('nominations', 'Nominierungen', array_merge(
                module_common_fields('oliv'),
                module_head_fields('Nominierungen')
            ))
            // -> press: Medienberichte aus dem Menü Medienberichte
            ->add_fields('press', 'Medienberichte', array_merge(
                module_common_fields('pink'),
                module_head_fields('In den Medien')
            ))
            // -> projects: alle Projekte, nach Kategorie gruppiert
            ->add_fields('projects', 'Projekte nach Kategorie', array_merge(
                module_common_fields('picknick'),
                [
                    Field::make('text', 'title', 'Titel')->set_default_value('Projekte'),
                    Field::make('textarea', 'text', 'Text')->set_rows(2),
                    Field::make('html', 'projects_info', 'Info')
                        ->set_html('<p>Die Projekte kommen aus dem Menü links «Projekte». Die Kategorien (Film, Fotografie, ...) werden dort unter «Kategorien» verwaltet.</p>'),
                ]
            ))
            // -> hero_center: grosser Titel in der Mitte mit Video
            ->add_fields('hero_center', 'Seitenkopf mit Video', array_merge(
                module_common_fields('streifen'),
                module_head_fields('Lass uns zusammenarbeiten'),
                [
                    Field::make('text', 'video_url', 'Video Link (YouTube oder Vimeo)')->set_width(50),
                    Field::make('file', 'video_file', 'Oder Video hochladen')->set_type(['video'])->set_width(50),
                ],
                module_button_fields('button_1', 'Button 1', 'Media Kit ansehen'),
                module_button_fields('button_2', 'Button 2', 'Anfrage senden'),
                [
                    Field::make('html', 'hero_center_info', 'Info')
                        ->set_html('<p>Bleibt ein Button Link leer und heisst der Button «Media Kit ansehen», wird automatisch der Media Kit Link aus Globales &gt; Zusammenarbeit verwendet.</p>'),
                ]
            ))
            // -> partners: Partner aus dem Menü Partner
            ->add_fields('partners', 'Partner', array_merge(
                module_common_fields('pink'),
                module_head_fields('Bisherige Partner')
            ))
            // -> offers: Kärtchen mit Angeboten
            ->add_fields('offers', 'Angebote', array_merge(
                module_common_fields('streifen'),
                module_head_fields('Was ich anbiete'),
                [
                    Field::make('complex', 'items', 'Angebote')
                        ->add_fields([
                            Field::make('text', 'title', 'Titel')->set_width(50),
                            Field::make('textarea', 'text', 'Text')->set_rows(2)->set_width(50),
                        ])
                        ->set_header_template('<% if (title) { %> <%- title %><% } %>')
                        ->set_layout('tabbed-horizontal'),
                ]
            ))
            // -> contact: Kontaktformular, schickt eine Mail
            ->add_fields('contact', 'Kontaktformular', array_merge(
                module_common_fields('pink'),
                module_head_fields('Anfrage senden'),
                [
                    Field::make('checkbox', 'show_media_kit', 'Button «Media Kit ansehen» zeigen')
                        ->set_option_value('yes')
                        ->set_default_value(true),
                    Field::make('textarea', 'options', 'Auswahl «Worum geht es?»')
                        ->set_rows(5)
                        ->set_help_text('Eine Auswahl pro Zeile'),
                    Field::make('text', 'success_text', 'Text nach dem Senden')
                        ->set_default_value('Danke für deine Anfrage! Ich melde mich so schnell wie möglich.'),
                    Field::make('html', 'contact_info', 'Info')
                        ->set_html('<p>Die Anfragen werden an die Mailadresse unter Globales &gt; Zusammenarbeit geschickt.</p>'),
                ]
            ))
            // todo: add new modules here
            // -> SETTINGS
            ->set_layout('tabbed-vertical')
    ]);

// -> Funktion, um die Module und die entsprechenden Templates auf der Seite zu laden
function render_modules (): void {
    $modules = carbon_get_the_post_meta('modules');
    if (empty($modules)) {
        return;
    }
    foreach ($modules as $module) {
        // -> Modifiziere die Daten des Moduls, falls nötig, basierend auf dem Modultyp
        switch ($module['_type']) {
            case 'example':
                $module['image'] = get_media_by_id($module['image'], 'full');
                break;
            case 'hero_front':
                $module['image'] = $module['image'] ? get_media_by_id($module['image'], 'l') : false;
                break;
            case 'latest_episode':
                // -> neuste Folge automatisch von Spotify, sonst die Angaben von Hand
                $latest = get_latest_episode();
                if ($latest) {
                    $module['title'] = $latest['title'];
                    $module['link'] = $latest['link'];
                }
                if (empty($module['title'])) {
                    continue 2; // nichts anzeigen, wenn es keine Folge gibt
                }
                break;
            case 'intro_text':
                $module['image_left'] = $module['image_left'] ? get_media_by_id($module['image_left'], 's') : false;
                $module['image_right'] = $module['image_right'] ? get_media_by_id($module['image_right'], 's') : false;
                break;
            case 'page_intro':
            case 'pinboard':
                if (isset($module['image'])) {
                    $module['image'] = $module['image'] ? get_media_by_id($module['image'], 'm') : false;
                }
                foreach ($module['items'] ?? [] as $i => $item) {
                    $module['items'][$i]['image'] = $item['image'] ? get_media_by_id($item['image'], 's') : false;
                }
                break;
            case 'social_phones':
                foreach ($module['items'] ?? [] as $i => $item) {
                    $module['items'][$i]['media'] = $item['media'] ? get_media_by_id($item['media'], 's') : false;
                }
                break;
            case 'polaroids':
                foreach ($module['items'] ?? [] as $i => $item) {
                    $module['items'][$i]['image'] = $item['image'] ? get_media_by_id($item['image'], 's') : false;
                }
                break;
            case 'spotify_episodes':
                $module['show_id'] = trim((string) carbon_get_theme_option('spotify_show_id')) ?: '0ci3VzojV1j9065WeBU0vW';
                break;
            case 'events':
                $module['upcoming'] = get_upcoming_auftritte();
                $module['past'] = get_past_auftritte((int) ($module['past_count'] ?: 4));
                break;
            case 'merch':
                $module['items'] = get_merch_items();
                break;
            case 'nominations':
                $module['items'] = get_nominierungen();
                break;
            case 'press':
                $module['items'] = get_medienberichte();
                break;
            case 'projects':
                $module['groups'] = get_projects_by_category();
                break;
            case 'hero_center':
                $module['video_embed'] = $module['video_url'] ? wp_oembed_get($module['video_url']) : false;
                $module['video_file'] = $module['video_file'] ? get_media_by_id($module['video_file']) : false;
                foreach (['button_1', 'button_2'] as $b) {
                    $module[$b . '_link'] = media_kit_fallback($module[$b . '_text'], $module[$b . '_link']);
                }
                break;
            case 'partners':
                $module['items'] = get_partners();
                break;
            case 'contact':
                $module['media_kit'] = get_media_kit_link();
                $module['email'] = carbon_get_theme_option('contact_email_show') ? carbon_get_theme_option('contact_email') : '';
                break;
                // todo: add data modifications for modules here as a new case
        }
        // -> Laden des Templates basierend auf dem Modultyp und weitergeben der Daten
        get_template_part(
            'frontend/views/modules/' . $module['_type'],
            null,
            [
                'data' => $module
            ]
        );
    }
}
