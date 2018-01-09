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
                    $list.find('[name="bulk_item_selected"]').each(function (i, el) {
                        $(el).prop('checked', false);
                    });
                }
            });


            $(document).on('change', '[name="bulk_item_selected"]', function () {
                alert()
                // module.toggle_item_select($(this).attr('value'), $(this).is(':checked'));
            });


        },

        toggle_item_select: function (id, selected) {
            alert(selected)
        }

    };

    // Initialize the module
    $(module.init);

})(jQuery, ucare);