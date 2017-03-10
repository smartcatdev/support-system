var SupportAdmin = (function (module, $, window) {
    "use strict";

    var $wp_inline_edit;

    var _toggle_flag = function (e) {
        var flag = $(e.target);

        $.ajax({
            url: SupportSystem.ajax_url,
            method: "post",
            dataType: "json",
            data: {
                action: "support_toggle_flag",
                id: flag.data("id"),
                _ajax_nonce: SupportSystem.ajax_nonce
            },
            success: function (response) {
                var inline = $("#support_inline_" + flag.data("id")).children(".flagged");

                if (response.data === "on") {
                    flag.addClass("active");
                    inline.text("on");
                } else {
                    flag.removeClass("active");
                    inline.text("");
                }
            }
        });
    };

    var _bind_events = function () {
        $(window.document).on("click", ".flag-ticket", _toggle_flag);
    };

    var _initialize_quick_editor = function () {
        if (window.inlineEditPost !== undefined) {
            $wp_inline_edit = inlineEditPost.edit;

            inlineEditPost.edit = function (id) {
                $wp_inline_edit.apply(this, arguments);

                var $post_id = 0;

                if (typeof(id) === "object") {
                    $post_id = parseInt(this.getId(id));
                }

                if ($post_id > 0) {
                    $("#support_inline_" + $post_id).children().each(function (index, element) {
                        var data = $(element);
                        var field = $(".quick-edit-field." + data.attr("class"));

                        if (field.attr("type") === "checkbox") {
                            field.attr("checked", data.text() === "on");
                        } else {
                            field.val(data.text());
                        }
                    });
                }
            };
        }
    };

    var initialize = function () {
        _bind_events();
        _initialize_quick_editor();

        $("#id.manage-column").addClass("column-primary");

        $.wpMediaUploader({
            target: "#support_login_logo",
            buttonText: "Select image"
        });

        $(".color_picker").wpColorPicker();
    };

    return {
        initialize: initialize
    };

})(SupportAdmin || {}, jQuery, window);


jQuery(document).ready(function ($) {
    "use strict";

    SupportAdmin.initialize();

});
