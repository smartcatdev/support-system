var Tickets = (function (module, $, window, globals, app) {
    "use strict";

    var container;
    var list;
    var filter;
    var filter_toggle;

    var create_ticket = function (e) {

    };

    var open_ticket = function (e) {
        $.ajax({
            url: globals.ajaxUrl + "?action=support_open_ticket",
            dataType: "json",
            data: {
                id: $(e.target).data("id")
            },
            success: app.open_tab
        });
    };

    var filter_off = function () {
        filter_toggle.removeClass("active");
    };

    var toggle_filter = function () {
        filter_toggle.toggleClass("active");

        if (!filter_toggle.hasClass("active")) {
            filter.children().each(function (index, element) {
                $(element).val("");
            });
        }

        load_tickets();
    };

    var init_list = function (data) {
        container.html(data.data);

        list = $("#tickets-list");

        var cols = [];

        list.find("th").each(function (index, element) {
            cols.push({
                data: $(element).data("column_name")
            });
        });

        list.DataTable({
            columns: cols
        });
    };

    var load_tickets = function () {
        var refresh = $(".refresh");
        var data = {
            url: globals.ajaxUrl + "?action=support_list_tickets",
            dataType: "json",
            success: init_list
        };

        refresh.addClass("rotate");

        if (filter_toggle.hasClass("active")) {
            data.data = filter.serializeArray();
        }

        $.ajax(data).done(function () {
            refresh.removeClass("rotate");
        });
    };

    var initialize = function () {
        container = $("#tickets-container");
        filter_toggle = $("#filter-toggle");
        filter = $("#ticket_filter");

        load_tickets();
        setInterval(load_tickets, 1000 * 60);

        filter_toggle.click(toggle_filter);
        filter.children().change(filter_off);
        $("#refresh-tickets").click(load_tickets);
        $("body").on("click", "button.open-ticket", open_ticket);
    };

    return {
        load_tickets: load_tickets,
        initialize: initialize
    };

})(Tickets || {}, jQuery, window, Globals, App);
