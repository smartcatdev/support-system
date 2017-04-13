var Settings = (function ($) {
    "use strict";

    var _bind_events = function () {
        $(document).on("keyup", "#confirm-password", _validate_password);
        $(document).on("click", "#save-settings", _save_settings);
    };

    var _validate_password = function (e) {
        var submit = $("#save-settings");
        var confirm = $(e.target);
        var group = confirm.parents(".form-group");

        submit.prop("disabled", false);

        group.removeClass("has-error has-success")
            .find(".form-control-feedback")
            .remove();

        if (confirm.val() !== $("#new-password").val()) {
            group.addClass("has-error");
            group.append('<span class="glyphicon glyphicon-remove form-control-feedback"></span>');
            submit.prop("disabled", true);
        } else {
            group.append('<span class="glyphicon glyphicon-ok form-control-feedback"></span>');
            group.addClass("has-success");
        }
    };

    var _save_settings = function (e) {
        var settings = $("#settings-form");
        var submit = $(e.target);
        var modal = submit.parents(".modal");

        submit.prop("disabled", true);

        settings.submit({
            url: Globals.ajax_url,
            action: "support_save_settings",
            method: "post",
            extras: {
                _ajax_nonce: Globals.ajax_nonce
            },
            success: function () {
                window.location.reload();
            },
            complete: function () {
                submit.prop("disabled", false);
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