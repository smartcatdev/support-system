var Comment = (function ($) {

    var _warning = _.template($("script.notice-comment-deleted").html());

    var _bind_events = function () {
        $(window.document).on("click", ".delete-comment", _delete_comment);
        $(window.document).on("click", ".edit-comment", _toggle_editor);
        $(window.document).on("click", ".cancel-edit-comment", _toggle_editor);
        $(window.document).on("click", ".undo-delete-comment", _undo_delete);
        $(window.document).on("submit", ".edit-comment-form", _save_comment);
        $(window.document).on("keyup", ".edit-comment-form", _empty_save_disable);
        $(window.document).on("keyup", ".comment-form", _empty_save_disable);
    };

    var _undo_delete = function (e) {
        e.preventDefault();

        var target = $(e.target).parents(".alert").hide();

        target.parents(".wrapper")
            .find(".comment")
            .data("delete", false)
            .fadeToggle()
            .find(".delete-comment")
            .prop("disabled", false);
    };

    var _delete_comment = function (e) {
        var target = $(e.target).prop("disabled", true);
        var id = target.data("id");
        var comment = $("#comment-" + id);

        comment.fadeToggle("slow", function () {
            comment.parents(".wrapper").append(_warning());
        });

        comment.data("delete", true);

        setTimeout(function () {

            if (comment.data("delete")) {
                $.ajax({
                    url: Globals.ajax_url,
                    dataType: "json",
                    data: {
                        action: "support_delete_comment",
                        comment_id: id,
                        _ajax_nonce: Globals.ajax_nonce
                    },
                    success: function (response) {
                        comment.parents(".wrapper").fadeToggle("slow", function () {
                            $(this).remove();
                        });
                    }
                });
            }

        }, 1000 * 15);

    };

    var _save_comment = function (e) {
        e.preventDefault();

        var form = $(e.target);
        var comment = form.parents(".comment");
        var submit_button = form.find(".button-submit");
        var data = form.serializeArray();

        submit_button.prop("disabled", true);
        data.push({ name: "_ajax_nonce", value:  Globals.ajax_nonce });

        $.ajax({
            url: Globals.ajax_url + "?action=support_update_comment",
            dataType: "json",
            method: "post",
            data: data,
            success: function (response) {
                comment.replaceWith(response.data);
            },
            complete: function () {
                submit_button.prop("disabled", false);
            }
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
            comment.find(".comment-content").show();
            editor.find(".button-submit").prop("disabled", false);
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