var Comments = (function (module, $, window, globals) {
    "use strict";

    var _delete_comment = function (e) {
        var target = $(e.target);
        var id = target.data("id");

        $.ajax({
            url: globals.ajaxUrl,
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
        });
    };

    var _submit_comment = function (e) {
        e.preventDefault();

        var form = $(e.target);

        var data = {
            url: globals.ajaxUrl + "?action=support_submit_comment",
            dataType: "json",
            method: "post",
            data: form.serializeArray(),
            success: function (response) {
                form.parents().find(".comments").append(response.data);
                form.find("textarea").val("");
            },
            error: function (xhr, status, error) {
                form.append("<p class=\"error\">" + xhr.responseJSON.data + "</p>");
            }
        };

        form.find(".error").remove();

        $.ajax(data);
    };

    var _save_comment = function (e) {
        e.preventDefault();

        var target = $(e.target);
        var comment = target.parents(".comment");
        var form = target.parents(".edit-comment-form");
        var data = {
            url: globals.ajaxUrl + "?action=support_update_comment",
            dataType: "json",
            method: "post",
            success: function (response) {
                comment.replaceWith(response.data);
            },
            error: function (xhr, status, error) {

            }
        };

        data.data = form.serializeArray();

        $.ajax(data);
    };

    var _show_editor = function (e) {
        var comment = $(e.target).parents(".comment");
        var editor = comment.find(".editor");
        var content = comment.find(".comment-content");

        content.hide();
        editor.addClass("active");
        editor.find(".editor-content").val(content.text());

    };

    var _close_editor = function (e) {
        var comment = $(e.target).parents(".comment");
        var editor = comment.find(".editor");
        var content = comment.find(".comment-content");

        editor.removeClass("active");
        content.show();
    };

    var load_comments = function (id) {
        var pane = $("#" + id);
        var comments = pane.find(".comments");

        $.ajax({
            url: globals.ajaxUrl,
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
        }).done(function () {
            pane.find(".comment_reply").show();
        });
    };

    var initialize = function () {
        setInterval(function () {
            $("div.panel").each(function (index, element) {
                var id = $(element).attr("id");

                if (!isNaN(id)) {
                    load_comments(id);
                }
            });
        }, 1000 * 30);

        _bind_events();
    };

    var _bind_events = function () {
        $(window.document).on("click", "span.delete-comment", _delete_comment);
        $(window.document).on("click", "span.edit-comment", _show_editor);
        $(window.document).on("click", "button.cancel-edit-comment", _close_editor);
        $(window.document).on("click", "button.save-comment", _save_comment);
        $(window.document).on("submit", "form.comment-form", _submit_comment);
    };

    return {
        initialize: initialize,
        load_comments: load_comments
    };

})(Comments || {}, jQuery, window, Globals);

jQuery(document).ready(function ($) {
    "use strict";

    Comments.initialize();

});