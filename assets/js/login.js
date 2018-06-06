/**
 * Module for handling the authentication process
 *
 * @since 1.7.0
 */
;(function ($, localize) {

    /**
     * Configure REST nonce
     *
     * @param {*} options
     *
     * @since 1.7.0
     * @return {*}
     */
    function ajax(options) {
        return $.ajax(Object.assign({}, {
            beforeSend: function (xhr) { xhr.setRequestHeader('X-WP-Nonce', localize.rest_nonce); }
        }, options));
    }

    $(function () {

        /**
         * Switch to the next step
         *
         * @param {string} step
         *
         * @since 1.7.0
         * @return {*|$.Deferred.promise}
         */
        function switchToStep(step) {
            return $.Deferred(function ($deferred) {
                var $current  = $('.ucare-login-screen:visible'),
                    $nextStep = $('.ucare-login-screen[data-step="' + step + '"]');

                $current.fadeOut(function () {
                    $nextStep.fadeIn(function () { $deferred.resolve(); });
                });
            });
        }

        /**
         * Accept the terms of service
         *
         * @param email
         * @return {*}
         */
        function acceptTOS(email) {
            return ajax({
               url:  localize.rest_url + 'ucare/v1/user/accept-tos?email=' + email
            });
        }

        /**
         * Display login notice
         *
         * @param {string} message
         *
         * @since 1.7.0
         * @return {void}
         */
        function showLoginNotice(message) {
            $('#ucare-login-notice').fadeIn().find('.inner').html(message);
        }

        /**
         * Handle notice dismissal
         */
        $('#ucare-login-notice .dismiss').click(function (e) {
            e.preventDefault();
            $(this).parents('#ucare-login-notice').fadeOut(function () {
                $(this).find('.inner').html('');
            });
        });

        /**
         * Handle login form submission
         */
        $('#ucare-login form').submit(function (e) {
            e.preventDefault();

            var data = $(this).serializeJSON(),
                step = $(this).data('step') || 'email';

            switch (step) {

                /**
                 * Handle flow from the email screen
                 */
                case 'email':
                    ajax({
                        url: localize.rest_url + 'ucare/v1/user/verify?email=' + data.email
                    })
                    .fail(function () {
                        if (!localize.enforce_tos) {
                            switchToStep('register').then(function () {
                                var $email = $('<input>', {
                                    name: 'email',
                                    type: 'hidden',
                                    value: data.email
                                });
                                $('#login-step-register').append($email);
                            });
                        } else {
                            switchToStep('tos').then(function () {
                                $('#terms-accept').click(function () {
                                    switchToStep('register').then(function () {
                                        var $tos = $('<input>', {
                                            name: 'tos_accepted',
                                            type: 'hidden',
                                            value: true
                                        });
                                        var $email = $('<input>', {
                                            name: 'email',
                                            type: 'hidden',
                                            value: data.email
                                        });
                                        $('#login-step-register').append($tos).append($email);
                                    });
                                });
                                $('#terms-decline').click(function () {
                                    switchToStep('email'); // Go back to the email step
                                });
                            });
                        }
                    })
                    .then(function (user) {
                        var switchToPassword = function () {
                            switchToStep('password').then(function () {
                                var $input = $('<input>', {
                                    name: 'log',
                                    type: 'hidden',
                                    value: data.email
                                });
                                $('#login-step-password').append($input);
                            });
                        };

                        if (localize.enforce_tos && !user.tos_accepted) {
                            switchToStep('tos').then(function () {
                                $('#terms-accept').click(function (){
                                    acceptTOS(data.email).then(switchToPassword);
                                });
                                $('#terms-decline').click(function () {
                                    switchToStep('email');
                                });
                            });

                        } else {
                            switchToPassword();
                        }
                    });

                    break;

                /**
                 * Handle password screen
                 */
                case 'password':
                    ajax({
                        url: localize.rest_url + 'ucare/v1/user/authenticate',
                        method: 'post',
                        data: $('#login-step-password').serializeJSON()
                    })
                    .fail(function (err) {
                        showLoginNotice(err.responseJSON.message);
                    })
                    .then(function (response, status, xhr) {
                        window.location.href = xhr.getResponseHeader('Location');
                    });

                    break;

                /**
                 * Handle registration screen
                 */
                case 'register':
                    ajax({
                        url: localize.rest_url + 'ucare/v1/user/register',
                        method: 'post',
                        data: $('#login-step-register').serializeJSON()
                    })
                    .fail(function (err) {
                        showLoginNotice(err.responseJSON.message);
                    })
                    .then(function (response, status, xhr) {
                        window.location.href = xhr.getResponseHeader('Location');
                    });

                    break;


            }

        });
    });

    // $(function () {
    //     var _nonce  = localize.rest_nonce,
    //         $notice = $('#ucare-login-notice');
    //
    //     /**
    //      * Handle TOS
    //      */
    //     $('button.terms').click(function () {
    //         var $form = $(this).parents('form');
    //         $form.find('input[name="terms"]').remove();
    //         $form.append($('<input>', {
    //             type: 'hidden',
    //             name: 'terms',
    //             value: $(this).val()
    //         }));
    //     });
    //
    //     /**
    //      * Handle login form submission
    //      */
    //     $('#ucare-login form').submit(function (e) {
    //         e.preventDefault();
    //
    //         // Process the current step
    //         processStep(
    //             $(this).serializeJSON()
    //         ).then(function (next) {
    //             var $screen = $('.ucare-login-screen:visible');
    //
    //             if (next.type === 'screen') {
    //                 $screen.fadeOut(function () {
    //                     var $next = $('.ucare-login-screen[data-step="' + next.screen + '"]');
    //                     $next.fadeIn();
    //
    //                     // Append all data to be sent back in the next step
    //                     if (next.data) {
    //                         Object.keys(next.data).forEach(function (key) {
    //                             $next.append($('<input>', {
    //                                 type: 'hidden',
    //                                 name: key,
    //                                 value: next.data[key]
    //                             }));
    //                         });
    //                     }
    //
    //                     // Update the _nonce after auth
    //                     if (next.nonce) {
    //                         _nonce = next.nonce;
    //                     }
    //                 });
    //
    //             } else if (next.type === 'redirect') {
    //                 window.location.href = next.to;
    //             }
    //         });
    //     });
    //
    //     /**
    //      * Process the login step
    //      *
    //      * @param {*} data
    //      *
    //      * @since 1.7.0
    //      * @return {*}
    //      */
    //     var processStep = function (data) {
    //         return $.when(
    //             $.ajax({
    //                 url: localize.rest_url + 'ucare/v1/users/me/authenticate',
    //                 data: data,
    //                 method: 'post',
    //                 beforeSend: function (xhr) {
    //                     xhr.setRequestHeader('X-WP-Nonce', _nonce);
    //                 }
    //             }))
    //         .then(function (result) {
    //             return $.Deferred().resolve(result);
    //         })
    //         .fail(function (response) {
    //             $notice.find('.inner').html(response.responseJSON.message);
    //
    //             if (!$notice.is(':visible')) {
    //                 $notice.fadeToggle();
    //             }
    //         });
    //     }
    //
    //     /**
    //      * Handle notice dismissal
    //      */
    //     $('#ucare-login-notice .dismiss').click(function (e) {
    //         e.preventDefault();
    //         $(this).parents('#ucare-login-notice').fadeOut(function () {
    //             $(this).find('.inner').html('');
    //         });
    //     });
    // });

})(jQuery, _ucare_login_l10n);
