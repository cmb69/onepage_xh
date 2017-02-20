<?php

/**
 * @copyright 2015-2017 Christoph M. Becker <http://3-magi.net>
 * @license  ttp://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 */

/*
 * Prevent direct access and usage from unsupported CMSimple_XH versions.
 */
if (!defined('CMSIMPLE_XH_VERSION')
    || strpos(CMSIMPLE_XH_VERSION, 'CMSimple_XH') !== 0
    || version_compare(CMSIMPLE_XH_VERSION, 'CMSimple_XH 1.6.3', 'lt')
) {
    header('HTTP/1.1 403 Forbidden');
    header('Content-Type: text/plain; charset=UTF-8');
    die(
        <<<EOT
Onepage_XH detected an unsupported CMSimple_XH version.
Uninstall Onepage_XH or upgrade to a supported CMSimple_XH version!
EOT
    );
}

define('ONEPAGE_VERSION', '@ONEPAGE_VERSION@');

/**
 * @return array
 */
function Onepage_templates()
{
    return array_merge(array(''), XH_templates());
}

/**
 * @return string
 */
function Onepage_toc()
{
    global $pth, $hc;

    if (!function_exists('XH_autoload')) {
        include_once $pth['folder']['classes'] . 'Menu.php';
    }
    $li = new Onepage\Li();
    return $li->render($hc, 1);
}

/**
 * @return string
 */
function Onepage_content()
{
    return Onepage\Controller::getContent();
}

/**
 * @param string $id
 * @return string
 */
function Onepage_toplink($id = '')
{
    return Onepage\Controller::renderTopLink($id);
}

Onepage\Controller::dispatch();
