<?php

/**
 * The li class.
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

/**
 * The li class.
 *
 * @category CMSimple_XH
 * @package  Onepage
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Onepage_XH
 */
class Onepage_Li extends XH_Li
{
    /**
     * Renders a menu item.
     *
     * @param int $i The current item index.
     *
     * @return string (X)HTML.
     *
     * @global array The headings of the pages.
     */
    function renderMenuItem($i)
    {
        global $h;

        return $this->renderAnchorStartTag($i) . $h[$this->ta[$i]] . '</a>';
    }

    /**
     * Renders an anchor start tag.
     *
     * @param int $i The index of the current item.
     *
     * @return string (X)HTML.
     *
     * @access protected
     */
    function renderAnchorStartTag($i)
    {
        $x = $this->shallOpenInNewWindow($i) ? '" target="_blank' : '';
        return $this->anchor($this->ta[$i], $x);
    }

    /**
     * Returns an opening a tag as link to a page.
     *
     * @param int    $i The page index.
     * @param string $x Arbitrary appendix of the URL.
     *
     * @global string The script name.
     * @global array  The URLs of the pages.
     * @global array  The configuration of the core.
     * @global bool   Whether we're in edit mode.
     *
     * @return string The (X)HTML.
     */
    protected function anchor($i, $x)
    {
        global $sn, $u, $cf, $edit;

        $html = '<a href="' . $sn;
        if (XH_ADM && !$edit) {
            $html .= '?' . $u[$i];
        }
        $html .= (XH_ADM && $edit) ? '?' : '#';
        if (isset($u[$i])) {
            $html .= $u[$i];
        }
        $html .= $x . '">';
        return $html;
    }
}

?>
