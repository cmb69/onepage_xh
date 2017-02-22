/*!
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

(function () {
    "use strict";

    /**
     * The master element, i.e. the documentElement or the body.
     */
    var masterElement;

    function getElementsByClassName(className) {
        if (typeof document.getElementsByClassName !== "undefined") {
            return document.getElementsByClassName(className);
        } else if (typeof document.querySelectorAll !== "undefined") {
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
    function map(items, func) {
        var i, length, result;

        result = [];
        for (i = 0, length = items.length; i < length; i++) {
            result.push(func(items[i]));
        }
        return result;
    }

    function filter(items, func) {
        var i, length, result;

        result = [];
        for (i = 0, length = items.length; i < length; i++) {
            if (func(items[i])) {
                result.push(items[i]);
            }
        }
        return result;
    }

    function reduce(items, init, func) {
        var i, length;

        for (i = 0, length = items.length; i < length; i++) {
            init = func(init, items[i]);
        }
        return init;
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
        element = document.getElementById(id);
        endOffset = element ? getElementTop(element) : 0;
        if (typeof window.requestAnimationFrame !== "undefined") {
            oldOffset = startOffset = master.scrollTop;
            delta = endOffset - startOffset;
            duration = ONEPAGE.scrollDuration * Math.abs(delta) / master.scrollHeight;
            if (duration) {
                window.requestAnimationFrame(step);
            }
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
        anchors = filter(document.getElementsByTagName("a"), function (anchor) {
            return anchor.href.split("#")[0] === url;
        });
        map(anchors, function (anchor) {
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
            if (typeof suffix !== "string") {
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
            var selectedId, menu, menuItems;

            function getSelectedId() {
                var pages, page;

                pages = getElementsByClassName("onepage_page");
                page = reduce(pages, pages.length ? pages[0] : null, function (page1, page2) {
                        return masterElement.scrollTop >= getElementTop(page2) ? page2 : page1;
                });
                if (page) {
                    return encodeURIComponent(page.id);
                }
            }

            selectedId = getSelectedId();
            menu = document.getElementById("onepage_menu");
            menuItems = menu.getElementsByTagName("li");
            map(menuItems, function (it) {
                var anchor, id;

                anchor = it.getElementsByTagName("a")[0];
                id = anchor.href.split("#")[1];
                it.className = id === selectedId ? "sdoc" : "doc";
            });
        }

        initMasterElement();
        fixViewModeLink();
        if (ONEPAGE.isOnepage) {
            if (+ONEPAGE.scrollDuration) {
                initSmoothScrolling();
            }
            topLink = document.getElementById("onepage_toplink");
            if (topLink) {
                on(window, "scroll", showOrHideTopLink);
                showOrHideTopLink("_immediate");
            }
            on(window, "scroll", adjustMenuClasses);
        }
    }

    init();
}());
