<?php
/**
* Taxonomien werden in diesem Ordner verwaltet. Für jede Taxonomie gibt es eine
* eigene Datei, die hier geladen wird. Der Dateiname entspricht dem Slug.
*
* Dokumentation: https://developer.wordpress.org/reference/functions/register_taxonomy/
*/

add_action('init', function () {
    require_once __DIR__ . '/_project_category.php';
}, 0);
