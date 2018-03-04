<?php

/**
 * Created by PhpStorm.
 * User: Тарас
 * Date: 23.02.2018
 * Time: 20:51
 */

if (!empty($_POST)) {
    // die('<pre>' . print_r($_POST, 1) . '</pre>');
    if (validateContactData($_POST)) {
        addContactData($_POST);
    }
}
echo getErrors();

echo '<hr>';

echo '<form action="" method="post" name="registration">' .
    '<div><label for="uname">User name <i>*</i>: </label> <input type="text" id="uname" name="uname" style="width:300px;" required></div>' .
    '<div><label for="umail">Email <i>*</i>: </label> <input type="text" id="umail" name="uemail" style="width:300px;" required></div>' .
    '<div><label for="utel">Phone: </label> <input type="text" id="utel" name="utel" style="width:300px;"></div>' .
    '<div><label for="umail">Message <i>*</i>: </label><br><textarea name="umsg" rows="10" style="width:100%;" required></textarea></div>' .
    '<div class="g-recaptcha" data-sitekey="6Lei1TIUAAAAAKLLF_9esLY1CyvbH34Nm0dRCnIL"></div>' .
    '<div><input type="submit" value="Add comment"></div>'.
    '<div><i>*</i> - required fields</div>' .
    '</form>';

echo '<hr>';

echo getComments();