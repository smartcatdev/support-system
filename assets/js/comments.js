var Comments = (function (module, $, window) {
    "use strict";

    var _doing_ajax = false;

    var _bind_events = function () {
        $(window.document).on("click", "button.delete-comment", _delete_comment);
        $(window.document).on("click", "button.edit-comment", _show_editor);
        $(window.document).on("click", "button.cancel-edit-comment", _close_editor);
        $(window.document).on("submit", "form.edit-comment-form", _save_comment);
        $(window.document).on("submit", "form.comment-form", _submit_comment);
        $(window.document).on("keyup", "form.edit-comment-form", _empty_save_disable);
        $(window.document).on("keyup", "form.comment-form", _empty_save_disable);
    };

    var _unlock = function () {
      _doing_ajax = false;
    };

    var _lock = function () {
      _doing_ajax = true;
    };

    var locked = function () {
        return _doing_ajax;
    };

    var _delete_comment = function (e) {
        if (!locked()) {
            _lock();

            var target = $(e.target);
            var id = target.data("id");

            target.prop("disabled", true);

            $.ajax({
                url: Globals.ajaxUrl,
                dataType: "json",
                data: {
                    action: "support_delete_comment",
                    comment_id: id
                },
                success: function (response) {
                    if (response.success) {
                        target.parents("#comment-" + id).remove();
                    }
                }
            }).done(_unlock);
        }
    };

    var _submit_comment = function (e) {
        e.preventDefault();

        if (!locked()) {
            _lock();

            var form = $(e.target);
            var content = form.find(".editor-content");
            var submit_button = form.find(".button-submit");

            submit_button.prop("disabled", true);

            $.ajax({
                url: Globals.ajaxUrl + "?action=support_submit_comment",
                dataType: "json",
                method: "post",
                data: form.serializeArray(),
                success: function (response) {
                    form.parents().find(".comments").append(response.data);
                    content.val("");
                    Tickets.load_sidebar(response.ticket);
                }
            }).done(_unlock);
        }
    };

    var _save_comment = function (e) {
        e.preventDefault();

        if (!locked()) {
            _lock();

            var form = $(e.target);
            var comment = form.parents(".comment");
            var submit_button = form.find(".button-submit");

            submit_button.prop("disabled", true);

            $.ajax({
                url: Globals.ajaxUrl + "?action=support_update_comment",
                dataType: "json",
                method: "post",
                data: form.serializeArray(),
                success: function (response) {
                    comment.replaceWith(response.data);
                }
            }).done(function () {
                _unlock();
                submit_button.prop("disabled", false);
            });
        }
    };

    var _empty_save_disable = function (e) {
        var form = $(e.target).parents("form");
        var content = form.find(".editor-content");
        var submit_button = form.find(".button-submit");

        submit_button.prop("disabled", content.val() === "");
    };

    var _show_editor = function (e) {
        var comment = $(e.target).parents(".comment");
        var editor = comment.find(".editor");
        var content = comment.find(".comment-content");

        content.hide();
        editor.addClass("active");
        editor.find(".editor-content").val(content.html());
    };

    var _close_editor = function (e) {
        var comment = $(e.target).parents(".comment");
        var editor = comment.find(".editor");

        editor.removeClass("active");
        editor.find(".button-submit").prop("disabled", false);
        comment.find(".comment-content").show();
    };

    var load_comments = function (id) {
        if (!locked()) {
            var pane = $("#" + id);
            var comments = pane.find(".comments");

            $.ajax({
                url: Globals.ajaxUrl,
                dataType: "json",
                data: {
                    action: "support_list_comments",
                    id: id
                },
                success: function (response) {

                    $.each(response.data, function (index, new_comment) {
                        var old_comment = comments.find("#comment-" + index);

                        if (old_comment.length) {
                            if (!$(old_comment.find("editor").hasClass("active"))) {
                                old_comment.replaceWith(new_comment);
                            }
                        } else {
                            comments.append(new_comment);
                        }
                    });

                }
            });
        }
    };

    var initialize = function () {
        window.setInterval(function () {
            $("div.pane").each(function (index, element) {

                var id = $(element).attr("id");
                if (!isNaN(id)) {
                    load_comments(id);
                }
            });
        }, 1000 * 30);

        _bind_events();
    };

    return {
        initialize: initialize,
        load_comments: load_comments,
        locked: locked
    };

})(Comments || {}, jQuery, window);

jQuery(document).ready(function ($) {
    "use strict";

    Comments.initialize();

});