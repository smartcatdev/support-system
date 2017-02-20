var Settings = (function ($) {
    "use strict";

    var _confirm_password;
    var _settings_form;

    var _bind_events = function () {
        $(document).on("keyup", _confirm_password, _validate_password);
        $(document).on("submit", _settings_form, _save_settings);
    };

    var _validate_password = function (e) {
        _confirm_password.addClass("form-control-danger");

        // var field = $(e.target);
        // var submit_button = $(".settings_form input[type=\"submit\"]");
        // var password_field = $(".settings_form input[name=\"new_password\"]");
        //
        // field.siblings(".error_msg").remove();
        // field.removeClass("error_field");
        // submit_button.prop("disabled", false);
        //
        // if (field.val() !== password_field.val() && field.val() !== "") {
        //     field.addClass("error_field");
        //     field.parent().append("<span class=\"error_msg glyphicon glyphicon-exclamation-sign\"></span>");
        //     submit_button.prop("disabled", true);
        // }
    };

    var _save_settings = function (e) {
        e.preventDefault();

        var form = $(".settings_form");

        form.find(".error_field").removeClass("error_field");
        form.find(".error_msg").remove();

        $.ajax({
            type: "post",
            url: SupportSystem.ajaxUrl + "?action=support_save_settings",
            data: form.serializeArray(),
            success: function (response) {

                if (response.success) {
                    form.find("p.status").text(response.data).removeClass("hidden");
                } else {
                    $.each(response.data, function (key, value) {
                        var field = $(".settings_form").find("[name=\"" + key + "\"]");
                        field.addClass("error_field");
                        field.parent().append("<span class=\"error_msg\">" + value + "</span>");
                    });
                }
            }
        });
    };

    var initialize = function () {
        _settings_form = $("#settings-form");
        _confirm_password = _settings_form.find(".confirm-password");

      _bind_events();
    };

    return {
        initialize: initialize
    };

})(jQuery);

jQuery(document).ready(function ($) {
   Settings.initialize();
});