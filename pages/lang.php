<?php
require_once dirname(__DIR__) . '/includes/functions.php';

set_lang($_GET['lang'] ?? 'uz');

$next = $_GET['next'] ?? '';
if ($next === '' || !str_starts_with($next, '/') || str_starts_with($next, '//')) {
    $next = base_url('index.php');
}

redirect($next);
