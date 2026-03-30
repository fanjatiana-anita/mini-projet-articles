<?php
// Définir la racine du projet
define('ROOT', __DIR__);

// Point d'entrée public minimal : inclure la vue d'accueil du front
// (évite d'inclure des fichiers qui n'existent pas dans un parent)
require_once ROOT . '/app/Views/front/index.php';
exit;