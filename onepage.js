/*global ONEPAGE */

(function () {
    "use strict";

    /**
     * The master element, i.e. the documentElement or the body.
     */
    var masterElement;

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

    /**
     * Scrolls to an element with a certain ID.
     *
     * @param   {string}    id
     * @returns {undefined}
     */
    function scrollToId(id) {
        var element, master, duration, start, delta, startOffset, endOffset;

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
            var progress, offset, percentage;

            if (!start) {
                start = timestamp;
            }
            progress = timestamp - start;
            percentage = Math.min(progress / duration, 1);
            percentage = ease(percentage);
            offset = percentage * delta;
            master.scrollTop = startOffset + offset;
            if (progress < duration) {
                window.requestAnimationFrame(step);
            }
        }

        master = masterElement;
        duration = ONEPAGE.scrollDuration;
        element = document.getElementById(id);
        endOffset = element ? element.offsetTop : 0;
        if (typeof window.requestAnimationFrame !== "undefined") {
            startOffset = master.scrollTop;
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
        scrollToId(id);
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

                });
            }
        });
    }

    /**
     * Initializes the onepage plugin.
     *
     * @returns {undefined}
     */
    function init() {
        var topLink;

        function showOrHideTopLink() {
            if (masterElement.scrollTop > 300) {
                topLink.className = "onepage_show";
            } else {
                topLink.className = "onepage_hide";
            }
        }

        initMasterElement();
        if (+ONEPAGE.scrollDuration) {
            initSmoothScrolling();
        }
        topLink = document.getElementById("onepage_toplink");
        if (topLink) {
            on(window, "scroll", showOrHideTopLink);
            showOrHideTopLink();
        }
    }

    init();
}());
