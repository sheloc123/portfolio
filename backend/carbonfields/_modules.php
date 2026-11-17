<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field;

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
                Field::make('image', 'image_right', 'Polaroid rechts'),
            ])
            // -> latest_episode: Balken mit der neusten Podcastfolge
            ->add_fields('latest_episode', 'Neuste Folge', [
                Field::make('text', 'label', 'Kleiner Titel')->set_default_value('Neuste Folge'),
                Field::make('text', 'title', 'Folge')->set_required(),
                Field::make('text', 'link', 'Link zur Folge'),
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
            case 'intro_text':
                $module['image_left'] = $module['image_left'] ? get_media_by_id($module['image_left'], 's') : false;
                $module['image_right'] = $module['image_right'] ? get_media_by_id($module['image_right'], 's') : false;
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
