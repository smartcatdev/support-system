var Ticket = (function ($) {
    "use strict";

    var _bind_events = function () {
        $(document).on("click", ".open-ticket", _open_ticket);
        $(document).on("click", ".close-ticket", _close_ticket);
        $(document).on("click", "#create-ticket", _create_ticket);
        $(document).on("submit", ".comment-form", _submit_comment);
        $(document).on("submit", ".ticket-status-form", _save_properties);
        $(document).on("click", ".flagged", _toggle_flag);
        $(document).on("show.bs.modal", ".attachment-modal", _init_media_dropzone);
        $(document).on("hidden.bs.modal", ".attachment-modal", _reset_media_dropzone);
        $(document).on("click", ".delete-attachment", _delete_attachment);
        $(document).on("focus", ".property-control", _lock_properties);
        $(document).on("focusout", ".property-control", _lock_properties);
    };

    var _lock_properties = function(e) {
        $(e.target).parents('.sidebar').toggleClass('locked');
    };

    var _delete_attachment = function (e) {

        $('body').confirm({
            id: 'delete_attachment',
            okay_text: Globals.strings.yes,
            cancel_text: Globals.strings.cancel,
            title: Globals.strings.delete_attachment,
            content: Globals.strings.warning_permanent

        }, function (val) {

            if(val) {
                var target = $(e.target);

                $.ajax({
                    url: Globals.ajax_url,
                    data: {
                        action: "support_delete_media",
                        _ajax_nonce: Globals.ajax_nonce,
                        attachment_id: target.data("attachment_id")
                    },
                    success: function () {
                        Ticket.load_sidebar(target.data("ticket_id"));
                    }
                });
            }

        });
    };

    var _reset_media_dropzone = function(e) {
        var ticket = $(e.target).data("ticket_id");
        var dropzone = Dropzone.forElement("#attachment-dropzone-" + ticket);

        dropzone.reset();
        dropzone.destroy();

        load_sidebar(ticket);
    };

    var _init_media_dropzone = function (e) {
         $(e.target).find('.dropzone').dropzone({
            addRemoveLinks: true,
            url: Globals.ajax_url + "?action=support_upload_media",

            init: function () {
                this.doingReset = false;

                this.on("success", function(file, res) {
                    file.id = res.data.id;
                });

                this.on("removedfile", function (file) {
                    if (!this.doingReset) {
                        $.ajax({
                            url: Globals.ajax_url,
                            dataType: "json",
                            data: {
                                action: "support_delete_media",
                                _ajax_nonce: Globals.ajax_nonce,
                                attachment_id: file.id
                            }
                        });
                    }
                });

                this.reset = function () {
                    this.doingReset = true;
                    this.removeAllFiles();
                    this.doingReset = false;
                };
            }
        });
    };

    var _toggle_flag = function (e) {
        var flag = $(e.target);

        $.ajax({
            url: Globals.ajax_url,
            method: "post",
            dataType: "json",
            data: {
                action: "support_toggle_flag",
                id: flag.data("id"),
                _ajax_nonce: Globals.ajax_nonce
            },
            success: function (response) {
                if (response.data === "on") {
                    flag.addClass("active");
                } else {
                    flag.removeClass("active");
                }
            }
        });
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
            success: function () {
                $("#create-modal").modal("toggle");

                Dropzone.forElement("#ticket-media-upload").reset();

                form.find(".form-control").each(function (index, element) {
                    var field = $(element);

                    if(typeof(field.data("default")) === "string") {
                        field.val(field.data("default"));
                    } else {
                        field.val(JSON.stringify(field.data("default")));
                    }
                });

                App.load_tickets();
            },
            complete: function () {
                submit.prop("disabled", false);
            }
        });
    };

    var _close_ticket = function (e) {
        var close_button = $(e.target);
        var id = close_button.data('ticket_id');

        $('body').confirm({
            id: 'delete_attachment',
            okay_text: Globals.strings.yes,
            cancel_text: Globals.strings.cancel,
            title: Globals.strings.close_ticket,
            content: Globals.strings.warning_permanent

        }, function (val) {

            if (val) {
                $.post({
                    url: Globals.ajax_url,
                    dataType: 'json',
                    data: {
                        _ajax_nonce: Globals.ajax_nonce,
                        action: 'support_close_ticket',
                        id: id
                    },
                    success: function () {
                        load_sidebar(id);
                        close_button.remove();
                    }
                });
            }

        });
    };

    var _open_ticket = function (e) {
        var target = $(e.target);
        var id = target.data("id");
        var statistics = $("#statistics-container");

        if(statistics.length > 0) {
            $('html, body').animate({
                scrollTop: statistics.offset().top
            }, 200 );
        }

        if (!App.open_tab(id)) {
            target.prop("disabled", true);

            $.ajax({
                url: Globals.ajax_url + "?use_support_media",
                dataType: "json",
                data: {
                    id: id,
                    action: "support_load_ticket",
                    _ajax_nonce: Globals.ajax_nonce
                },
                success: function (data) {
                    App.new_tab(data);

                    $("#" + id).find(".loader-mask").html(App.ajax_loader(Globals.strings.loading_generic));

                    load_sidebar(data.id);
                    load_comments(data.id);
                },
                complete: function () {
                    target.prop("disabled", false);
                    
                    setTimeout(function () {
                        var ticket = $("#" + id);
                        
                        ticket.find(".ticket-detail").fadeToggle();
                        ticket.find(".loader-mask").hide();
                        
                    }, 500 );
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
                var message = _.template($("script.notice-inline").html());

                sidebar.find(".message").html(message(response.data));
                sidebar.removeClass("saving");

                load_sidebar(response.ticket_id);
                App.load_tickets();
                App.load_statistics();
            },
            complete: function (xhr) {
                sidebar.removeClass("saving");
                form.find(".button-submit").prop("disabled", false);
            }
        });
    };

    var load_sidebar = function (id) {
        var sidebar = $("#" + id).find(".sidebar");

        if (!sidebar.hasClass('saving') && !sidebar.hasClass('locked')) {
            $.ajax({
                url: Globals.ajax_url,
                dataType: "json",
                data: {
                    id: id,
                    action: "support_ticket_sidebar",
                    _ajax_nonce: Globals.ajax_nonce
                },
                success: function (response) {
                    var collapsed = [];
                    var message = sidebar.find(".message");

                    sidebar.find(".panel").each(function (index, element) {
                        var panel = $(element);

                        if (panel.find(".panel-collapse").attr("aria-expanded") === "false") {
                            collapsed.push(panel.data("id"));
                        }
                    });

                    sidebar.html(response.data);
                    sidebar.find(".message").html(message);

                    sidebar.find(".gallery").lightGallery({
                        selector: '.image'
                    });

                    sidebar.find(".panel").each(function (index, element) {
                        var panel = $(element);

                        if (collapsed.indexOf(panel.data("id")) !== -1) {
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

        if (comments.find(".locked").length === 0) {
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

    var _submit_comment = function (e) {
        e.preventDefault();

        var form = $(e.target);
        var tabs = form.parents('.panel').find('.nav-tabs');
        var comments = form.parents(".discussion-area").find(".comments");
        var content = form.find(".editor-content");
        var submit_button = form.find(".button-submit");
        var data = form.serializeArray();

        submit_button.prop("disabled", true);
        data.push({ name: "_ajax_nonce", value:  Globals.ajax_nonce });

        $.ajax({
            url: Globals.ajax_url + "?action=support_submit_comment",
            dataType: "json",
            method: "post",
            data: data,
            success: function (response) {
                var comment = $(response.data);

                comment.hide();
                comments.append(comment);
                comment.fadeToggle();
                content.val("");

                App.close_preview(tabs);

                load_sidebar(response.ticket);
                App.load_statistics();
            },
            complete: function () {
                submit_button.prop("disabled", false);
            }
        });
    };

    var initialize = function () {
        _bind_events();

        var looper = function(callback) {
            return function () {
                $("div.tab-pane").each(function (index, element) {
                    var id = $(element).attr("id");

                    if (!isNaN(id)) {
                        callback(id);
                    }
                });
            };
        };

        setInterval(looper(load_comments), 1000 * Globals.refresh_interval);
        //setInterval(looper(load_sidebar), 1000 * Globals.refresh_interval);
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