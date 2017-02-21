var Ticket = (function ($) {
    "use strict";

    var _bind_events = function () {
        $(document).on("click", ".open-ticket", _open_ticket);
        $(document).on("click", "#create-ticket", _create_ticket);
        $(document).on("submit", ".ticket-status-form", _save_properties);
    };

    var _create_ticket = function (e) {
        var form = $("#create-ticket-form");
        var submit = $(e.target);

        submit.prop("disabled", true);

        form.submit({
            url: Globals.ajax_url,
            action: "support_create_ticket",
            extras: {
                _ajax_nonce: Globals.ajax_nonce
            },
            success: function (response) {
                $("#create-modal").modal('toggle');

                form.find(".form-control").each(function (index, element) {
                    $(element).val("");
                });

                App.load_tickets();
            },
            complete: function () {
                submit.prop("disabled", false);
            }
        });
    };

    var _open_ticket = function (e) {
        var target = $(e.target);
        var id = target.data("id");

        if (!App.open_tab(id)) {
            target.prop("disabled", true);

            $.ajax({
                url: Globals.ajax_url,
                dataType: "json",
                data: {
                    id: id,
                    action: "support_load_ticket",
                    _ajax_nonce: Globals.ajax_nonce
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

        form.find(".button-submit").prop("disabled", true);
        sidebar.addClass("saving");

        form.submit({
            url: Globals.ajax_url,
            action: "support_update_ticket",
            method: "post",
            extras: {
              _ajax_nonce: Globals.ajax_nonce
            },
            success: function (response) {
                var message = $("<div style=\"border-radius: 0; margin: 0\" class=\"alert alert-success fade in\">" +
                                    "<a href=\"#\" class=\"close\" data-dismiss=\"alert\">×</a>" +
                                    response.data +
                                "</div>");

                sidebar.find(".message").html(message);

                load_sidebar(response.ticket_id);
                App.load_tickets();
            },
            complete: function (xhr) {

                sidebar.removeClass("saving");
                form.find(".button-submit").prop("disabled", false);
            }
        });
    };

    var load_sidebar = function (id) {
        var sidebar = $("#" + id).find(".sidebar");

        if (!sidebar.hasClass("saving")) {
            $.ajax({
                url: Globals.ajax_url,
                dataType: "json",
                data: {
                    action: "support_ticket_sidebar",
                    id: id,
                    _ajax_nonce: Globals.ajax_nonce
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
                url: Globals.ajax_url,
                dataType: "json",
                data: {
                    action: "support_list_comments",
                    id: id,
                    _ajax_nonce: Globals.ajax_nonce
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