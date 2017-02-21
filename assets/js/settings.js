var Settings = (function ($) {
    "use strict";

    var _bind_events = function () {
        $(document).on("keyup", "#confirm-password", _validate_password);
        $(document).on("click", "#save-settings", _save_settings);
    };

    var _validate_password = function (e) {
        var confirm_password = $(e.target);
        var new_password = $("#new-password");
        var submit = $("#save-settings");
        var group = confirm_password.parents(".form-group");

        group.removeClass("has-error has-success");
        group.find(".form-control-feedback").remove();
        submit.prop("disabled", false);

        if (new_password.val() !== confirm_password.val()) {
            group.addClass("has-error");
            group.append("<span class=\"glyphicon glyphicon-remove form-control-feedback\"></span>");
            submit.prop("disabled", true);
        } else {
            group.append("<span class=\"glyphicon glyphicon-ok form-control-feedback\"></span>");
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
            success: function (response) {
                var new_password = $("#new-password");
                var confirm_password = $("#confirm-password");
                var container = confirm_password.parents(".form-group");
                var message = $("<div style=\"border-radius: 0\" class=\"alert alert-success fade in\">" +
                                    "<a href=\"#\" class=\"close\" data-dismiss=\"alert\">×</a>" +
                                    response.data +
                                "</div>");

               new_password.val("");
               confirm_password.val("");
               container.find(".form-control-feedback").remove();
               container.removeClass("has-success");
               modal.find(".message").html(message);
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