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
    ->add_tab('Podcast', [
        Field::make('text', 'podcast_rss_feed', 'RSS Feed des Podcasts')
            ->set_default_value('https://anchor.fm/s/c809068c/podcast/rss')
            ->set_help_text('Daraus kommt automatisch die neuste Folge. Findest du in Spotify for Creators unter Einstellungen > Verfügbarkeit.'),
        Field::make('html', 'spotify_info', 'Info')
            ->set_html('<p><strong>Optional, braucht Spotify Premium:</strong> Wenn die drei Felder unten ausgefüllt sind, kommt die neuste Folge direkt von Spotify und der Link führt zur Folge in Spotify. Sonst wird der RSS Feed oben verwendet.</p>'),
        Field::make('text', 'spotify_show_id', 'Show ID')
            ->set_default_value('0ci3VzojV1j9065WeBU0vW')
            ->set_help_text('Der Teil nach /show/ im Spotify Link deines Podcasts'),
        Field::make('text', 'spotify_client_id', 'Client ID'),
        Field::make('text', 'spotify_client_secret', 'Client Secret')
            ->set_attribute('type', 'password'),
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
