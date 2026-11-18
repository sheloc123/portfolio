<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field;

// -> Felder der Inhaltstypen (Projekte, Auftritte, Merch, Nominierungen, Medienberichte, Partner)

Container::make('post_meta', 'Angaben zum Projekt')
    ->where('post_type', '=', 'project')
    ->add_fields([
        Field::make('image', 'project_image', 'Bild'),
        Field::make('text', 'project_meta', 'Kurze Beschreibung')
            ->set_help_text('Zum Beispiel: Dokumentarfilm, auf YouTube'),
        Field::make('text', 'project_status', 'Status (optional)')
            ->set_help_text('Kleines pinkes Schild, zum Beispiel: bald online, in Produktion'),
        Field::make('text', 'project_link', 'Link (optional)')
            ->set_help_text('Zum Beispiel der Link zum Film auf YouTube, Vimeo oder Spotify. Ohne eigenes Bild wird bei YouTube und Spotify automatisch das Vorschaubild verwendet.')
            ->set_width(50),
        Field::make('text', 'project_link_text', 'Text zum Link')
            ->set_default_value('Ansehen')
            ->set_help_text('Wird nur gezeigt, wenn es auch einen zweiten Link gibt')
            ->set_width(50),
        Field::make('text', 'project_link_2', 'Zweiter Link (optional)')
            ->set_help_text('Zum Beispiel eine Webseite')
            ->set_width(50),
        Field::make('text', 'project_link_2_text', 'Text zum zweiten Link')
            ->set_default_value('Webseite')
            ->set_width(50),
    ]);

Container::make('term_meta', 'Angaben zur Kategorie')
    ->where('term_taxonomy', '=', 'project_category')
    ->add_fields([
        Field::make('text', 'category_order', 'Reihenfolge')
            ->set_attribute('type', 'number')
            ->set_default_value('1')
            ->set_help_text('Kleinere Zahl steht weiter oben auf der Seite'),
    ]);

Container::make('post_meta', 'Angaben zum Auftritt')
    ->where('post_type', '=', 'auftritt')
    ->add_fields([
        Field::make('date', 'auftritt_date', 'Datum')
            ->set_required()
            ->set_storage_format('Y-m-d')
            ->set_input_format('d.m.Y', 'd.m.Y')
            ->set_help_text('Nach diesem Datum rutscht der Auftritt automatisch in den Rückblick'),
        Field::make('text', 'auftritt_place', 'Ort'),
        Field::make('text', 'auftritt_kind', 'Art')
            ->set_help_text('Zum Beispiel: Liveshow, Gastauftritt, Moderation'),
        Field::make('text', 'auftritt_link', 'Link (optional)'),
        Field::make('text', 'auftritt_link_text', 'Text auf dem Button')
            ->set_default_value('Mehr Infos'),
        Field::make('image', 'auftritt_image', 'Foto für den Rückblick'),
    ]);

Container::make('post_meta', 'Angaben zum Produkt')
    ->where('post_type', '=', 'merch')
    ->add_fields([
        Field::make('image', 'merch_image', 'Foto'),
        Field::make('text', 'merch_price', 'Preis')
            ->set_help_text('Zum Beispiel: CHF 35'),
        Field::make('text', 'merch_link', 'Link zum Produkt im Shop (optional)'),
    ]);

Container::make('post_meta', 'Angaben zur Nominierung')
    ->where('post_type', '=', 'nominierung')
    ->add_fields([
        Field::make('html', 'nominierung_info', 'Info')
            ->set_html('<p>Der Titel oben ist der Name des Preises.</p>'),
        Field::make('text', 'nominierung_category', 'Kategorie'),
        Field::make('text', 'nominierung_year', 'Jahr'),
        Field::make('select', 'nominierung_status', 'Status')
            ->set_options([
                'nominiert' => 'nominiert',
                'gewonnen' => 'gewonnen',
            ]),
        Field::make('text', 'nominierung_link', 'Link (optional)'),
    ]);

Container::make('post_meta', 'Angaben zum Medienbericht')
    ->where('post_type', '=', 'medienbericht')
    ->add_fields([
        Field::make('html', 'medienbericht_info', 'Info')
            ->set_html('<p>Der Titel oben ist der Titel des Artikels oder Beitrags.</p>'),
        Field::make('text', 'medienbericht_medium', 'Medium')
            ->set_help_text('Zum Beispiel: SRF, 20 Minuten, Südostschweiz'),
        Field::make('date', 'medienbericht_date', 'Datum')
            ->set_required()
            ->set_storage_format('Y-m-d')
            ->set_input_format('d.m.Y', 'd.m.Y'),
        Field::make('image', 'medienbericht_logo', 'Logo des Mediums (optional)'),
        Field::make('text', 'medienbericht_link', 'Link zum Artikel'),
        Field::make('text', 'medienbericht_link_text', 'Text auf dem Link')
            ->set_default_value('Artikel lesen'),
    ]);

Container::make('post_meta', 'Angaben zum Partner')
    ->where('post_type', '=', 'partner')
    ->add_fields([
        Field::make('image', 'partner_logo', 'Logo'),
        Field::make('text', 'partner_service', 'Was wir zusammen gemacht haben')
            ->set_help_text('Zum Beispiel: Podcastwerbung, Social Media, Moderation'),
        Field::make('text', 'partner_link', 'Link zur Webseite (optional)'),
    ]);
