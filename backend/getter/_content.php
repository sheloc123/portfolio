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
            'image' => get_optional_image(carbon_get_post_meta($post->ID, 'project_image')),
         ], $posts),
      ];
   }
   return $groups;
}

// -> Link zum Media Kit: aus Globales > Zusammenarbeit, sonst der Link vom Management
const MEDIA_KIT_DEFAULT = 'https://app.yannik-tschan.workers.dev/media/sheryn-locher';
function get_media_kit_link(): string {
   return trim((string) carbon_get_theme_option('media_kit_link')) ?: MEDIA_KIT_DEFAULT;
}

// -> Button «Media Kit ansehen» ohne Link bekommt den Media Kit Link
function media_kit_fallback(string $text, string $link): string {
   if (!$link && stripos($text, 'media kit') !== false) {
      return get_media_kit_link();
   }
   return $link;
}
