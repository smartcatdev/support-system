var App = (function ($) {
    "use strict";

    var _tabs;
    var _filter;
    var _filter_toggle;
    var _filter_fields;
    var _tickets_container;
    var _list;

    var _bind_events = function () {
        $(document).on("click", ".close-tab", _close_tab);
        $(document).on("click", "#filter-toggle", _toggle_filter);
        $(document).on("click", "#refresh-tickets", load_tickets);
        $(document).on("click", ".registration-toggle", _toggle_registration);
        $(document).on("submit", "#registration-form", _register_user);
        $(document).on("change", ".filter-field", _filter_off);
    };

    var _close_tab = function (e) {
        var tab = $(e.target).closest("li").remove().attr("aria-controls");
        $("#" + tab).remove();
        _tabs.tabs("refresh");
    };

    var new_tab = function (data) {
        var li = $("<li><a href=\"#" + data.id + "\">" + data.title + "</a><span class=\"ui-icon-close close-tab icon-cross\"></span></li>");
        var panel = $("<div id=\"" + data.id + "\" class=\"pane\"></div>");

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

    var _find_tab = function (id) {
        var tab = false;

        _tabs.find("li").each(function (index, element) {
            if ($(element).data("id") === id) {
                tab = index;
            }
        });

        return tab;
    };

    var load_tickets = function (e) {
        var refresh = $(".refresh");
        var data = {
            url: Globals.ajaxUrl + "?action=support_list_tickets",
            dataType: "json",
            success: _init_list
        };

        refresh.addClass("rotate");

        if (_filter_toggle.hasClass("active")) {
            data.data = _filter.serializeArray();
        }

        $.ajax(data).done(function () {
            refresh.removeClass("rotate");
        });
    };

    var _init_list = function (data) {
        _tickets_container.html(data.data);

        _list = $("#tickets-list");

        var cols = [];

        _list.find("th").each(function (index, element) {
            cols.push({
                data: $(element).data("column_name")
            });
        });

        _list.dataTable({
            columns: cols,
            saveState: true
        });
    };

    var _register_user = function (e) {
        e.preventDefault();

        var form = $(e.target);
        var submit = $("#registration-submit");

        submit.prop("disabled", true);

        form.submit({
            url: Globals.ajaxUrl,
            action: "support_register_user",
            method: "post",
            success: function (response) {
                window.location.reload();
            },
            complete: function () {
                submit.prop("disabled", false);
            }
        });
    };

    var _filter_off = function () {
        _filter_toggle.removeClass("active");
    };

    var _toggle_filter = function () {
        _filter_toggle.toggleClass("active");

        if (!_filter_toggle.hasClass("active")) {
            _filter_fields.each(function (index, element) {
                $(element).val("");
            });
        }

        load_tickets();
    };

    var _toggle_registration = function () {
        $("#login").toggle();
        $("#register").toggle();
    };

    var initialize = function () {
        _tabs = $("#tabs").tabs();
        _filter = $("#ticket_filter");
        _filter_toggle = $("#filter-toggle");
        _filter_fields = _filter.find(".filter-field");
        _tickets_container = $("#tickets-container");

        $(".login-submit").prepend($("#show-registration")).addClass("text-center");

        _bind_events();
        load_tickets();
        setInterval(load_tickets, 1000 * 60);
    };

    return {
        load_tickets: load_tickets,
        initialize: initialize,
        new_tab: new_tab,
        open_tab: open_tab
    };

})(jQuery);

jQuery(document).ready(function () {
    App.initialize();
});
