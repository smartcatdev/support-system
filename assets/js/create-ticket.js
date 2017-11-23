(function ($, createTicket) {
    "use strict";

    const $form = $('#create-ticket-form');

    const module = {

        /**
         * @summary Setup event handlers.
         *
         * @since 1.5.1
         */
        init: function () {

            $form.submit(function (e) {
                e.preventDefault();
                module.create();
            });



        },

        create: function () {

            /**
             * @summary Construct the URI
             */
            const uri = createTicket.api.endpoints.tickets + '/' + $form.data('id') +
                            '?' + $form.find(':input').serialize();

            $.ajax({
                url: uri,
                method: 'post',
                beforeSend: function (xhr) {
                    xhr.setRequestHeader( 'X-WP-Nonce', createTicket.api.nonce );
                },
                success: function (res) {
                    console.log(res);
                }
            })

        }

    };

    /**
     * @summary Call the init method when the DOM loads.
     */
    $(document).ready(function () { module.init(); })

})(jQuery, createTicket || {});