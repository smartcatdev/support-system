/**
 * Module for handling the authentication process
 *
 * @since 1.7.0
 */
;(function ($, localize) {

    $(function () {

        /**
         * Handle login form submission
         */
        $('#ucare-login form').submit(function (e) {
            e.preventDefault();

            var step = $(this).data('step');

            // Process the current step
            processStep(step).then(function () {
                var $screen = $('.ucare-login-screen:visible');
                $screen.fadeOut(function () {
                    $(this).next().fadeIn();
                });
            });
        });

        /**
         * Process the login step
         *
         * @param {string} step
         *
         * @since 1.7.0
         * @return {*}
         */
        var processStep = function (step) {
            return $.Deferred(function (deferred) {
                deferred.resolve()
            });
        }

    });

})(jQuery, _ucare_login_l10n);








// /**
//  * @summary Module for managing the login page.
//  *
//  * @since 1.6.0
//  */
// ;(function ($, ucare) {
//     "use strict";
//
//     // Run on init
//     $(function () {
//
//         /**
//          * @summary Handle registration form submissions.
//          */
//         $('#registration-form').submit(function (e) {
//             e.preventDefault();
//
//             const $submit = $('#registration-submit'),
//                   $msgs   = $('#message-area');
//
//             // Disable the submit button
//             $submit.prop('disabled', true);
//
//             // Remove any alerts
//             $('.alert').remove();
//
//             $.ajax({
//                 url: ucare.api.root + 'ucare/v1/users/register',
//                 method: 'post',
//                 data: $(this).serializeJSON(),
//                 beforeSend: function (xhr) {
//                     xhr.setRequestHeader('X-WP-Nonce', ucare.api.nonce);
//                 }
//             })
//             .success(function () {
//                 location.reload();
//             })
//             .fail(function (xhr) {
//                 $msgs.append(make_alert(xhr.responseJSON.message, 'error'));
//             })
//             .complete(function () {
//                 $submit.prop('disabled', false);
//             });
//
//         });
//
//         /**
//          * @summary Handle password resetting.
//          */
//         $('#reset-pw-form').submit(function (e) {
//             e.preventDefault();
//
//             const $submit = $('#reset-password'),
//                   $msgs   = $('#message-area');
//
//             // Disable the submit button
//             $submit.prop('disabled', true);
//
//             // Remove any alerts
//             $('.alert').remove();
//
//             $.ajax({
//                 url: ucare.api.root + 'ucare/v1/auth/reset-password',
//                 method: 'post',
//                 data: $(this).serializeJSON(),
//                 beforeSend: function (xhr) {
//                     xhr.setRequestHeader('X-WP-Nonce', ucare.api.nonce);
//                 }
//             })
//             .success(function (res) {
//                 $msgs.append(make_alert(res.message, 'success'));
//             })
//             .fail(function (xhr) {
//                 $msgs.append(make_alert(xhr.responseJSON.message, 'error'));
//             })
//             .complete(function () {
//                 $submit.prop('disabled', false);
//             });
//
//         });
//
//     });
//
//
//     /**
//      * Create an alert message.
//      *
//      * @param {string} message
//      * @param {string} type
//      *
//      * @since 1.6.0
//      * @return {*|HTMLElement}
//      */
//     function make_alert(message, type) {
//         return $(
//             '<div class="alert alert-' + type + ' alert-dismissable fade in"> \
//                 <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + message + '</div>');
//     }
//
// })(jQuery, ucare);/**
//  * @summary Module for managing the login page.
//  *
//  * @since 1.6.0
//  */
// ;(function ($, ucare) {
//     "use strict";
//
//     // Run on init
//     $(function () {
//
//         /**
//          * @summary Handle registration form submissions.
//          */
//         $('#registration-form').submit(function (e) {
//             e.preventDefault();
//
//             const $submit = $('#registration-submit'),
//                   $msgs   = $('#message-area');
//
//             // Disable the submit button
//             $submit.prop('disabled', true);
//
//             // Remove any alerts
//             $('.alert').remove();
//
//             $.ajax({
//                 url: ucare.api.root + 'ucare/v1/users/register',
//                 method: 'post',
//                 data: $(this).serializeJSON(),
//                 beforeSend: function (xhr) {
//                     xhr.setRequestHeader('X-WP-Nonce', ucare.api.nonce);
//                 }
//             })
//             .success(function () {
//                 location.reload();
//             })
//             .fail(function (xhr) {
//                 $msgs.append(make_alert(xhr.responseJSON.message, 'error'));
//             })
//             .complete(function () {
//                 $submit.prop('disabled', false);
//             });
//
//         });
//
//         /**
//          * @summary Handle password resetting.
//          */
//         $('#reset-pw-form').submit(function (e) {
//             e.preventDefault();
//
//             const $submit = $('#reset-password'),
//                   $msgs   = $('#message-area');
//
//             // Disable the submit button
//             $submit.prop('disabled', true);
//
//             // Remove any alerts
//             $('.alert').remove();
//
//             $.ajax({
//                 url: ucare.api.root + 'ucare/v1/auth/reset-password',
//                 method: 'post',
//                 data: $(this).serializeJSON(),
//                 beforeSend: function (xhr) {
//                     xhr.setRequestHeader('X-WP-Nonce', ucare.api.nonce);
//                 }
//             })
//             .success(function (res) {
//                 $msgs.append(make_alert(res.message, 'success'));
//             })
//             .fail(function (xhr) {
//                 $msgs.append(make_alert(xhr.responseJSON.message, 'error'));
//             })
//             .complete(function () {
//                 $submit.prop('disabled', false);
//             });
//
//         });
//
//     });
//
//
//     /**
//      * Create an alert message.
//      *
//      * @param {string} message
//      * @param {string} type
//      *
//      * @since 1.6.0
//      * @return {*|HTMLElement}
//      */
//     function make_alert(message, type) {
//         return $(
//             '<div class="alert alert-' + type + ' alert-dismissable fade in"> \
//                 <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + message + '</div>');
//     }
//
// })(jQuery, ucare);