<?php

/**
 * Created by PhpStorm.
 * User: Тарас
 * Date: 02.03.2018
 * Time: 19:54
 */

$fileUploadErr = [];

$allowExt = [
    'jpg',
    'png',
    'jpeg',
    'gif',
    'svg',
];

$allowMime = [
    'image/jpeg',
    'image/gif',
    'image/png',
    'image/svg',
];

$uploadDir = dirname(__FILE__) . DIRECTORY_SEPARATOR .
    '..' . DIRECTORY_SEPARATOR .
    'files' . DIRECTORY_SEPARATOR .
    'gallery1';

$pathToFilesWeb = 'files/gallery1/';

function uploadFiles($files, $maxFileSize = 2048)
{
    global $uploadDir, $allowExt, $allowMime, $fileUploadErr;

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    /**
     * @example Array
    (
    [file1] => Array
    (
    [name] => 30423_pets-products_january-site-flip_3-cathealth_short-tile_592x304._CB286975940_.jpg
    [type] => image/jpeg
    [tmp_name] => C:\os\OSPanel\userdata\temp\php1B5.tmp
    [error] => 0
    [size] => 23017
    )

    ) */
    foreach ($files as $f1) {
        if ($f1['error'] != 0) {
            $fileUploadErr[] = 'Upload file error: ' . $f1['error'];
        } elseif ($f1['size'] > $maxFileSize) {
            $fileUploadErr[] = 'File size ' . $f1['size'] . ' exceeds maxium allowed: ' . $maxFileSize;
        } elseif ($f1['size'] <= $maxFileSize) {
            $pInfo = pathinfo($f1['name']);
            $ext = strtolower($pInfo['extension']);
            if (!in_array($ext, $allowExt)) {
                $fileUploadErr[] = 'Wrong file extension ' . $ext . '. Allowed extensions are: ' . var_export($allowExt, 1);
            }
            $mime = mime_content_type($f1['tmp_name']);
            // die('Uploaded mime type: |' . $mime . '| allowed mimes: ' . var_export($allowMime, 1));
            if (!in_array($mime, $allowMime)) {
                $fileUploadErr[] = 'Wrong file mime type ' . $mime . '. Allowed mime types are: ' . var_export($allowMime, 1);
            }

            $uploadFileFullName = $uploadDir . $f1['name'];
            if (file_exists($uploadFileFullName)) {
                $uploadFileFullName = $uploadDir . date('Y-m-d-H-i-s') . '.' . $ext;
            }
        }

        if (!empty($fileUploadErr)) {
            foreach ($fileUploadErr as $err) {
                echo '<p class="error file-upload-error">' . $err . '</p>';
            }
            exit;
        }

        if (move_uploaded_file($f1['tmp_name'], $uploadFileFullName)) {
            echo '<p>Uploaded file to: ' . $uploadFileFullName . '</p>';
        }
    }
}

function showFiles($dir)
{
    global $file_list, $allowExt;
    if (is_dir($dir)) {
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                // if ($file === '.' || $file === '..') {
                if ($file === '.' or $file === '..') {
                    continue; // Skip directory links
                }
                $fullFilePath = $dir . DIRECTORY_SEPARATOR . $file;
                if (filetype($fullFilePath)=='dir'){
//                    echo "dir {$fullFilePath}";
                    showFiles($fullFilePath);
                }
                $pattern='/(';
                foreach ($allowExt as $key => $value){
                    $pattern.='.'.$value.'|';
                }
                $pattern.='SADFSAsfsaf098)$/i';
                $check_ext=preg_match($pattern,$fullFilePath);
                if ((filetype($fullFilePath)!='dir') and ($check_ext)) {
                    $file_list[]=$fullFilePath;
                }
            }
            closedir($dh);
        }
    } else {
        die('Error: directory ' . $dir . ' does not exists!');
    }
    return $file_list;
}

function displayGallery1()
{
    global $file_list, $uploadDir, $allowExt, $pathToFilesWeb;
     unset($file_list);
    $extList = '{*.' . implode(',*.', $allowExt) . '}';
    // die($extList);

//    $imageFiles = glob($uploadDir . $extList, GLOB_BRACE);
    $imageFiles=showFiles($uploadDir);
//    die(var_dump($uploadDir . $extList, GLOB_BRACE));

    $galleryHtml = '';
    if (!empty($imageFiles)) {
        foreach ($imageFiles as $file) {
            $imgName = basename($file);
            $galleryHtml .= '<figure><img src="' . $pathToFilesWeb . $imgName . '" width="750px" alt=""></figure>';
        }
    }
    return $galleryHtml;
}