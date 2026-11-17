<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field;

// -> Inhaltsfeld-Konfiguration der globalen Einstellungen des Themes
Container::make('theme_options', 'Globales')
    ->add_tab('Header', [
        Field::make('image', 'logo', 'Logo'),
        Field::make('text', 'header_button_text', 'Button Text')
            ->set_default_value('Anfragen'),
        Field::make('text', 'header_button_link', 'Button Link')
            ->set_help_text('Zum Beispiel /zusammenarbeit/#kontakt'),
    ])
    ->add_tab('Footer', [
        Field::make('text', 'footer_title', 'Grosser Titel')
            ->set_default_value('Lass uns zusammenarbeiten'),
        Field::make('text', 'footer_button_text', 'Button Text')
            ->set_default_value('Projekt anfragen'),
        Field::make('text', 'footer_button_link', 'Button Link'),
        Field::make('text', 'footer_name', 'Name')
            ->set_default_value('Sheryn Locher'),
        Field::make('text', 'footer_tagline', 'Kurzer Satz unter dem Namen')
            ->set_default_value('Podcasterin, Moderatorin und Creatorin. Vor und hinter der Kamera.'),
    ])
    ->add_tab('Socials', [
        Field::make('complex', 'socials', 'Socials')
            ->add_fields([
                Field::make('text', 'title', 'Titel'),
                Field::make('text', 'link', 'Link'),
                Field::make('image', 'icon', 'Icon')
            ])
            ->set_header_template('<% if (title) { %> <%- title %><% } %>')
            ->set_layout('tabbed-vertical'),
    ]);
