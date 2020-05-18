<?php

ini_set('display_errors', 1);

define('DSN', 'mysql:host=mysql144.phy.lolipop.lan;dbname=LAA1104681-todocalendar;charset=utf8');

define('DB_USERNAME', 'LAA1104681');
define('DB_PASSWORD', 'Roim0624');

define('SITE_URL', 'https://' . $_SERVER['HTTP_HOST']);
define('HOME_DIR',  SITE_URL . '/works/todo_calendar/public_html');

require_once(__DIR__ . '/../lib/functions.php');
require_once(__DIR__ . '/autoload.php');

session_start();