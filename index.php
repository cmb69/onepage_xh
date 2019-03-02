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

const ONEPAGE_VERSION = '1.0beta3';

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
