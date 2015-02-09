<?php

/**
 * Front-end of Onepage_XH.
 *
 * PHP version 5
 *
 * @category  CMSimple_XH
 * @package   Onepage
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2015 Christoph M. Becker <http://3-magi.net>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link      http://3-magi.net/?CMSimple_XH/Onepage_XH
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
    die(<<<EOT
Onepage_XH detected an unsupported CMSimple_XH version.
Uninstall Onepage_XH or upgrade to a supported CMSimple_XH version!
EOT
    );
}

/**
 * The plugin version.
 */
define('ONEPAGE_VERSION', '@ONEPAGE_VERSION@');

/**
 * Returns the available templates for metaconfig.php.
 *
 * @return array
 */
function Onepage_templates()
{
    return array_merge(array(''), XH_templates());
}

/**
 * Returns the table of contents.
 *
 * @return string (X)HTML.
 *
 * @global array The paths of system files and folders.
 * @global array The indexes of the visible pages.
 */
function Onepage_toc()
{
    global $pth, $hc;

    include_once $pth['folder']['classes'] . 'Menu.php';
    $li = new Onepage_Li();
    return $li->render($hc, 1);
}

/**
 * Returns the current page content.
 *
 * @return string (X)HTML.
 */
function Onepage_content()
{
    return Onepage_Controller::getContent();
}

/**
 * Returns the top link.
 *
 * @param string $id An (X)HTML id.
 *
 * @return string (X)HTML.
 */
function Onepage_toplink($id = '')
{
    return Onepage_Controller::renderTopLink($id);
}

Onepage_Controller::dispatch();

?>
