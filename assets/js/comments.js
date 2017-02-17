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

    };

    var _toggle_editor = function (e) {
        var comment = $(e.target).parents(".comment");
        comment.find(".editor").toggle();
        comment.find(".content").toggle();
    };

    var load_comments = function (id) {
        $.ajax({
            url: globals.ajaxUrl,
            dataType: "json",
            data: {
                action: "support_list_comments",
                id: id
            },
            success: function (response) {
                var pane = $("#" + id);
                pane.find(".comments").html(response.data);
                pane.find(".comment_reply").show();
            }
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

        $(window.document).on("click", "span.delete-comment", _delete_comment);
        $(window.document).on("click", "span.edit-comment", _toggle_editor);
        $(window.document).on("click", "button.cancel-edit-comment", _toggle_editor);
        $(window.document).on("submit", "form.comment-form", _submit_comment);


    };

    return {
        initialize: initialize,
        load_comments: load_comments
    };

})(Comments || {}, jQuery, window, Globals);