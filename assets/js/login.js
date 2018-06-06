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

    var $emailStep = $('[data-step="email"]'),
        $termsStep = $('[data-step="tos"]'),
        $passStep  = $('[data-step="password"]'),
        $regStep   = $('[data-step="register"]');

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
     * Verify if the user exists or not
     *
     * @param {*} data
     *
     * @since 1.7.0
     * @return {void}
     */
    function verify(data) {
        ajax({
            url: localize.rest_url + 'ucare/v1/user/verify',
            data: data
        })
        .fail(function () {
            if (!localize.enforce_tos) {
                switchToStep('register').then(function () {
                    var $email = $('<input>', {
                        name: 'email',
                        type: 'hidden',
                        value: data.email
                    });
                    $regStep.append($email);
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
                            $regStep.append($tos).append($email);
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
                    $passStep.append($input);
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
    }

    /**
     * Handle authentication form submission
     *
     * @param {*} data
     *
     * @since 1.7.0
     * @return {void}
     */
    function authenticate(data) {
        ajax({
            url: localize.rest_url + 'ucare/v1/user/authenticate',
            method: 'post',
            data: data
        })
        .fail(function (err) {
            showLoginNotice(err.responseJSON.message);
        })
        .then(function (response, status, xhr) {
            window.location.href = xhr.getResponseHeader('Location');
        });
    }

    /**
     * Handle registration form submission
     *
     * @param {*} data
     *
     * @since 1.7.0
     * @return {void}
     */
    function register(data) {
        ajax({
            url: localize.rest_url + 'ucare/v1/user/register',
            method: 'post',
            data: data
        })
        .fail(function (err) {
            showLoginNotice(err.responseJSON.message);
        })
        .then(function (response, status, xhr) {
            window.location.href = xhr.getResponseHeader('Location');
        });
    }
    
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
                verify(data);
                break;

            /**
             * Handle password screen
             */
            case 'password':
                authenticate(data);
                break;

            /**
             * Handle registration screen
             */
            case 'register':
                register(data);
                break;
        }

    });
});

})(jQuery, _ucare_login_l10n);
