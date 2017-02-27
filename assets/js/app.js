var App = (function ($) {
    "use strict";

    var _tabs;
    var _filter;
    var _filter_toggle;
    var _filter_fields;
    var _tickets_container;

    var ajax_loader = _.template($("script.ajax-loader-mask").html());

    var _bind_events = function () {
        $(document).on("click", ".close-tab", _close_tab);
        $(document).on("click", "#filter-toggle", _toggle_filter);
        $(document).on("click", "#show-filters", _toggle_filter_display);
        $(document).on("click", "#refresh-tickets", load_tickets);
        $(document).on("click", ".registration-toggle", _toggle_registration);
        $(document).on("click", ".page", _page);
        $(document).on("keyup", "#search", _search);
        $(document).on("submit", "#registration-form", _register_user);
        $(document).on("change", ".filter-field", _filter_off);
    };

    var _search = _.debounce(function () {
        load_tickets();
    }, 250);

    var _page = function (e) {
        sessionStorage.setItem("page", $(e.target).data("id"));
        load_tickets();
    };

    var _close_tab = function (e) {
        var tab = $(e.target).closest("li");
        var prev =  tab.prev().find("a");
        var next = tab.next();

        $("#" + tab.data("id")).remove();
        tab.remove();

        if (next.length === 0) {
            prev.tab("show");
        }
    };

    var new_tab = function (data) {
        var li = $("<li class=\"tab\" data-id=\"" + data.id + "\">" +
                        "<a href=\"#" + data.id + "\" data-toggle=\"tab\">" +
                            "<span class=\"title\">" + data.title + "</span>" +
                            "<span class=\"close close-tab\">&times;</span>" +
                        "</a>" +
                    "</li>");
        var panel = $("<div id=\"" + data.id + "\" class=\"tab-pane fade\">" + data.content + "</div>");

        _tabs.find(".nav").append(li);
        _tabs.find(".tab-content").append(panel);

        li.find("a").tab("show");
    };

    var open_tab = function (id) {
        var tab = _find_tab(id);

        if (tab.length > 0) {
            tab.find("a").tab("show");
        }

        return tab.length > 0;
    };

    var _find_tab = function (id) {
        return $("li.tab").filter(function () {
            return $(this).data("id") === id;
        });
    };

    var load_tickets = function () {
        var refresh = $("#refresh-tickets").find(".refresh");
        var filter_controls = $("#filter-controls");
        var page = sessionStorage.getItem("page");
        var request = {
            url: Globals.ajax_url + "?action=support_list_tickets",
            dataType: "json",
            data: [{
                name: "_ajax_nonce",
                value: Globals.ajax_nonce
            }, {
                name: "page",
                value: page === undefined ? "1" : page
            }, {
                name: "search",
                value: $("#search").val()
            }],
            success: function (response) {
                _tickets_container.html(response.data);

                if (filter_controls.css("display") === "none") {
                    filter_controls.slideToggle();
                    _tickets_container.hide().fadeToggle();
                }
            },
            complete: function () {
                refresh.removeClass("rotate");
            }
        };

        if (_filter_toggle.hasClass("active")) {
            request.data = $.merge(request.data, _filter.serializeArray());
        }

        refresh.addClass("rotate");
        $.ajax(request);
    };

    var _register_user = function (e) {
        e.preventDefault();

        var form = $(e.target);
        var submit = $("#registration-submit");

        submit.prop("disabled", true);

        form.submit({
            url: Globals.ajax_url,
            action: "support_register_user",
            method: "post",
            extras: {
                _ajax_nonce: Globals.ajax_nonce
            },
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
                var field = $(element);

                field.val(field.data("default"));
            });
        }

        load_tickets();
    };

    var _toggle_filter_display = function (e) {
        $("#filters").slideToggle();
    };

    var _toggle_registration = function () {
        $("#login").toggle();
        $("#register").toggle();
    };

    var _time = function () {
        var time = $("#time");

    };

    var initialize = function () {
        _tabs = $("#tabs");
        _filter = $("#ticket_filter");
        _filter_toggle = $("#filter-toggle");
        _filter_fields = _filter.find(".filter-field");
        _tickets_container = $("#tickets-container");

        ajax_loader = _.template($("script.ajax-loader-mask").html());
        _tickets_container.html(ajax_loader(Globals.strings.loading_tickets));

        $(".login-submit").prepend($("#show-registration")).addClass("text-center");

        _time();
        _bind_events();
        load_tickets();
        setInterval(load_tickets, 1000 * 60);
    };

    return {
        load_tickets: load_tickets,
        initialize: initialize,
        new_tab: new_tab,
        open_tab: open_tab,
        ajax_loader: ajax_loader
    };

})(jQuery);

jQuery(document).ready(function () {
    App.initialize();
});
