var Comment = (function ($) {

    var _bind_events = function () {
        $(window.document).on("click", ".delete-comment", _delete_comment);
        $(window.document).on("click", ".edit-comment", _toggle_editor);
        $(window.document).on("click", ".cancel-edit-comment", _toggle_editor);
        $(window.document).on("submit", ".edit-comment-form", _save_comment);
        $(window.document).on("submit", ".comment-form", _submit_comment);
        $(window.document).on("keyup", ".edit-comment-form", _empty_save_disable);
        $(window.document).on("keyup", ".comment-form", _empty_save_disable);
    };

    var _delete_comment = function (e) {
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
        });
    };

    var _submit_comment = function (e) {
        e.preventDefault();

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
                Ticket.load_sidebar(response.ticket);
            }
        });
    };

    var _save_comment = function (e) {
        e.preventDefault();

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
            submit_button.prop("disabled", false);
        });
    };

    var _empty_save_disable = function (e) {
        var form = $(e.target).parents("form");
        var content = form.find(".editor-content");
        var submit_button = form.find(".button-submit");

        submit_button.prop("disabled", content.val() === "");
    };

    var _toggle_editor = function (e) {
        var comment = $(e.target).parents(".comment");
        var editor = comment.find(".editor");
        var content = comment.find(".comment-content");

        if (editor.hasClass("active")) {
            editor.removeClass("active");
            editor.find(".button-submit").prop("disabled", false);
            comment.find(".comment-content").show();
            comment.find(".comment-controls").show();
        } else {
            comment.find(".comment-controls").hide();
            content.hide();
            editor.addClass("active");
            editor.find(".editor-content").val(content.html());
        }
    };

    var initialize = function () {
        _bind_events();
    };

    return {
        initialize: initialize
    };

})(jQuery);

jQuery(document).ready(function () {
    Comment.initialize();
});