<?php
/**
 * Point d'entrée public — délègue au routeur
 */
// ROOT sera défini par router.php — ne pas le redéfinir ici
$_GET['_route'] = $_GET['_route'] ?? '/';
require_once __DIR__ . '/router.php';
