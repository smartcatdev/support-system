/**
 * @summary Module for handling the dashboard UI.
 *
 * @since 1.6.0
 */
;(function ($, ucare) {
    "use strict";





    /**
     * @since 1.6.0
     */
    const module = {

        /**
         * @summary Initialize events and handlers.
         *
         * @since 1.6.0
         * @return void
         */
        init: function () {

            /**
             * @summary Adjust UI state when the store changes.
             */
            ucare.stores.toolbar.subscribe(function (store) {
                const state = store.getState(),
                      $list = $('#the-tickets');

                if (state['bulk-action']) {
                    $list.addClass('has-bulk-action no-replace');

                // Uncheck all bulk selectors
                } else {
                    $list.removeClass('has-bulk-action no-replace');
                    $list.find('[name="bulk_selected"]').each(function (i, el) {
                        $(el).prop('checked', false);
                    });
                }
            });

        }

    };

    ucare.stores.tickets.subscribe(function (store) {
        console.log(store.getState())
    });


    ucare.Actions.selectTicket(2);
    ucare.Actions.selectTicket(55);
    ucare.Actions.deselectTicket(2);





    // Initialize the module
    $(module.init);

})(jQuery, ucare);