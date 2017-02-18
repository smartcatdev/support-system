var App = (function (module, $, window) {
    "use strict";

    var _tabs;

    var _bind_events = function () {
        $(window.document).on("click", "span.close-tab", _close_tab);
        $(".registration-toggle").click(_toggle_registration);
    };

    var _find_tab = function (id) {
        var tab = false;

        _tabs.find("li").each(function (index, element) {
            if ($(element).data("id") === id) {
                tab = index;
            }
        });

        return tab;
    };

    var _close_tab = function (e) {
        var tab = $(e.target).closest("li").remove().attr("aria-controls");

        $("#" + tab).remove();
        _tabs.tabs("refresh");
    };

    var new_tab = function (data) {
        var li = $("<li><a href=\"#" + data.id + "\">" + data.title + "</a><span class=\"ui-icon-close close-tab icon-cross\"></span></li>");
        var panel = $($.parseHTML("<div id=\"" + data.id + "\" class=\"pane\"></div>"));

        li.data("id", data.id);
        panel.html(data.content);

        _tabs.find("ul").append(li);
        _tabs.append(panel);

        _tabs.tabs("refresh");
        _tabs.tabs("option", "active", li.index());
    };

    var open_tab = function (tab) {
        var index = _find_tab(tab);

        if (index !== false) {
            _tabs.tabs("option", "active", index);
            _tabs.tabs("refresh");
        }

        return index !== false;
    };

    var _register_user = function () {

    };

    var _toggle_registration = function () {
        $("#login").toggle();
        $("#register").toggle();
    };

    var _add_registration_toggle = function () {
        $("p.login-submit").prepend(
            "<button class=\"button button-primary registration-toggle\" type=\"button\">" +
                Globals.strings.register_form_toggle +
            "</button>"
        );
    };

    var form_errors = function (form) {

    };

    var initialize = function () {
        _tabs = $("#tabs");
        _tabs.tabs();

        if ($("#register_form")) {
            _add_registration_toggle();
        }

        _bind_events();
    };

    return {
        initialize: initialize,
        open_tab: open_tab,
        new_tab: new_tab,
        form_errors: form_errors
    };

})(App || {}, jQuery, window);

jQuery(document).ready(function ($) {
    "use strict";

    App.initialize();

});





