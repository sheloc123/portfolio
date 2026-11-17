<?php

/**
 * Holt die neuste Folge eines Spotify Podcasts über die Spotify API.
 *
 * Das Resultat wird 6 Stunden zwischengespeichert (Transient), damit die
 * Webseite schnell bleibt und Spotify nicht bei jedem Seitenaufruf gefragt wird.
 * Gibt false zurück, wenn in "Globales > Spotify" nichts eingetragen ist
 * oder Spotify nicht erreichbar ist. Dann zeigt das Modul die Angaben von Hand.
 */
function get_spotify_latest_episode(): array|false {
   $show_id = trim((string) carbon_get_theme_option('spotify_show_id'));
   $client_id = trim((string) carbon_get_theme_option('spotify_client_id'));
   $client_secret = trim((string) carbon_get_theme_option('spotify_client_secret'));
   if (!$show_id || !$client_id || !$client_secret) {
      return false;
   }

   $cache_key = 'spotify_latest_' . md5($show_id);
   $cached = get_transient($cache_key);
   if ($cached !== false) {
      return $cached;
   }

   // -> 1. Zugangsschlüssel bei Spotify holen
   $token_response = wp_remote_post('https://accounts.spotify.com/api/token', [
      'headers' => [
         'Authorization' => 'Basic ' . base64_encode($client_id . ':' . $client_secret),
      ],
      'body' => ['grant_type' => 'client_credentials'],
      'timeout' => 8,
   ]);
   if (is_wp_error($token_response) || wp_remote_retrieve_response_code($token_response) !== 200) {
      return false;
   }
   $token = json_decode(wp_remote_retrieve_body($token_response), true)['access_token'] ?? '';
   if (!$token) {
      return false;
   }

   // -> 2. Den Podcast abfragen, er enthält die Folgen (die neuste zuerst).
   //       GET /shows/{id} ist auch nach den Spotify Änderungen 2026 erlaubt.
   $episodes_response = wp_remote_get(
      'https://api.spotify.com/v1/shows/' . rawurlencode($show_id) . '?market=CH',
      [
         'headers' => ['Authorization' => 'Bearer ' . $token],
         'timeout' => 8,
      ]
   );
   if (is_wp_error($episodes_response) || wp_remote_retrieve_response_code($episodes_response) !== 200) {
      return false;
   }
   $episode = json_decode(wp_remote_retrieve_body($episodes_response), true)['episodes']['items'][0] ?? null;
   if (!$episode || empty($episode['external_urls']['spotify'])) {
      return false;
   }

   $result = [
      'title' => $episode['name'],
      'link' => $episode['external_urls']['spotify'],
   ];
   set_transient($cache_key, $result, 6 * HOUR_IN_SECONDS);
   return $result;
}
