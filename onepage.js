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
        var element, master, duration, start, delta, startOffset;

        function step(timestamp) {
            var progress, offset;

            if (!start) {
                start = timestamp;
            }
            progress = timestamp - start;
            offset = delta * Math.min(progress / duration, 1);
            master.scrollTop = startOffset + offset;
            if (progress < duration) {
                window.requestAnimationFrame(step);
            }
        }

        master = masterElement;
        duration = ONEPAGE.scrollDuration;
        element = document.getElementById(id);
        if (element) {
            if (typeof window.requestAnimationFrame !== "undefined") {
                startOffset = master.scrollTop;
                delta = element.offsetTop - startOffset;
                window.requestAnimationFrame(step);
            } else {
                master.scrollTop = element.offsetTop;
            }
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
                topLink.style.display = "";
            } else {
                topLink.style.display = "none";
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
