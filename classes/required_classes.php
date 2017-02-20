<?php

/**
 * @copyright 2015-2017 Christoph M. Becker <http://3-magi.net>
 * @license http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 */

spl_autoload_register(function ($class) {
    global $pth;

    $parts = explode('\\', $class, 2);
    if ($parts[0] == 'Onepage') {
        include_once $pth['folder']['plugins'] . 'onepage/classes/'
            . $parts[1] . '.php';
    }
});
