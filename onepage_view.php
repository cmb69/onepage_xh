<?php

/**
 * Copyright (c) Christoph M. Becker
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

function Onepage_view($pageData)
{
    global $sn, $su, $plugin_tx;

    $action = XH_hsc("$sn?$su");
    $class = XH_hsc($pageData['onepage_class']);
    return <<<HTML
<form id="onepage" action="$action" method="post">
    <p>
        <b>{$plugin_tx['onepage']['tab_title']}</b>
    <p>
        <label>
            <span>{$plugin_tx['onepage']['tab_class']}</span>
            <input type="text" name="onepage_class" value="$class">
        </label>
    </p>
    <p>
        <button name="save_page_data">{$plugin_tx['onepage']['tab_save']}</button>
    </p>
</form>
HTML;
}
