/**
 * Module for handling the authentication process
 *
 * @since 1.7.0
 */
;(function ($, localize) {

    $(function () {
        var _nonce  = localize.rest_nonce,
            $notice = $('#ucare-login-notice');

        /**
         * Handle TOS
         */
        $('button.terms').click(function () {
            var $form = $(this).parents('form');
            $form.find('input[name="terms"]').remove();
            $form.append($('<input>', {
                type: 'hidden',
                name: 'terms',
                value: $(this).val()
            }));
        });

        /**
         * Handle login form submission
         */
        $('#ucare-login form').submit(function (e) {
            e.preventDefault();

            // Process the current step
            processStep(
                $(this).serializeJSON()
            ).then(function (next) {
                var $screen = $('.ucare-login-screen:visible');

                if (next.type === 'screen') {
                    $screen.fadeOut(function () {
                        var $next = $('.ucare-login-screen[data-step="' + next.screen + '"]');
                        $next.fadeIn();

                        // Append all data to be sent back in the next step
                        if (next.data) {
                            Object.keys(next.data).forEach(function (key) {
                                $next.append($('<input>', {
                                    type: 'hidden',
                                    name: key,
                                    value: next.data[key]
                                }));
                            });
                        }

                        // Update the _nonce after auth
                        if (next.nonce) {
                            _nonce = next.nonce;
                        }
                    });

                } else if (next.type === 'redirect') {
                    window.location.href = next.to;
                }
            });
        });

        /**
         * Process the login step
         *
         * @param {*} data
         *
         * @since 1.7.0
         * @return {*}
         */
        var processStep = function (data) {
            return $.when(
                $.ajax({
                    url: localize.rest_url + 'ucare/v1/users/me/authenticate',
                    data: data,
                    method: 'post',
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-WP-Nonce', _nonce);
                    }
                }))
            .then(function (result) {
                return $.Deferred().resolve(result);
            })
            .fail(function (response) {
                $notice.find('.inner').html(response.responseJSON.message);

                if (!$notice.is(':visible')) {
                    $notice.fadeToggle();
                }
            });
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
    });

})(jQuery, _ucare_login_l10n);
