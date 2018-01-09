/**
 * @summary Module for handling the dashboard UI.
 *
 * @since 1.6.0
 */
;(function ($, ucare) {
    "use strict";

    /**
     * @summary Adjust UI state when the store changes.
     */
    ucare.stores.toolbar.subscribe(function (store) {
        const state = store.getState(),
              $list = $('#the-tickets');

        if (state['bulk-action']) {
            $list.addClass('has-bulk-action');

        // Uncheck all bulk selectors
        } else {
            $list.removeClass('has-bulk-action');
            $list.find('[name="bulk_selected"]').each(function (i, el) {
                $(el).prop('checked', false);
            });
        }
    });

})(jQuery, ucare);