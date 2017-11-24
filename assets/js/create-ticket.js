/**
 * @summary Module for handling the create ticket form.
 *
 * @since 1.5.1
 * @access public
 */
(function ($, createTicket) {
    "use strict";

    const $form = $('#create-ticket-form');

    const module = {

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
            $form.find(':input').on('change paste', _.debounce(function () {
                module.clear_errors();
                module.save('draft');
            }, 400));

            $('#set-author').change(module.toggle_author_select);

        },


        /**
         * @summary Toggle the author selection.
         *
         * @since 1.5.1
         * @return void
         */
        toggle_author_select: function () {
            $('#author-select').slideToggle();
            $('[name="author"]').prop('disabled', !$(this).is(':checked'));
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
