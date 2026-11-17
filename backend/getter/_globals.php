<?php

// -> Logo aus den globalen Einstellungen
function get_globals_logo(): array|false {
   $logo_id = carbon_get_theme_option('logo');
   if (!$logo_id) {
      return false;
   }
   return get_media_by_id($logo_id);
}

// -> Social Media Links mit Icons
function get_global_socials(): array {
   $socials = carbon_get_theme_option('socials') ?: [];
   return array_map(function ($item) {
      $item['icon'] = $item['icon'] ? get_media_by_id($item['icon']) : false;
      return $item;
   }, $socials);
}

// -> Button oben rechts im Header
function get_globals_header_button(): array|false {
   $text = carbon_get_theme_option('header_button_text');
   $link = carbon_get_theme_option('header_button_link');
   if (!$text || !$link) {
      return false;
   }
   return ['text' => $text, 'link' => $link];
}

// -> Inhalte für den Footer
function get_globals_footer(): array {
   return [
      'title' => carbon_get_theme_option('footer_title') ?: 'Lass uns zusammenarbeiten',
      'button_text' => carbon_get_theme_option('footer_button_text'),
      'button_link' => carbon_get_theme_option('footer_button_link'),
      'name' => carbon_get_theme_option('footer_name') ?: get_bloginfo('name'),
      'tagline' => carbon_get_theme_option('footer_tagline'),
   ];
}
