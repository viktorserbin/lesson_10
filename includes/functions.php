<?php

/**
 * Created by PhpStorm.
 * User: Тарас
 * Date: 23.02.2018
 * Time: 20:16
 */

define('GOOGLE_SECRET_KEY', '6Lei1TIUAAAAAJOX9E44uEhrjhe9uhpYBPPJJA_h');

$errors = [];
$contactMessages = dirname(__FILE__) . DIRECTORY_SEPARATOR .
    '..' . DIRECTORY_SEPARATOR .
    'files' . DIRECTORY_SEPARATOR . 'contact.dat';

function validateContactData($data) {
    global $errors;

    $validData = true;

/**
 * @example $data = [
 *  'uname'  => 'Taras',
 *  'uemail' => 'taras@shkodenko.com',
 *  'utel'   => '0674464212',
 *  'umsg'   => 'Test 1',
 * ];
 */

    $data['uname'] = trim(strip_tags($data['uname']));
    if (empty($data['uname'])) {
        $errors[] = 'Username is required.';
    } elseif (strlen($data['uname']) > 255) {
        $errors[] = 'Username is too long > 255 characters.';
    } elseif (strlen($data['uname']) < 2) {
        $errors[] = 'Username is too short < 2 characters.';
    }

    if (empty($data['uemail'])) {
        $errors[] = 'Email is required.';
    } else {
        if (!filter_var($data['uemail'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid Email format.';
        }
    }

    if (!empty($data['utel'])) {
        $data['utel'] = trim(preg_replace('|\D|', '', $data['utel']));

        if (strlen($data['utel']) < 10) {
            $errors[] = 'Phone number must be at least 10 digits.';
        } elseif(strlen($data['utel']) > 12) {
            $errors[] = 'Phone number must be not more than 12 digits.';
        }
    }

    if (empty($data['umsg'])) {
        $errors[] = 'Message can`t be empty.';
    } else {
        $data['umsg'] = trim(strip_tags($data['umsg']));

        if (strlen($data['umsg']) > 1024) {
            $errors[] = 'Text it too long > 1024 characters.';
        }
    }

    if (empty($data['g-recaptcha-response'])) {
        $errors[] = 'Invalid captcha code.';
    } else {
        $reqData = http_build_query([
            'secret' => GOOGLE_SECRET_KEY,
            'response' => $data['g-recaptcha-response'],
            // remoteip => $_SERVER['REMOTE_ADDR'],
        ]);
        $context = stream_context_create([
            'http' => [
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $reqData,
            ]
        ]);
        $resp = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);

        if ($resp !== false) {
            $oResp = json_decode($resp);

            if ($oResp->success) { // Correct captcha code
            } else {
                $errors[] = 'Wrong captcha code.';
            }
        } else {
            $errors[] = 'Error validating captcha code.';
        }
    }

    if (!empty($errors)) {
        $validData = false;
    }

    return $validData;
}

function getErrors() {
    global $errors;

    $messages = '';

    if (!empty($errors)) {
        foreach ($errors as $err) {
            $messages .= '<p class="error danger-error">' . $err . '</p>';
        }
    }

    return $messages;
}

function addContactData($data) {
    global $contactMessages;

    $data['addedDt'] = date('Y-m-d H:i:s');
    $data['browser'] = $_SERVER['HTTP_USER_AGENT'];
    $data['ref'] = $_SERVER['HTTP_REFERER'];
    $data['ip'] = $_SERVER['REMOTE_ADDR'];

    $previousMessages = _getCommentsAsArray();
    $newMessages = array_merge($previousMessages, [$data]);
    $sMessages = serialize($newMessages);
    file_put_contents($contactMessages, $sMessages);
}

function _getCommentsAsArray() {
    global $contactMessages;

    $aComments = [];

    if (file_exists($contactMessages)) {
        $fData = file_get_contents($contactMessages);
        if (!empty($fData)) {
            $aComments = unserialize($fData);
            if ($aComments !== false) {
                return $aComments;
            }
        }
    }

    return $aComments;
}

function _antimat($str)
{
    if (strlen($str)) {
        return str_replace(
            [
                'Test',
            ],
            [
                'T**t',
            ],
            $str);
    }
}

function getComments() {
    $messages = _getCommentsAsArray();

    $htmlMsgs = '';

    if ($messages) {
        foreach ($messages as $n => $m) {
            $tel = '';
            if (!empty($m['utel'])) {
                $ac = '';
                if (strlen($tel) < 12) {
                    $ac = '38';
                }
                $tel = ' <a href="tel:+' . $ac . $m['utel'] . '">' . $m['utel'] . '</a>';
            }
            $htmlMsgs .= '<div class="message message-' . $n . '">' .
                '<dl>' .
                '<dt>Added by <a href="mailto:' . $m['uemail'] . '">' . $m['uname'] .
                '</a>' . $tel . ' on ' . $m['addedDt'] . '</dt>' .
                '<dd>' . _antimat($m['umsg']) . '</dd>' .
                '</dl>' .
                '</div>';
        }
    }

    return $htmlMsgs;
}

function getTopNavigation()
{
    $navHtml = '';

    $pages = [
        ['href' => 'index.php?page=home', 'title' => 'Home page'],
        ['href' => 'index.php?page=about', 'title' => 'About us'],
        ['href' => 'index.php?page=gallery', 'title' => 'Gallery'],
        ['href' => 'index.php?page=contact', 'title' => 'Contact us'],
    ];

    if (!empty($pages)) {
        $navHtml .= '<nav class="navbar navbar-inverse" style="background: #2c3e50;"><ul class="nav navbar-nav">';
        foreach ($pages as $i => $page) {
            $navHtml .= '<li class="active"><a style="background: #2c3e50;" href="'. $page['href'] .'">'. $page['title'] .'</a></li>';
        }
        $navHtml .= '</ul></nav>';
    }

    return $navHtml;
}

