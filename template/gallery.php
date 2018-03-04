<?php

/**
 * Created by PhpStorm.
 * User: Тарас
 * Date: 02.03.2018
 * Time: 19:41
 */

define('FILE_SIZE_MAX', 1024*1024*2);

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR .
    '..' . DIRECTORY_SEPARATOR .
    'includes' . DIRECTORY_SEPARATOR .
    'functions_gallery.php';

if (!empty($_FILES)) {
    // die('<pre>' . print_r($_POST, 1) . '</pre>');
    /**
     * @example Array
    (
    [MAX_FILE_SIZE] => 2097152
    )
    */
    // die('<pre>' . print_r($_FILES, 1) . '</pre>');

    uploadFiles($_FILES, FILE_SIZE_MAX);

}
echo <<<EOD
<div class="showhide">
<label for="fileloader" class="button">File Loader Show/Hide</label>
<input type="checkbox" checked name="one" id="fileloader" class="loader">
   <div>
       <iframe src="files/gallery1/index.php" height=70% width=100%></iframe>
   </div>
</div>
EOD;
echo '<hr>';
/*
echo '<form action="" method="post" enctype="multipart/form-data">' .
    '<div><label for="ufile1">Choose file <i>*</i>: </label> <input type="file" id="ufile1" name="file1" required></div>' .
    '<input type="hidden" name="MAX_FILE_SIZE" value="' . FILE_SIZE_MAX . '">'.
    '<div><input type="submit" value="Upload file"></div>'.
    '<div><i>*</i> - required fields</div>' .
    '</form>';

echo '<hr>';
*/
echo <<<EOD
<div class="row">
<div class="gallery">
EOD;

echo displayGallery1();

echo '<script src="js/popup.js"></script>';

echo '<script> popup.init() </script>';

echo '</div></div>';