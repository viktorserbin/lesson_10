<?php

/**
 * Created by PhpStorm.
 * User: Тарас
 * Date: 23.02.2018
 * Time: 20:15
 */

require_once 'includes' . DIRECTORY_SEPARATOR . 'functions.php';

@$page = $_REQUEST['page'];

ob_start();

switch ($page)
{
    case 'about':
        $title = 'About us';
        $h1 = 'About us page title';

        include_once 'template' . DIRECTORY_SEPARATOR . 'about.php';
        break;
    case 'gallery':
        $title = 'Gallery';
        $h1 = 'Gallery title';

        include_once 'template' . DIRECTORY_SEPARATOR . 'gallery.php';
        break;
    case 'contact':
        $title = 'Contact us';
        $h1 = 'Contact us page #1 title';

        include_once 'template' . DIRECTORY_SEPARATOR . 'contact.php';
        break;
    case '':
    case 'home':
    default:
        $title = 'Home page';
        $h1 = 'Home page #1 title';

        include_once 'template' . DIRECTORY_SEPARATOR . 'home.php';
        break;
}

$topNav = getTopNavigation();

$content = ob_get_contents();
ob_end_clean();

// echo $content;

include_once 'template' . DIRECTORY_SEPARATOR . '_layout.php';
