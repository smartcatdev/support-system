var Tickets = (function (module, $, window, globals, app, comments) {
    "use strict";

    var _container;
    var _list;
    var _filter;
    var _filter_toggle;

    var _quick_edit_save = function (e) {

    };

    var _create_ticket = function (e) {

    };

    var _open_ticket = function (e) {
        var id = $(e.target).data("id");

        $.ajax({
            url: globals.ajaxUrl,
            dataType: "json",
            data: {
                id: id,
                action: "support_open_ticket"
            },
            success: function (data) {
                app.open_tab(data);
                comments.load_comments(id);
            }
        });
    };

    var _filter_off = function () {
        _filter_toggle.removeClass("active");
    };

    var _toggle_filter = function () {
        _filter_toggle.toggleClass("active");

        if (!_filter_toggle.hasClass("active")) {
            _filter.children().each(function (index, element) {
                $(element).val("");
            });
        }

        load_tickets();
    };

    var _init_list = function (data) {
        _container.html(data.data);

        _list = $("#tickets-list");

        var cols = [];

        _list.find("th").each(function (index, element) {
            cols.push({
                data: $(element).data("column_name")
            });
        });

        _list.DataTable({
            columns: cols
        });
    };

    var load_tickets = function () {
        var refresh = $(".refresh");
        var data = {
            url: globals.ajaxUrl + "?action=support_list_tickets",
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

    var initialize = function () {
        _container = $("#tickets-container");
        _filter_toggle = $("#filter-toggle");
        _filter = $("#ticket_filter");

        load_tickets();
        setInterval(load_tickets, 1000 * 60);

        _bind_events();
    };

    var _bind_events = function () {
        _filter_toggle.click(_toggle_filter);
        _filter.children().change(_filter_off);
        $("#refresh-tickets").click(load_tickets);
        $(window.document).on("click", "button.open-ticket", _open_ticket);
    };

    return {
        load_tickets: load_tickets,
        initialize: initialize
    };

})(Tickets || {}, jQuery, window, Globals, App, Comments);

jQuery(document).ready(function ($) {
    "use strict";

    Tickets.initialize();

});