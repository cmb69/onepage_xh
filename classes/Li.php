<?php

/**
 * Copyright 2015-2017 Christoph M. Becker
 *
 * This file is part of Onepage_XH.
 *
 * Onepage_XH is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Onepage_XH is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Onepage_XH.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Onepage;

use XH_Li;

class Li extends XH_Li
{
    /**
     * @param int $i
     * @return string
     */
    public function renderULStartTags($i)
    {
        global $l;

        $html = parent::renderULStartTags($i);
        if ($l[$i] == 1) {
            return preg_replace('/<ul class/', '<ul id="onepage_menu" class', $html, 1);
        } else {
            return $html;
        }
    }

    /**
     * @param int $i
     * @return string
     */
    public function renderMenuItem($i)
    {
        global $h;

        return $this->renderAnchorStartTag($i) . $h[$this->ta[$i]] . '</a>';
    }

    /**
     * @param int $i
     * @return string
     */
    public function renderAnchorStartTag($i)
    {
        $x = $this->shallOpenInNewWindow($i) ? '" target="_blank' : '';
        return $this->anchor($this->ta[$i], $x);
    }

    /**
     * @param int $i
     * @param string $x
     * @return string
     */
    protected function anchor($i, $x)
    {
        global $sn, $u, $edit, $plugin_cf;

        $html = '<a href="' . $sn;
        if (XH_ADM && !$edit) {
            $html .= '?' . $u[$i];
        }
        $html .= (XH_ADM && $edit) ? '?' : '#';
        if (isset($u[$i])) {
            if ($plugin_cf['onepage']['url_numeric'] && !(XH_ADM && $edit)) {
                $html .= $i;
            } else {
                $html .= $u[$i];
            }
        }
        $html .= $x . '">';
        return $html;
    }
}
