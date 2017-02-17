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

    var _save_comment = function (e) {

    };

    var _edit_comment = function (e) {

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

        $("body").on("click", ".delete-comment", _delete_comment);

    };

    return {
        initialize: initialize,
        load_comments: load_comments
    };

})(Comments || {}, jQuery, window, Globals);