<?php

/**
 * The plugin controller.
 *
 * PHP version 5
 *
 * @category  CMSimple_XH
 * @package   Onepage
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2015-2017 Christoph M. Becker <http://3-magi.net>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link      http://3-magi.net/?CMSimple_XH/Onepage_XH
 */

namespace Onepage;

/**
 * The plugin controller.
 *
 * @category CMSimple_XH
 * @package  Onepage
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Onepage_XH
 */
class Controller
{
    /**
     * Handles plugin related requests.
     *
     * @return void
     *
     * @global bool  Whether we're in edit mode.
     * @global array The configuration of the plugins.
     * @global int   The requested page.
     */
    public static function dispatch()
    {
        global $edit, $plugin_cf, $s;

        if ((!XH_ADM || (!$edit && $s >= 0)) && $plugin_cf['onepage']['use_javascript']) {
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
            if (self::isAdministrationRequested()) {
                self::handleAdministration();
            }
        } else {
            XH_afterPluginLoading(array('Onepage\\Controller', 'evaluateScripting'));
        }
    }

    /**
     * Emits the JavaScript.
     *
     * @return void
     *
     * @global array  The paths of system files and folders.
     * @global array  The URLs of the pages.
     * @global string The (X)HTML to append to the body element.
     * @global array  The configuration of the plugins.
     */
    protected static function emitJavaScript()
    {
        global $pth, $u, $bjs, $plugin_cf;

        $pcf = $plugin_cf['onepage'];
        $config = array(
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
            . 'onepage/onepage.js"></script>';
    }

    /**
     * Sets the template.
     *
     * @param string $template A template name.
     *
     * @return void
     *
     * @global array The paths of system files and folders.
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
     * Returns whether the plugin administration is requested.
     *
     * @return bool
     *
     * @global string Whether the plugin administration is requested.
     */
    protected static function isAdministrationRequested()
    {
        global $onepage;

        return function_exists('XH_wantsPluginAdministration')
            && XH_wantsPluginAdministration('onepage')
            || isset($onepage) && $onepage == 'true';
    }

    /**
     * Handles the plugin administration.
     *
     * @return void
     *
     * @global string The value of the <var>admin</var> GP parameter.
     * @global string The value of the <var>action</var> GP parameter.
     * @global string The (X)HTML fragment to insert at the top of the content.
     */
    protected static function handleAdministration()
    {
        global $admin, $action, $o;

        $o .= print_plugin_admin('off');
        switch ($admin) {
        case '':
            $o .= self::render('info');
            break;
        default:
            $o .= plugin_admin_common($action, $admin, 'onepage');
        }
    }

    /**
     * Renders a view template.
     *
     * @param string $template The name of the template.
     *
     * @return string
     *
     * @global array The paths of system files and folders.
     * @global array The configuration of the core.
     */
    protected static function render($template)
    {
        global $pth, $cf;

        $template = $pth['folder']['plugins'] . 'onepage/views/'
            . $template . '.php';
        ob_start();
        include $template;
        $o = ob_get_clean();
        if (!$cf['xhtml']['endtags']) {
            $o = str_replace('/>', '>', $o);
        }
        return $o;
    }

    /**
     * Returns the localization of a string.
     *
     * @param string $key The key of the string.
     *
     * @return string
     *
     * @global array The localization of the plugins.
     */
    protected static function l10n($key)
    {
        global $plugin_tx;

        return $plugin_tx['onepage'][$key];
    }

    /**
     * Returns the path of the plugin logo.
     *
     * @return string
     *
     * @global array The paths of system files and folders.
     */
    protected static function logoPath()
    {
        global $pth;

        return $pth['folder']['plugins'] . 'onepage/onepage.png';
    }

    /**
     * Evaluates scripting of all visible pages.
     *
     * @return void
     *
     * @global array The contents of the pages.
     * @global int   The number of pages.
     * @global int   The requested page.
     */
    static public function evaluateScripting()
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
     * Returns the current page content.
     *
     * @return string (X)HTML.
     *
     * @global int    The requested page.
     * @global string The (X)HTML fragment to be prepended to the contents area.
     * @global array  The indexes of the visible pages.
     * @global array  The contents of the pages.
     * @global array  The URLs of the pages.
     * @global bool   Whether we're in edit mode.
     * @global array  The configuration of the plugins.
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
                    '<div id="%s" class="onepage_page">%s</div>',
                    $url,
                    sprintf(
                        '<div class="%s">%s</div>',
                        $plugin_cf['onepage']['inner_class'],
                        $content
                    )
                );
            }
            return $o . preg_replace('/#CMSimple (.*?)#/is', '', $contents);
        } else {
            return $o;
        }
    }

    /**
     * Replaces the existing heading with an alternative heading if configured.
     *
     * @param string $content  The page content.
     * @param array  $pageData The page data.
     *
     * @return string
     *
     * @global array The configuration of the core.
     *
     * @todo Use Pageparams_replaceAlternativeHeading() if available.
     */
    protected static function replaceAlternativeHeading($content, $pageData)
    {
        global $cf;
    
        if ($pageData['show_heading'] == '1') {
            $pattern = '/(<h[1-' . $cf['menu']['levels'] . '].*>).+(<\/h[1-'
                . $cf['menu']['levels'] . ']>)/isU';
            if (trim($pageData['heading']) == '') {
                return preg_replace($pattern, '', $content);
            } else {
                return preg_replace(
                    $pattern, '${1}' . addcslashes($pageData['heading'], '$\\') . '$2',
                    $content
                );
            }
        } else {
            return $content;
        }
    }

    /**
     * Renders the top link.
     *
     * @param string $id An (X)HTML id.
     *
     * @return string (X)HTML.
     *
     * @global array The paths of system files and folders.
     * @global array The localization of the plugins.
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

?>
