var SupportAdmin = (function (module, $, window) {
    "use strict";

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

    var initialize = function () {
        _bind_events();

        $("#id.manage-column").addClass("column-primary");

        $.ucareMediaUploader({
            target: ".image-upload",
            buttonText: "Select image"
        });

        // $(".color_picker").wpColorPicker();
        
        $('#uc-settings_menu_page select').selectize({
            searchField : ['this']
        });

    };

    return {
        initialize: initialize
    };

})(SupportAdmin || {}, jQuery, window);


jQuery(document).ready(function ($) {
    "use strict";

    SupportAdmin.initialize();

});
