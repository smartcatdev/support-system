/**
 * Module for managing the login page.
 *
 * @since 1.6.0
 */
;(function ($, ucare) {
    "use strict";

    const $form = $('#registration-form'),
          $msgs = $('#message-area');

    /**
     * @summary Handle registration form submissions.
     */
    $('#registration-submit').click(function (e) {
        e.preventDefault();

        // Disable the submit button
        $(e.target).prop('disabled', true);

        // Remove any alerts
        $('.alert').remove();

        $.ajax({
            url: ucare.api.root + 'ucare/v1/users/register',
            method: 'post',
            data: $form.serializeJSON()
        })
        .success(function (res) {
            ucare.events.publish(ucare.events.channels.REGISTER_USER, res);
            location.reload();
        })
        .fail(function (xhr) {

            const $message = $(
                '<div class="alert alert-error alert-dismissable fade in"> \
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + xhr.responseJSON.message + '</div>');

            $msgs.append($message)

        })
        .complete(function () {
            $(e.target).prop('disabled', false);
        });

    });

})(jQuery, ucare);