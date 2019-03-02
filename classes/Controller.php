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

namespace Onepage;

class Controller
{
    public static function dispatch()
    {
        global $edit, $plugin_cf, $plugin_tx, $pd_router, $pth;

        $pd_router->add_interest('onepage_class');
        if ($plugin_cf['onepage']['use_javascript']) {
                self::emitJavaScript();
        }
        if (XH_ADM) {
            if ($edit) {
                $template = trim($plugin_cf['onepage']['admin_template']);
                if ($template != '') {
                    self::setTemplate($template);
                }
            }
            if (function_exists('XH_registerStandardPluginMenuItems')) {
                XH_registerStandardPluginMenuItems(false);
            }
            $pd_router->add_tab(
                $plugin_tx['onepage']['tab_title'],
                "{$pth['folder']['plugins']}onepage/onepage_view.php"
            );
            if (self::isAdministrationRequested()) {
                self::handleAdministration();
            }
        } else {
            XH_afterPluginLoading(array('Onepage\\Controller', 'evaluateScripting'));
        }
    }

    protected static function emitJavaScript()
    {
        global $pth, $u, $bjs, $plugin_cf, $edit, $s;

        $pcf = $plugin_cf['onepage'];
        $config = array(
            'isOnepage' => !$edit && (!XH_ADM || $s >= 0),
            'numericUrls' => $pcf['url_numeric'],
            'scrollDuration' => $pcf['scroll_duration'],
            'scrollEasing' => $pcf['scroll_easing']
        );
        if (XH_ADM && $pcf['url_numeric']) {
            $config['urls'] = array_flip($u);
        }
        $bjs .= '<script type="text/javascript">/* <![CDATA[ */'
            . 'var ONEPAGE = ' . json_encode($config)
            . '/* ]]> */</script>'
            . '<script type="text/javascript" src="' . $pth['folder']['plugins']
            . 'onepage/onepage.min.js"></script>';
    }

    /**
     * @param string $template
     */
    protected static function setTemplate($template)
    {
        global $pth;

        $pth['folder']['template'] = $pth['folder']['templates'] . $template . '/';
        $pth['file']['template'] = $pth['folder']['template'] . 'template.htm';
        $pth['file']['stylesheet'] = $pth['folder']['template'] . 'stylesheet.css';
        $pth['folder']['menubuttons'] = $pth['folder']['template'] . 'menu/';
        $pth['folder']['templateimages'] = $pth['folder']['template'] . 'images/';
    }

    /**
     * @return bool
     */
    protected static function isAdministrationRequested()
    {
        global $onepage;

        return function_exists('XH_wantsPluginAdministration')
            && XH_wantsPluginAdministration('onepage')
            || isset($onepage) && $onepage == 'true';
    }

    protected static function handleAdministration()
    {
        global $admin, $action, $o;

        $o .= print_plugin_admin('off');
        switch ($admin) {
            case '':
                $o .= self::renderInfo();
                break;
            default:
                $o .= plugin_admin_common($action, $admin, 'onepage');
        }
    }

    /**
     * @return string
     */
    private static function renderInfo()
    {
        $view = new View('info');
        $view->logo = self::logoPath();
        $view->version = ONEPAGE_VERSION;
        return $view->render();
    }

    /**
     * @return string
     */
    protected static function logoPath()
    {
        global $pth;

        return $pth['folder']['plugins'] . 'onepage/onepage.png';
    }

    public static function evaluateScripting()
    {
        global $c, $cl, $s;

        $oldS = $s;
        for ($i = 0; $i < $cl; $i++) {
            if (hide($i)) {
                continue;
            }
            $s = $i;
            $c[$i] = evaluate_scripting($c[$i]);
        }
        $s = $oldS;
    }

    /**
     * @return string
     */
    public static function getContent()
    {
        global $s, $o, $hc, $c, $u, $edit, $plugin_cf, $pd_router;

        if (!($edit && XH_ADM) && $s > -1) {
            $contents = '';
            foreach ($hc as $i) {
                $url = $plugin_cf['onepage']['url_numeric']
                    ? $i
                    : XH_hsc(urldecode($u[$i]));
                $pageData = $pd_router->find_page($i);
                $content = self::replaceAlternativeHeading($c[$i], $pageData);
                $contents .= sprintf(
                    '<div id="%s" class="onepage_page %s">%s</div>',
                    $url,
                    $pageData['onepage_class'],
                    sprintf(
                        '<div class="%s">%s</div>',
                        $plugin_cf['onepage']['inner_class'],
                        $content
                    )
                );
            }
            $o .= preg_replace('/#CMSimple (.*?)#/is', '', $contents);
        }
        return preg_replace('/<!--XH_ml[1-9]:.*?-->/is', '', $o);
    }

    /**
     * @param string $content
     * @return string
     * @todo Use Pageparams_replaceAlternativeHeading() if available.
     */
    protected static function replaceAlternativeHeading($content, array $pageData)
    {
        global $cf;

        if (isset($pageData['show_heading']) && $pageData['show_heading'] == '1') {
            $pattern = '/(<h[1-' . $cf['menu']['levels'] . '].*>).+(<\/h[1-'
                . $cf['menu']['levels'] . ']>)/isU';
            if (trim($pageData['heading']) == '') {
                return preg_replace($pattern, '', $content);
            } else {
                return preg_replace(
                    $pattern,
                    '${1}' . addcslashes($pageData['heading'], '$\\') . '$2',
                    $content
                );
            }
        } else {
            return $content;
        }
    }

    /**
     * @param string $id
     * @return string
     */
    public static function renderTopLink($id)
    {
        global $pth, $plugin_tx;

        if ($id != '' && $id[0] == '#') {
            $id = substr($id, 1);
        }
        $image = $pth['folder']['templateimages'] . 'up.png';
        if (!file_exists($image)) {
            $image = $pth['folder']['plugins'] . 'onepage/images/up.png';
        }
        $alt = $plugin_tx['onepage']['alt_toplink'];
        return '<a id="onepage_toplink" href="#' . $id . '">'
            . tag('img src="' . $image . '" alt="' . $alt . '"')
            . '</a>';
    }
}
