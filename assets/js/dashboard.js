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
            ucare.stores.toolbar.on('change', function (store) {
                const state = store.getState(),
                      $list = $('#the-tickets');

                if (state['bulk_action']) {
                    $list.addClass('has-bulk-action no-replace');

                // Uncheck all bulk selectors
                } else {
                    $list.removeClass('has-bulk-action no-replace');
                    $list.find('[name="bulk_item_selected"]')
                         .each(function (i, el) {

                             if (el.checked) {
                                 $(el).prop('checked', false).trigger('change');
                             }
                    });
                }
            });

            /**
             * @summary Toggle selected ticket state
             */
            $(document).on('change', '[name="bulk_item_selected"]', function () {
                module.toggleItemSelected($(this).prop('value'), this.checked);
            });

            /**
             * @summary Handle bulk action execution.
             */
            $('#bulk-action-execute').click(function () {
                module.bulkDeleteTickets();
            });

        },

        /**
         * @summary Toggle selected ticket.
         *
         * @param {int}  id
         * @param {bool} selected
         *
         * @since 1.6.0
         * @return {void}
         */
        toggleItemSelected: function (id, selected) {
            if (selected) {
                ucare.Actions.selectTicket(id);

            } else {
                ucare.Actions.deselectTicket(id);
            }
        },

        /**
         * @summary Delete all currently selected tickets.
         *
         * @since 1.6.0
         * @return void
         */
        bulkDeleteTickets: function () {
            ucare.stores.tickets.getState().selected.forEach(function (selected) {
                ucare.Actions.deleteTicket(selected);
            });
        },

    };


    // Initialize the module
    $(module.init);

})(jQuery, ucare);