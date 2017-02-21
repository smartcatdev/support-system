var Settings = (function ($) {
    "use strict";

    var _bind_events = function () {
        $(document).on("keyup", "#confirm-password", _validate_password);
        $(document).on("submit", "#settings-form", _save_settings);
    };

    var _validate_password = function (e) {
        var confirm_password = $(e.target);
        var new_password = $("#new-password");
        var submit = $("#settings-form").find(".button-submit");
        var group = confirm_password.parents(".form-group");

        group.removeClass("has-error");
        submit.prop("disabled", false);

        if (new_password.val() !== confirm_password.val()) {
            group.addClass("has-error");
            submit.prop("disabled", true);
        }
    };

    var _save_settings = function (e) {
        e.preventDefault();
        var settings = $("#settings-form");

        settings.submit({
            url: Globals.ajaxUrl,
            action: "support_save_settings",
            method: "post",
            data: settings.serializeArray(),
            success: function (response) {

            }
        });
    };

    var initialize = function () {
      _bind_events();
    };

    return {
        initialize: initialize
    };

})(jQuery);

jQuery(document).ready(function ($) {
   Settings.initialize();
});