<?php
require_once dirname(__DIR__) . '/includes/functions.php';
$_SESSION = [];
session_destroy();
session_start();
flash('info', 'Tizimdan chiqdingiz.');
redirect(base_url('index.php'));
