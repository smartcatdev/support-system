var Ticket = (function ($) {
    "use strict";

    var _bind_events = function () {
        $(document).on("click", ".open-ticket", _open_ticket);
        $(document).on("submit", ".create-ticket-form", _create_ticket);
        $(document).on("submit", ".ticket-status-form", _save_properties);
    };

    var _create_ticket = function (e) {

    };

    var _open_ticket = function (e) {
        var target = $(e.target);
        var id = target.data("id");

        if (!App.open_tab(id)) {
            target.prop("disabled", true);

            $.ajax({
                url: Globals.ajaxUrl,
                dataType: "json",
                data: {
                    id: id,
                    action: "support_load_ticket"
                },
                success: function (data) {
                    App.new_tab(data);
                    load_sidebar(data.id);
                    load_comments(data.id);
                    target.prop("disabled", false);
                }
            });
        }
    };

    var _save_properties = function (e) {
        e.preventDefault();

        var form = $(e.target);
        var sidebar = form.parents(".sidebar");
        var data = {
            url: Globals.ajaxUrl + "?action=support_update_ticket",
            dataType: "json",
            method: "post",
            success: function (response) {
                sidebar.removeClass("saving");
                load_sidebar(response.data);
                App.load_tickets();
            }
        };

        form.find(".button-submit").prop("disabled", true);
        sidebar.addClass("saving");

        form.submit({
            url: Globals.ajaxUrl,
            action: "support_update_ticket",
            method: "post",
            success: function (response) {
                load_sidebar(response.data);
                App.load_tickets();
            }
        }).done(function () {
            sidebar.removeClass("saving");
        });
    };

    var load_sidebar = function (id) {
        var sidebar = $("#" + id).find(".sidebar");

        if (!sidebar.hasClass("saving")) {
            $.ajax({
                url: Globals.ajaxUrl,
                dataType: "json",
                data: {
                    action: "support_ticket_sidebar",
                    id: id
                },
                success: function (response) {
                    var collapsed = [];

                    sidebar.find(".panel").each(function (index, element) {
                        var panel = $(element);

                        if (panel.find(".panel-collapse").attr("aria-expanded") === "false") {
                            collapsed.push(index);
                        }
                    });

                    sidebar.html(response.data);
                    sidebar.find(".panel").each(function (index, element) {
                        var panel = $(element);

                        if (collapsed.indexOf(index) !== -1) {
                            panel.find(".panel-collapse")
                                .removeClass("in")
                                .addClass("collapse")
                                .attr("aria-expanded", false);
                        }
                    });
                }
            });
        }
    };

    var load_comments = function (id) {
        var pane = $("#" + id);
        var comments = pane.find(".comments");

        if (comments.find(".editor.active").length === 0) {
            $.ajax({
                url: Globals.ajaxUrl,
                dataType: "json",
                data: {
                    action: "support_list_comments",
                    id: id
                },
                success: function (response) {
                    comments.html(response.data);
                }
            });
        }
    };

    var initialize = function () {
        _bind_events();

        setInterval(function () {
            $("div.pane").each(function (index, element) {
                var id = $(element).attr("id");

                if (!isNaN(id)) {
                    load_sidebar(id);
                    load_comments(id);
                }
            });
        }, 1000 * 30);
    };

    return {
        load_sidebar: load_sidebar,
        load_comments: load_comments,
        initialize: initialize
    };

})(jQuery);

jQuery(document).ready(function () {
    Ticket.initialize();
});