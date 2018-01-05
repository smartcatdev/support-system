/**
 * @summary Module for handling the create ticket form.
 *
 * @since 1.5.1
 * @access public
 */
;(function ($, ucare) {
    "use strict";

    const $form     = $('#create-ticket-form'),

          $dropzone = $('#ticket-media'),

        /**
         * Module for handling ticket creation and auto-drafting.
         *
         * @since 1.5.1
         */
        module = {

        /**
         * @summary Holds the state of the current save operation.
         */
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
            $('#submit').click(function () {
                module.clear_errors();
                module.save('publish');
            });

            /**
             * @summary Auto draft the post after editing.
             */
            $form.find(':input').on('change paste keyup', function () {
                module.save();
            });


            /**
             * @summary Toggle author select if user decides to override.
             */
            $('#set-author').change(module.toggle_author_select);


            /**
             * @summary Set the max filesize for dropzone.js
             */
            Dropzone.prototype.defaultOptions.maxFilesize = ucare.settings.max_file_size;

            /**
             * Disable dropzone auto discovery
             */
            Dropzone.options.ticketMedia = false;

            /**
             * @summary Initialize the upload form
             */
            $dropzone.dropzone({
                init: module.dropzone_init,
                addRemoveLinks: true,
                headers: {
                    'X-WP-Nonce': ucare.api.nonce
                },
                url: ucare.api.root + 'wp/v2/media'
            });

        },

        /**
         * @summary initialize the dropzone instance
         *
         * @since 1.5.1
         * @return void
         */
        dropzone_init: function () {
            const dropzone  = this,
                  ticket_id = $dropzone.find('[name="post"]').val();

            $.ajax({
                url: ucare.api.root + 'wp/v2/media?order=asc&parent=' + ticket_id,
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', ucare.api.nonce);
                },
                success: function (res) {

                    if (res.length > 0) {
                        res.forEach(function (media) {

                            // Clone the result and append the file name
                            const file = Object.assign({ name: media.title.rendered }, media);

                            // Add the file to the dropzone
                            dropzone.emit('addedfile', file);

                            // Set the media thumbnail
                            if (file.media_type === 'image') {
                                dropzone.emit('thumbnail', file, file.media_details.sizes.thumbnail.source_url);
                            }

                        });
                    }

                }
            });

            /**
             * @summary Save the attachment ID on success.
             */
            dropzone.on('success', function (file, res) {
                file.id = res.id;
            });

            /**
             * @summary Remove the file from the server when removed from the dropzone.
             */
            dropzone.on("removedfile", function(file) {
                module.delete_attachment(file.id)
            });

            /**
             * @summary Append attachment title in xhr
             */
            dropzone.on('sending', function(file, xhr, form) {
                form.append('title', file.name);
            });

        },

        /**
         * @summary Delete an attachment from a ticket.
         *
         * @param {int} id
         *
         * @since 1.5.1
         * @return void
         */
        delete_attachment: function (id) {
            $.ajax({
                url: ucare.api.root + 'wp/v2/media/' + id + '?force=true',
                method: 'delete',
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', ucare.api.nonce);
                    module.toggle_submit('delete_media');
                },
                complete: function () {
                    module.toggle_submit('delete_media');
                }
            })
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
            $('.alert').each(function (i, el) {
                $(el).fadeToggle('fast', function () {
                    $(el).remove();
                });
            });
        },

        /**
         * Create and append an error notification to the DOM.
         *
         * @param message
         * @param parent
         *
         * @since 1.6.0
         * @return {*|HTMLElement}
         */
        error: function (message, parent) {
            const err = $(
                '<div class="alert alert-danger alert-dismissable fade in"> \
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + message + '</div>');

            if (!parent) {
                parent = 'body';
            }

            $(parent).append(err.fadeIn());

            return err;

        },

        /**
         * @summary Save a support ticket.
         *
         * @param status
         *
         * @since 1.5.1
         * @return void
         */
        save: _.debounce(function (status) {

            // Prevent multiple save requests
            if (!module.saving_in_progress) {
                module.saving_in_progress = true;

                $('#submit').prop('disabled', true);

                /**
                 * @summary Construct the URI.
                 */
                const uri = ucare.api.root + 'wp/v2/support-tickets/' + $form.data('id') +
                    '?' + $form.find(':input').serialize();

                /**
                 * @summary make the ajax request to the API, if no status is passed, the post will draft.
                 */
                $.ajax({
                    url: uri,
                    data: {
                        status: status || 'ucare-auto-draft'
                    },
                    method: 'post',
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-WP-Nonce', ucare.api.nonce);
                    },
                    complete: function () {
                        $('#submit').prop('disabled', false);
                        module.saving_in_progress = false;
                    }
                })
                .success(function (post) {

                    // Redirect back to the support page if the ticket has been published
                    if (post.status === 'publish') {
                        location.href = ucare.vars.support_url
                    }

                })
                .fail(function (xhr) {

                    if (status === 'publish' && xhr.responseJSON) {
                        module.error(xhr.responseJSON.message, '#message-area');
                    }

                });

            }

        }, 1000)

    };

    // Initialize module
    $(module.init);

})(jQuery, ucare);
