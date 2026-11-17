<?php
/**
 * Alles, was mit Carbon Fields zu tun hat, ist in diesem Ordner verwaltet.
 *
 * Dokumentation: https://docs.carbonfields.net/
 */

add_action('carbon_fields_register_fields', function () {
    require_once __DIR__ . '/_globals.php';
    require_once __DIR__ . '/_content.php';
    require_once __DIR__ . '/_modules.php';
});
