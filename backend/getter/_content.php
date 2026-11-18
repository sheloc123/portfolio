<?php
/**
 * Getter für die Inhaltstypen: Projekte, Auftritte, Merch, Nominierungen,
 * Medienberichte und Partner. Sortiert wird nach "Reihenfolge" (Attribute)
 * und danach nach Datum, Auftritte und Medienberichte nach ihrem Datum.
 */

// -> Bild laden, oder false wenn keins da ist
function get_optional_image(mixed $id, string $size = 's'): array|false {
   return $id ? get_media_by_id($id, $size) : false;
}

// -> Beiträge eines Inhaltstyps holen
function get_content_posts(string $post_type, array $args = []): array {
   return get_posts(array_merge([
      'post_type' => $post_type,
      'post_status' => 'publish',
      'numberposts' => -1,
      'orderby' => ['menu_order' => 'ASC', 'date' => 'DESC'],
   ], $args));
}

// -> Datum wie 2026-03-14 als 14.03.2026 anzeigen
function format_date_ch(string $date, string $format = 'd.m.Y'): string {
   $time = $date ? strtotime($date) : false;
   return $time ? date_i18n($format, $time) : '';
}

function map_auftritt(WP_Post $post): array {
   $date = (string) carbon_get_post_meta($post->ID, 'auftritt_date');
   return [
      'title' => get_the_title($post),
      'date' => $date,
      'day' => format_date_ch($date, 'j. M'),
      'year' => format_date_ch($date, 'Y'),
      'place' => carbon_get_post_meta($post->ID, 'auftritt_place'),
      'kind' => carbon_get_post_meta($post->ID, 'auftritt_kind'),
      'link' => carbon_get_post_meta($post->ID, 'auftritt_link'),
      'link_text' => carbon_get_post_meta($post->ID, 'auftritt_link_text') ?: 'Mehr Infos',
      'image' => get_optional_image(carbon_get_post_meta($post->ID, 'auftritt_image')),
   ];
}

// -> kommende Auftritte, der nächste zuerst
function get_upcoming_auftritte(): array {
   $posts = get_content_posts('auftritt', [
      'meta_key' => '_auftritt_date',
      'orderby' => 'meta_value',
      'order' => 'ASC',
      'meta_query' => [[
         'key' => '_auftritt_date',
         'value' => current_time('Y-m-d'),
         'compare' => '>=',
         'type' => 'DATE',
      ]],
   ]);
   return array_map('map_auftritt', $posts);
}

// -> vergangene Auftritte, der letzte zuerst
function get_past_auftritte(int $count = 4): array {
   $posts = get_content_posts('auftritt', [
      'numberposts' => $count,
      'meta_key' => '_auftritt_date',
      'orderby' => 'meta_value',
      'order' => 'DESC',
      'meta_query' => [[
         'key' => '_auftritt_date',
         'value' => current_time('Y-m-d'),
         'compare' => '<',
         'type' => 'DATE',
      ]],
   ]);
   return array_map('map_auftritt', $posts);
}

function get_merch_items(): array {
   return array_map(fn($post) => [
      'title' => get_the_title($post),
      'price' => carbon_get_post_meta($post->ID, 'merch_price'),
      'link' => carbon_get_post_meta($post->ID, 'merch_link'),
      'image' => get_optional_image(carbon_get_post_meta($post->ID, 'merch_image')),
   ], get_content_posts('merch'));
}

function get_nominierungen(): array {
   return array_map(fn($post) => [
      'title' => get_the_title($post),
      'category' => carbon_get_post_meta($post->ID, 'nominierung_category'),
      'year' => carbon_get_post_meta($post->ID, 'nominierung_year'),
      'status' => carbon_get_post_meta($post->ID, 'nominierung_status') ?: 'nominiert',
      'link' => carbon_get_post_meta($post->ID, 'nominierung_link'),
   ], get_content_posts('nominierung'));
}

function get_medienberichte(): array {
   $posts = get_content_posts('medienbericht', [
      'meta_key' => '_medienbericht_date',
      'orderby' => ['meta_value' => 'DESC', 'menu_order' => 'ASC'],
   ]);
   return array_map(fn($post) => [
      'title' => get_the_title($post),
      'medium' => carbon_get_post_meta($post->ID, 'medienbericht_medium'),
      'date' => format_date_ch((string) carbon_get_post_meta($post->ID, 'medienbericht_date')),
      'link' => carbon_get_post_meta($post->ID, 'medienbericht_link'),
      'link_text' => carbon_get_post_meta($post->ID, 'medienbericht_link_text') ?: 'Artikel lesen',
      'logo' => get_optional_image(carbon_get_post_meta($post->ID, 'medienbericht_logo'), 'xs'),
   ], $posts);
}

function get_partners(): array {
   return array_map(fn($post) => [
      'title' => get_the_title($post),
      'service' => carbon_get_post_meta($post->ID, 'partner_service'),
      'link' => carbon_get_post_meta($post->ID, 'partner_link'),
      'logo' => get_optional_image(carbon_get_post_meta($post->ID, 'partner_logo'), 'xs'),
   ], get_content_posts('partner'));
}

// -> Projekte nach Kategorie, Kategorien nach ihrer Reihenfolge
function get_projects_by_category(): array {
   $terms = get_terms(['taxonomy' => 'project_category', 'hide_empty' => true]);
   if (is_wp_error($terms)) {
      return [];
   }
   usort($terms, fn($a, $b) =>
      (int) carbon_get_term_meta($a->term_id, 'category_order') <=> (int) carbon_get_term_meta($b->term_id, 'category_order')
   );
   $groups = [];
   foreach ($terms as $term) {
      $posts = get_content_posts('project', [
         'tax_query' => [[
            'taxonomy' => 'project_category',
            'field' => 'term_id',
            'terms' => $term->term_id,
         ]],
      ]);
      $groups[] = [
         'id' => $term->slug,
         'title' => $term->name,
         'sub' => $term->description,
         'items' => array_map(fn($post) => [
            'title' => get_the_title($post),
            'meta' => carbon_get_post_meta($post->ID, 'project_meta'),
            'status' => carbon_get_post_meta($post->ID, 'project_status'),
            'link' => carbon_get_post_meta($post->ID, 'project_link'),
            'link_text' => carbon_get_post_meta($post->ID, 'project_link_text') ?: 'Ansehen',
            'link_2' => carbon_get_post_meta($post->ID, 'project_link_2'),
            'link_2_text' => carbon_get_post_meta($post->ID, 'project_link_2_text') ?: 'Webseite',
            'image' => get_optional_image(carbon_get_post_meta($post->ID, 'project_image'))
               ?: get_link_thumbnail((string) carbon_get_post_meta($post->ID, 'project_link'), get_the_title($post)),
         ], $posts),
      ];
   }
   return $groups;
}

// -> Button «Media Kit ansehen» ohne Link bekommt den Media Kit Link aus Globales
function media_kit_fallback(string $text, string $link): string {
   if (!$link && stripos($text, 'media kit') !== false) {
      return (string) carbon_get_theme_option('media_kit_link');
   }
   return $link;
}

/**
 * Vorschaubild für einen YouTube oder Spotify Link, wenn kein eigenes Bild da ist.
 * YouTube: direkt über die Video ID. Spotify: über den öffentlichen oEmbed Dienst,
 * eine Woche zwischengespeichert.
 */
function get_link_thumbnail(string $link, string $alt = ''): array|false {
   if (!$link) {
      return false;
   }
   if (preg_match('#(?:youtu\.be/|youtube\.com/(?:watch\?v=|embed/|shorts/))([A-Za-z0-9_-]{11})#', $link, $match)) {
      return ['src' => 'https://i.ytimg.com/vi/' . $match[1] . '/hqdefault.jpg', 'alt' => $alt, 'type' => 'image'];
   }
   if (str_contains($link, 'open.spotify.com/')) {
      $cache_key = 'link_thumb_' . md5($link);
      $src = get_transient($cache_key);
      if ($src === false) {
         $response = wp_remote_get('https://open.spotify.com/oembed?url=' . rawurlencode($link), ['timeout' => 6]);
         $src = '';
         if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
            $src = json_decode(wp_remote_retrieve_body($response), true)['thumbnail_url'] ?? '';
         }
         set_transient($cache_key, $src, $src ? WEEK_IN_SECONDS : HOUR_IN_SECONDS);
      }
      return $src ? ['src' => $src, 'alt' => $alt, 'type' => 'image'] : false;
   }
   return false;
}
