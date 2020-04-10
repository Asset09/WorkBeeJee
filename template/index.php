<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$TEMPLATE_DIR = __DIR__ . '/template-parts/';

/* HEADER */
require_once $TEMPLATE_DIR . 'header/header.php';
/* NAVIGATION */
require_once $TEMPLATE_DIR . 'navigation/navigation.php';
/* CONTENT */
require_once $TEMPLATE_DIR . 'page/content-main.php';
/* FOOTER */
require_once $TEMPLATE_DIR . 'footer/footer.php';