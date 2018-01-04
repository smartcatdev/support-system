/**
 * Module for managing the login page.
 *
 * @since 1.6.0
 */
;(function ($, ucare) {
    "use strict";

    // Run on init
    $(function () {

        /**
         * @summary Handle registration form submissions.
         */
        $('#registration-submit').click(function (e) {
            e.preventDefault();

            const $form = $('#registration-form'),
                  $msgs = $('#message-area');

            // Disable the submit button
            $(e.target).prop('disabled', true);

            // Remove any alerts
            $('.alert').remove();

            $.ajax({
                url: ucare.api.root + 'ucare/v1/users/register',
                method: 'post',
                data: $form.serializeJSON(),
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', ucare.api.nonce);
                }
            })
            .success(function (res) {
                ucare.events.publish(ucare.events.channels.REGISTER_USER, res);
                location.reload();
            })
            .fail(function (xhr) {
                $msgs.append(make_alert(xhr.responseJSON.message, 'error'));
            })
            .complete(function () {
                $(e.target).prop('disabled', false);
            });

        });

        /**
         * @summary Handle password resetting.
         */
        $('#reset-password').click(function (e) {
            e.preventDefault();

            const $form = $('#reset-pw-form'),
                  $msgs = $('#message-area');

            // Disable the submit button
            $(e.target).prop('disabled', true);

            // Remove any alerts
            $('.alert').remove();

            $.ajax({
                url: ucare.api.root + 'ucare/v1/auth/reset-password',
                method: 'post',
                data: $form.serializeJSON(),
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', ucare.api.nonce);
                }
            })
            .success(function (res) {
                $msgs.append(make_alert(res.message, 'success'));
            })
            .fail(function (xhr) {
                $msgs.append(make_alert(xhr.responseJSON.message, 'error'));
            })
            .complete(function () {
                $(e.target).prop('disabled', false);
            });

        });

    });


    /**
     * Create an alert message.
     *
     * @param {string} message
     * @param {string} type
     *
     * @since 1.6.0
     * @return {*|HTMLElement}
     */
    function make_alert(message, type) {
        return $(
            '<div class="alert alert-' + type + ' alert-dismissable fade in"> \
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + message + '</div>');
    }

})(jQuery, ucare);