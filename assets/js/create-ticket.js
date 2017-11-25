/**
 * @summary Module for handling the create ticket form.
 *
 * @since 1.5.1
 * @access public
 */
(function ($, createTicket) {
    "use strict";

    const $form = $('#create-ticket-form'),

        /**
         * Module for handling ticket creation and auto-drafting.
         *
         * @since 1.5.1
         */
        module = {

        saving_in_progress: false,

        /**
         * @summary Setup event handlers.
         *
         * @since 1.5.1
         */
        init: function () {

            /**
             * @summary Manual form submission.
             */
            $form.submit(function (e) {
                e.preventDefault();
                module.clear_errors();
                module.save('publish');
            });


            /**
             * @summary Auto draft the post after editing.
             */
            $form.find(':input').on('change paste keyup', _.debounce(function () {
                module.clear_errors();
                module.save('draft');
            }, 1000));


            /**
             * @summary Toggle author select if user decides to override.
             */
            $('#set-author').change(module.toggle_author_select);


            /**
             * @summary Set the max filesize for dropzone.js
             */
            Dropzone.prototype.defaultOptions.maxFilesize = createTicket.dropzone.max_attachment_size;

            /**
             * Disable dropzone auto discovery
             */
            Dropzone.options.ticketMedia = false;

            /**
             * @summary Initialize the dropzone
             */
            $('#ticket-media').dropzone({
                addRemoveLinks: true,
                url: createTicket.api.endpoints.media,
                headers: {
                    'X-WP-Nonce': createTicket.api.nonce
                }
            });

        },

        add_media: function () {

        },

        remove_media: function () {

        },

        /**
         * @summary Toggle the author selection.
         *
         * @since 1.5.1
         * @return void
         */
        toggle_author_select: function () {
            $('#author-select').slideToggle();
            $('#assign-author').prop('disabled', !$(this).is(':checked'));
            $('#current-user').prop('disabled',   $(this).is(':checked'));
        },


        /**
         * @summary Clear form input errors.
         *
         * @since 1.5.2
         * @return void
         */
        clear_errors: function () {

            $form.find('.has-error').each(function (i, el) {
                $(el).siblings('.error-message').remove();
                $(el).removeClass('has-error');
            });

        },


        /**
         * @summary Save a support ticket.
         *
         * @param status
         *
         * @since 1.5.1
         * @return void
         */
        save: function (status) {

            // Prevent multiple save requests
            if (!module.saving_in_progress) {
                module.saving_in_progress = true;

                /**
                 * @summary Construct the URI.
                 */
                const uri = createTicket.api.endpoints.tickets + '/' + $form.data('id') +
                    '?' + $form.find(':input').serialize();

                /**
                 * @summary make the ajax request to the API, if no status is passed, the post will draft.
                 */
                $.ajax({
                    url: uri,
                    data: {
                        status: status || 'draft'
                    },
                    method: 'post',
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-WP-Nonce', createTicket.api.nonce);
                    },
                    complete: function () {
                        module.saving_in_progress = false;
                    }
                })
                .done(function () {

                    // Redirect back to the support page if the ticket has been published
                    if (status === 'publish') {
                        location.href = createTicket.redirect.support_page
                    }

                })
                .fail(function (xhr) {

                    // Output input errors below their respective fields
                    if (status === 'publish' && xhr.responseJSON) {

                        const field  = xhr.responseJSON.data.field,
                              $field = $('[name="' + field  +'"]');

                        $field.addClass('has-error');
                        $field.parent().append('<p class="error-message">' + xhr.responseJSON.message + '</p>');

                    }

                });

            }

        }

    };

    /**
     * @summary Call the init method when the DOM loads.
     */
    $(document).ready(function () { module.init(); })

})(jQuery, createTicket || {});
