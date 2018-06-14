/**
 * Module for manging the edit profile page.
 *
 * @since 1.6.0
 */
;(function ($, ucare) {
    "use strict";


    const $form = $('#edit-profile-form'),

        /**
         * @summary Module for managing the UI for the profile edit form.
         */
        module = {

            /**
             * @summary Holds the state of the current save operation.
             */
            saving_in_progress: false,

            /**
             * @summary Initialize DOM and event handlers.
             *
             * @since 1.6.0
             * @return {void}
             */
            init: function () {

                $('#submit').click(function (e) {
                    e.preventDefault();
                    module.clear_errors();
                    module.save();
                });


                $('.pw-input').on('keyup paste', function () {
                    module.check_password();
                });


                /**
                 * @summary Remove updated query var.
                 */
                var url = location.href.replace(/updated=true/, '');
                if (url.charAt(url.length - 1) === '?') {
                    url = url.substr(0, url.length - 1);
                }

                history.pushState({ path: url }, '', url);

            },

            /**
             * @summary Save the users settings.
             *
             * @since 1.6.0
             */
            save: function () {

                if (!module.saving_in_progress) {
                    module.saving_in_progress = true;

                    const data = $form.serializeJSON();

                    if (!data.password) {
                        delete(data.password);
                    }

                    ucare.user
                        .update(data)
                        .success(function () {
                            location.search = '?updated=true';
                        })
                        .fail(function (xhr) {

                            if (xhr.responseJSON) {
                                module.alert(xhr.responseJSON.message, '#message-area');
                            }

                        })
                        .complete(function () {
                           module.saving_in_progress = false;
                        });

                }

            },

            /**
             * @summary Check the user's password as they type.
             *
             * @since 1.6.0
             */
            check_password: function () {
                const $submit   = $('#submit'),
                      $password = $('#password'),
                      $confirm  = $('#confirm');

                if ($password.val().length > 0) {
                    const match = $password.val() === $confirm.val();

                    $confirm
                        .parents('.has-feedback')
                        .toggleClass('has-success', match);

                    $confirm
                        .siblings('.form-control-feedback')
                        .toggleClass('hidden', !match);

                    $submit.prop('disabled', !match);

                }

            },

            /**
             * @summary Clear all error messages.
             *
             * @since 1.6.0
             */
            clear_errors: function () {

                $('.alert').each(function (i, el) {
                    $(el).fadeToggle('fast', function () {
                        $(el).remove();
                    });
                });

            },

            /**
             * Create and append an alert notification to the DOM.
             *
             * @param {string}             message
             * @param {string|HTMLElement} parent
             * @param {string}             type
             *
             * @since 1.6.0
             * @return {*|HTMLElement}
             */
            alert: function (message, parent, type) {
                const err = $(
                    '<div class="alert alert-' + ( type || 'danger' ) + ' alert-dismissable fade in"> \
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + message + '</div>');

                if (!parent) {
                    parent = 'body';
                }

                $(parent).append(err.fadeIn());

                return err;

            },

        };


    // Initialize module
    $(module.init);

    /**
     * Handle user data requests
     *
     * @since 1.7.1
     */
    $('#request-data-erase,#request-data-export').click(function (e) {
        e.preventDefault();
        var $this = $(this);

        swal(ucare.l10n.strings.are_you_sure, {
            icon: 'warning',
            dangerMode: true,
            buttons: [
                ucare.l10n.strings.no,
                ucare.l10n.strings.yes
            ]
        })
        .then(function (confirm) {
            if (!confirm) {
                return;
            }

            $.ajax({
                url: ucare.api.root + 'ucare/v1/user/data-request',
                method: 'post',
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', ucare.api.nonce);
                },
                data: {
                    action: $this.data('action')
                }
            })
            .fail(function (xhr) {
                module.alert(xhr.responseJSON.message, '#message-area');
            })
            .then(function (response) {
                $this.remove();
                module.alert(response.message, '#message-area');
            });
        });
    });

})(jQuery, ucare);