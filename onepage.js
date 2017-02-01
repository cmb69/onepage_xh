/*!
 *
 * Onepage_XH browser scripting.
 *
 * @copyright 2015-2017 Christoph M. Becker <http://3-magi.net/>
 * @license   GNU GPLv3 <http://www.gnu.org/licenses/gpl-3.0.en.html>
 */

/*jslint browser: true, maxlen: 80, regexp: true */
/*global ONEPAGE */

(function () {
    "use strict";

    /**
     * The master element, i.e. the documentElement or the body.
     */
    var masterElement;

    function getElementsByClassName(className) {
        if (typeof document.getElementsByClassName != "undefined") {
            return document.getElementsByClassName(className);
        } else if (typeof document.querySelectorAll != "undefined") {
            return document.querySelectorAll("." + className);
        } else {
            return [];
        }
    }

    /**
     * Calls a callback for each item in a list.
     *
     * @param   {array-like} items
     * @param   {Function}   callback
     * @returns {undefined}
     */
    function forEach(items, callback) {
        var i, n;

        for (i = 0, n = items.length; i < n; i += 1) {
            callback(items[i]);
        }
    }

    /**
     * Adds an event listener to an object.
     *
     * @param   {Object}        object
     * @param   {String}        event
     * @param   {EventListener} listener
     * @returns {undefined}
     */
    function on(object, event, listener) {
        if (typeof object.addEventListener !== "undefined") {
            object.addEventListener(event, listener, false);
        } else if (typeof object.attachEvent !== "undefined") {
            object.attachEvent("on" + event, listener);
        }
    }

    function getElementTop(element) {
        var top = 0;

        while (element) {
            top += element.offsetTop;
            element = element.offsetParent;
        }
        return top;
    }

    /**
     * Scrolls to an element with a certain ID.
     *
     * @param   {string}    id
     * @returns {undefined}
     */
    function scrollToId(id) {
        var element, master, duration, start, delta, startOffset, endOffset,
            oldOffset;

        /**
         * Calculates the easing.
         *
         * @param   {Number} percentage
         * @returns {Number}
         */
        function ease(percentage) {
            switch (ONEPAGE.scrollEasing) {
            case "quad-in":
                return percentage * percentage;
            case "quad-out":
                return -percentage * (percentage - 2);
            case "sine":
                return 0.5 - Math.cos(Math.PI * percentage) / 2;
            default:
                return percentage;
            }
        }

        /**
         * Renders the next animation step.
         *
         * @param   {Number} timestamp
         * @returns {undefined}
         */
        function step(timestamp) {
            var progress, offset, percentage, EPSILON = 1;

            if (!start) {
                start = timestamp;
            }
            progress = timestamp - start;
            percentage = Math.min(progress / duration, 1);
            percentage = ease(percentage);
            offset = percentage * delta;
            if (Math.abs(master.scrollTop - oldOffset) > EPSILON) {
                return;
            }
            oldOffset = master.scrollTop = startOffset + offset;
            if (progress < duration) {
                window.requestAnimationFrame(step);
            }
        }

        master = masterElement;
        duration = ONEPAGE.scrollDuration;
        element = document.getElementById(id);
        endOffset = element ? getElementTop(element) : 0;
        if (typeof window.requestAnimationFrame !== "undefined") {
            oldOffset = startOffset = master.scrollTop;
            delta = endOffset - startOffset;
            window.requestAnimationFrame(step);
        } else {
            master.scrollTop = endOffset;
        }
    }

    /**
     * Navigates to a fragment.
     *
     * @param   {string}    id
     * @returns {undefined}
     */
    function navigateToFragment(id) {
        var master, scrollTop;

        master = masterElement;
        scrollTop = master.scrollTop;
        location.hash = id;
        master.scrollTop = scrollTop;
        scrollToId(decodeURIComponent(id));
    }

    /**
     * Initializes the master element, i.e. the documentElement or the body.
     *
     * @returns {undefined}
     */
    function initMasterElement() {
        var scrollTop, html;

        html = document.documentElement;
        scrollTop = html.scrollTop;
        html.scrollTop = 1;
        if (html.scrollTop === 1) {
            html.scrollTop = scrollTop;
            masterElement = html;
        } else {
            masterElement = document.body;
        }
    }

    /**
     * Initializes smooth scrolling for all links to the current page.
     *
     * @returns {undefined}
     */
    function initSmoothScrolling() {
        var url, anchors;

        url = window.location.href.split("#")[0];
        anchors = document.getElementsByTagName("a");
        forEach(anchors, function (anchor) {
            if (anchor.href.split("#")[0] === url) {
                on(anchor, "click", function (event) {
                    navigateToFragment(anchor.hash.substr(1));
                    if (typeof event.preventDefault !== "undefined") {
                        event.preventDefault();
                    } else {
                        event.returnValue = false;
                    }
                    if (anchor.id === "onepage_toplink") {
                        anchor.blur();
                    }
                });
            }
        });
    }

    /**
     * Fixes the view mode link of the admin menu.
     *
     * @returns {undefined}
     */
    function fixViewModeLink() {
        var menu, anchor, url;

        menu = document.getElementById("xh_adminmenu");
        if (menu) {
            anchor = menu
                .getElementsByTagName("ul")[0]
                .getElementsByTagName("li")[0]
                .getElementsByTagName("a")[0];
            if (anchor.href.match(/&normal$/)) {
                url = anchor.href.match(/\?(.*)&/)[1];
                if (ONEPAGE.numericUrls) {
                    anchor.href += "#" + ONEPAGE.urls[url];
                } else {
                    anchor.href += "#" + url;
                }
            }
        }
    }

    /**
     * Initializes the onepage plugin.
     *
     * @returns {undefined}
     */
    function init() {
        var topLink;

        function showOrHideTopLink(suffix) {
            if (typeof suffix != "string") {
                suffix = "";
            } else {
                topLink.style.display = "block";
            }
            if (masterElement.scrollTop > 300) {
                topLink.className = "onepage_show" + suffix;
            } else {
                topLink.className = "onepage_hide" + suffix;
            }
        }

        function adjustMenuClasses() {
            var pages, i, selectedURL, menu, menuItems;
            
            pages = getElementsByClassName("onepage_page");
            for (i = pages.length - 1; i >= 0; i--) {
                var page;

                page = pages[i];
                if (masterElement.scrollTop >= getElementTop(page)) {
                    selectedURL = encodeURIComponent(page.id);
                    break;
                }
            }
            if (!selectedURL && pages.length >= 1) {
                selectedURL = encodeURIComponent(pages[0].id);
            }

            menu = document.getElementById("onepage_menu");
            menuItems = menu.getElementsByTagName("li");
            forEach(menuItems, function (it) {
                var a, id;

                a = it.getElementsByTagName("a")[0];
                if (!a) return;
                id = a.href.split("#")[1];
                it.className = (id == selectedURL) ? "sdoc" : "doc";
            });
        }

        initMasterElement();
        if (+ONEPAGE.scrollDuration) {
            initSmoothScrolling();
        }
        fixViewModeLink();
        topLink = document.getElementById("onepage_toplink");
        if (topLink) {
            on(window, "scroll", showOrHideTopLink);
            showOrHideTopLink("_immediate");
        }
        on(window, "scroll", adjustMenuClasses);
    }

    init();
}());
