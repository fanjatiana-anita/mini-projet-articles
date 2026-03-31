<?php
session_destroy();
header('Location: ' . (defined('BASE_URL_ADMIN') ? BASE_URL_ADMIN : '/') . '/');
exit;