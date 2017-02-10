jQuery("document").ready(function ($) {
    "use strict";

    var SupportSettings = (function() {

        var initialize_events = function () {
            var body = $("body");

            $(body).on("keyup", ".settings_form input[name=\"confirm_password\"]", validate_password);
            $(body).on("submit", ".settings_form", save_settings);
        };

        var validate_password = function (e) {
            var field = $(e.target);
            var submit_button = $(".settings_form input[type=\"submit\"]");
            var password_field = $(".settings_form input[name=\"new_password\"]");

            field.siblings(".error_msg").remove();
            field.removeClass("error_field");
            submit_button.prop("disabled", false);

            if (field.val() !== password_field.val() && field.val() !== "") {
                field.addClass("error_field");
                field.parent().append("<span class=\"error_msg glyphicon glyphicon-exclamation-sign\"></span>");
                submit_button.prop("disabled", true);
            }
        };

        var save_settings = function (e) {
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

        initialize_events();

    })();

});